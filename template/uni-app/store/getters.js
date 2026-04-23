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
  token: state => state.app.token,
  isLogin: state => !!state.app.token,
  backgroundColor: state => state.app.backgroundColor,
  userInfo: state => state.app.userInfo || {},
	uid:state => state.app.uid,
	homeActive: state => state.app.homeActive,
	home: state => state.app.home,
	cartNum: state => state.indexData.cartNum,
	activityTab: state => state.app.activityTab,
};
// export default {
//   token: state => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJrYWlmYS5jcm1lYi5uZXQiLCJhdWQiOiJrYWlmYS5jcm1lYi5uZXQiLCJpYXQiOjE1NzcwODM1MzQsIm5iZiI6MTU3NzA4MzUzNCwiZXhwIjoxNTc3MDk0MzM0LCJqdGkiOnsiaWQiOjExMCwidHlwZSI6InVzZXIifX0.U-i1pbdRjyXI1gr79Uq2XBPZ89T8f5Ai9jwrR8woTwE',
//   isLogin: state => true,
//   backgroundColor: state => state.app.backgroundColor,
//   userInfo: state => state.app.userInfo || {}
// };
