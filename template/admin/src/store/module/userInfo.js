// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

export default {
  namespaced: true,
  state: {
    userInfo: null,
    uniqueAuth: [],
    name: '',
    avatar: '',
    access: '',
    logo: '',
    logoSmall: '',
    version: '',
    newOrderAudioLink: '',
    pageName: '',
    //Lưu trữ dữ liệu mặc định khi xóa dữ liệu và gửi(Trực quan hóa)
    uploadListDataswiperBg: {},
    uploadListDatamenus: {},
    uploadListDataactivity: {},
    uploadListDatarecommend: {},
    uploadListDataadsRecommend: {},
    txtListData: {},
  },
  mutations: {
    //Lưu trữ dữ liệu mặc định khi xóa dữ liệu và gửi(Trực quan hóa)
    uploadListswiperBg(state, data) {
      state.uploadListDataswiperBg = data;
    },
    uploadListmenus(state, data) {
      state.uploadListDatamenus = data;
    },
    uploadListrecommend(state, data) {
      state.uploadListDatarecommend = data;
    },
    uploadListactivity(state, data) {
      state.uploadListDataactivity = data;
    },
    uploadListadsRecommend(state, data) {
      state.uploadListDataadsRecommend = data;
    },
    txtList(state, data) {
      state.txtListData = data;
    },
    //
    setPageName(state, id) {
      state.pageName = id;
    },
    userInfo(state, userInfo) {
      state.userInfo = userInfo;
    },
    userRealName(state, realName) {
      state.userInfo.real_name = realName;
    },
    userRealHeadPic(state, headPic) {
      state.userInfo.head_pic = headPic;
    },
    uniqueAuth(state, uniqueAuth) {
      state.uniqueAuth = uniqueAuth;
    },
    name(state, name) {
      state.name = name;
    },
    avatar(state, avatar) {
      state.avatar = avatar;
    },
    access(state, access) {
      state.access = access;
    },
    logo(state, logo) {
      state.logo = logo;
    },
    logoSmall(state, logoSmall) {
      state.logoSmall = logoSmall;
    },
    version(state, version) {
      state.version = version;
    },
    newOrderAudioLink(state, newOrderAudioLink) {
      state.newOrderAudioLink = newOrderAudioLink;
    },
  },
  actions: {
    getMenusNavList({ commit }) {
      return new Promise((resolve, reject) => {
        menusApi()
          .then(async (res) => {
            resolve(res);
            commit('getmenusNav', res.data.menus);
          })
          .catch((res) => {
            reject(res);
          });
      });
    },
  },
};
