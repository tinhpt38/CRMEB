// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2021 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
/**
 * diyThành phần tùy chỉnh
 * */

const bottomMenu = {
  cname: 'trình đơn dưới cùng',
  name: 'bottomMenu',
  isHide: false,
  setUp: {
    tabVal: 0,
  },
  entryConfig: {
    title: 'Nội dung dự thi',
    tabVal: 0,
    tabList: [{ name: 'mặc định' }, { name: 'Tùy chỉnh' }],
  },
  styleTitle: 'Cài đặt kiểu',

  iconColor: {
    title: 'màu biểu tượng',
    default: [{ item: '#333' }],
    color: [{ item: '#333' }],
  },
  iconSize: {
    title: 'kích thước biểu tượng',
    val: 20,
    min: 10,
    max: 50,
  },
  iconRotate: {
    title: 'góc quay',
    val: 0,
    min: 0,
    max: 360,
  },
  padding: {
    title: 'phần đệm',
    val: 0,
    min: 0,
    max: 50,
  },
  contentConfigTitle: 'Cài đặt nội dung',
  showContent: {
    title: 'Hiển thị nội dung',
    name: 'showContent',
    type: [3, 1, 2],
    list: [
      { id: 3, name: 'trang đầu', icon: 'icon-shouye6' },
      { id: 1, name: 'sưu tầm', icon: 'icon-shoucang4' },
      { id: 2, name: 'giỏ hàng', icon: 'icon-gouwuche' },
      { id: 0, name: 'dịch vụ khách hàng', icon: 'icon-kefu' },
      { id: 4, name: 'chia sẻ', icon: 'icon-fenxiang4' },
    ],
  },
  cartButton: {
    title: 'nút giỏ hàng',
    tabVal: 0,
    tabList: [{ name: 'trình diễn' }, { name: 'trốn' }],
  },
  menuConfig: {
    title: 'Bạn có thể thêm tối đa 1 ảnh, chiều rộng khuyến nghị90 * 90px',
    bnt: 'Thêm mới',
    type: 1,
    listStyle: 0,
    maxList: 100,
    list: [
      {
        img: '',
        type: 0,
        show: true,
        icon: '',
        info: [
          {
            title: 'tiêu đề',
            value: 'tiêu đề',
            tips: 'Tùy chọn, không quá 4 từ',
            max: 4,
          },
          {
            title: 'liên kết',
            value: '',
            tips: 'Vui lòng nhập liên kết',
            max: 100,
          },
        ],
      },
    ],
  },
  buttonStyleTitle: 'Cài đặt nút',
  toneConfig: {
    title: 'Màu nút',
    tabVal: 0, // 0: Follow Theme, 1: Custom
    tabList: [{ name: 'Theo dõi chủ đề' }, { name: 'Tùy chỉnh' }],
  },
  cartColor: {
    title: 'nút giỏ hàng',
    default: [{ item: '#FAAD14' }, { item: '#FAAD14' }],
    color: [{ item: '#FAAD14' }, { item: '#FAAD14' }],
  },
  buyColor: {
    title: 'nút mua',
    default: [{ item: '#E93323' }, { item: '#E93323' }],
    color: [{ item: '#E93323' }, { item: '#E93323' }],
  },
  generalStyleTitle: 'Phong cách phổ quát',
  moduleColor: {
    title: 'Nền thành phần',
    default: [{ item: '#fff' }, { item: '#fff' }],
    color: [{ item: '#fff' }, { item: '#fff' }],
  },
  bottomBgColor: {
    title: 'nền dưới cùng',
    default: [{ item: '#F5F5F5' }],
    color: [{ item: '#F5F5F5' }],
  },
  componentBgConfig: {
    title: 'Cài đặt nền',
    tabVal: 0,
    tabList: [{ name: 'màu sắc' }, { name: 'hình ảnh' }],
    colorConfig: {
      title: 'màu nền',
      default: [{ item: '#fff' }, { item: '#fff' }],
      color: [{ item: '#fff' }, { item: '#fff' }],
    },
    colorDirection: {
      title: 'Hướng dốc',
      tabVal: 0,
      tabList: [{ name: 'Nằm ngang' }, { name: 'chân dung' }, { name: 'xiên trái' }, { name: 'Nghiêng phải' }],
    },
    imageConfig: {
      header: 'hình nền',
      title: '',
      name: 'Tải ảnh lên',
      type: 'code',
      url: '',
      info: 'Kích thước đề xuất：750px * 400px',
    },
  },
  zIndexConfig: {
    title: 'Thành phần nổi',
    val: 0,
    min: 0,
  },
  borderConfig: {
    title: 'Cài đặt đường viền',
    tabVal: 0,
    tabList: [{ name: 'trốn' }, { name: 'trình diễn' }],
    val: 0, // 0: Hide, 1: Show
    styleConfig: {
      title: 'phong cách biên giới',
      tabVal: 0,
      tabList: [
        { name: 'đường liền nét', style: 'solid' },
        { name: 'đường chấm chấm', style: 'dashed' },
        { name: 'Say mê', style: 'dotted' },
      ],
    },
    widthConfig: {
      title: 'Độ dày viền',
      val: 1,
      min: 1,
    },
    colorConfig: {
      title: 'màu viền',
      default: [{ item: '#e5e5e5' }],
      color: [{ item: '#e5e5e5' }],
    },
  },
  shadowConfig: {
    title: 'Cài đặt bóng',
    tabVal: 0,
    tabList: [{ name: 'trốn' }, { name: 'trình diễn' }],
    val: 0, // 0: Off, 1: On
    colorConfig: {
      title: 'màu bóng',
      default: [{ item: 'rgba(0,0,0,0.1)' }],
      color: [{ item: 'rgba(0,0,0,0.1)' }],
    },
    xConfig: {
      title: 'Xđộ lệch trục',
      val: 0,
      min: -50,
    },
    yConfig: {
      title: 'Yđộ lệch trục',
      val: 0,
      min: -50,
    },
    blurConfig: {
      title: 'bán kính lờ mờ',
      val: 10,
      min: 0,
    },
    spreadConfig: {
      title: 'Bán kính mở rộng',
      val: 0,
      min: -50,
    },
  },
  paddingConfig: {
    title: 'phần đệm',
    isAll: false,
    val: 0,
    min: 0,
    valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
  },
  marginConfig: {
    title: 'lề',
    isAll: false,
    val: 0,
    min: 0,
    valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
  },
  fillet: {
    title: 'Nền bo tròn các góc',
    type: 0,
    list: [
      {
        val: 'Tất cả',
        icon: 'iconcaozuo-zhengti',
      },
      {
        val: 'đơn',
        icon: 'iconcaozuo-bianjiao',
      },
    ],
    valName: 'Giá trị phi lê',
    val: 0,
    min: 0,
    valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
  },
  menuPcFillet: {
    title: 'Cài đặt góc tròn',
    type: 0,
    list: [
      {
        val: 'Tất cả',
        icon: 'iconcaozuo-zhengti',
      },
      {
        val: 'đơn',
        icon: 'iconcaozuo-bianjiao',
      },
    ],
    valName: 'Giá trị phi lê',
    val: 0,
    min: 0,
    valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
  },
};

export default {
  namespaced: true,
  state: {
    configName: '',
    pageTitle: '',
    pageName: 'Tên mẫu1',
    pageShow: 1,
    pageColor: 0,
    pagePic: 0,
    pageColorPicker: '#f5f5f5',
    pageTabVal: 0,
    pagePicUrl: '',
    // Mảng dữ liệu mặc định của danh sách thành phần đã biết
    defaultArray: {},
    bottomMenu: JSON.parse(JSON.stringify(bottomMenu)),
    pageFooter: {
      cname: 'Điều hướng dưới cùng',
      name: 'pageFoot',
      setUp: {
        tabVal: 0,
      },
      titleLeft: 'Cài đặt hiển thị',
      titleNav: 'Nội dung điều hướng',
      titleRight: 'Cài đặt màu',
      titleCurrency: 'Phong cách phổ quát',
      effectConfig: {
        title: 'Hiệu ứng hiển thị',
        tabVal: 1,
        tabList: [
          {
            name: 'Mặc định hệ thống',
          },
          {
            name: 'Tùy chỉnh',
          },
        ],
      },
      navConfig: {
        title: 'Loại điều hướng',
        tabVal: 0,
        tabList: [
          {
            name: 'Cố định đáy',
          },
          {
            name: 'hệ thống treo dưới',
          },
        ],
      },
      navStyleConfig: {
        title: 'Kiểu điều hướng',
        tabVal: 0,
        tabList: [
          {
            name: 'Hình ảnh + văn bản',
          },
          {
            name: 'Từ',
          },
          {
            name: 'hình ảnh',
          },
        ],
      },
      toneConfig: {
        title: 'giai điệu',
        tabVal: 1,
        tabList: [
          {
            name: 'Theo dõi chủ đề',
          },
          {
            name: 'Tùy chỉnh',
          },
        ],
      },
      topConfig: {
        title: 'lề trên',
        val: 0,
        min: 0,
      },
      bottomConfig: {
        title: 'lề dưới',
        val: 0,
        min: 0,
      },
      prConfig: {
        title: 'lề trái và lề phải',
        val: 10,
        min: 0,
      },
      mbConfig: {
        title: 'Khoảng cách bên dưới trang',
        val: 25,
        min: 0,
      },
      fillet: {
        title: 'Nền bo tròn các góc',
        type: 0,
        list: [
          {
            val: 'Tất cả',
            icon: 'iconcaozuo-zhengti',
          },
          {
            val: 'đơn',
            icon: 'iconcaozuo-bianjiao',
          },
        ],
        valName: 'Giá trị phi lê',
        val: 30,
        min: 0,
        valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
      },
      txtColor: {
        title: 'màu văn bản',
        name: 'txtColor',
        default: [{ item: '#282828' }],
        color: [{ item: '#282828' }],
      },
      activeTxtColor: {
        title: 'Chọn màu văn bản',
        name: 'txtColor',
        default: [{ item: '#F62C2C' }],
        color: [{ item: '#F62C2C' }],
      },
      bgColor: {
        title: 'màu nền',
        name: 'bgColor',
        default: [{ item: '#fff' }],
        color: [{ item: '#fff' }],
      },
      bgColor2: {
        title: 'màu nền',
        name: 'bgColor2',
        default: [{ item: 'rgba(255,255,255,0.8)' }],
        color: [{ item: 'rgba(255,255,255,0.8)' }],
      },

      status: {
        title: 'Có nên tùy chỉnh không',
        name: 'status',
        status: false,
      },

      menuList: [
        {
          imgList: [require('@/assets/images/foot-001.png'), require('@/assets/images/foot-002.png')],
          name: 'trang đầu',
          link: '/pages/index/index',
        },
        {
          imgList: [require('@/assets/images/foot-003.png'), require('@/assets/images/foot-004.png')],
          name: 'Phân loại',
          link: '/pages/goods_cate/goods_cate',
        },
        {
          imgList: [require('@/assets/images/foot-005.png'), require('@/assets/images/foot-006.png')],
          name: 'giỏ hàng',
          link: '/pages/order_addcart/order_addcart',
        },
        {
          imgList: [require('@/assets/images/foot-007.png'), require('@/assets/images/foot-008.png')],
          name: 'của tôi',
          link: '/pages/user/index',
        },
      ],
    },
  },
  mutations: {
    FOOTER(state, data) {
      // state.pageFooter.status.title = data.title;
      state.pageFooter.menuList[2] = data.name;
    },
    UPBOTTOMMENU(state, data) {
      state.bottomMenu = data;
    },
    /**
     * @description Cấu hình mặc định được đẩy vào mảng
     * @param {Object} state vuex state
     * @param {Object} data
     * Thêm dữ liệu mặc định vào mảng mặc định để giải quyết vấn đề các thành phần trùng lặp có chung cấu hình
     */
    ADDARRAY(state, data) {
      data.val.id = 'id' + data.val.timestamp;
      state.defaultArray[data.num] = data.val;
    },
    /**
     * @description Xóa dữ liệu mặc định trong danh sách
     * @param {Object} state vuex state
     * @param {Object} data dữ liệu
     */
    DELETEARRAY(state, data) {
      let tempObj = delete state.defaultArray[data.num];
    },
    /**
     * @description Xóa dữ liệu mặc định trong danh sách
     * @param {Object} state vuex state
     * @param {Object} data dữ liệu
     */
    ARRAYREAST(state, data) {
      let tempObj = delete state.defaultArray[data];
    },
    /**
     * @description Sắp xếp mảng
     * @param {Object} state vuex state
     * @param {Object} data Bản ghi chỉ số vị trí
     */
    defaultArraySort(state, data) {
      let newArr = objToArr(state.defaultArray);
      let sortArr = [];
      let newObj = {};
      function objToArr(data) {
        let obj = Object.keys(data);
        let m = obj.map((key) => Data[key]);
        return m;
      }
      function swapArray(arr, index1, index2) {
        let oldObj = {};
        let newObj = {};
        let active = 0;
        arr.forEach((el, index) => {
          if (!el.id) {
            el.id = 'id' + el.timestamp;
          }
          data.list.forEach((item, j) => {
            if (el.id == item.id) {
              el.timestamp = item.num;
            }
          });
        });
        return arr;
      }
      if (data.oldIndex != undefined) {
        sortArr = JSON.parse(JSON.stringify(swapArray(newArr, data.newIndex, data.oldIndex)));
      } else {
        newArr.splice(data.newIndex, 0, data.element.data().defaultConfig);
        sortArr = JSON.parse(JSON.stringify(swapArray(newArr, 0, 0)));
      }
      for (let i = 0; i < sortArr.length; i++) {
        newObj[sortArr[i].timestamp] = sortArr[i];
      }
      state.defaultArray = Object.assign({}, newObj);
    },
    /**
     * @description Cập nhật một bộ dữ liệu nhất định trong mảng
     * @param {Object} state vuex state
     * @param {Object} data
     */
    UPDATEARR(state, data) {
      for (var k in state.defaultArray) {
        if (state.defaultArray[k].id == data.val.id) {
          state.defaultArray[k] = data.val;
        }
      }
      let value = Object.assign({}, state.defaultArray);
      state.defaultArray = value;
    },
    /**
     * @description Lưu tên thành phần
     * @param {Object} state vuex state
     * @param {string} data
     */
    SETCONFIGNAME(state, name) {
      state.configName = name;
    },
    /**
     * @description Xóa các thành phần mặc định
     * @param {Object} state vuex state
     * @param {string} data
     */
    SETEMPTY(state, name) {
      state.defaultArray = {};
    },
    UPTITLE(state, val) {
      state.pageTitle = val;
    },
    UPNAME(state, val) {
      state.pageName = val;
    },
    UPSHOW(state, val) {
      state.pageShow = val;
    },
    UPCOLOR(state, val) {
      state.pageColor = val;
    },
    UPPIC(state, val) {
      state.pagePic = val;
    },
    UPPICKER(state, val) {
      state.pageColorPicker = val;
    },
    UPRADIO(state, val) {
      state.pageTabVal = val;
    },
    UPPICURL(state, val) {
      state.pagePicUrl = val;
    },
    /**
     * @description Cập nhật cấu hình menu chân
     * @param {Object} state vuex state
     * @param {string} data
     */
    footUpdata(state, data) {
      state.pageFooter.menuList = [];
      state.pageFooter.menuList = data;
    },
    /**
     * @description Cập nhật switch tùy chỉnh chân
     * @param {Object} state vuex state
     * @param {string} data
     */
    footStatus(state, data) {
      // state.pageFooter.status.status = data
    },
    // Cập nhật loại điều hướng
    footType(state, data) {
      state.pageFooter.navConfig.tabVal = data;
    },
    //Lề dưới của điều hướng phía dưới；
    footBottom(state, data) {
      state.pageFooter.mbConfig.val = data;
    },
    /**
     * @description Cập nhật cấu hình chân
     * @param {Object} state vuex state
     * @param {string} data
     */
    footPageUpdata(state, data) {
      state.pageFooter = data;
    },
    /**
     * @description Cập nhật cấu hình BottomMenu
     * @param {Object} state vuex state
     * @param {string} data
     */
    bottomMenuUpdata(state, data) {
      state.bottomMenu = data;
    },
    RESET_BOTTOM_MENU(state) {
      state.bottomMenu = JSON.parse(JSON.stringify(bottomMenu));
    },
    /**
     * @description Cập nhật cấu hình tiêu đề
     * @param {Object} state vuex state
     * @param {string} data
     */
    titleUpdata(state, data) {
      state.pageTitle = data;
    },
    /**
     * @description Cập nhật cấu hình tên
     * @param {Object} state vuex state
     * @param {string} data
     */
    nameUpdata(state, data) {
      state.pageName = data;
    },
    //
    showUpdata(state, data) {
      state.pageShow = data;
    },
    colorUpdata(state, data) {
      state.pageColor = data;
    },
    picUpdata(state, data) {
      state.pagePic = data;
    },
    pickerUpdata(state, data) {
      state.pageColorPicker = data;
    },
    radioUpdata(state, data) {
      state.pageTabVal = data;
    },
    picurlUpdata(state, data) {
      state.pagePicUrl = data;
    },
  },
  actions: {
    getData({ commit }, data) {},
  },
};
