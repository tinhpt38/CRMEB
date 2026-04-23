<template>
	<view class="aleart" v-if="aleartStatus" :style="colorStyle">
		<view class="icon-top">
			<text class="iconfont icon-fapiao2"
				:style="invoiceData.is_invoice?'background-color: var(--view-theme)':'background-color: #999'"></text>
			<view class="bill">
				{{invoiceData.is_invoice?$t(`Đã lập hoá đơn`): $t(`Không được lập hóa đơn`)}}
			</view>
		</view>

		<view class="aleart-body">
			<view class="body-head">{{$t(`Thông tin hóa đơn`)}}</view>
			<view class="label">
				<view class="">
					{{$t(`Tiêu đề hóa đơn`)}}
				</view>
				<view class="label-value">
					{{invoiceData.name}}
				</view>
			</view>
			<view class="label">
				<view class="">
					{{$t(`Loại tiêu đề hóa đơn`)}}
				</view>
				<view class="label-value">
					{{invoiceData.header_type == 1?$t(`riêng tư`):$t(`doanh nghiệp`)}}
				</view>
			</view>
			<view class="label">
				<view class="">
					{{$t(`Loại hóa đơn`)}}
				</view>
				<view class="label-value">
					{{invoiceData.type==1?$t(`Hóa đơn điện tử thông thường`):$t(`Hóa đơn điện tử đặc biệt`)}}
				</view>
			</view>
			<view class="label" v-if="invoiceData.duty_number">
				<view class="">
					{{$t(`Mã số thuế doanh nghiệp`)}}
				</view>
				<view class="label-value">
					{{invoiceData.duty_number}}
				</view>
			</view>

			<view class="body-head">{{$t(`thông tin liên lạc`)}}</view>
			<view class="label">
				<view class="">
					{{$t(`tên thật`)}}
				</view>
				<view class="label-value">
					{{invoiceData.name}}
				</view>
			</view>
			<view class="label">
				<view class="">
					{{$t(`Số liên lạc`)}}
				</view>
				<view class="label-value">
					{{invoiceData.drawer_phone}}
				</view>
			</view>
			<view class="label">
				<view class="">
					{{$t(`Email liên hệ`)}}
				</view>
				<view class="label-value">
					{{invoiceData.email}}
				</view>
			</view>
			<view class="label">
				<view class="">
					{{$t(`nhận xét hóa đơn`)}}
				</view>
				<view class="label-value">
					{{invoiceData.remark}}
				</view>
			</view>
		</view>
		<view class="btn" @click="close">
{{$t(`xác nhận`)}}
		</view>
	</view>
</template>

<script>
	import colors from '@/mixins/color.js';
	export default ({
		data() {
			return {

			}
		},
		mixins: [colors],
		props: {
			aleartStatus: {
				type: Boolean,
				default: false
			},
			invoiceData: {
				type: Object,
				default: () => {}
			}
		},
		methods: {
			close() {
				this.$emit('close')
			},
		}
	})
</script>

<style lang="scss" scoped>
	.aleart {
		width: 80%;
		// height: 714rpx;
		position: fixed;
		left: 50%;
		transform: translateX(-50%);
		z-index: 9999;
		top: 45%;
		margin-top: -357rpx;
		background-color: #fff;
		padding: 30rpx;
		border-radius: 12rpx;
		background-image: -webkit-gradient(linear, //Biểu diễn gradient là một đường thẳng. Một giá trị khác làradial
				50% 0, //Có một thuộc tính kích thước nền đằng sau vị trí bắt đầu của gradient tuyến tính chỉ định kích thước của nền. 30 X 15px 50% 0 đều được nhân với chiều rộng và chiều cao của phần tử gốc.。 
				0 100%, //Vị trí điểm cuối tương tự như trên
				from(transparent), //màu điểm bắt đầu
				color-stop(.5, transparent), //Một điểm nhất định ở giữa phải đạt đến màu này, tượng trưng cho quá trình thay đổi. 5b biểu thị tổng chiều dài của phạm vi độ dốc này.50%
				color-stop(.5, #999999), //Tương tự như trên
				to(#999999)), //Màu sắc của đoạn kết
			//Một khối nền được chia thành hai thành phần 15X15。

			-webkit-gradient(linear, 50% 0, 100% 100%, from(transparent),
				color-stop(.5, transparent),
				color-stop(.5, #999999),
				to(#999999));
		background-size: 20rpx 10rpx;
		background-repeat: repeat-x;
		background-position: 0 100%;

		.icon-top {
			margin-left: calc(50% - 40rpx);
			margin-top: -40rpx;
			display: flex;
			flex-direction: column;
			align-items: center;
			border-radius: 50%;
			width: 100rpx;
			height: 100rpx;

			.icon-fapiao2 {
				text-align: center;
				border-radius: 50%;
				font-size: 80rpx;
				color: #fff;
				background-color: var(--view-theme);
				padding: 20rpx;
				border: 4rpx solid #fff;
				margin-top: -40rpx;
			}
			.bill {
				width: 172rpx;
				text-align: center;
			}
		}

		.title {
			font-size: 34rpx;
			color: var(--view-theme);
			font-weight: bold;
			text-align: center;
			padding-bottom: 10rpx;
			border-bottom: 1px solid var(--view-op-ten);
		}

		.aleart-body {
			display: flex;
			justify-content: center;
			flex-direction: column;
			padding: 60rpx 0;

			.body-head {
				font-size: 30rpx;
				font-weight: bold;
				padding-bottom: 10rpx;
				border-bottom: 1px solid #EEEEEE;
				margin: 10rpx 0;
			}

			.label {
				width: 100%;
				display: flex;
				justify-content: space-between;
				margin-bottom: 15rpx;
				color: #333333;
				font-size: 28rpx;

				.label-value {
					color: #666666;
				}
			}
		}

		.btn {
			width: 100%;
			padding: 15rpx 0;
			color: #fff;
			background: var(--view-theme);
			border-radius: 20px;
			text-align: center;
			margin-bottom: 30rpx;
		}
	}
</style>
