<template>
	<view class="uni-badge--x">
		<slot />
		<text v-if="text" :class="classNames" :style="[positionStyle, customStyle, dotStyle]"
			class="uni-badge" @click="onClick()">{{displayValue}}</text>
	</view>
</template>

<script>
	/**
	 * Badge dấu góc kỹ thuật số
	 * @description Điểm đánh dấu góc kỹ thuật số thường được sử dụng cùng với các điều khiển khác (danh sách, lưới 9 ô vuông, v.v.) để nhắc nhở về số lượng. Mặc định là nền màu xám đồng nhất.
	 * @tutorial https://ext.dcloud.net.cn/plugin?id=21
	 * @property {String} text Nội dung phụ đề
	 * @property {String} size = [normal|small] Nội dung phụ đề
	 * @property {String} type = [info|primary|success|warning|error] loại màu
	 * Thông tin @value màu xám
	 * @value màu xanh chính
	 * @value thành công xanh
	 * @value cảnh báo màu vàng
	 * Lỗi @value màu đỏ
	 * @property {String} inverted = [true|false] Có cần màu nền hay không
	 * @property {Number} maxNum Hiển thị giá trị số bị giới hạn, trên 99 99+
	 * @property {String} absolute = [rightTop|rightBottom|leftBottom|leftTop] Bật định vị tuyệt đối, Nhãn góc sẽ được định vị trên bốn góc của nhãn mà nó bao bọc
	 * @value rightTop phía trên bên phải
	 * @value rightDưới cùng bên phải
	 * @value leftTop phía trên bên trái
	 * @value leftDưới dưới bên trái
	 * @property {Array[number]} offset	Offset từ tâm của góc định vị, chỉ hợp lệ khi tồn tại thuộc tính tuyệt đối chẳng hạn：[-10, -10] Biểu thị sự dịch chuyển ra bên ngoài 10px，[10, 10] Đại diện cho một phần bù bên trong được chỉ định bởi tuyệt đối 10px
	 * @property {String} isDot = [true|false] Có hiển thị dưới dạng dấu chấm nhỏ hay không
	 * @event {Function} click Nhấp vào Huy hiệu để kích hoạt sự kiện
	 * @example <uni-badge text="1"></uni-badge>
	 */

	export default {
		name: 'UniBadge',
		emits: ['click'],
		props: {
			type: {
				type: String,
				default: 'error'
			},
			inverted: {
				type: Boolean,
				default: false
			},
			isDot: {
				type: Boolean,
				default: false
			},
			maxNum: {
				type: Number,
				default: 99
			},
			absolute: {
				type: String,
				default: ''
			},
			offset: {
				type: Array,
				default () {
					return [0, 0]
				}
			},
			text: {
				type: [String, Number],
				default: ''
			},
			size: {
				type: String,
				default: 'small'
			},
			customStyle: {
				type: Object,
				default () {
					return {}
				}
			}
		},
		data() {
			return {};
		},
		computed: {
			width() {
				return String(this.text).length * 8 + 12
			},
			classNames() {
				const {
					inverted,
					type,
					size,
					absolute
				} = this
				return [
					inverted ? 'uni-badge--' + type + '-inverted' : '',
					'uni-badge--' + type,
					'uni-badge--' + size,
					absolute ? 'uni-badge--absolute' : ''
				].join(' ')
			},
			positionStyle() {
				if (!this.absolute) return {}
				let w = this.width / 2,
					h = 10
				if (this.isDot) {
					w = 5
					h = 5
				}
				const x = `${- w  + this.offset[0]}px`
				const y = `${- h + this.offset[1]}px`

				const whiteList = {
					rightTop: {
						right: x,
						top: y
					},
					rightBottom: {
						right: x,
						bottom: y
					},
					leftBottom: {
						left: x,
						bottom: y
					},
					leftTop: {
						left: x,
						top: y
					}
				}
				const match = whiteList[this.absolute]
				return match ? match : whiteList['rightTop']
			},
			dotStyle() {
				if (!this.isDot) return {}
				return {
					width: '10px',
					minWidth: '0',
					height: '10px',
					padding: '0',
					borderRadius: '10px'
				}
			},
			displayValue() {
				const {
					isDot,
					text,
					maxNum
				} = this
				return isDot ? '' : (Number(text) > maxNum ? `${maxNum}+` : text)
			}
		},
		methods: {
			onClick() {
				this.$emit('click');
			}
		}
	};
</script>

<style lang="scss" >
	$uni-primary: #2979ff !default;
	$uni-success: #4cd964 !default;
	$uni-warning: #f0ad4e !default;
	$uni-error: var(--view-theme) !default;
	$uni-info: #909399 !default;


	$bage-size: 12px;
	$bage-small: scale(0.8);

	.uni-badge--x {
		/* #ifdef APP-NVUE */
		// align-self: flex-start;
		/* #endif */
		/* #ifndef APP-NVUE */
		display: inline-block;
		/* #endif */
		position: relative;
	}

	.uni-badge--absolute {
		position: absolute;
	}

	.uni-badge--small {
		transform: $bage-small;
		transform-origin: center center;
	}

	.uni-badge {
		/* #ifndef APP-NVUE */
		display: flex;
		overflow: hidden;
		box-sizing: border-box;
		/* #endif */
		justify-content: center;
		flex-direction: row;
		height: 20px;
		min-width: 20px;
		padding: 0 4px;
		line-height: 18px;
		color: #fff;
		border-radius: 100px;
		background-color: $uni-info;
		background-color: transparent;
		border: 1px solid #fff;
		text-align: center;
		font-family: 'Helvetica Neue', Helvetica, sans-serif;
		font-feature-settings: "tnum";
		font-size: $bage-size;
		/* #ifdef H5 */
		z-index: 999;
		cursor: pointer;
		/* #endif */

		&--info {
			color: #fff;
			background-color: $uni-info;
		}

		&--primary {
			background-color: $uni-primary;
		}

		&--success {
			background-color: $uni-success;
		}

		&--warning {
			background-color: $uni-warning;
		}

		&--error {
			background-color: $uni-error;
		}

		&--inverted {
			padding: 0 5px 0 0;
			color: $uni-info;
		}

		&--info-inverted {
			color: $uni-info;
			background-color: transparent;
		}

		&--primary-inverted {
			color: $uni-primary;
			background-color: transparent;
		}

		&--success-inverted {
			color: $uni-success;
			background-color: transparent;
		}

		&--warning-inverted {
			color: $uni-warning;
			background-color: transparent;
		}

		&--error-inverted {
			color: $uni-error;
			background-color: transparent;
		}

	}
</style>
