<template>
	<view class="wrap" v-if="popup_show">
		<view class="popup-bg" :style="getHeight">
			<view class="popup-content" :class="{ 'popup-content-show': popup_show }">
				<view class="update-wrap">
					<image src="./images/img.png" class="top-img"></image>
					<view class="content">
						<text class="title">{{$t(`phiên bản mới được tìm thấy`)}}{{ update_info.version }}</text>
						<!-- Mô tả nâng cấp -->
						<view class="title-sub" v-html="update_info.info"></view>
						<!-- Nút nâng cấp -->
						<button class="btn" v-if="downstatus < 1" @click="nowUpdate()">
							{{$t(`Nâng cấp ngay bây giờ`)}}
						</button>
						<!-- Tiến trình tải xuống -->
						<view class="sche-wrap" v-else>
							<!-- Tải xuống gói cập nhật -->
							<view class="sche-bg">
								<view class="sche-bg-jindu" :style="lengthWidth"></view>
							</view>
							<text class="down-text">{{$t(`Tiến trình tải xuống`)}}:{{ (downSize / 1024 / 1024).toFixed(2) }}M/{{
                  (fileSize / 1024 / 1024).toFixed(2)
                }}M</text>
						</view>
					</view>
				</view>
				<image src="./images/close.png" class="close-ioc" @click="closeUpdate()"></image>
			</view>
		</view>
	</view>
</template>

<script>
	let vm;
	import {
		getUpdateInfo
	} from '@/api/public.js'

	export default {
		name: "appUpdate",
		//@Có buộc phải cập nhật không
		props: {
			tabbar: {
				type: Boolean,
				default: false, //Có thành phần thanh tab gốc không?
			},
			getVer: {
				type: Boolean,
				default: false, //Có thành phần thanh tab gốc không?
			},
		},
		data() {
			return {
				popup_show: false, //Cửa sổ bật lên có được hiển thị hay không
				platform: "", //ios or android
				version: "1.0.0", //Phiên bản phần mềm hiện tại
				need_update: false, // Cập nhật hay không
				downing: false, //Đang tải xuống
				downstatus: 0, //0Chưa tải xuống 1 Đã bắt đầu 2 Đã kết nối với tài nguyên 3 Đã nhận dữ liệu 4 Đã hoàn tất tải xuống
				update_info: {
					os: "", //Hệ thống thiết bị
					version: "", //phiên bản mới nhất
					info: "", //Hướng dẫn nâng cấp
				},
				fileSize: 0, //kích thước tập tin
				downSize: 0, //Kích thước đã tải xuống
				viewObj: null, //mặt nạ bản địaview
			};
		},
		created() {
			vm = this;
			if (!this.getVer) this.update()
		},
		computed: {
			// Tải xuống tính toán tiến độ
			lengthWidth: function() {
				let w = (this.downSize / this.fileSize) * 100;
				if (!w) {
					w = 0;
				} else {
					w = w.toFixed(2);
				}
				return {
					width: w + "%", //return tỷ lệ nửa chiều rộng
				};
			},
			getHeight() {
				let bottom = 0;
				if (this.tabbar) {
					bottom = 50;
				}
				return {
					bottom: bottom + "px",
					height: "auto",
				};
			},
		},
		methods: {
			// Kiểm tra các bản cập nhật
			update() {
				// #ifdef APP-PLUS
				// Nhận thông tin hệ thống điện thoại
				uni.getSystemInfo({
					success: function(res) {
						vm.platform = res.platform; //ios  or android
						console.log("Thông tin hệ thống điện thoại di động", vm.platform);
					},
				});

				// Nhận số phiên bản
				plus.runtime.getProperty(plus.runtime.appid, function(inf) {
					vm.version = inf.version;
				});
				console.log("Phiên bản hiện tại", vm.version);
				this.getUpdateInfo(); //Nhận thông tin cập nhật
				// #endif
			},

			// Nhận thông tin phiên bản trực tuyến
			getUpdateInfo() {
				//Bắt đầu yêu cầu chạy nền để lấy số phiên bản mới nhất
				getUpdateInfo(this.platform === "ios" ? 2 : 1)
					.then((res) => {
						if(Array.isArray(res.data)){
						 return	this.$emit('isNew')
						}
						const tagDate = uni.getStorageSync('app_update_time') || '',
							nowDate = new Date().toLocaleDateString();
						if (tagDate !== nowDate && !this.getVer) {
							uni.setStorageSync('app_update_time', new Date().toLocaleDateString());
						} else if ((tagDate !== nowDate) && this.getVer) {
							if (!res.data.is_force) return
						} else if (tagDate == nowDate && !this.getVer && !res.data.is_force) {
							return
						}
						// Dữ liệu trả về ở đây phù hợp với nền
						let data = res.data;
						// Vòng lặp để lấy dữ liệu cập nhật tương ứng với thiết bị hiện tại
						vm.update_info = data;
						if (!vm.update_info.platform) {
							// Dữ liệu nâng cấp của hệ thống hiện tại không được cấu hình ở chế độ nền.
						} else {
							vm.checkUpdate(); ///Kiểm tra các bản cập nhật
						}
					})
					.catch((err) => {
						vm.popup_show = false
					});
			},
			// Kiểm tra các bản cập nhật
			checkUpdate() {
				vm.need_update = vm.compareVersion(vm.version, vm.update_info.version); // Kiểm tra xem có cần nâng cấp không
				if (vm.need_update) {
					vm.popup_show = true; //Số phiên bản trực tuyến lớn hơn số phiên bản hiện được cài đặt. Hộp nâng cấp được hiển thị.
					if (vm.tabbar) {
						//Trang này có thành phần thanh tab gốc không?
						//Tạo chế độ xem gốc để che giấu sự kiện nhấp chuột của thanh tab (Nếu bạn không sử dụng thanh tab gốc, bạn có thể hủy bước này.)
						vm.viewObj = new plus.nativeObj.View("viewObj", {
							bottom: "0px",
							left: "0px",
							height: "50px",
							width: "100%",
							backgroundColor: "rgba(0,0,0,.6)",
						});
						vm.viewObj.show(); //Hiển thị mặt nạ gốc
					}
				} else {
					this.$emit('isNew')
				}
			},

			// Hủy cập nhật
			closeUpdate() {
				if (vm.update_info.is_force) {
					// Buộc cập nhật, hủy và thoátapp
					this.platform == "android" ?
						plus.runtime.quit() :
						plus.ios
						.import("UIApplication")
						.sharedApplication()
						.performSelector("exit");
				} else {
					vm.popup_show = false; //Đóng cửa sổ bật lên nâng cấp
					if (vm.viewObj) vm.viewObj.hide(); //Ẩn mặt nạ gốc
				}
			},
			// Cập nhật ngay bây giờ
			nowUpdate() {
				if (vm.downing) return false; //Dừng tải xuống nếu nó đang được tiến hành
				vm.downing = true; //Thay đổi trạng thái Đang tải xuống

				if (/\.apk$/.test(vm.update_info.url)) {
					// Nếu đó là địa chỉ apk
					vm.download_wgt(); // Gói cài đặt/cập nhật gói nâng cấp
				} else if (/\.wgt$/.test(vm.update_info.url)) {
					// Nếu đó là gói cập nhật
					vm.download_wgt(); // Gói cài đặt/cập nhật gói nâng cấp
				} else {
					plus.runtime.openURL(vm.update_info.url, function() {
						//Gọi trình duyệt bên ngoài để mở địa chỉ cập nhật
						plus.nativeUI.toast("lỗi mở");
					});
				}
			},
			// Tải xuống gói tài nguyên nâng cấp
			download_wgt() {
				plus.nativeUI.showWaiting("Tải xuống tập tin cập nhật..."); //Tải xuống tập tin cập nhật...
				let options = {
					method: "get",
				};
				let dtask = plus.downloader.createDownload(
					vm.update_info.url,
					options,
					function(d, status) {}
				);

				dtask.addEventListener("statechanged", function(task, status) {
					if (status === null) {} else if (status == 200) {
						//Việc in ấn ở đây sẽ được thực hiện liên tục. Các bạn lưu ý khi ra mắt chính thức nhớ đừng in gì vào đây nhé.///////////////////////////////////////////////////
						vm.downstatus = task.state;
						switch (task.state) {
							case 3: // Dữ liệu đã nhận
								vm.downSize = task.downloadedSize;
								if (task.totalSize) {
									vm.fileSize = task.totalSize; //Máy chủ phải trả về độ dài nội dung chính xác để có độ dài
								}
								break;
							case 4:
								vm.installWgt(task.filename); // Cài đặt gói wgt
								break;
						}
					} else {
						plus.nativeUI.closeWaiting();
						plus.nativeUI.toast("Lỗi tải xuống");
						vm.downing = false;
						vm.downstatus = 0;
					}
				});
				dtask.start();
			},

			// Tập tin cài đặt
			installWgt(path) {
				plus.nativeUI.showWaiting("Cài đặt tập tin cập nhật..."); //Cài đặt tập tin cập nhật...
				plus.runtime.install(
					path, {},
					function() {
						plus.nativeUI.closeWaiting();
						// Tải xuống tài nguyên ứng dụng đã hoàn tất！
						plus.nativeUI.alert("Tải xuống tài nguyên ứng dụng đã hoàn tất！", function() {
							plus.runtime.restart();
						});
					},

					function(e) {
						plus.nativeUI.closeWaiting();
						// Cài đặt tập tin cập nhật không thành công
						plus.nativeUI.alert("Cài đặt tập tin cập nhật không thành công[" + e.code + "]：" + e.message);
					}
				);
			},
			// So sánh số phiên bản
			compareVersion(ov, nv) {
				if (!ov || !nv || ov == "" || nv == "") {
					return false;
				}
				let b = false,
					ova = ov.split(".", 4),
					nva = nv.split(".", 4);
				for (let i = 0; i < ova.length && i < nva.length; i++) {
					let so = ova[i],
						no = parseInt(so),
						sn = nva[i],
						nn = parseInt(sn);
					if (nn > no || sn.length > so.length) {
						return true;
					} else if (nn < no) {
						return false;
					}
				}
				if (nva.length > ova.length && 0 == nv.indexOf(ov)) {
					return true;
				} else {
					return false;
				}
			},
		},
	};
</script>

<style lang="scss" scoped>
	.popup-bg {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		position: fixed;
		top: 0;
		left: 0rpx;
		right: 0;
		bottom: 0;
		width: 750rpx;
		background-color: rgba(0, 0, 0, 0.6);
		z-index: 10000;
	}

	.popup-content {
		display: flex;
		flex-direction: column;
		align-items: center;
	}

	.popup-content-show {
		animation: mymove 500ms;
		transform: scale(1);
	}

	@keyframes mymove {
		0% {
			transform: scale(0);
			/*Bắt đầu ở kích thước ban đầu*/
		}

		100% {
			transform: scale(1);
		}
	}

	.update-wrap {
		width: 580rpx;
		border-radius: 18rpx;
		position: relative;
		display: flex;
		flex-direction: column;
		background-color: #ffffff;
		padding: 170rpx 30rpx 0;

		.top-img {
			position: absolute;
			left: 0;
			width: 100%;
			height: 256rpx;
			top: -128rpx;
		}

		.content {
			display: flex;
			flex-direction: column;
			align-items: center;
			padding-bottom: 40rpx;

			.title {
				font-size: 32rpx;
				font-weight: bold;
				color: #6526f3;
			}

			.title-sub {
				text-align: center;
				font-size: 24rpx;
				color: #666666;
				padding: 30rpx 0;
			}

			.btn {
				width: 460rpx;
				display: flex;
				align-items: center;
				justify-content: center;
				color: #ffffff;
				font-size: 30rpx;
				height: 80rpx;
				line-height: 80rpx;
				border-radius: 100px;
				background-color: #6526f3;
				margin-top: 20rpx;
			}
		}
	}

	.close-ioc {
		width: 70rpx;
		height: 70rpx;
		margin-top: 30rpx;
	}

	.sche-wrap {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: flex-end;
		padding: 10rpx 50rpx 0;

		.sche-wrap-text {
			font-size: 24rpx;
			color: #666;
			margin-bottom: 20rpx;
		}

		.sche-bg {
			position: relative;
			background-color: #cccccc;
			height: 30rpx;
			border-radius: 100px;
			width: 480rpx;
			display: flex;
			align-items: center;

			.sche-bg-jindu {
				position: absolute;
				left: 0;
				top: 0;
				height: 30rpx;
				min-width: 40rpx;
				border-radius: 100px;
				background: url(images/round.png) #5775e7 center right 4rpx no-repeat;
				background-size: 26rpx 26rpx;
			}
		}

		.down-text {
			font-size: 24rpx;
			color: #5674e5;
			margin-top: 16rpx;
		}
	}
</style>
