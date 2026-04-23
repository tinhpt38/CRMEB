module.exports = {
	// Cấu hình yêu cầu chương trình/APP nhỏ
	// #ifdef MP || APP-PLUS
	// Yêu cầu định dạng tên miền： https://tên miền của bạn
	HTTP_REQUEST_URL: `https://demo.crmeb.com`,
	// #endif

	// H5Yêu cầu cấu hình
	// #ifdef H5
	// Giao diện H5 là địa chỉ trình duyệt và không cần sửa đổi trừ khi được triển khai riêng.
	HTTP_REQUEST_URL: window.location.protocol + "//" + window.location.host,
	// #endif 

	// Các cấu hình sau được cung cấp trên cơ sở không cần cài đặt thứ cấp.,Không cần thực hiện bất kỳ sửa đổi nào
	HEADER: {
		'content-type': 'application/json',
		//#ifdef H5
		'Form-type': navigator.userAgent.toLowerCase().indexOf("micromessenger") !== -1 ? 'wechat' : 'h5',
		//#endif
		//#ifdef MP
		'Form-type': 'routine',
		//#endif
		//#ifdef APP-VUE
		'Form-type': 'app',
		//#endif
	},
	// Tên khóa phiên Không sửa đổi cấu hình này
	TOKENNAME: 'Authori-zation',
	// Thời gian lưu trữ 0 mãi mãi
	EXPIRE: 0,
	//Số lượng mục tối đa được hiển thị trong phân trang
	LIMIT: 10,
	// Giới hạn thời gian chờ yêu cầu: 10 giây theo mặc định
	TIMEOUT: 100000
}