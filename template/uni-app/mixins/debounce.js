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
		return {};
	},
	created() {},
	methods: {
		Debounce(fn, t) {
			const delay = t || 500
			let timer
			return function() {
				const args = arguments
				if (timer) {
					clearTimeout(timer)
				}
				timer = setTimeout(() => {
					timer = null
					fn.apply(this, args)
				}, delay)
			}
		}
	}
};