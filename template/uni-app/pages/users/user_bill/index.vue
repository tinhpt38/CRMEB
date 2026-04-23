<template>
	<view :style="colorStyle">
		<view class='bill-details'>
			<view class='nav acea-row'>
				<view class='item' :class='type==0 ? "on":""' @click='changeType(0)'>{{$t(`tất cả`)}}</view>
				<view class='item' :class='type==1 ? "on":""' @click='changeType(1)'>{{$t(`Sự tiêu thụ`)}}</view>
				<view class='item' :class='type==2 ? "on":""' @click='changeType(2)'>{{$t(`nạp tiền`)}}</view>
			</view>
			<view class='sign-record'>
				<view class='list' v-for="(item,index) in userBillList" :key="index">
					<view class='item'>
						<view class='data'>{{item.time}}</view>
						<view class='listn'>
							<view class='itemn acea-row row-between-wrapper' v-for="(vo,indexn) in item.child" :key="indexn">
								<view>
									<view class='name line1'>{{$t(vo.title)}}</view>
									<view>{{vo.add_time}}</view>
								</view>
								<view class='num' v-if="vo.pm">+{{vo.number}}</view>
								<view class='num font-color' v-else>-{{vo.number}}</view>
							</view>
						</view>
					</view>
				</view>
				<view class='loadingicon acea-row row-center-wrapper' v-if="userBillList.length>0">
					<text class='loading iconfont icon-jiazai' :hidden='loading==false'></text>{{loadTitle}}
				</view>
				<view v-if="userBillList.length == 0">
					<emptyPage :title="$t(`Hiện chưa có hồ sơ nào về hóa đơn.～`)"></emptyPage>
				</view>
			</view>
		</view>
		<!-- #ifdef MP -->
		<!-- <authorize @onLoadFun="onLoadFun" :isAuto="isAuto" :isShowAuth="isShowAuth" @authColse="authColse"></authorize> -->
		<!-- #endif -->
		<home v-if="navigation"></home>
	</view>
</template>

<script>
	import {
		getCommissionInfo
	} from '@/api/user.js';
	import {
		toLogin
	} from '@/libs/login.js';
	import {
		mapGetters
	} from "vuex";
	// #ifdef MP
	import authorize from '@/components/Authorize';
	// #endif
	import emptyPage from '@/components/emptyPage.vue';
	import home from '@/components/home';
	import colors from "@/mixins/color";
	export default {
		components: {
			// #ifdef MP
			authorize,
			// #endif
			emptyPage,
			home
		},
		mixins: [colors],
		data() {
			return {
				loadTitle: this.$t(`tải thêm`),
				loading: false,
				loadend: false,
				page: 1,
				limit: 15,
				type: 0,
				userBillList: [],
				times:[],
				isAuto: false, //Nếu không có ủy quyền, nó sẽ không được ủy quyền tự động.
				isShowAuth: false //Có ẩn ủy quyền hay không
			};
		},
		computed: mapGetters(['isLogin']),
		onShow() {
			if (this.isLogin) {
				this.getUserBillList();
			} else {
				toLogin();
			}
		},
		/**
		 * Chức năng vòng đời--nghe tải trang
		 */
		onLoad: function(options) {
			this.type = options.type || 0;
		},
		/**
		 * Chức năng xử lý sự kiện kéo trang xuống
		 */
		onReachBottom: function() {
			this.getUserBillList();
		},
		methods: {
			/**
			 * Gọi lại ủy quyền
			 */
			onLoadFun: function() {
				this.getUserBillList();
			},
			// Ủy quyền đã đóng
			authColse: function(e) {
				this.isShowAuth = e
			},
			/**
			 * Nhận chi tiết tài khoản
			 */
			getUserBillList: function() {
				let that = this;
				let page = that.page;
				let limit = that.limit;
				if (that.loading) return;
				if (that.loadend) return;
				that.loading = true;
				that.loadTitle = '';
				getCommissionInfo({
					page: page,
					limit: limit
				},that.type).then(res => {
					for (let i = 0; i < res.data.time.length; i++) {
						
						if (!this.times.includes(res.data.time[i])) {
							this.times.push(res.data.time[i])
							this.userBillList.push({
								time: res.data.time[i],
								child: []
							})
						}
					}
					
					for (let x = 0; x < this.times.length; x++) {
						for (let j = 0; j < res.data.list.length; j++) {
							if (this.times[x] === res.data.list[j].time_key) {
								this.userBillList[x].child.push(res.data.list[j])
							}
						}
					}
					let loadend = res.data.list.length < that.limit;
					that.loadend = loadend;
					that.loadTitle = loadend ? that.$t(`Tôi cũng có một điểm mấu chốt`) : that.$t(`tải thêm`);
					that.page += 1;
					that.loading = false;
				}).catch(err=>{
					that.loading = false;
					that.loadTitle = that.$t(`tải thêm`);
				})
			},
			/**
			 * Chuyển đổi điều hướng
			 */
			changeType: function(type) {
				this.type = type;
				this.loadend = false;
				this.page = 1;
				this.times = [];
				this.$set(this, 'userBillList', []);
				this.getUserBillList();
			},
		}
	}
</script>

<style scoped lang='scss'>
	.bill-details .nav {
		background-color: #fff;
		height: 90rpx;
		width: 100%;
		line-height: 90rpx;
	}

	.bill-details .nav .item {
		flex: 1;
		text-align: center;
		font-size: 30rpx;
		color: #282828;
	}

	.bill-details .nav .item.on {
		color: var(--view-theme);
		border-bottom: 3rpx solid var(--view-theme);
	}
</style>
