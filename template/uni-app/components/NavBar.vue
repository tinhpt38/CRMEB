<template>
	<view class="navbar">
		<view class="content" :style="{ background: isScrolling ? '#fff' : bagColor,'padding-top': sysHeight + 'px' }">
			<view class="flex-center h-80 px-20">
				<view class="w-40 h-40 flex-center">
					<view
						v-show="showBack"
						@click="back"
						class="iconfont icon-fanhui"
						:style="{ color: `${iconColor}`, fontSize: `${iconSize}`, fontWeight: `${iconWeight}` }"
					></view>
				</view>
				<view class="flex-1 text-center"
				:style="{ color: `${textColor}`, fontSize: `${textSize}`, fontWeight: `${textWeight}` }"
				v-if="!custom">{{ titleText }}</view>
				<view class="flex-1" v-else>
					<slot></slot>
				</view>
				<view class="w-40 h-40 flex-center">
					<view v-show="showRight" class="right-icon"></view>
				</view>
			</view>
		</view>
		<view :style="{'padding-top': sysHeight + 'px'}" v-if="showEmpty">
			<view class="h-80"></view>
		</view>
	</view>
</template>

<script>
let sysHeight = uni.getWindowInfo().statusBarHeight;
export default {
	name: 'navbar',
	props: {
		custom:{
			type: Boolean,
			default: false
		},
		fixed: {
			type: Boolean,
			default: true
		},
		showEmpty:{
			type: Boolean,
			default: true
		},
		// Cuộn xuống dưới cùng
		isScrolling: {
			type: Boolean,
			default: false
		},
		// Có hiển thị trả lại hay khôngicon
		showBack: {
			type: Boolean,
			default: false
		},
		// Title
		titleText: {
			type: String,
			default: ''
		},
		// icon màu sắc
		iconColor: {
			type: String,
			default: '#000000'
		},
		// icon Cỡ chữ
		iconSize: {
			type: String,
			default: '40rpx'
		},
		// icon Độ dày phông chữ
		iconWeight: {
			type: String,
			default: 'bold'
		},
		// Title màu sắc
		textColor: {
			type: String,
			default: '#333'
		},
		// Title Cỡ chữ
		textSize: {
			type: String,
			default: '34rpx'
		},
		// Title Độ dày phông chữ
		textWeight: {
			type: String,
			default: '500'
		},
		// màu nền
		bagColor: {
			type: String,
			default: 'transparent'
		},
		showRight:{
			type: Boolean,
			default: false
		}
	},
	data() {
		return {
			sysHeight:sysHeight,
		};
	},
	methods: {
		back() {
			let pages = getCurrentPages(); // Lấy số lượng tuyến trang hiện đang mở，
			if (pages.length > 1) {
				uni.navigateBack()
			} else {
				uni.switchTab({
					url: '/pages/index/index'
				});
			}
		}
	}
};
</script>

<style lang="scss">
.navbar {
	position: relative;
	color: #333;
	.content {
		position: fixed;
		top: 0;
		right: 0;
		left: 0;
		z-index: 998;
		background-color: var(--view-theme);
		font-weight: 500;
		font-size: 34rpx;
		color: #ffffff;
	}
}
</style>
