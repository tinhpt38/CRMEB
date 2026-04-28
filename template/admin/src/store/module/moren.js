// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

/**
 * diyCấu hình
 * */

import toolCom from '@/components/diyComponents/index.js';

export default {
  namespaced: true,
  state: {
    activeName: {},
    defaultConfig: {
      customComponent: {
        defaultVal: {
          customComponent: {
            setUp: {
              tabVal: 0,
            },
            selectType: {
              title: 'Chọn thông tin',
              activeValue: 'user',
              list: [
                { activeValue: 'user', title: 'người dùng' },
                { activeValue: 'article', title: 'bài báo' },
                { activeValue: 'coupon', title: 'Mã giảm giá' },
                { activeValue: 'goods', title: 'sản phẩm' },
              ],
            },
            // Article Config
            articleDisplayMode: {
              title: 'Phương pháp hiển thị',
              tabVal: 0,
              tabList: [{ name: 'Ngói theo chiều dọc' }, { name: 'Trượt theo chiều ngang' }],
            },
            articleColumnStyle: {
              title: 'Sắp xếp',
              tabVal: 0,
              tabList: [{ name: '1Danh sách' }, { name: '2Danh sách' }, { name: '3Danh sách' }, { name: '4Danh sách' }],
            },
            articleDataSource: {
              title: 'Lựa chọn dữ liệu',
              tabVal: 0,
              tabList: [{ name: 'Chỉ định dữ liệu' }, { name: 'Lọc dữ liệu' }],
            },
            articleList: {
              list: [],
            },
            articleClass: {
              title: 'Phân loại bài viết',
              activeValue: '',
              list: [],
            },
            articleNum: {
              title: 'Hiển thị số lượng',
              val: 1,
              min: 1,
            },
            articleSort: {
              title: 'loại sắp xếp',
              tabVal: 0,
              tabList: [{ name: 'toàn diện' }, { name: 'Lượt xem' }, { name: 'Thời gian phát hành' }],
            },
            articleSortRule: {
              title: 'Quy tắc sắp xếp',
              tabVal: 0,
              tabList: [{ name: 'Đơn hàng tăng dần' }, { name: 'thứ tự giảm dần' }],
            },

            // Coupon Config
            couponDisplayMode: {
              title: 'Phương pháp hiển thị',
              tabVal: 0,
              tabList: [{ name: 'Ngói theo chiều dọc' }, { name: 'Trượt theo chiều ngang' }],
            },
            couponColumnStyle: {
              title: 'Sắp xếp',
              tabVal: 0,
              tabList: [{ name: '1Danh sách' }, { name: '2Danh sách' }, { name: '3Danh sách' }, { name: '4Danh sách' }],
            },
            couponDataSource: {
              title: 'Lựa chọn dữ liệu',
              tabVal: 0,
              tabList: [{ name: 'Chỉ định dữ liệu' }, { name: 'Lọc dữ liệu' }],
            },
            couponList: {
              list: [],
            },
            couponType: {
              title: 'Loại phiếu giảm giá',
              activeValue: '',
              list: [
                { activeValue: '', title: 'Tất cả' },
                { activeValue: '0', title: 'Mã giảm giá phổ quát' },
                { activeValue: '1', title: 'Mã giảm giá danh mục' },
                { activeValue: '2', title: 'phiếu giảm giá sản phẩm' },
              ],
            },
            couponSendType: {
              title: 'Phương thức gửi',
              activeValue: '',
              list: [
                { activeValue: '', title: 'Tất cả' },
                { activeValue: '1', title: 'Thu thập thủ công' },
                { activeValue: '3', title: 'phiếu quà tặng' },
              ],
            },
            couponUserType: {
              title: 'Loại người dùng',
              activeValue: '',
              list: [
                { activeValue: '', title: 'Tất cả' },
                { activeValue: '1', title: 'Người dùng thông thường' },
                { activeValue: '2', title: 'Người dùng thành viên' },
              ],
            },
            couponThreshold: {
              title: 'Ngưỡng sử dụng',
              tabVal: 0,
              tabList: [{ name: 'Không có ngưỡng' }, { name: 'Có một ngưỡng' }],
            },
            couponThresholdValue: {
              title: 'số tiền ngưỡng',
              val: 0,
              min: 0,
              max: 100000,
            },
            couponTime: {
              title: 'Thời gian thu thập',
              val: [],
            },
            couponSort: {
              title: 'loại sắp xếp',
              tabVal: 0,
              tabList: [{ name: 'Mệnh giá' }, { name: 'Thời gian phát hành' }],
            },
            couponSortRule: {
              title: 'Quy tắc sắp xếp',
              tabVal: 0,
              tabList: [{ name: 'Đơn hàng tăng dần' }, { name: 'thứ tự giảm dần' }],
            },
            couponNum: {
              title: 'Hiển thị số lượng',
              val: 1,
              min: 1,
            },

            // Goods Config
            goodsDisplayMode: {
              title: 'Phương pháp hiển thị',
              tabVal: 0,
              tabList: [{ name: 'Ngói theo chiều dọc' }, { name: 'Trượt theo chiều ngang' }],
            },
            goodsColumnStyle: {
              title: 'Sắp xếp',
              tabVal: 0,
              tabList: [{ name: '1Danh sách' }, { name: '2Danh sách' }, { name: '3Danh sách' }, { name: '4Danh sách' }],
            },
            goodsDataSource: {
              title: 'Lựa chọn dữ liệu',
              tabVal: 0,
              tabList: [{ name: 'Chỉ định dữ liệu' }, { name: 'Chỉ định danh mục' }],
            },
            goodsList: {
              title: 'Danh sách sản phẩm',
              max: 20,
              list: [],
            },
            goodsClass: {
              title: 'Danh mục sản phẩm',
              activeValue: '',
              list: [],
            },
            goodsNum: {
              title: 'Hiển thị số lượng',
              val: 6,
              min: 1,
            },
            goodsSort: {
              title: 'Danh mục sản phẩm',
              name: 'goodsSort',
              type: 0,
              list: [
                {
                  val: 'toàn diện',
                  icon: 'iconComm_whole',
                },
                {
                  val: 'Doanh số bán hàng',
                  icon: 'iconComm_number',
                },
                {
                  val: 'giá',
                  icon: 'iconjiage',
                },
              ],
            },

            // Common Styles
            paddingConfig: {
              isAll: false,
              title: 'phần đệm',
              val: 0,
              min: 0,
              max: 100,
              valList: [{ val: 10 }, { val: 10 }, { val: 10 }, { val: 10 }],
            },
            marginConfig: {
              isAll: false,
              title: 'lề',
              val: 0,
              min: 0,
              max: 100,
              valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
            },
            c_common_style: {
              color: 'rgba(255,255,255,1)',
              color2: 'rgba(255,255,255,1)',
              lr: 0,
              type: 0,
            },
          },
        },
        default: {
          customComponent: {
            setUp: {
              tabVal: 0,
            },
            selectType: {
              title: 'Chọn thông tin',
              activeValue: 'user',
              list: [
                { activeValue: 'user', title: 'người dùng' },
                { activeValue: 'article', title: 'bài báo' },
                { activeValue: 'coupon', title: 'Mã giảm giá' },
                { activeValue: 'goods', title: 'sản phẩm' },
              ],
            },
            // Article Config
            articleDisplayMode: {
              title: 'Phương pháp hiển thị',
              tabVal: 0,
              tabList: [{ name: 'Ngói theo chiều dọc' }, { name: 'Trượt theo chiều ngang' }],
            },
            articleColumnStyle: {
              title: 'Sắp xếp',
              tabVal: 0,
              tabList: [{ name: '1Danh sách' }, { name: '2Danh sách' }, { name: '3Danh sách' }, { name: '4Danh sách' }],
            },
            articleDataSource: {
              title: 'Lựa chọn dữ liệu',
              tabVal: 0,
              tabList: [{ name: 'Chỉ định dữ liệu' }, { name: 'dữ liệu động' }],
            },
            articleNum: {
              title: 'Hiển thị số lượng',
              val: 1,
              min: 1,
            },

            // Coupon Config
            couponDisplayMode: {
              title: 'Phương pháp hiển thị',
              tabVal: 0,
              tabList: [{ name: 'Ngói theo chiều dọc' }, { name: 'Trượt theo chiều ngang' }],
            },
            couponColumnStyle: {
              title: 'Sắp xếp',
              tabVal: 0,
              tabList: [{ name: '1Danh sách' }, { name: '2Danh sách' }, { name: '3Danh sách' }, { name: '4Danh sách' }],
            },
            couponDataSource: {
              title: 'Lựa chọn dữ liệu',
              tabVal: 0,
              tabList: [{ name: 'Chỉ định dữ liệu' }, { name: 'Lọc dữ liệu' }],
            },
            couponList: {
              list: [],
            },
            couponType: {
              title: 'Loại phiếu giảm giá',
              activeValue: '',
              list: [
                { activeValue: '', title: 'Tất cả' },
                { activeValue: '0', title: 'Mã giảm giá phổ quát' },
                { activeValue: '1', title: 'Mã giảm giá danh mục' },
                { activeValue: '2', title: 'phiếu giảm giá sản phẩm' },
              ],
            },
            couponSendType: {
              title: 'Phương thức gửi',
              activeValue: '',
              list: [
                { activeValue: '', title: 'Tất cả' },
                { activeValue: '1', title: 'Thu thập thủ công' },
                { activeValue: '3', title: 'phiếu quà tặng' },
              ],
            },
            couponUserType: {
              title: 'Loại người dùng',
              activeValue: '',
              list: [
                { activeValue: '', title: 'Tất cả' },
                { activeValue: '1', title: 'Người dùng thông thường' },
                { activeValue: '2', title: 'Người dùng thành viên' },
              ],
            },
            couponThreshold: {
              title: 'Ngưỡng sử dụng',
              tabVal: 0,
              tabList: [{ name: 'Không có ngưỡng' }, { name: 'Có một ngưỡng' }],
            },
            couponThresholdValue: {
              title: 'số tiền ngưỡng',
              val: 0,
              min: 0,
              max: 100000,
            },
            couponTime: {
              title: 'Thời gian thu thập',
              val: [],
            },
            couponSort: {
              title: 'loại sắp xếp',
              tabVal: 0,
              tabList: [{ name: 'Mệnh giá' }, { name: 'Thời gian phát hành' }],
            },
            couponSortRule: {
              title: 'Quy tắc sắp xếp',
              tabVal: 0,
              tabList: [{ name: 'Đơn hàng tăng dần' }, { name: 'thứ tự giảm dần' }],
            },
            couponNum: {
              title: 'Hiển thị số lượng',
              val: 1,
              min: 1,
            },

            // Goods Config
            goodsDisplayMode: {
              title: 'Phương pháp hiển thị',
              tabVal: 0,
              tabList: [{ name: 'Ngói theo chiều dọc' }, { name: 'Trượt theo chiều ngang' }],
            },
            goodsColumnStyle: {
              title: 'Sắp xếp',
              tabVal: 0,
              tabList: [{ name: '1Danh sách' }, { name: '2Danh sách' }, { name: '3Danh sách' }, { name: '4Danh sách' }],
            },
            goodsDataSource: {
              title: 'Lựa chọn dữ liệu',
              tabVal: 0,
              tabList: [{ name: 'Chỉ định dữ liệu' }, { name: 'Chỉ định danh mục' }],
            },
            goodsList: {
              title: 'Danh sách sản phẩm',
              max: 20,
              list: [],
            },
            goodsClass: {
              title: 'Danh mục sản phẩm',
              activeValue: '',
              list: [],
            },
            goodsNum: {
              title: 'Hiển thị số lượng',
              val: 6,
              min: 1,
            },
            goodsSort: {
              title: 'Danh mục sản phẩm',
              tabVal: 0,
              tabList: [{ name: 'toàn diện' }, { name: 'Doanh số bán hàng' }, { name: 'giá' }],
            },
            goodsSortRule: {
              title: 'Quy tắc sắp xếp',
              tabVal: 0,
              tabList: [{ name: 'thứ tự giảm dần' }, { name: 'Đơn hàng tăng dần' }],
            },

            // Common Styles
            paddingConfig: {
              isAll: false,
              title: 'phần đệm',
              val: 0,
              min: 0,
              max: 100,
              valList: [{ val: 10 }, { val: 10 }, { val: 10 }, { val: 10 }],
            },
            marginConfig: {
              isAll: false,
              title: 'lề',
              val: 0,
              min: 0,
              max: 100,
              valList: [{ val: 0 }, { val: 0 }, { val: 0 }, { val: 0 }],
            },
            c_common_style: {
              color: 'rgba(255,255,255,1)',
              color2: 'rgba(255,255,255,1)',
              lr: 0,
              type: 0,
            },
          },
        },
      },
      headerSerch: {
        defaultVal: {
          isShow: {
            val: true,
          },
          imgUrl: {
            title: 'Bạn có thể thêm tối đa 1 hình ảnh. Chiều rộng khuyến nghị của hình ảnh là128 * 45px',
            url: '',
          },
          titleInfo: {
            title: '',
            type: 8,
            list: [
              {
                title: 'Giới thiệu trung tâm thương mại',
                val: 'Tận hưởng mọi điều tốt đẹp theo sự lựa chọn của bạn',
                max: 20,
                pla: 'Tùy chọn, không quá 10 từ',
              },
            ],
          },
          hotList: {
            title: 'Số lượng từ nóng tối đa là 20 ký tự. Kéo dấu chấm bên trái chuột để điều chỉnh thứ tự các từ hot.',
            max: 99,
            list: [
              {
                val: '',
                maxlength: 20,
              },
            ],
          },
        },
        default: {
          isShow: {
            val: true,
          },
          imgUrl: {
            title: 'Bạn có thể thêm tối đa 1 hình ảnh. Chiều rộng khuyến nghị của hình ảnh là128 * 45px',
            url: '',
          },
          titleInfo: {
            title: '',
            type: 8,
            list: [
              {
                title: 'Giới thiệu trung tâm thương mại',
                val: 'Tận hưởng mọi điều tốt đẹp theo sự lựa chọn của bạn',
                max: 20,
                pla: 'Tùy chọn, không quá 10 từ',
              },
            ],
          },
          hotList: {
            title: 'Số lượng từ nóng tối đa là 20 ký tự. Kéo dấu chấm bên trái chuột để điều chỉnh thứ tự các từ hot.',
            max: 99,
            list: [
              {
                val: '',
                maxlength: 20,
              },
            ],
          },
        },
      },
      swiperBg: {
        defaultVal: {
          isShow: {
            val: true,
          },
          imgList: {
            title: 'Bạn có thể thêm tối đa 10 ảnh, chiều rộng khuyến nghị750px',
            max: 10,
            list: [
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: '',
                    maxlength: 10,
                    tips: 'Tùy chọn, không quá mười từ',
                  },
                  {
                    title: 'liên kết',
                    value: '',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: '',
                    maxlength: 10,
                    tips: 'Tùy chọn, không quá mười từ',
                  },
                  {
                    title: 'liên kết',
                    value: '',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
            ],
          },
        },
        default: {
          isShow: {
            val: true,
          },
          imgList: {
            title: 'Bạn có thể thêm tối đa 10 ảnh, chiều rộng khuyến nghị750px',
            max: 10,
            list: [
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: '',
                    maxlength: 10,
                    tips: 'Tùy chọn, không quá mười từ',
                  },
                  {
                    title: 'liên kết',
                    value: '',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: '',
                    maxlength: 10,
                    tips: 'Tùy chọn, không quá mười từ',
                  },
                  {
                    title: 'liên kết',
                    value: '',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
            ],
          },
        },
      },
      menus: {
        defaultVal: {
          isShow: {
            val: true,
          },
          imgList: {
            title: 'Có thể thêm tối đa 20 và chiều rộng được đề xuất của hình ảnh là 96*96px; dùng chuột kéo chấm bên trái để điều chỉnh thứ tự các biểu tượng',
            max: 20,
            list: [
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'Danh mục sản phẩm',
                    maxlength: 5,
                    tips: 'Vui lòng điền tiêu đề',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/goods_cate/goods_cate',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'Nhận phiếu giảm giá',
                    maxlength: 5,
                    tips: 'Vui lòng điền tiêu đề',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/users/user_get_coupon/index',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'Thông tin ngành',
                    maxlength: 5,
                    tips: 'Vui lòng điền tiêu đề',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/extension/news_list/index',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'bộ sưu tập của tôi',
                    maxlength: 5,
                    tips: 'Vui lòng điền tiêu đề',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/users/user_goods_collection/index',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'Hoạt động nhóm',
                    maxlength: 5,
                    tips: 'Vui lòng điền tiêu đề',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/activity/goods_combination/index',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'hoạt động flash sale',
                    maxlength: 5,
                    tips: 'Vui lòng điền tiêu đề',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/activity/goods_seckill/index',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'Hoạt động mặc cả',
                    maxlength: 5,
                    tips: 'Vui lòng điền tiêu đề',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/activity/goods_bargain/index',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'Quản lý địa chỉ',
                    maxlength: 5,
                    tips: 'Vui lòng điền tiêu đề',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/activity/goods_bargain/index',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
            ],
          },
        },
        default: {
          isShow: {
            val: true,
          },
          imgList: {
            title: 'Có thể thêm tối đa 20 và chiều rộng được đề xuất của hình ảnh là 96*96px; dùng chuột kéo chấm bên trái để điều chỉnh thứ tự các biểu tượng',
            max: 20,
            list: [
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'Danh mục sản phẩm',
                    maxlength: 5,
                    tips: 'Vui lòng điền tiêu đề',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/goods_cate/goods_cate',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'Nhận phiếu giảm giá',
                    maxlength: 5,
                    tips: 'Vui lòng điền tiêu đề',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/users/user_get_coupon/index',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'Thông tin ngành',
                    maxlength: 5,
                    tips: 'Vui lòng điền tiêu đề',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/extension/news_list/index',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'bộ sưu tập của tôi',
                    maxlength: 5,
                    tips: 'Vui lòng điền tiêu đề',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/users/user_goods_collection/index',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'Hoạt động nhóm',
                    maxlength: 5,
                    tips: 'Vui lòng điền tiêu đề',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/activity/goods_combination/index',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'hoạt động flash sale',
                    maxlength: 5,
                    tips: 'Vui lòng điền tiêu đề',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/activity/goods_seckill/index',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'Hoạt động mặc cả',
                    maxlength: 5,
                    tips: 'Vui lòng điền tiêu đề',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/activity/goods_bargain/index',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'Quản lý địa chỉ',
                    maxlength: 5,
                    tips: 'Vui lòng điền tiêu đề',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/activity/goods_bargain/index',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
            ],
          },
        },
      },

      tabNav: {
        defaultVal: {
          isShow: {
            val: true,
          },
        },
        default: {
          isShow: {
            val: true,
          },
        },
      },
      news: {
        defaultVal: {
          isShow: {
            val: true,
          },
          imgUrl: {
            title: 'Có thể thêm tối đa 10 mẫu và chiều rộng được đề xuất cho hình ảnh là124 * 28px',
            url: '',
          },
          newList: {
            max: 10,
            list: [
              {
                chiild: [
                  {
                    title: 'tiêu đề',
                    val: 'CRMEB_PRO 1.1Phiên bản beta mở chính thức hiện nay',
                    max: 20,
                    pla: 'Tùy chọn, không quá bốn từ',
                  },
                  {
                    title: 'liên kết',
                    val: 'liên kết',
                    max: 99,
                    pla: 'Không bắt buộc',
                  },
                ],
              },
            ],
          },
        },
        default: {
          isShow: {
            val: true,
          },
          imgUrl: {
            title: 'Có thể thêm tối đa 10 mẫu và chiều rộng được đề xuất cho hình ảnh là124 * 28px',
            url: '',
          },
          newList: {
            max: 10,
            list: [
              {
                chiild: [
                  {
                    title: 'tiêu đề',
                    val: 'CRMEB_PRO 1.1Phiên bản beta mở chính thức hiện nay',
                    max: 20,
                    pla: 'Tùy chọn, không quá bốn từ',
                  },
                  {
                    title: 'liên kết',
                    val: 'liên kết',
                    max: 99,
                    pla: 'Không bắt buộc',
                  },
                ],
              },
            ],
          },
        },
      },
      activity: {
        defaultVal: {
          isShow: {
            val: true,
          },
          imgList: {
            isDelete: true,
            title: 'Có thể thêm tối đa 3 nhóm mô-đun, nhóm đầu tiên336*298px,Hai cái cuối cùng416*124px',
            max: 3,
            list: [
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'Hãy tham gia cùng nhau',
                    maxlength: 20,
                    tips: 'tiêu đề',
                  },
                  {
                    title: 'mô tả',
                    value: 'Rất nhiều ưu đãi',
                    maxlength: 20,
                    tips: 'mô tả',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/activity/goods_combination/index',
                    maxlength: 999,
                    tips: 'liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'Khu vực flash sale',
                    maxlength: 20,
                    tips: 'tiêu đề',
                  },
                  {
                    title: 'mô tả',
                    value: 'Có nhiều ưu đãi giảm giá cho xe sử dụng năng lượng mới',
                    maxlength: 20,
                    tips: 'mô tả',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/activity/goods_seckill/index',
                    maxlength: 999,
                    tips: 'liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'Hoạt động mặc cả',
                    maxlength: 20,
                    tips: 'tiêu đề',
                  },
                  {
                    title: 'mô tả',
                    value: 'Mời bạn bè thương lượng giá~~',
                    maxlength: 20,
                    tips: 'mô tả',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/activity/goods_bargain/index',
                    maxlength: 999,
                    tips: 'liên kết',
                  },
                ],
              },
            ],
          },
          max: 3,
        },
        default: {
          isShow: {
            val: true,
          },
          imgList: {
            isDelete: true,
            title: 'Có thể thêm tối đa 3 nhóm mô-đun, nhóm đầu tiên336*298px,Hai cái cuối cùng416*124px',
            max: 3,
            list: [
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'Hãy tham gia cùng nhau',
                    maxlength: 20,
                    tips: 'tiêu đề',
                  },
                  {
                    title: 'mô tả',
                    value: 'Rất nhiều ưu đãi',
                    maxlength: 20,
                    tips: 'mô tả',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/activity/goods_combination/index',
                    maxlength: 999,
                    tips: 'liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'Khu vực flash sale',
                    maxlength: 20,
                    tips: 'tiêu đề',
                  },
                  {
                    title: 'mô tả',
                    value: 'Có nhiều ưu đãi giảm giá cho xe sử dụng năng lượng mới',
                    maxlength: 20,
                    tips: 'mô tả',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/activity/goods_seckill/index',
                    maxlength: 999,
                    tips: 'liên kết',
                  },
                ],
              },
              {
                img: '',
                info: [
                  {
                    title: 'tiêu đề',
                    value: 'Hoạt động mặc cả',
                    maxlength: 20,
                    tips: 'tiêu đề',
                  },
                  {
                    title: 'mô tả',
                    value: 'Mời bạn bè thương lượng giá~~',
                    maxlength: 20,
                    tips: 'mô tả',
                  },
                  {
                    title: 'liên kết',
                    value: '/pages/activity/goods_bargain/index',
                    maxlength: 999,
                    tips: 'liên kết',
                  },
                ],
              },
            ],
          },
          max: 3,
        },
      },
      alive: {
        defaultVal: {
          isShow: {
            val: true,
          },
          titleInfo: {
            title: '',
            list: [
              {
                title: 'tiêu đề',
                val: 'Phòng phát sóng trực tiếp',
                max: 20,
                pla: 'Tùy chọn, không quá sáu từ',
              },
              {
                title: 'giới thiệu',
                val: 'Truyền hình trực tiếp tuyệt vời',
                max: 8,
                pla: 'Tùy chọn, không quá 8 từ',
              },
              {
                title: 'liên kết',
                val: '/pages/columnGoods/live_list/index',
                max: 999,
                pla: 'Không bắt buộc',
              },
            ],
          },
          numConfig: {
            title: 'Hiển thị số lượng',
            val: 3,
          },
        },
        default: {
          isShow: {
            val: true,
          },
          titleInfo: {
            title: '',
            list: [
              {
                title: 'tiêu đề',
                val: 'Phòng phát sóng trực tiếp',
                max: 20,
                pla: 'Tùy chọn, không quá sáu từ',
              },
              {
                title: 'giới thiệu',
                val: 'Truyền hình trực tiếp tuyệt vời',
                max: 8,
                pla: 'Tùy chọn, không quá 8 từ',
              },
              {
                title: 'liên kết',
                val: '/pages/columnGoods/live_list/index',
                max: 999,
                pla: 'Không bắt buộc',
              },
            ],
          },
          numConfig: {
            title: 'Hiển thị số lượng',
            val: 3,
          },
        },
      },
      scrollBox: {
        defaultVal: {
          isShow: {
            val: true,
          },
          titleInfo: {
            title: '',
            list: [
              {
                title: 'tiêu đề',
                val: 'Lựa chọn nhanh',
                max: 4,
                pla: 'Tùy chọn, không quá 4 từ',
              },
              {
                title: 'giới thiệu',
                val: 'Trân trọng giới thiệu sản phẩm chất lượng',
                max: 8,
                pla: 'Tùy chọn, không quá 8 từ',
              },
              {
                title: 'liên kết',
                val: '/pages/goods_cate/goods_cate',
                max: 999,
                pla: 'Không bắt buộc',
              },
            ],
          },
          // tabConfig: {
          //     tabVal: 0,
          //     type: 1,
          //     tabList: [
          //         {
          //             name: 'lựa chọn tự động',
          //             icon: 'iconzidongxuanze'
          //         },
          //         {
          //             name: 'Lựa chọn thủ công',
          //             icon: 'iconshoudongxuanze'
          //         }
          //     ]
          // },
          // selectConfig: {
          //     title: 'Phân loại sản phẩm',
          //     type: 1,//type=1Khi chỉ vượt qua phân loại thứ cấp
          //     activeValue: '',
          //     list: [
          //         {
          //             activeValue: '',
          //             title: ''
          //         },
          //         {
          //             activeValue: '',
          //             title: ''
          //         }
          //     ]
          // },
          // numConfig: {
          //     title:'Hiển thị số lượng',
          //     val: 6
          // },
          // goodsList: {
          //     max: 20,
          //     list: []
          // }
        },
        default: {
          isShow: {
            val: true,
          },
          titleInfo: {
            title: '',
            list: [
              {
                title: 'tiêu đề',
                val: 'Lựa chọn nhanh',
                max: 4,
                pla: 'Tùy chọn, không quá 4 từ',
              },
              {
                title: 'giới thiệu',
                val: 'Trân trọng giới thiệu sản phẩm chất lượng',
                max: 8,
                pla: 'Tùy chọn, không quá 8 từ',
              },
              {
                title: 'liên kết',
                val: '/pages/goods_cate/goods_cate',
                max: 999,
                pla: 'Không bắt buộc',
              },
            ],
          },
          // tabConfig: {
          //     tabVal: 0,
          //     type: 1,
          //     tabList: [
          //         {
          //             name: 'lựa chọn tự động',
          //             icon: 'iconzidongxuanze'
          //         },
          //         {
          //             name: 'Lựa chọn thủ công',
          //             icon: 'iconshoudongxuanze'
          //         }
          //     ]
          // },
          // selectConfig: {
          //     title: 'Phân loại sản phẩm',
          //     type: 1,//type=1Khi chỉ vượt qua phân loại thứ cấp
          //     activeValue: '',
          //     list: [
          //         {
          //             activeValue: '',
          //             title: ''
          //         },
          //         {
          //             activeValue: '',
          //             title: ''
          //         }
          //     ]
          // },
          // numConfig: {
          //     title:'Hiển thị số lượng',
          //     val: 6
          // },
          // goodsList: {
          //     max: 20,
          //     list: []
          // }
        },
      },
      adsRecommend: {
        defaultVal: {
          isShow: {
            val: true,
          },
          imgList: {
            title: 'Kích thước hình ảnh được đề xuất là 338 * 206px; kéo dấu chấm bên trái để điều chỉnh thứ tự các phần.',
            max: 10,
            list: [
              {
                img: '',
                info: [
                  {
                    title: 'liên kết',
                    value: '',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
            ],
          },
        },
        default: {
          isShow: {
            val: true,
          },
          imgList: {
            title: 'Kích thước hình ảnh được đề xuất là 338 * 206px; kéo dấu chấm bên trái để điều chỉnh thứ tự các phần.',
            max: 10,
            list: [
              {
                img: '',
                info: [
                  {
                    title: 'liên kết',
                    value: '',
                    maxlength: 999,
                    tips: 'Vui lòng điền vào liên kết',
                  },
                ],
              },
            ],
          },
        },
      },
      coupon: {
        defaultVal: {
          isShow: {
            val: true,
          },
          numConfig: {
            val: 10,
          },
        },
        default: {
          isShow: {
            val: true,
          },
          numConfig: {
            val: 10,
          },
        },
      },
      seckill: {
        defaultVal: {
          isShow: {
            val: true,
          },
          tabConfig: {
            tabVal: 0,
            type: 1,
            tabList: [
              {
                name: 'lựa chọn tự động',
                icon: 'iconzidongxuanze',
              },
              {
                name: 'Lựa chọn thủ công',
                icon: 'iconshoudongxuanze',
              },
            ],
          },
          titleInfo: {
            title: '',
            type: 2,
            list: [
              {
                title: 'Loại sản phẩm',
                val: 'Khuyến mại chớp nhoáng trong thời gian có hạn',
                max: 20,
                pla: 'Tùy chọn, không quá bốn từ',
              },
            ],
          },
          selectConfig: {
            title: 'Danh mục sản phẩm',
            activeValue: '',
            list: [
              {
                activeValue: '',
                title: '',
              },
              {
                activeValue: '',
                title: '',
              },
            ],
          },
          numConfig: {
            val: 6,
          },
          goodsSort: {
            title: 'Danh mục sản phẩm',
            name: 'goodsSort',
            type: 0,
            list: [
              {
                val: 'Sắp xếp hệ thống',
                icon: 'iconComm_whole',
              },
              {
                val: 'doanh số bán hàng cao nhất',
                icon: 'iconComm_number',
              },
              {
                val: 'Sự xuất hiện mới nhất',
                icon: 'iconzuixin',
              },
            ],
          },
          goodsList: {
            max: 20,
            list: [],
          },
        },
        default: {
          isShow: {
            val: true,
          },
          tabConfig: {
            tabVal: 0,
            type: 1,
            tabList: [
              {
                name: 'lựa chọn tự động',
                icon: 'iconzidongxuanze',
              },
              {
                name: 'Lựa chọn thủ công',
                icon: 'iconshoudongxuanze',
              },
            ],
          },
          titleInfo: {
            title: '',
            type: 2,
            list: [
              {
                title: 'Loại sản phẩm',
                val: 'Khuyến mại chớp nhoáng trong thời gian có hạn',
                max: 20,
                pla: 'Tùy chọn, không quá bốn từ',
              },
            ],
          },
          selectConfig: {
            title: 'Danh mục sản phẩm',
            activeValue: '',
            list: [
              {
                activeValue: '',
                title: '',
              },
              {
                activeValue: '',
                title: '',
              },
            ],
          },
          numConfig: {
            val: 6,
          },
          goodsSort: {
            title: 'Danh mục sản phẩm',
            name: 'goodsSort',
            type: 0,
            list: [
              {
                val: 'Sắp xếp hệ thống',
                icon: 'iconComm_whole',
              },
              {
                val: 'doanh số bán hàng cao nhất',
                icon: 'iconComm_number',
              },
              {
                val: 'Sự xuất hiện mới nhất',
                icon: 'iconzuixin',
              },
            ],
          },
          goodsList: {
            max: 20,
            list: [],
          },
        },
      },
      combination: {
        defaultVal: {
          isShow: {
            val: true,
          },
          tabConfig: {
            tabVal: 0,
            type: 1,
            tabList: [
              {
                name: 'lựa chọn tự động',
                icon: 'iconzidongxuanze',
              },
              {
                name: 'Lựa chọn thủ công',
                icon: 'iconshoudongxuanze',
              },
            ],
          },
          titleInfo: {
            title: '',
            type: 3,
            list: [
              {
                title: 'Loại sản phẩm',
                val: 'Đơn hàng mua chung',
                max: 20,
                pla: 'Tùy chọn, không quá bốn từ',
              },
            ],
          },
          selectConfig: {
            title: 'Danh mục sản phẩm',
            activeValue: '',
            list: [
              {
                activeValue: '',
                title: '',
              },
              {
                activeValue: '',
                title: '',
              },
            ],
          },
          numConfig: {
            val: 6,
          },
          goodsSort: {
            title: 'Danh mục sản phẩm',
            name: 'goodsSort',
            type: 0,
            list: [
              {
                val: 'Sắp xếp hệ thống',
                icon: 'iconComm_whole',
              },
              {
                val: 'doanh số bán hàng cao nhất',
                icon: 'iconComm_number',
              },
              {
                val: 'Sự xuất hiện mới nhất',
                icon: 'iconzuixin',
              },
            ],
          },
          goodsList: {
            max: 20,
            list: [],
          },
        },
        default: {
          isShow: {
            val: true,
          },
          tabConfig: {
            tabVal: 0,
            type: 1,
            tabList: [
              {
                name: 'lựa chọn tự động',
                icon: 'iconzidongxuanze',
              },
              {
                name: 'Lựa chọn thủ công',
                icon: 'iconshoudongxuanze',
              },
            ],
          },
          titleInfo: {
            title: '',
            type: 3,
            list: [
              {
                title: 'Loại sản phẩm',
                val: 'Đơn hàng mua chung',
                max: 20,
                pla: 'Tùy chọn, không quá bốn từ',
              },
            ],
          },
          selectConfig: {
            title: 'Danh mục sản phẩm',
            activeValue: '',
            list: [
              {
                activeValue: '',
                title: '',
              },
              {
                activeValue: '',
                title: '',
              },
            ],
          },
          numConfig: {
            val: 6,
          },
          goodsSort: {
            title: 'Danh mục sản phẩm',
            name: 'goodsSort',
            type: 0,
            list: [
              {
                val: 'Sắp xếp hệ thống',
                icon: 'iconComm_whole',
              },
              {
                val: 'doanh số bán hàng cao nhất',
                icon: 'iconComm_number',
              },
              {
                val: 'Sự xuất hiện mới nhất',
                icon: 'iconzuixin',
              },
            ],
          },
          goodsList: {
            max: 20,
            list: [],
          },
        },
      },
      bargain: {
        defaultVal: {
          isShow: {
            val: true,
          },
          tabConfig: {
            tabVal: 0,
            type: 1,
            tabList: [
              {
                name: 'lựa chọn tự động',
                icon: 'iconzidongxuanze',
              },
              {
                name: 'Lựa chọn thủ công',
                icon: 'iconshoudongxuanze',
              },
            ],
          },
          titleInfo: {
            title: '',
            type: 8,
            list: [
              {
                title: 'Loại sản phẩm',
                val: 'Lịch sử trả giá',
                max: 20,
                pla: 'Tùy chọn, không quá bốn từ',
              },
            ],
          },
          selectConfig: {
            title: 'Danh mục sản phẩm',
            activeValue: '',
            list: [
              {
                activeValue: '',
                title: '',
              },
              {
                activeValue: '',
                title: '',
              },
            ],
          },
          numConfig: {
            val: 6,
          },
          goodsSort: {
            title: 'Danh mục sản phẩm',
            name: 'goodsSort',
            type: 0,
            list: [
              {
                val: 'Sắp xếp hệ thống',
                icon: 'iconComm_whole',
              },
              {
                val: 'doanh số bán hàng cao nhất',
                icon: 'iconComm_number',
              },
              {
                val: 'Sự xuất hiện mới nhất',
                icon: 'iconzuixin',
              },
            ],
          },
          goodsList: {
            max: 20,
            list: [],
          },
        },
        default: {
          isShow: {
            val: true,
          },
          tabConfig: {
            tabVal: 0,
            type: 1,
            tabList: [
              {
                name: 'lựa chọn tự động',
                icon: 'iconzidongxuanze',
              },
              {
                name: 'Lựa chọn thủ công',
                icon: 'iconshoudongxuanze',
              },
            ],
          },
          titleInfo: {
            title: '',
            type: 8,
            list: [
              {
                title: 'Loại sản phẩm',
                val: 'Lịch sử trả giá',
                max: 20,
                pla: 'Tùy chọn, không quá bốn từ',
              },
            ],
          },
          selectConfig: {
            title: 'Danh mục sản phẩm',
            activeValue: '',
            list: [
              {
                activeValue: '',
                title: '',
              },
              {
                activeValue: '',
                title: '',
              },
            ],
          },
          numConfig: {
            val: 6,
          },
          goodsSort: {
            title: 'Danh mục sản phẩm',
            name: 'goodsSort',
            type: 0,
            list: [
              {
                val: 'Sắp xếp hệ thống',
                icon: 'iconComm_whole',
              },
              {
                val: 'doanh số bán hàng cao nhất',
                icon: 'iconComm_number',
              },
              {
                val: 'Sự xuất hiện mới nhất',
                icon: 'iconzuixin',
              },
            ],
          },
          goodsList: {
            max: 20,
            list: [],
          },
        },
      },
      goodList: {
        defaultVal: {
          isShow: {
            val: true,
          },
          titleInfo: {
            title: '',
            list: [
              {
                title: 'tiêu đề',
                val: 'Lựa chọn nhanh',
                max: 4,
                pla: 'Tùy chọn, không quá 4 từ',
              },
              {
                title: 'giới thiệu',
                val: 'Trân trọng giới thiệu sản phẩm chất lượng',
                max: 8,
                pla: 'Tùy chọn, không quá 8 từ',
              },
              {
                title: 'liên kết',
                val: '/pages/columnGoods/HotNewGoods/index',
                max: 999,
                pla: 'Không bắt buộc',
              },
            ],
          },
          tabConfig: {
            tabVal: 0,
            type: 1,
            tabList: [
              {
                name: 'lựa chọn tự động',
                icon: 'iconzidongxuanze',
              },
              {
                name: 'Lựa chọn thủ công',
                icon: 'iconshoudongxuanze',
              },
            ],
          },
          selectSortConfig: {
            title: 'Loại sản phẩm',
            activeValue: '',
            list: [
              {
                activeValue: '0',
                title: 'Danh sách sản phẩm',
              },
              // {
              //   activeValue: '4',
              //   title: 'Danh sách phổ biến',
              // },
              // {
              //   activeValue: '5',
              //   title: 'Sản phẩm mới đầu tiên',
              // },
              // {
              //   activeValue: '6',
              //   title: 'Mặt hàng khuyến mại',
              // },
              {
                activeValue: '7',
                title: 'Sản phẩm được đề xuất',
              },
            ],
          },
          selectConfig: {
            title: 'Danh mục sản phẩm',
            activeValue: '',
            list: [
              {
                activeValue: '',
                title: '',
              },
              {
                activeValue: '',
                title: '',
              },
            ],
          },
          numConfig: {
            val: 6,
          },
          goodsSort: {
            title: 'Danh mục sản phẩm',
            name: 'goodsSort',
            type: 0,
            list: [
              {
                val: 'Sắp xếp hệ thống',
                icon: 'iconComm_whole',
              },
              {
                val: 'doanh số bán hàng cao nhất',
                icon: 'iconComm_number',
              },
              {
                val: 'Sự xuất hiện mới nhất',
                icon: 'iconzuixin',
              },
            ],
          },
          goodsList: {
            max: 20,
            list: [],
          },
        },
        default: {
          isShow: {
            val: true,
          },
          titleInfo: {
            title: '',
            list: [
              {
                title: 'tiêu đề',
                val: 'Lựa chọn nhanh',
                max: 4,
                pla: 'Tùy chọn, không quá 4 từ',
              },
              {
                title: 'giới thiệu',
                val: 'Trân trọng giới thiệu sản phẩm chất lượng',
                max: 8,
                pla: 'Tùy chọn, không quá 8 từ',
              },
              {
                title: 'liên kết',
                val: '/pages/columnGoods/HotNewGoods/index?type=1',
                max: 999,
                pla: 'Không bắt buộc',
              },
            ],
          },
          tabConfig: {
            tabVal: 0,
            type: 1,
            tabList: [
              {
                name: 'lựa chọn tự động',
                icon: 'iconzidongxuanze',
              },
              {
                name: 'Lựa chọn thủ công',
                icon: 'iconshoudongxuanze',
              },
            ],
          },
          selectSortConfig: {
            title: 'Loại sản phẩm',
            activeValue: '',
            list: [
              {
                activeValue: '0',
                title: 'Danh sách sản phẩm',
              },
              // {
              //   activeValue: '4',
              //   title: 'Danh sách phổ biến',
              // },
              // {
              //   activeValue: '5',
              //   title: 'Sản phẩm mới đầu tiên',
              // },
              // {
              //   activeValue: '6',
              //   title: 'Mặt hàng khuyến mại',
              // },
              {
                activeValue: '7',
                title: 'Sản phẩm được đề xuất',
              },
            ],
          },
          selectConfig: {
            title: 'Danh mục sản phẩm',
            activeValue: '',
            list: [
              {
                activeValue: '',
                title: '',
              },
              {
                activeValue: '',
                title: '',
              },
            ],
          },
          numConfig: {
            val: 6,
          },
          goodsSort: {
            title: 'Danh mục sản phẩm',
            name: 'goodsSort',
            type: 0,
            list: [
              {
                val: 'Sắp xếp hệ thống',
                icon: 'iconComm_whole',
              },
              {
                val: 'doanh số bán hàng cao nhất',
                icon: 'iconComm_number',
              },
              {
                val: 'Sự xuất hiện mới nhất',
                icon: 'iconzuixin',
              },
            ],
          },
          goodsList: {
            max: 20,
            list: [],
          },
        },
      },
      picTxt: {
        defaultVal: {
          isShow: {
            val: true,
          },
          richText: {
            val: '',
          },
        },
        default: {
          isShow: {
            val: true,
          },
          richText: {
            val: '',
          },
        },
      },
      titles: {
        defaultVal: {
          isShow: {
            val: true,
          },
          titleInfo: {
            title: '',
            list: [
              {
                title: 'tiêu đề',
                val: 'Sản phẩm được đề xuất',
                max: 20,
                pla: 'Tùy chọn, không quá bốn từ',
              },
              {
                title: 'tiêu đề',
                val: 'Sản phẩm được đề xuất',
                max: 20,
                pla: 'Tùy chọn, không quá bốn từ',
              },
              {
                title: 'liên kết',
                val: '/pages/columnGoods/HotNewGoods/index?type=1',
                max: 999,
                pla: 'Không bắt buộc',
              },
            ],
          },
        },
        default: {
          isShow: {
            val: true,
          },
          titleInfo: {
            title: '',
            list: [
              {
                title: 'tiêu đề',
                val: 'Sản phẩm được đề xuất',
                max: 20,
                pla: 'Tùy chọn, không quá bốn từ',
              },
              {
                title: 'tiêu đề',
                val: 'Sản phẩm được đề xuất',
                max: 20,
                pla: 'Tùy chọn, không quá bốn từ',
              },
              {
                title: 'liên kết',
                val: '/pages/columnGoods/HotNewGoods/index?type=1',
                max: 999,
                pla: 'Không bắt buộc',
              },
            ],
          },
        },
      },
      customerService: {
        defaultVal: {
          isShow: {
            val: true,
          },
          imgUrl: {
            title: 'Bạn có thể thêm tối đa 1 hình ảnh. Chiều rộng khuyến nghị của hình ảnh là128 * 45px',
            url: '',
          },
        },
        default: {
          isShow: {
            val: true,
          },
          imgUrl: {
            title: 'Bạn có thể thêm tối đa 1 hình ảnh. Chiều rộng khuyến nghị của hình ảnh là128 * 45px',
            url: '',
          },
        },
      },
      tabBar: {
        defaultVal: {
          isShow: {
            val: true,
          },
          tabBarList: {
            title: 'Chiều rộng hình ảnh được đề xuất81*81px',
            list: [
              {
                name: 'trang đầu',
                imgList: [
                  'https://qiniu.crmeb.net/attach/2021/04/9ebdf202104251644215768.png',
                  'https://qiniu.crmeb.net/attach/2021/04/44bc420210425164421586.png',
                ],
                link: '/pages/index/index',
              },
              {
                name: 'Phân loại',
                imgList: [
                  'https://qiniu.crmeb.net/attach/2021/04/b62c8202104251644218412.png',
                  'https://qiniu.crmeb.net/attach/2021/04/9509c202104251644214836.png',
                ],
                link: '/pages/goods_cate/goods_cate',
              },
              // {
              //     name:'xung quanh',
              //     imgList:[require('@/assets/images/foo3-01.png'),require('@/assets/images/foo3-02.png')],
              //     pagePath: ''
              // },
              {
                name: 'giỏ hàng',
                imgList: [
                  'https://qiniu.crmeb.net/attach/2021/04/2e682202104251644216849.png',
                  'https://qiniu.crmeb.net/attach/2021/04/6b3cb202104251644218211.png',
                ],
                link: '/pages/order_addcart/order_addcart',
              },
              {
                name: 'của tôi',
                imgList: [
                  'https://qiniu.crmeb.net/attach/2021/04/3329c20210425164421428.png',
                  'https://qiniu.crmeb.net/attach/2021/04/031ce202104251644215432.png',
                ],
                link: '/pages/user/index',
              },
            ],
          },
        },
        default: {
          isShow: {
            val: true,
          },
          tabBarList: {
            title: 'Chiều rộng hình ảnh được đề xuất81*81px',
            list: [
              {
                name: 'trang đầu',
                imgList: [
                  'https://qiniu.crmeb.net/attach/2021/04/9ebdf202104251644215768.png',
                  'https://qiniu.crmeb.net/attach/2021/04/44bc420210425164421586.png',
                ],
                link: '/pages/index/index',
              },
              {
                name: 'Phân loại',
                imgList: [
                  'https://qiniu.crmeb.net/attach/2021/04/b62c8202104251644218412.png',
                  'https://qiniu.crmeb.net/attach/2021/04/9509c202104251644214836.png',
                ],
                link: '/pages/goods_cate/goods_cate',
              },
              // {
              //     name:'xung quanh',
              //     imgList:[require('@/assets/images/foo3-01.png'),require('@/assets/images/foo3-02.png')],
              //     pagePath: ''
              // },
              {
                name: 'giỏ hàng',
                imgList: [
                  'https://qiniu.crmeb.net/attach/2021/04/2e682202104251644216849.png',
                  'https://qiniu.crmeb.net/attach/2021/04/6b3cb202104251644218211.png',
                ],
                link: '/pages/order_addcart/order_addcart',
              },
              {
                name: 'của tôi',
                imgList: [
                  'https://qiniu.crmeb.net/attach/2021/04/3329c20210425164421428.png',
                  'https://qiniu.crmeb.net/attach/2021/04/031ce202104251644215432.png',
                ],
                link: '/pages/user/index',
              },
            ],
          },
        },
      },
    },

    component: {
      customComponent: {
        list: [
          {
            components: toolCom.c_custom_component,
            configNme: 'customComponent',
          },
        ],
      },
      headerSerch: {
        list: [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
          {
            components: toolCom.c_input_list,
            configNme: 'titleInfo',
          },
          {
            components: toolCom.c_upload_img,
            configNme: 'imgUrl',
          },
          {
            components: toolCom.c_hot_word,
            configNme: 'hotList',
          },
        ],
      },
      swiperBg: {
        list: [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
          {
            components: toolCom.c_upload_list,
            configNme: 'imgList',
          },
        ],
      },
      menus: {
        list: [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
          {
            components: toolCom.c_upload_list,
            configNme: 'imgList',
          },
        ],
      },
      news: {
        list: [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
          {
            components: toolCom.c_upload_img,
            configNme: 'imgUrl',
          },
          {
            components: toolCom.c_txt_list,
            configNme: 'newList',
          },
        ],
      },
      tabNav: {
        list: [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
        ],
      },
      activity: {
        list: [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
          {
            components: toolCom.c_upload_list,
            configNme: 'imgList',
          },
        ],
      },
      alive: {
        list: [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
          {
            components: toolCom.c_input_list,
            configNme: 'titleInfo',
          },
          {
            components: toolCom.c_input_number,
            configNme: 'numConfig',
          },
        ],
      },
      scrollBox: {
        list: [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
          {
            components: toolCom.c_input_list,
            configNme: 'titleInfo',
          },
          // {
          //     components: toolCom.c_tab,
          //     configNme: 'tabConfig'
          // },
          // {
          //     components: toolCom.c_select,
          //     configNme: 'selectConfig'
          // },
          // {
          //     components: toolCom.c_input_number,
          //     configNme: 'numConfig'
          // }
        ],
      },
      adsRecommend: {
        list: [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
          {
            components: toolCom.c_upload_list,
            configNme: 'imgList',
          },
        ],
      },
      coupon: {
        list: [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
          {
            components: toolCom.c_input_number,
            configNme: 'numConfig',
          },
        ],
      },
      seckill: {
        list: [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
          {
            components: toolCom.c_tab,
            configNme: 'tabConfig',
          },
          {
            components: toolCom.c_input_list,
            configNme: 'titleInfo',
          },
          {
            components: toolCom.c_select,
            configNme: 'selectConfig',
          },
          {
            components: toolCom.c_input_number,
            configNme: 'numConfig',
          },
          {
            components: toolCom.c_txt_tab,
            configNme: 'goodsSort',
          },
        ],
      },
      combination: {
        list: [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
          {
            components: toolCom.c_tab,
            configNme: 'tabConfig',
          },
          {
            components: toolCom.c_input_list,
            configNme: 'titleInfo',
          },
          {
            components: toolCom.c_select,
            configNme: 'selectConfig',
          },
          {
            components: toolCom.c_input_number,
            configNme: 'numConfig',
          },
          {
            components: toolCom.c_txt_tab,
            configNme: 'goodsSort',
          },
        ],
      },
      bargain: {
        list: [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
          {
            components: toolCom.c_tab,
            configNme: 'tabConfig',
          },
          {
            components: toolCom.c_input_list,
            configNme: 'titleInfo',
          },
          {
            components: toolCom.c_select,
            configNme: 'selectConfig',
          },
          {
            components: toolCom.c_input_number,
            configNme: 'numConfig',
          },
          {
            components: toolCom.c_txt_tab,
            configNme: 'goodsSort',
          },
        ],
      },
      goodList: {
        list: [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
          {
            components: toolCom.c_input_list,
            configNme: 'titleInfo',
          },
          {
            components: toolCom.c_tab,
            configNme: 'tabConfig',
          },
          {
            components: toolCom.c_select,
            configNme: 'selectSortConfig',
          },
          {
            components: toolCom.c_select,
            configNme: 'selectConfig',
          },
          {
            components: toolCom.c_input_number,
            configNme: 'numConfig',
          },
          {
            components: toolCom.c_txt_tab,
            configNme: 'goodsSort',
          },
        ],
      },
      picTxt: {
        list: [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
          {
            components: toolCom.c_page_ueditor,
            configNme: 'richText',
          },
        ],
      },
      titles: {
        list: [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
          {
            components: toolCom.c_input_list,
            configNme: 'titleInfo',
          },
        ],
      },
      customerService: {
        list: [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
          {
            components: toolCom.c_upload_img,
            configNme: 'imgUrl',
          },
        ],
      },
      tabBar: {
        list: [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
          {
            components: toolCom.c_tab_bar,
            configNme: 'tabBarList',
          },
        ],
      },
    },
  },
  mutations: {
    /**
     * @description Đặt đã chọnname
     * @param {Object} state vuex state
     * @param {String} name
     */
    setConfig(state, name) {
      state.activeName = name;
    },

    upDataName(state, data) {
      state.defaultConfig[state.activeName] = data;
    },

    upDataGoodList(state, data) {
      let list = [];
      if (data.type) {
        list = [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
          {
            components: toolCom.c_input_list,
            configNme: 'titleInfo',
          },
          {
            components: toolCom.c_tab,
            configNme: 'tabConfig',
          },
          {
            components: toolCom.c_goods,
            configNme: 'goodsList',
          },
        ];
      } else {
        list = [
          {
            components: toolCom.c_is_show,
            configNme: 'isShow',
          },
          {
            components: toolCom.c_input_list,
            configNme: 'titleInfo',
          },
          {
            components: toolCom.c_tab,
            configNme: 'tabConfig',
          },
          {
            components: toolCom.c_select,
            configNme: 'selectConfig',
          },
          {
            components: toolCom.c_input_number,
            configNme: 'numConfig',
          },
        ];
        let sort = {
          components: toolCom.c_txt_tab,
          configNme: 'goodsSort',
        };
        let type = {
          components: toolCom.c_select,
          configNme: 'selectSortConfig',
        };
        let fixed = {
          components: toolCom.c_input_list,
          configNme: 'titleInfo',
        };
        if (data.name === 'seckill' || data.name === 'combination' || data.name === 'bargain') {
          list.splice(2, 0, fixed);
          list.push(sort);
        }
        if (data.name === 'goodList') {
          list.splice(3, 0, type);
          list.push(sort);
        }
      }
      let recommend = {
        components: toolCom.c_upload_list,
        configNme: 'imgList',
      };
      if (data.name === 'recommend') {
        list.splice(1, 0, recommend);
      }
      switch (data.name) {
        case 'scrollBox':
          state.component.scrollBox.list = list;
          break;
        case 'popular':
          state.component.popular.list = list;
          break;
        case 'recommend':
          state.component.recommend.list = list;
          break;
        case 'seckill':
          state.component.seckill.list = list;
          break;
        case 'combination':
          state.component.combination.list = list;
          break;
        case 'bargain':
          state.component.bargain.list = list;
          break;
        case 'newGoods':
          state.component.newGoods.list = list;
          break;
        case 'promotion':
          state.component.promotion.list = list;
          break;
        case 'goodList':
          state.component.goodList.list = list;
          break;
        default:
      }
    },
    /**
     * @description Cập nhật dữ liệu mặc định
     * @param {Object} state vuex state
     * @param {Object} data
     */
    updataConfig(state, data) {
      let value = state.defaultConfig;
      for (let i in data) {
        for (let j in value) {
          if (i === j) {
            value[j] = data[i];
          }
        }
      }
      state.defaultConfig = value;
    },
  },
  actions: {},
};
