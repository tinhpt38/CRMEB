<template>
	<view class="main-warper" style="background-color: var(--view-theme); padding-bottom: 50rpx" :style="colorStyle">
		<view class="bargain">
			<!-- #ifndef APP-PLUS || MP -->
			<view class="iconfont icon-xiangzuo" v-if="retunTop" @tap="goBack" :style="'top:' + navH + 'px'"></view>
			<!-- #endif -->
			<view :style="'background-image: url(' + (bargainUid != userInfo.uid ? imgHost + picUrl.support : imgHost + picUrl.barga) + ');'" class="header">
				<view class="people">
					{{ peopleCount.lookCount || 0 }}{{ $t(`mọi người xem`) }} | {{ peopleCount.shareCount || 0 }}{{ $t(`mọi người chia sẻ`) }} | {{ peopleCount.userCount || 0 }}{{ $t(`mọi người tham gia`) }}
				</view>
				<countDown
					:tipText="$t(`Đếm ngược`)"
					:dayText="$t(`ngày`)"
					:hourText="$t(`giờ`)"
					:minuteText="$t(`phút`)"
					:secondText="$t(`giây`)"
					:datatime="datatime"
					:isDay="true"
					v-if="bargainUid == userInfo.uid"
				></countDown>
				<view v-if="bargainUid != userInfo.uid" class="pictxt acea-row row-center-wrapper">
					<view class="pictrue">
						<image :src="bargainUserInfo.avatar"></image>
					</view>
					<view class="text">
						{{ bargainUserInfo.nickname || '' }}
						<text>{{ $t(`Mời bạn giúp thương lượng giá`) }}</text>
					</view>
				</view>
			</view>
			<view class="wrapper">
				<view class="pictxt acea-row row-between-wrapper" @tap="goProduct">
					<view class="pictrue">
						<image :src="bargainInfo.image"></image>
						<view class="bargain_view" v-if="bargainInfo.product_is_show">
							{{ $t(`Xem sản phẩm`) }}
							<text class="iconfont icon-jiantou iconfonts"></text>
						</view>
					</view>
					<view class="text acea-row row-column-around">
						<view class="line2">{{ bargainInfo.title || '' }}</view>
						<view class="money">
							{{ $t(`hiện hành`) }}: {{ $t(`￥`) }}
							<text class="num">{{ bargainInfo.price || '' }}</text>
						</view>
						<view class="successNum">{{ $t(`thấp nhất`) }}:{{ $t(`￥`) }}{{ bargainInfo.min_price || '' }}</view>
					</view>
				</view>
				<!-- thanh tiến trình -->
				<block v-if="userBargainInfo.price > 0">
					<view class="cu-progress acea-row row-middle round margin-top">
						<view class="acea-row row-middle bg-red" :style="'width:' + userBargainInfo.pricePercent + '%;'"></view>
					</view>
					<view class="money acea-row row-between-wrapper">
						<view>{{ $t(`Cắt`) }}{{ userBargainInfo.alreadyPrice }}</view>
						<view>{{ $t(`bên trái`) }}{{ userBargainInfo.price }}</view>
					</view>
				</block>
				<!-- Mặc cả bản thân -->
				<view v-if="userBargainInfo.bargainType == 1">
					<view class="bargainBnt" @tap="userBargain" v-if="productStock > 0 && quota > 0">{{ $t(`Tham gia thương lượng ngay bây giờ`) }}</view>
					<view class="bargainBnt grey" v-if="productStock <= 0 || quota <= 0">{{ $t(`Sản phẩm hiện đã hết hàng`) }}</view>
				</view>
				<!-- Giúp đàm phán giá cả và thành công trong thương lượng： -->
				<view v-if="userBargainInfo.bargainType == 2">
					<view class="bargainBnt" @tap="shareModal">{{ $t(`Mời bạn bè giúp thương lượng giá`) }}</view>
					<view class="tip">
						{{ $t(`Đã có rồi`) }}
						<text class="num">{{ userBargainInfo.count }}</text>
						{{ $t(`bạn bè đã thương lượng thành công`) }}
					</view>
				</view>

				<view v-if="userBargainInfo.bargainType == 3">
					<view class="bargainBnt" @tap="setBargainHelp">{{ $t(`Đưa cho một người bạn một con dao`) }}</view>
				</view>
				<view v-if="userBargainInfo.bargainType == 4">
					<view class="bargainSuccess">
						<text class="iconfont icon-xiaolian"></text>
						{{ $t(`Bạn bè đã mặc cả thành công`) }}
					</view>
					<view class="bargainBnt" @tap="currentBargainUser">{{ $t(`Tôi cũng muốn tham gia`) }}</view>
				</view>

				<view v-if="userBargainInfo.bargainType == 5">
					<view class="bargainSuccess">
						<text class="iconfont icon-xiaolian"></text>
						{{ $t(`Đã giúp bạn bè mặc cả thành công`) }}
					</view>
					<view class="bargainBnt" @tap="currentBargainUser">{{ $t(`Tôi cũng muốn tham gia`) }}</view>
				</view>
				<view v-if="userBargainInfo.bargainType == 6">
					<view class="bargainSuccess">
						<text class="iconfont icon-xiaolian"></text>
						{{ $t(`Chúc mừng bạn đã thương lượng thành công, hãy đi và thanh toán`) }}
					</view>
					<view class="bargainBnt" @tap="goPay">{{ $t(`Thanh toán ngay`) }}</view>
					<view class="bargainBnt on" @tap="goBargainList">{{ $t(`Lấy thêm sản phẩm`) }}</view>
				</view>

				<view class="lock" :style="'background-image: url(' + imgHost + picUrl.lock + ');'"></view>
			</view>
			<view class="bargainGang">
				<view class="title acea-row row-center-wrapper">
					<view class="pictrue">
						<image :src="picUrl.lace"></image>
					</view>
					<view class="titleCon">{{ $t(`Nhóm mặc cả`) }}</view>
					<view class="pictrue on">
						<image :src="picUrl.lace"></image>
					</view>
				</view>
				<view class="list">
					<block v-for="(item, index) in bargainUserHelpList" :key="index" v-if="index < 3 || !couponsHidden">
						<view class="item acea-row row-between-wrapper">
							<view class="pictxt acea-row row-between-wrapper">
								<view class="pictrue">
									<image :src="item.avatar"></image>
								</view>
								<view class="text">
									<view class="name line1">{{ item.nickname }}</view>
									<view class="line1">{{ item.add_time }}</view>
								</view>
							</view>
							<view class="money">
								<text class="iconfont icon-kanjia"></text>
								{{ $t(`cắt đứt`) }}{{ $t(`￥`) }}{{ item.price }}
							</view>
						</view>
					</block>
					<view class="open acea-row row-center-wrapper" @click="openTap" v-if="bargainUserHelpList.length > 3">
						{{ couponsHidden ? $t(`Hơn`) : $t(`đóng cửa`) }}
						<text class="iconfont" :class="couponsHidden == true ? 'icon-xiangxia' : 'icon-xiangshang'"></text>
					</view>
				</view>
				<view class="load" v-if="!limitStatus" @tap="getBargainUser">{{ $t(`Bấm để tải thêm`) }}</view>
				<view class="lock" :style="'background-image: url(' + imgHost + picUrl.lock + ');'"></view>
			</view>
			<view class="goodsDetails">
				<view class="title acea-row row-center-wrapper">
					<view class="pictrue">
						<image src="/images/left.png"></image>
					</view>
					<view class="titleCon">{{ $t(`Chi tiết sản phẩm`) }}</view>
					<view class="pictrue on">
						<image src="/images/left.png"></image>
					</view>
				</view>
				<view class="conter">
					<jyf-parser :html="bargainInfo.description" ref="article" :tag-style="tagStyle"></jyf-parser>
				</view>
				<view class="lock" :style="'background-image: url(' + imgHost + picUrl.lock + ');'"></view>
			</view>
			<view class="goodsDetails">
				<view class="title acea-row row-center-wrapper">
					<view class="pictrue">
						<image src="/images/left.png"></image>
					</view>
					<view class="titleCon">{{ $t(`quy tắc thương lượng`) }}</view>
					<view class="pictrue on">
						<image src="/images/left.png"></image>
					</view>
				</view>
				<view class="conter">
					<jyf-parser :html="bargainInfo.rule" ref="article" :tag-style="tagStyle"></jyf-parser>
				</view>
			</view>
			<view class="bargainTip" :class="active == true ? 'on' : ''">
				<view class="pictrue">
					<image :src="picUrl.popup"></image>
				</view>
				<view v-if="bargainUid == userInfo.uid">
					<view class="cutOff">
						{{ $t(`bạn đã cắt`) }}
						<text style="color: var(--view-theme)">{{ userBargainPrice }}</text>
						{{ $t(`Yuan, tôi nghe nói chia sẻ càng nhiều thì cơ hội thương lượng thành công càng lớn.`) }}
					</view>
					<!-- #ifdef MP -->
					<button class="tipBnt" @tap="shareModal">{{ $t(`Mời bạn bè giúp thương lượng giá`) }}</button>
					<!-- #endif -->
					<!-- #ifdef H5 -->
					<view class="tipBnt" @tap="shareModal">{{ $t(`Mời bạn bè giúp thương lượng giá`) }}</view>
					<!-- #endif -->
				</view>
				<view v-else>
					<view class="help" style="color: #fc4141">{{ $t(`Đã giúp cắt thành công`) }}{{ $t(`￥`) }}{{ userBargainPrice }}</view>
					<view class="cutOff on">{{ $t(`Bạn cũng có thể mặc cả và mua nó với giá thấp. Hãy đi và lựa chọn sản phẩm bạn yêu thích.`) }}</view>
					<view @tap="currentBargainUser" class="tipBnt">{{ $t(`Tôi cũng muốn tham gia`) }}</view>
				</view>
			</view>
			<view class="mask" catchtouchmove="true" v-show="active == true" @tap="close"></view>
		</view>
		<!-- Gửi ảnh cho bạn bè -->
		<view class="share-box" v-if="H5ShareBox">
			<image :src="imgHost + '/statics/images/share-info.png'" @click="H5ShareBox = false"></image>
		</view>
		<!-- #ifndef MP -->
		<home></home>
		<!-- #endif -->
		<!-- #ifdef H5 -->
		<view class="followCode" v-if="followCode">
			<view class="pictrue">
				<view class="code-bg"><img class="imgs" :src="codeSrc" /></view>
			</view>
			<view class="mask" @click="closeFollowCode"></view>
		</view>
		<zb-code
			ref="qrcode"
			v-show="false"
			:show="codeShow"
			:cid="cid"
			:val="val"
			:size="size"
			:unit="unit"
			:background="background"
			:foreground="foreground"
			:pdground="pdground"
			:icon="icon"
			:iconSize="iconsize"
			:onval="onval"
			:loadMake="loadMake"
			@result="qrR"
		/>
		<!-- #endif -->
		<!-- #ifdef MP -->
		<canvas class="canvas posters" canvas-id="myCanvas"></canvas>
		<!-- #endif -->
		<div class="posters" v-if="bargainPosterModal">
			<bargainPoster v-if="bargainPosterModal" ref="bargainPoster" comType="1" :comId="id" :comBargain="bargainUid" @getPosterImgae="getPosterImgae"></bargainPoster>
		</div>
		<!-- hiển thị áp phích -->
		<view class="mask" v-if="posterImageModal" @click="listenerActionClose"></view>
		<view class="poster-pop" v-if="posterImageModal">
			<image src="/static/images/poster-close.png" class="close" @click="listenerActionClose"></image>
			<image class="poster-img" :src="posterImage"></image>
			<!-- #ifndef H5  -->
			<view class="save-poster" @click="savePosterPath">{{ $t(`Lưu vào điện thoại`) }}</view>
			<!-- #endif -->
			<!-- #ifdef H5 -->
			<view class="keep">{{ $t(`Nhấn và giữ hình ảnh để lưu nó vào điện thoại của bạn`) }}</view>
			<!-- #endif -->
		</view>
		<!-- nút chia sẻ -->
		<view class="generate-posters acea-row row-middle" :class="posters ? 'on' : ''">
			<!-- #ifndef MP -->
			<button class="item" hover-class="none" v-if="weixinStatus === true" @click="H5ShareBox = true">
				<view class="iconfont icon-weixin3"></view>
				<view class="">{{ $t(`Gửi cho bạn bè`) }}</view>
			</button>
			<!-- #endif -->
			<!-- #ifdef MP -->
			<button class="item" open-type="share" hover-class="none" @click="goFriend">
				<view class="iconfont icon-weixin3"></view>
				<view class="">{{ $t(`Gửi cho bạn bè`) }}</view>
			</button>
			<!-- #endif -->
			<!-- #ifdef APP-PLUS -->
			<view class="item" @click="appShare('WXSceneSession')">
				<view class="iconfont icon-weixin3"></view>
				<view class="">{{ $t(`bạn bè WeChat`) }}</view>
			</view>
			<!-- #endif -->
			<button class="item" hover-class="none" @click="getBargainUserBargainPricePoster">
				<view class="iconfont icon-haibao"></view>
				<view class="">{{ $t(`Tạo áp phích`) }}</view>
			</button>
		</view>
		<view class="mask" v-if="posters" @click="listenerActionClose"></view>
	</view>
</template>

<script>
import zbCode from '@/components/zb-code/zb-code.vue';
import bargainPoster from '../poster-poster/index.vue';
import { getBargainDetail, postBargainStartUser, postBargainStart, postBargainHelp, postBargainHelpList, postBargainShare } from '../../../api/activity.js';
import { colorChange } from '@/api/api.js';
import { postCartAdd } from '../../../api/store.js';
import util from '../../../utils/util.js';
import { toLogin } from '@/libs/login.js';
import { mapGetters } from 'vuex';
// #ifdef MP
import authorize from '@/components/Authorize';
// #endif
import countDown from '@/components/countDown';
import home from '@/components/home';
import parser from '@/components/jyf-parser/jyf-parser';
import { TOKENNAME, HTTP_REQUEST_URL } from '@/config/app.js';
const app = getApp();
import colors from '@/mixins/color';

export default {
	components: {
		countDown,
		// #ifdef MP
		authorize,
		// #endif
		home,
		'jyf-parser': parser,
		bargainPoster
	},
	/**
	 * Dữ liệu ban đầu của trang
	 */
	mixins: [colors],
	data() {
		return {
			imgHost: HTTP_REQUEST_URL,
			countDownDay: '00',
			countDownHour: '00',
			countDownMinute: '00',
			countDownSecond: '00',
			active: false,
			id: 0, //Số sản phẩm ưu đãi
			userInfo: {}, //Thông tin người dùng hiện tại
			bargainUid: 0, //Cho phép người dùng thương lượng
			bargainUserInfo: {}, //Cho phép thương lượng thông tin người dùng
			bargainUserId: 0, //Kích hoạt số thương lượng
			bargainInfo: [], //Sản phẩm ưu đãi
			userBargainInfo: [],
			offset: 0,
			limit: 20,
			limitStatus: false,
			bargainUserHelpList: [],
			bargainUserHelpInfo: [],
			userBargainPrice: 0,
			status: '', // 0 Bắt đầu thương lượng 1 Bạn bè đã giúp mặc cả 2 Bạn bè đã giúp mặc cả thành công 3 Thương lượng hoàn thành 4 Thương lượng không thành công 5 Đơn hàng được tạo
			peopleCount: [], //Số người đã chia sẻ Số người đã xem Số người đã tham gia
			retunTop: true,
			bargainPartake: 0,
			isHelp: false,
			interval: null,
			userBargainStatus: 0, //Quyết định xem có nên mặc cả không
			bargainSumCount: 0, // Số lượng mua hàng
			productStock: 0, //Xác định xem đã bán hết chưa；
			quota: 0, //Xác định xem nó có bị giới hạn không；
			userBargainStatusHelp: true,
			navH: '',
			statusPay: '',
			bargainPrice: 0,
			datatime: 0,
			offest: '',
			tagStyle: {
				img: 'width:100%;display:block;',
				table: 'width:100%',
				video: 'width:100%'
			},
			H5ShareBox: false, //Hình ảnh chia sẻ tài khoản công khai
			systemH: 100,
			isAuto: false, //Nếu không có ủy quyền, nó sẽ không được ủy quyền tự động.
			isShowAuth: false, //Có ẩn ủy quyền hay không
			pages: '',
			posters: false,
			weixinStatus: false,
			couponsHidden: true,
			followCode: false,
			//Thông số mã QR
			codeShow: false,
			cid: '1',
			ifShow: true,
			val: '', // Giá trị mã QR sẽ được tạo
			size: 200, // Kích thước mã QR
			unit: 'upx', // đơn vị
			background: '#FFF', // màu nền
			foreground: '#000', // màu nền trước
			pdground: '#000', // Màu nhân vật
			icon: '', // Biểu tượng mã QR
			iconsize: 40, // Kích thước biểu tượng mã QR
			lv: 3, // Mức độ chấp nhận lỗi mã QR, nói chung không cần đặt, mặc định là ổn
			onval: true, // valTự động tạo lại mã QR khi giá trị thay đổi
			loadMake: true, // Sau khi thành phần được tải, mã QR sẽ được tạo tự động.
			src: '', // Địa chỉ hình ảnh sau khi mã QR được tạo hoặcbase64
			codeSrc: '',
			picUrl: {},
			picList: [
				{
					popup: '../static/bulet.jpg',
					barga: '/statics/system_images/bargain_dt_bg_0.jpeg',
					support: '/statics/system_images/bargain_dt2_bg_0.jpeg',
					lock: '/statics/system_images/bargain_dt_lock_0.png',
					lace: '../static/buled.png'
				},
				{
					popup: '../static/greent.jpg',
					barga: '/statics/system_images/bargain_dt_bg_1.jpeg',
					support: '/statics/system_images/bargain_dt2_bg_1.jpeg',
					lock: '/statics/system_images/bargain_dt_lock_1.png',
					lace: '../static/greend.png'
				},
				{
					popup: '../static/redt.jpg',
					lace: '../static/redd.png',
					barga: '/statics/system_images/bargain_dt_bg_2.jpeg',
					support: '/statics/system_images/bargain_dt2_bg_2.jpeg',
					lock: '/statics/system_images/bargain_dt_lock_2.png'
				},
				{
					popup: '../static/pinkt.jpg',
					lace: '../static/pinkd.png',
					barga: '/statics/system_images/bargain_dt_bg_3.jpeg',
					support: '/statics/system_images/bargain_dt2_bg_3.jpeg',
					lock: '/statics/system_images/bargain_dt_lock_3.png'
				},
				{
					popup: '../static/oranget.jpg',
					lace: '../static/oranged.png',
					barga: '/statics/system_images/bargain_dt_bg_4.jpeg',
					support: '/statics/system_images/bargain_dt2_bg_4.jpeg',
					lock: '/statics/system_images/bargain_dt_lock_4.png'
				}
			],
			bargainPosterModal: false,
			posterImageModal: false,
			posterImage: ''
		};
	},
	computed: mapGetters(['isLogin']),
	watch: {
		isLogin: {
			handler: function (newV, oldV) {
				if (newV) {
					this.getBargainDetails();
					this.addShareBargain();
				}
			},
			deep: true
		},
		colorStatus(newValue, oldValue) {
			if (newValue) {
				this.colorShow(newValue);
			}
		}
	},
	/**
	 * Chức năng vòng đời--nghe tải trang
	 */
	onLoad(options) {
		var that = this;
		// #ifdef H5
		if (this.$wechat.isWeixin()) {
			this.weixinStatus = true;
		}
		// #endif
		if (!this.colorStatus) {
			colorChange('color_change').then((res) => {
				this.colorShow(res.data.status);
			});
		}
		// #ifdef MP
		uni.getSystemInfo({
			success: function (res) {
				that.systemH = res.statusBarHeight;
				that.navH = that.systemH + 10;
			}
		});
		// #endif

		var pages = getCurrentPages();
		if (pages.length <= 1) {
			that.retunTop = false;
		}
		//Quét mã để thực hiện xử lý tham số
		// #ifdef MP
		if (options.scene) {
			var value = util.getUrlParams(decodeURIComponent(options.scene));
			if (typeof value === 'object') {
				if (value.id) options.id = value.id;
				if (value.bargain) options.bargain = value.bargain;
				//người quảng bá kỷ lụcuid
				if (value.pid) app.globalData.spid = value.pid;
			} else {
				app.globalData.spid = value;
			}
		}
		//người quảng bá kỷ lụcuid
		if (options.spid) app.globalData.spid = options.spid;
		// #endif
		if (options.hasOwnProperty('id')) {
			that.id = options.id;
			that.bargainUid = options.bargain || 0;
		}

		if (this.isLogin) {
			if (that.bargainUid == 'undefined' || !that.bargainUid) {
				that.bargainUid = that.$store.state.app.uid;
			}
			this.getBargainDetails();
			this.addShareBargain();
		} else {
			this.$Cache.set('login_back_url', `/pages/activity/goods_bargain_details/index?id=${options.id}&bargain=${this.bargainUid}`);
			toLogin();
		}
		uni.setNavigationBarTitle({
			title: this.$t(`Chi tiết mặc cả`)
		});
	},
	methods: {
		getPosterImgae(url) {
			this.posterImage = url;
			this.bargainPosterModal = false;
			this.posterImageModal = true;
		},
		colorShow(colorStatus) {
			switch (colorStatus) {
				case 1:
					this.picUrl = this.picList[0];
					break;
				case 2:
					this.picUrl = this.picList[1];
					break;
				case 3:
					this.picUrl = this.picList[2];
					break;
				case 4:
					this.picUrl = this.picList[3];
					break;
				case 5:
					this.picUrl = this.picList[4];
					break;
				default:
					this.picUrl = this.picList[2];
					break;
			}
		},
		// appchia sẻ
		// #ifdef APP-PLUS
		appShare(scene) {
			let that = this;
			let routes = getCurrentPages(); // Lấy mảng định tuyến trang hiện đang mở
			let curRoute = routes[routes.length - 1].$page.fullPath; // Nhận lộ trình trang hiện tại, là tuyến trang được mở cuối cùng

			uni.share({
				provider: 'weixin',
				scene: scene,
				type: 0,
				href: `${HTTP_REQUEST_URL}${curRoute}`,
				title: that.bargainInfo.title,
				imageUrl: that.bargainInfo.small_image,
				success: function (res) {
					uni.showToast({
						title: this.$t(`Chia sẻ thành công`),
						icon: 'success'
					});
					that.posters = false;
				},
				fail: function (err) {
					uni.showToast({
						title: this.$t(`Chia sẻ không thành công`),
						icon: 'none',
						duration: 2000
					});
					that.posters = false;
				}
			});
		},
		qrR(res) {
			this.codeSrc = res;
		},
		// #endif
		/**
		 * Chia sẻ mở
		 *
		 */
		listenerActionSheet() {
			if (this.isLogin == false) {
				toLogin();
			} else {
				// #ifdef H5
				if (this.$wechat.isWeixin() === true) {
					this.weixinStatus = true;
				}
				// #endif
				this.posters = true;
			}
		},
		shareModal() {
			this.active = false;
			this.posters = true;
		},
		getBargainUserBargainPricePoster() {
			if (!this.posterImage) {
				this.bargainPosterModal = true;
				this.posters = false;
			} else {
				this.bargainPosterModal = false;
				this.posterImageModal = true;
			}
			// uni.navigateTo({
			// 	url: '/pages/activity/poster-poster/index?type=1&id=' + this.id + '&bargain=' + this.bargainUid
			// });
		},
		// Tắt chia sẻ
		listenerActionClose() {
			this.posters = false;
			this.posterImageModal = false;
		},
		// Chương trình nhỏ đóng cửa sổ bật lên chia sẻ；
		goFriend() {
			this.posters = false;
		},
		openTap() {
			this.$set(this, 'couponsHidden', !this.couponsHidden);
		},
		// Ủy quyền đã đóng
		authColse(e) {
			this.isShowAuth = e;
		},
		// Tới trang sản phẩm
		goProduct() {
			if (!this.bargainInfo.product_is_show) return;
			uni.navigateTo({
				url: `/pages/goods_details/index?id=${this.bargainInfo.product_id}`
			});
		},
		// Mặc cả bản thân；
		userBargain() {
			let that = this;
			if (that.userInfo.uid == that.bargainUid) {
				if (that.userBargainInfo.bargainOrderCount >= that.bargainInfo.num) {
					return that.$util.Tips({
						title: that.$t(`Sản phẩm này được giới hạn mua cho mỗi người`) + `${that.bargainInfo.num}${that.bargainInfo.unit_name}`
					});
				} else {
					that.setBargain();
				}
			}
		},
		goBack() {
			uni.navigateBack({
				delta: 1
			});
		},
		gobargainUserInfo() {
			//Lấy thông tin người dùng cho phép thương lượng
			var that = this;
			var data = {
				userId: that.bargainUid
			};
			postBargainStartUser({
				bargainId: that.id,
				bargainUserUid: that.bargainUid
			}).then((res) => {
				that.$set(that, 'bargainUserInfo', res.data);
			});
		},
		goPay() {
			//Thanh toán ngay
			var that = this;
			var data = {
				productId: that.bargainInfo.product_id,
				bargainId: that.id,
				cartNum: 1,
				uniqueId: '',
				combinationId: 0,
				secKillId: 0,
				new: 1
			};
			postCartAdd(data)
				.then((res) => {
					uni.navigateTo({
						url: '/pages/goods/order_confirm/index?new=1&cartId=' + res.data.cartId
					});
				})
				.catch((err) => {
					return that.$util.Tips({
						title: err
					});
				});
		},
		getBargainDetails() {
			//Nhận thông tin chi tiết sản phẩm giá hời
			var that = this;
			var id = that.id;
			getBargainDetail(id, that.bargainUid)
				.then((res) => {
					that.bargainInfo = res.data.bargain;
					that.userBargainInfo = res.data.userBargainInfo;
					that.bargainPrice = res.data.bargain.price;
					that.userInfo = res.data.userInfo;
					that.productStock = res.data.bargain.attr.product_stock;
					that.quota = res.data.bargain.attr.quota;
					that.datatime = res.data.bargain.stop_time;
					that.pages = '/pages/activity/goods_bargain_details/index?id=' + that.id + '&bargain=' + that.bargainUid + '&scene=' + that.userInfo.uid;
					uni.setNavigationBarTitle({
						title: res.data.bargain.title.substring(0, 13) + '...'
					});
					that.bargainUserHelpList = [];
					that.getBargainUser();
					if (that.bargainUid != that.userInfo.uid) that.gobargainUserInfo();
					//#ifdef H5
					that.setOpenShare();
					//#endif
				})
				.catch(function (err) {
					that.$util.Tips(
						{
							title: err
						},
						{
							tab: 2,
							url: '/pages/activity/goods_bargain/index'
						}
					);
				});
		},
		currentBargainUser() {
			//Ưu đãi của người dùng hiện tại
			this.$set(this, 'bargainUid', this.userInfo.uid);
			this.setBargain();
		},
		setBargain() {
			//Tham gia thương lượng
			var that = this;
			postBargainStart(that.id).then(
				(res) => {
					that.$set(that, 'userBargainPrice', res.data.price);
					that.$set(that, 'active', true);
					that.getBargainDetails();
					that.userBargainStatus = 1;
				},
				(error) => {
					that.$util.Tips({
						title: error
					});
				}
			);
		},
		setBargainHelp() {
			//Mặc cả cho bạn bè
			var that = this;
			var data = {
				bargainId: that.id,
				bargainUserUid: that.bargainUid
			};
			postBargainHelp(data)
				.then((res) => {
					that.$set(that, 'userBargainPrice', res.data.price);
					that.$set(that, 'active', true);
					that.getBargainDetails();
				})
				.catch((err) => {
					that.$util.Tips({
						title: err
					});
					that.getBargainDetails();
				});
		},
		getBargainUser() {
			//Nhận trợ giúp thương lượng
			var that = this;
			var data = {
				bargainId: that.id,
				bargainUserUid: that.bargainUid,
				offset: that.offset,
				limit: that.limit
			};
			postBargainHelpList(data).then((res) => {
				var bargainUserHelpListNew = [];
				var bargainUserHelpList = that.bargainUserHelpList;
				var len = res.data.length;

				bargainUserHelpListNew = bargainUserHelpList.concat(res.data);

				that.$set(that, 'bargainUserHelpList', res.data);
				that.$set(that, 'limitStatus', data.limit > len);
				that.$set(that, 'offest', Number(data.offset) + Number(data.limit));
			});
		},
		goBargainList() {
			uni.navigateTo({
				url: '/pages/activity/goods_bargain/index'
			});
		},
		close() {
			this.$set(this, 'active', false);
		},
		addShareBargain() {
			//Thêm số lượng chia sẻ và nhận được số lượng người
			var that = this;
			postBargainShare(this.id).then((res) => {
				that.$set(that, 'peopleCount', res.data);
				this.pages = '/pages/activity/goods_bargain_details/index?id=' + this.id + '&bargain=' + this.bargainUid + '&spid=' + this.userInfo.uid;
			});
		},
		//#ifdef H5
		setOpenShare() {
			let that = this;
			let configTimeline = {
				title: that.$t(`bạn bè của bạn`) + that.userInfo.nickname + that.$t(`Mời bạn thương lượng`) + that.bargainInfo.title,
				desc: that.bargainInfo.info,
				link:
					window.location.protocol +
					'//' +
					window.location.host +
					'/pages/activity/goods_bargain_details/index?id=' +
					that.id +
					'&bargain=' +
					that.userInfo.uid +
					'&spid=' +
					this.userInfo.uid,
				imgUrl: that.bargainInfo.image
			};
			if (this.$wechat.isWeixin()) {
				this.$wechat
					.wechatEvevt(['updateAppMessageShareData', 'updateTimelineShareData', 'onMenuShareAppMessage', 'onMenuShareTimeline'], configTimeline)
					.then((res) => {})
					.catch((res) => {
						if (res.is_ready) {
							res.wx.updateAppMessageShareData(configTimeline);
							res.wx.updateTimelineShareData(configTimeline);
							res.wx.onMenuShareAppMessage(configTimeline);
							res.wx.onMenuShareTimeline(configTimeline);
						}
					});
			}
		},
		closeFollowCode() {
			this.$set(this, 'followCode', false);
		},
		//#endif
		savePosterPath() {
			let that = this;
			uni.getSetting({
				success(res) {
					if (!res.authSetting['scope.writePhotosAlbum']) {
						uni.authorize({
							scope: 'scope.writePhotosAlbum',
							success() {
								uni.saveImageToPhotosAlbum({
									filePath: that.posterImage,
									success: function (res) {
										that.posterImageClose();
										that.$util.Tips({
											title: that.$t(`Đã lưu thành công`),
											icon: 'success'
										});
									},
									fail: function (res) {
										that.$util.Tips({
											title: that.$t(`Lưu không thành công`)
										});
									}
								});
							}
						});
					} else {
						uni.saveImageToPhotosAlbum({
							filePath: that.posterImage,
							success: function (res) {
								that.posterImageClose();
								that.$util.Tips({
									title: that.$t(`Đã lưu thành công`),
									icon: 'success'
								});
							},
							fail: function (res) {
								that.$util.Tips({
									title: that.$t(`Lưu không thành công`)
								});
							}
						});
					}
				}
			});
		}
	},
	/**
	 * Chức năng vòng đời - nghe ẩn trang
	 */
	onHide: function () {
		if (this.interval !== null) clearInterval(this.interval);
	},
	/**
	 * Chức năng vòng đời--lắng nghe quá trình tải trang
	 */
	onUnload: function () {
		if (this.interval !== null) clearInterval(this.interval);
	},
	//#ifdef MP
	/**
	 * Người dùng nhấn vào góc trên bên phải để chia sẻ
	 */
	onShareAppMessage: function () {
		let that = this,
			share = {
				title: that.$t(`bạn bè của bạn`) + that.userInfo.nickname + this.$t(`Mời bạn thương lượng`) + that.bargainInfo.title + this.$t(`go_help`),
				path: '/pages/activity/goods_bargain_details/index?id=' + this.id + '&bargain=' + this.bargainUid + '&spid=' + this.userInfo.uid,
				imageUrl: that.bargainInfo.image
			};
		that.close();
		that.addShareBargain();
		return share;
	}
	//#endif
};
</script>

<style lang="scss">
page {
	// background-color: #e93323 !important;
}

.generate-posters {
	width: 100%;
	height: 170rpx;
	background-color: #fff;
	position: fixed;
	left: 0;
	bottom: 0;
	z-index: 300;
	transform: translate3d(0, 100%, 0);
	transition: all 0.3s cubic-bezier(0.25, 0.5, 0.5, 0.9);
	border-top: 1rpx solid #eee;
}

.generate-posters.on {
	transform: translate3d(0, 0, 0);
}

.generate-posters .item {
	flex: 1;
	text-align: center;
	font-size: 30rpx;
}

.generate-posters .item .iconfont {
	font-size: 80rpx;
	color: #5eae72;
}

.generate-posters .item .iconfont.icon-haibao {
	color: #5391f1;
}

.bargain .bargainGang .open {
	font-size: 24rpx;
	color: #999;
	margin-top: 30rpx;
}

.bargain .bargainGang .open .iconfont {
	font-size: 25rpx;
	margin: 5rpx 0 0 10rpx;
}

.bargain .icon-xiangzuo {
	font-size: 40rpx;
	color: #fff;
	position: fixed;
	top: 56rpx;
	left: 30rpx;
	z-index: 99;
	font-size: 36rpx;
}

.bargain .header {
	background-repeat: no-repeat;
	background-size: 100% 100%;
	width: 100%;
	height: 572rpx;
	margin: 0 auto;
	padding-top: 340rpx;
	position: relative;
}

.bargain .header .pictxt {
	margin: -60rpx auto 0 auto;
	font-size: 26rpx;
	color: #fff;
}

.bargain .header .pictxt .pictrue {
	width: 56rpx;
	height: 56rpx;
	margin-right: 30rpx;
}

.bargain .header .pictxt .pictrue image {
	width: 100%;
	height: 100%;
	border-radius: 50%;
	border: 2rpx solid #fff;
}

.bargain .header .pictxt .text text {
	margin-left: 20rpx;
}

.bargain .header .time {
	width: 440rpx;
	font-size: 22rpx;
	line-height: 36rpx;
	text-align: center;
	box-sizing: border-box;
	position: absolute;
	left: 50%;
	margin-left: -220rpx;
	top: 298rpx;
}

.bargain .header .time .red {
	color: var(--view-theme);
}

.bargain .header .people {
	text-align: center;
	color: #fff;
	font-size: 20rpx;
	position: absolute;
	width: 100%;
	/* #ifdef MP || APP-PLUS */
	height: 44px;
	line-height: 44px;
	top: 40rpx;
	/* #endif */
	/* #ifdef H5 */
	top: 58rpx;
	/* #endif */
}

.bargain .header .time text {
	color: #333;
}

.bargain .wrapper,
.bargain .bargainGang,
.bargain .goodsDetails {
	width: 660rpx;
	border: 6rpx solid #fc8b42;
	background-color: #fff;
	border-radius: 20rpx;
	margin: -190rpx auto 0 auto;
	box-sizing: border-box;
	padding: 0 24rpx 47rpx 24rpx;
	position: relative;
}

.bargain .wrapper .pictxt {
	margin: 26rpx 0 37rpx 0;
}

.bargain .wrapper .pictxt .pictrue {
	width: 180rpx;
	height: 180rpx;
	position: relative;
}

.bargain .wrapper .pictxt .pictrue image {
	width: 100%;
	height: 100%;
	border-radius: 6rpx;
}

.bargain .wrapper .pictxt .text {
	width: 395rpx;
	font-size: 28rpx;
	color: #282828;
	height: 180rpx;
}

.bargain .wrapper .pictxt .text .money {
	font-weight: bold;
	font-size: 24rpx;
}

.bargain .wrapper .pictxt .text .money .num {
	font-size: 36rpx;
}

.bargain .wrapper .pictxt .text .successNum {
	font-size: 22rpx;
	color: #999;
}

.bargain .wrapper .cu-progress {
	overflow: hidden;
	height: 12rpx;
	background-color: #eee;
	width: 100%;
	border-radius: 20rpx;
}

.bargain .wrapper .cu-progress .bg-red {
	width: 0;
	height: 100%;
	transition: width 0.6s ease;
	border-radius: 20rpx;
	background-image: linear-gradient(to right, var(--view-minorColor) 0%, var(--view-theme) 100%);
}

.bargain .wrapper .money {
	font-size: 22rpx;
	color: #999;
	margin-top: 15rpx;
	color: var(--view-priceColor);
}

.bargain .wrapper .bargainSuccess {
	font-size: 26rpx;
	color: #282828;
	text-align: center;
}

.bargain .wrapper .bargainSuccess .iconfont {
	font-size: 45rpx;
	color: #54c762;
	padding-right: 18rpx;
	vertical-align: -5rpx;
}

.bargain .wrapper .bargainBnt {
	font-size: 30rpx;
	font-weight: bold;
	color: #fff;
	width: 600rpx;
	height: 80rpx;
	border-radius: 40rpx;
	// background-image: linear-gradient(to right, var(--view-minorColor) 0%, var(--view-theme) 100%);
	background-color: var(--view-theme);
	text-align: center;
	line-height: 80rpx;
	margin-top: 32rpx;
}

.bargain .wrapper .bargainBnt.on {
	border: 2rpx solid var(--view-theme);
	color: var(--view-theme);
	background-image: linear-gradient(to right, #fff 0%, #fff 100%);
	width: 596rpx;
	height: 76rpx;
}

.bargain .wrapper .bargainBnt.grey {
	color: #fff;
	background-image: linear-gradient(to right, #bbbbbb 0%, #bbbbbb 100%);
}

.bargain .wrapper .tip {
	font-size: 22rpx;
	color: #999;
	text-align: center;
	margin-top: 20rpx;
}

.bargain .wrapper .tip .num {
	color: var(--view-theme);
}

.bargain .wrapper .lock,
.bargain .bargainGang .lock,
.bargain .goodsDetails .lock {
	background-repeat: no-repeat;
	background-size: 100% 100%;
	width: 548rpx;
	height: 66rpx;
	position: absolute;
	left: 50%;
	transform: translateX(-50%);
	bottom: -43rpx;
	z-index: 5;
}

.bargain .bargainGang {
	margin: 13rpx auto 0 auto;
}

.bargain .bargainGang .title,
.bargain .goodsDetails .title {
	font-size: 32rpx;
	font-weight: bold;
	height: 80rpx;
	margin-top: 30rpx;
	color: var(--view-theme);
}

.bargain .bargainGang .title .pictrue,
.bargain .goodsDetails .title .pictrue {
	width: 46rpx;
	height: 24rpx;
}

.bargain .bargainGang .title .pictrue.on,
.bargain .goodsDetails .title .pictrue.on {
	transform: rotate(180deg);
}

.bargain .bargainGang .title .pictrue image,
.bargain .goodsDetails .title .pictrue image {
	width: 100%;
	height: 100%;
	display: block;
}

.bargain .bargainGang .title .titleCon,
.bargain .goodsDetails .title .titleCon {
	margin: 0 20rpx;
}

.bargain .bargainGang .list .item {
	border-bottom: 1rpx dashed #ddd;
	height: 112rpx;
}

.bargain .bargainGang .list .item .pictxt {
	width: 310rpx;
}

.bargain .bargainGang .list .item .pictxt .pictrue {
	width: 70rpx;
	height: 70rpx;
}

.bargain .bargainGang .list .item .pictxt .pictrue image {
	width: 100%;
	height: 100%;
	border-radius: 50%;
	border: 2rpx solid var(--view-theme);
}

.bargain .bargainGang .list .item .pictxt .text {
	width: 225rpx;
	font-size: 20rpx;
	color: #999;
}

.bargain .bargainGang .list .item .pictxt .text .name {
	font-size: 25rpx;
	color: #282828;
	margin-bottom: 7rpx;
}

.bargain .bargainGang .list .item .money {
	font-size: 25rpx;
	color: var(--view-theme);
}

.bargain .bargainGang .list .item .money .iconfont {
	font-size: 35rpx;
	vertical-align: middle;
	margin-right: 10rpx;
}

.bargain .bargainGang .load {
	font-size: 24rpx;
	text-align: center;
	line-height: 80rpx;
	height: 80rpx;
	color: var(--view-theme);
}

.bargain .goodsDetails {
	margin: 13rpx auto 0 auto;
}

.bargain .goodsDetails ~ .goodsDetails {
	margin-bottom: 50rpx;
}

.bargain .goodsDetails .conter {
	margin-top: 20rpx;
	overflow: hidden;
}

.bargain .goodsDetails .conter image {
	width: 100% !important;
	display: block !important;
}

.bargain .bargainTip {
	position: fixed;
	top: 50%;
	left: 50%;
	width: 560rpx;
	margin-left: -280rpx;
	z-index: 111;
	border-radius: 20rpx;
	background-color: #fff;
	transition: all 0.3s ease-in-out 0s;
	opacity: 0;
	transform: scale(0);
	padding-bottom: 60rpx;
	margin-top: -330rpx;
}

.bargain .bargainTip.on {
	opacity: 1;
	transform: scale(1);
}

.bargain .bargainTip .pictrue {
	width: 100%;
	height: 321rpx;
}

.bargain .bargainTip .pictrue image {
	width: 100%;
	height: 100%;
	border-radius: 20rpx 20rpx 0 0;
}

.bargain .bargainTip .cutOff {
	font-size: 30rpx;
	color: #666;
	padding: 0 29rpx;
	text-align: center;
	margin-top: 50rpx;
}

.bargain .bargainTip .cutOff.on {
	margin-top: 26rpx;
}

.bargain .bargainTip .help {
	font-size: 32rpx;
	font-weight: bold;
	text-align: center;
	margin-top: 40rpx;
}

.bargain .bargainTip .tipBnt {
	font-size: 32rpx;
	color: #fff;
	width: 360rpx;
	height: 82rpx;
	border-radius: 41rpx;
	// background-image: linear-gradient(to right, var(--view-minorColor) 0%, var(--view-theme) 100%);
	background-color: var(--view-theme);
	text-align: center;
	line-height: 82rpx;
	margin: 50rpx auto 0 auto;
}

.bargain_view {
	width: 180rpx;
	height: 48rpx;
	background: rgba(0, 0, 0, 0.5);
	opacity: 1;
	border-radius: 0 0 6rpx 6rpx;
	position: absolute;
	bottom: 0;
	font-size: 22rpx;
	color: #fff;
	text-align: center;
	line-height: 48rpx;
}

.iconfonts {
	font-size: 22rpx !important;
}

.wxParse-div {
	width: auto !important;
	height: auto !important;
}

.bargain .mask {
	z-index: 100;
}

.share-box {
	z-index: 1000;
	position: fixed;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;

	image {
		width: 100%;
		height: 100%;
	}
}

.followCode {
	.pictrue {
		width: 500rpx;
		height: 530rpx;
		border-radius: 12px;
		left: 50%;
		top: 50%;
		margin-left: -250rpx;
		margin-top: -360rpx;
		position: fixed;
		z-index: 10000;

		.code-bg {
			display: flex;
			justify-content: center;
			width: 100%;
			height: 100%;
			background-image: url('~@/static/images/code-bg.png');
			background-size: 100% 100%;
		}

		.imgs {
			width: 310rpx;
			height: 310rpx;
			margin-top: 92rpx;
		}
	}

	.mask {
		z-index: 9999;
	}
}
.main-warper {
	position: relative;
	::v-deep .posterCon {
		position: static;
	}
}
.posters {
	position: fixed;
	bottom: -5000px;
	left: -5000px;
}

.poster-pop {
	width: 450rpx;
	height: 714rpx;
	position: fixed;
	left: 50%;
	transform: translateX(-50%);
	z-index: 399;
	top: 50%;
	margin-top: -377rpx;
	.poster-img{
		border-radius: 6px;
	}
}

.poster-pop image {
	width: 100%;
	height: 100%;
	display: block;
}

.poster-pop .close {
	width: 46rpx;
	height: 75rpx;
	position: fixed;
	right: 0;
	top: -73rpx;
	display: block;
}

.poster-pop .save-poster {
	background-color: #df2d0a;
	font-size: ：22rpx;
	color: #fff;
	text-align: center;
	height: 76rpx;
	line-height: 76rpx;
	width: 100%;
	margin-top: 20rpx;
}

.poster-pop .keep {
	color: #fff;
	text-align: center;
	font-size: 25rpx;
	margin-top: 10rpx;
}
.canvas {
	width: 700rpx;
	height: 1100rpx;
}
</style>
