<template>
	<view :class="'wf-page wf-page' + type">
		<!--    left    -->
		<view>
			<view id="left" v-if="leftList.length">
				<view v-for="(item, index) in leftList" :key="index" class="wf-item" @tap="itemTap(item)">
					<WaterfallsFlowItem :item="item" :isStore="isStore" :type="type" :recommend="recommend" :goDetail="goDetail" />
				</view>
			</view>
		</view>
		<!--    right    -->
		<view>
			<view id="right" v-if="rightList.length">
				<view v-for="(item, index) in rightList" :key="index" class="wf-item" @tap="itemTap(item)">
					<WaterfallsFlowItem :item="item" :isStore="isStore" :type="type" :recommend="recommend" :goDetail="goDetail" />
				</view>
			</view>
		</view>
	</view>
</template>

<script>
import WaterfallsFlowItem from './WaterfallsFlowItem.vue';
export default {
	components: {
		WaterfallsFlowItem
	},
	props: {
		// Danh sách thác nước
		wfList: {
			type: Array,
			require: true
		},
		updateNum: {
			type: Number,
			default: 10
		},
		type: {
			type: Number,
			default: 0
		},
		isStore: {
			type: [String, Number],
			default: '1'
		},
		recommend: {
			type: Boolean,
			default: false
		},
		goDetail: {
			type: String,
			default: ''
		}
	},
	data() {
		return {
			allList: [], // Tất cả danh sách
			leftList: [], // danh sách bên trái
			rightList: [], // danh sách bên phải
			mark: 0, // dấu danh sách
			boxHeight: [] // Chỉ số 0 và 1 lần lượt là chiều cao của cột bên trái và bên phải.
		};
	},
	watch: {
		// Theo dõi thay đổi dữ liệu danh sách
		wfList: {
			handler(nVal, oVal) {
				// Nếu dữ liệu trống hoặc dữ liệu danh sách mới nhỏ hơn dữ liệu danh sách cũ (thường làm mới thả xuống hoặc chuyển đổi sắp xếp hoặc sử dụng bộ lọc), hãy khởi tạo biến

				if (!this.wfList.length || (this.wfList.length === this.updateNum && this.wfList.length <= this.allList.length)) {
					this.allList = [];
					this.leftList = [];
					this.rightList = [];
					this.boxHeight = [];
					this.mark = 0;
				}

				// Nếu danh sách có giá trị, hãy gọi phương thức thác nước

				if (this.wfList.length) {
					this.allList = this.wfList;
					this.leftList = [];
					this.rightList = [];
					this.boxHeight = [];
					this.allList.forEach((v, i) => {
						if (this.allList.length < 3 || (this.allList.length <= 7 && this.allList.length - i > 1) || (this.allList.length > 7 && this.allList.length - i > 2)) {
							if (i % 2) {
								this.rightList.push(v);
							} else {
								this.leftList.push(v);
							}
						}
					});
					if (this.allList.length < 3) {
						this.mark = this.allList.length + 1;
					} else if (this.allList.length <= 7) {
						this.mark = this.allList.length - 1;
					} else {
						this.mark = this.allList.length - 2;
					}
					if (this.mark < this.allList.length) {
						this.waterFall();
					}
				}
			},
			immediate: true,
			deep: true
		},
		mounted() {},

		// Theo dõi nhãn hiệu. Khi dấu thay đổi, thực hiện sắp xếp mục tiếp theo.
		mark() {
			const len = this.allList.length;
			if (this.mark < len && this.mark !== 0 && this.boxHeight.length) {
				this.waterFall();
			}
		}
	},
	methods: {
		// phân loại thác nước
		waterFall() {
			const i = this.mark;
			if (i == 0) {
				// Khởi tạo, chèn từ bên trái
				this.leftList.push(this.allList[i]);
				// Cập nhật chiều cao danh sách bên trái
				this.getViewHeight(0);
			} else if (i == 1) {
				// Mục thứ 2 được chèn vào, mặc định là chèn vào bên phải.
				this.rightList.push(this.allList[i]);
				// Cập nhật chiều cao của danh sách bên phải
				this.getViewHeight(1);
			} else {
				// Xác định vị trí chèn mục tiếp theo dựa trên chiều cao của danh sách bên trái và bên phải
				if (!this.boxHeight.length) {
					this.rightList.length < this.leftList.length ? this.rightList.push(this.allList[i]) : this.leftList.push(this.allList[i]);
				} else {
					const leftOrRight = this.boxHeight[0] > this.boxHeight[1] ? 1 : 0;
					if (leftOrRight) {
						this.rightList.push(this.allList[i]);
					} else {
						this.leftList.push(this.allList[i]);
					}
				}
				// Cập nhật chiều cao danh sách chèn
				this.getViewHeight();
			}
		},
		// Lấy chiều cao danh sách
		getViewHeight() {
			// Sử dụng nextTick để đảm bảo rằng trang được cập nhật trước khi yêu cầu chiều cao.
			this.$nextTick(() => {
				setTimeout(() => {
					uni
						.createSelectorQuery()
						.in(this)
						.select('#right')
						.boundingClientRect((res) => {
							res ? (this.boxHeight[1] = res.height) : '';
							uni
								.createSelectorQuery()
								.in(this)
								.select('#left')
								.boundingClientRect((res) => {
									res ? (this.boxHeight[0] = res.height) : '';
									this.mark = this.mark + 1;
								})
								.exec();
						})
						.exec();
				}, 100);
			});
		},
		// itemnhấp chuột
		itemTap(item) {
			this.$emit('itemTap', item);
		},
		// itemnhấp chuột

		goShop(item) {
			this.$emit('goShop', item);
		}
	}
};
</script>

<style lang="scss" scoped>
$page-padding: 10px;
$grid-gap: 10px;

.wf-page {
	display: grid;
	grid-template-columns: 1fr 1fr;
	grid-gap: $grid-gap;
}
.wf-item {
	width: calc((100vw - 2 * #{$page-padding} - #{$grid-gap}) / 2);
	padding-bottom: $grid-gap;
}
.wf-page1 .wf-item {
	margin-top: 20rpx;
	background-color: #fff;
	border-radius: 20rpx;
	padding-bottom: 0;
}
</style>
