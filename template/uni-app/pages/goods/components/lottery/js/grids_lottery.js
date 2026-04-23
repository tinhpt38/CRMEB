
function LotteryDraw(obj, callback) {
	this.timer = null; //hẹn giờ
	this.startIndex = obj.startIndex-1 || 0; //Xổ số sẽ bắt đầu từ vị trí nào? [Mặc định là 0]
	this.count = 0; //đếm, chạy vòng
	this.winingIndex = obj.winingIndex || 0;//Vị trí đạt giải thưởng
	this.totalCount = obj.totalCount || 6;//Số vòng chạy xổ số
	this.speed = obj.speed || 100;
	this.domData=obj.domData;
	this.rollFn();
	this.callback = callback;
}

LotteryDraw.prototype = {
	rollFn: function() {
		var that = this;
		// Giá trị chỉ số hoạt động tăng lên, nghĩa là chuyển sang lưới tiếp theo
		this.startIndex++;
		
		//startIndexGiờ cuối cùng rồi. Hoàn thành vòng tròn và bắt đầu lại.
		if (this.startIndex >= this.domData.length - 1) {
			this.startIndex = 0;
			this.count++;
		}
		
		// Dừng khi số vòng chạy bằng số vòng đã đặt và giá trị chỉ số của hoạt động là vị trí của giải thưởng
		if (this.count >= this.totalCount && this.startIndex === this.winingIndex) {
			if (typeof this.callback === 'function') {
				setTimeout(function() {
					that.callback(that.startIndex,that.count); //Thực hiện chức năng gọi lại và hoàn thành các thao tác liên quan của xổ số
				}, 400);
			}
			clearInterval(this.timer);
		}else { //Bắt đầu lại một vòng tròn
			if (this.count >= this.totalCount - 1) {
				this.speed += 30;
			}
			this.timer = setTimeout(function() {
				that.callback(that.startIndex,that.count);
				that.rollFn();
			}, this.speed);
		}
	}
}

module.exports = LotteryDraw;