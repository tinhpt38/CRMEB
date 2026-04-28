<template>
	<view :style="colorStyle">
		<view class="top-tabs">
			<view class="tabs" :class="{btborder:type === index}" v-for="(item,index) in tabsList" :key="index"
				@tap="changeTabs(index)">
				{{item.name}}
			</view>
		</view>
		<view class='return-list' v-if="orderList.length">
			<view class='goodWrapper' v-for="(item,index) in orderList" :key="index"
				@click='goOrderDetails(item.order_id)'>
				<view class='iconfont icon-shenqingzhong powder' v-if="item.refund_type==1 ||item.refund_type==2">
				</view>
				<view class='iconfont icon-yijujue' v-if="item.refund_type==3"></view>
				<view class='iconfont icon-daituihuo1 powder' v-if="item.refund_type==4"></view>
				<view class='iconfont icon-tuikuanzhong powder' v-if="item.refund_type==5"></view>
				<view class='iconfont icon-yituikuan' v-if="item.refund_type==6"></view>
				<view class='orderNum'>{{$t(`Số đơn hàng`)}}：{{item.order_id}}</view>
				<view class='item acea-row row-between-wrapper' v-for="(items,index) in item.cart_info" :key="index">
					<view class='pictrue'>
						<image :src='items.productInfo.attrInfo?items.productInfo.attrInfo.image:items.productInfo.image'>
						</image>
					</view>
					<view class='text'>
						<view class='acea-row row-between-wrapper'>
							<view class='name line1'>{{items.productInfo.store_name}}</view>
							<view class='num'>x {{items.cart_num}}</view>
						</view>
						<view class='attr line1' v-if="items.productInfo.attrInfo">{{items.productInfo.attrInfo.suk}}
						</view>
						<view class='attr line1' v-else>{{items.productInfo.store_name}}</view>
						<view class='money'>
							{{$t(`￥`)}}{{items.productInfo.attrInfo?items.productInfo.attrInfo.price:items.productInfo.price}}</view>
					</view>
				</view>
				<view class='totalSum'>{{$t(`chung`)}} {{item.refund_num || 0}} {{$t(`mặt hàng, tổng số tiền`)}} <text
						class='font-color price'>{{$t(`￥`)}}{{item.refund_price}}</text></view>
			</view>
		</view>
		<view class='loadingicon acea-row row-center-wrapper' v-if="orderList.length > 0">
			<text class='loading iconfont icon-jiazai' :hidden='loading==false'></text>{{loadTitle}}
		</view>
		<view v-if="orderList.length == 0  && !loading">
			<emptyPage :title="$t(`Chưa có đơn đặt hàng hoàn tiền nào~`)"></emptyPage>
		</view>
		<!-- #ifdef MP -->
		<!-- <authorize @onLoadFun="onLoadFun" :isAuto="isAuto" :isShowAuth="isShowAuth" @authColse="authColse"></authorize> -->
		<!-- #endif -->
		<!-- #ifndef MP -->
		<home></home>
		<!-- #endif -->
	</view>
</template>

<script>
	import home from '@/components/home';
	import emptyPage from '@/components/emptyPage';
	import {
		getNewOrderList
	} from '@/api/order.js';
	import {
		toLogin
	} from '@/libs/login.js';
	import {
		mapGetters
	} from "vuex";
	// #ifdef MP
	import authorize from '@/components/Authorize';
	// #endif
	import colors from '@/mixins/color.js';
	export default {
		components: {
			home,
			emptyPage,
			// #ifdef MP
			authorize
			// #endif
		},
		mixins: [colors],
		data() {
			return {
				type: 0,
				loading: false,
				loadend: false,
				loadTitle: this.$t(`tải thêm`), //nhắc nhở
				orderList: [], //Mảng thứ tự
				orderStatus: -3, //Trạng thái đơn hàng
				page: 1,
				limit: 20,
				isAuto: false, //Nếu không có ủy quyền, nó sẽ không được ủy quyền tự động.
				isShowAuth: false, //Có ẩn ủy quyền hay không
				tabsList: [{
					key: 0,
					name: this.$t(`Tất cả`)
				},
				{
					key: 1,
					name: this.$t(`Áp dụng`)
				},
				// {
				// 	key: 2,
				// 	name: 'Đang chờ trả lại'
				// }, 
				// {
				// 	key: 3,
				// 	name: 'Đang hoàn tiền'
				// }, 
				{
					key: 2,
					name: this.$t(`Đã hoàn tiền`)
				}]
			};
		},
		computed: mapGetters(['isLogin']),
		watch: {
			isLogin: {
				handler: function(newV, oldV) {
					if (newV) {
						this.getOrderList();
					}
				},
				deep: true
			}
		},
		onLoad() {
			if (this.isLogin) {
				this.getOrderList();
			} else {
				toLogin();
			}
		},
		/**
		 * Chức năng xử lý sự kiện kéo trang xuống
		 */
		onReachBottom: function() {
			this.getOrderList();
		},
		methods: {
			onLoadFun() {
				this.getOrderList();
			},
			// Ủy quyền đã đóng
			authColse: function(e) {
				this.isShowAuth = e
			},
			/**
			 * Đi tới chi tiết đơn hàng
			 */
			goOrderDetails: function(order_id) {
				if (!order_id) return that.$util.Tips({
					title: that.$t(`Không thể xem chi tiết đơn hàng nếu không có mã đơn hàng`)
				});
				uni.navigateTo({
					url: '/pages/goods/order_details/index?order_id=' + order_id + '&isReturn=1'
				})
			},
			changeTabs(index) {
				this.type = index
				this.loadend = false;
				this.page = 1
				this.limit = 20
				this.orderList = []
				this.getOrderList(index)
			},
			/**
			 * Nhận danh sách đặt hàng
			 */
			getOrderList(type) {
				let that = this;
				if (that.loading) return;
				if (that.loadend) return;
				that.loading = true;
				that.loadTitle = "";
				getNewOrderList({
					// type: that.orderStatus,
					page: that.page,
					limit: that.limit,
					refund_status: type ? type : that.type
				}).then(res => {
					let list = res.data.list || [];
					let loadend = list.length < that.limit;
					that.orderList = that.orderList.concat(list);
					that.$set(that, 'orderList', that.orderList);
					that.loadend = loadend;
					that.loading = false;
					that.loadTitle = loadend ? that.$t(`Tôi cũng có một điểm mấu chốt`) : that.$t(`tải thêm`);
					that.page = that.page + 1;
				}).catch(err => {
					that.loading = false;
					that.loadTitle = that.$t(`tải thêm`);
				});
			}
		}
	}
</script>

<style lang="scss" scoped>
	.return-list .goodWrapper {
		background-color: #fff;
		margin-top: 13rpx;
		position: relative;
	}

	.return-list .goodWrapper .orderNum {
		padding: 0 30rpx;
		border-bottom: 1px solid #eee;
		height: 87rpx;
		line-height: 87rpx;
		font-size: 30rpx;
		color: #282828;
	}

	.return-list .goodWrapper .item {
		border-bottom: 0;
		padding: 30rpx;
	}

	.return-list .goodWrapper .totalSum {
		padding: 0 30rpx 32rpx 30rpx;
		text-align: right;
		font-size: 26rpx;
		color: #282828;
	}

	.return-list .goodWrapper .totalSum .price {
		font-size: 28rpx;
		font-weight: bold;
	}

	.return-list .goodWrapper .iconfont {
		position: absolute;
		font-size: 109rpx;
		top: 7rpx;
		right: 30rpx;
		color: #ccc;
	}

	.return-list .goodWrapper .iconfont.powder {
		color: var(--view-minorColor);
	}

	.top-tabs {
		display: flex;
		justify-content: space-around;
		align-items: center;
		height: 80rpx;
		background-color: #fff;
	}

	.top-tabs .tabs {
		position: relative;
		height: 100%;
		padding: 12px 0;
	}

	.btborder {
		&::after {
			position: absolute;
			content: ' ';
			width: 39px;
			height: 2px;
			background-color: var(--view-theme);
			bottom: 2px;
			left: 50%;
			margin-left: -19px;
		}
	}
</style>
