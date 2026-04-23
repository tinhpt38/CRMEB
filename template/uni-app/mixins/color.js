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
      colorStyle: "",
      colorStatus: "",
    };
  },
  created() {
    this.colorStyle = uni.getStorageSync("viewColor");
    uni.$on("ok", (data) => {
      this.colorStyle = data;
    });
  },
  methods: {},
};
