// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2024 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

export default {
	data() {
		return {
			disabled: false,
			text: this.$t('Mã xác minh'),
			runTime: undefined,
			captchaType: 'clickWord'
		};
	},
	methods: {
		sendCode() {
			if (this.disabled) return;
			this.disabled = true;
			let n = 60;
			this.text = this.$t('Còn lại') + n + "s";
			this.runTime = setInterval(() => {
				n = n - 1;
				if (n < 0) {
					clearInterval(this.runTime);
					this.disabled = false;
					this.text = this.$t('đáp lại');
					return
				}
				this.text = this.$t('Còn lại') + n + "s";
			}, 1000);
		}
	},
	onHide() {
		clearInterval(this.runTime);
	}
};
