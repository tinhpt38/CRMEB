-- Full DB i18n normalization for eb_system_menus.menu_name
-- Source dump: /Users/tinhp/Downloads/eb_system_menus.sql
-- Parsed rows: 1095, distinct menu_name: 835, updates: 834
START TRANSACTION;

UPDATE eb_system_menus
SET menu_name = CASE menu_name
  WHEN 'App' THEN 'sm_ac863f346e618f9a959b5c95'
  WHEN 'APP' THEN 'sm_0bdbb2c325525e986d55ef50'
  WHEN 'APP配置' THEN 'sm_fe4f36faf7eee55f599dc2d0'
  WHEN 'Bảo hành sản phẩm' THEN 'sm_70e3112591005831e3d206ab'
  WHEN 'Bảo trì an toàn' THEN 'sm_36a23e75693806be721814d0'
  WHEN 'Bảo trì dữ liệu' THEN 'sm_f71d86a1bea053451e16e6d3'
  WHEN 'Biểu mẫu bình luận ảo' THEN 'sm_b79b7db8bc35938e9b7496cd'
  WHEN 'Biểu mẫu nhóm người dùng' THEN 'sm_18bf545567a3ed7d997454be'
  WHEN 'Biểu mẫu Thêm/Chỉnh sửa Nhóm' THEN 'sm_bf9a014db1423a2734d3ff28'
  WHEN 'Cài đặt ngôn ngữ' THEN 'sm_c4ea134c599dd1fd1132db3b'
  WHEN 'Cấp độ người dùng' THEN 'sm_89deb25440654f72f3652324'
  WHEN 'Cấu hình' THEN 'sm_4c4c92f9de2b13b217b351be'
  WHEN 'Cấu hình đơn hàng' THEN 'sm_b9cf2f4b1b5b58ea61d4d7c7'
  WHEN 'Cấu hình mô-đun' THEN 'sm_c50bd355c644d5b8f6740521'
  WHEN 'Cấu hình người dùng' THEN 'sm_a0e6390f785e27d6c7fc2ae9'
  WHEN 'Chỉnh sửa biểu mẫu cân bằng điểm' THEN 'sm_bf3c63aba21bdeeb0992e363'
  WHEN 'Chỉnh sửa người dùng' THEN 'sm_63fbe2ff6fb81a26a78d32ec'
  WHEN 'CMS' THEN 'sm_c7da501f54544eba67879602'
  WHEN 'Công cụ phát triển' THEN 'sm_27820ec7aec4d49cc15ca4e0'
  WHEN 'Cung cấp thời gian thành viên tr' THEN 'sm_dcc14469082887008f74ad4c'
  WHEN 'Customer Serivce' THEN 'sm_e7afdc6f864e885f1fecfc7e'
  WHEN 'Đánh giá sản phẩm' THEN 'sm_aa868e66c13f296d9245fc6d'
  WHEN 'Danh mục cấu hình' THEN 'sm_5969f854a393f6748649fa81'
  WHEN 'Danh mục sản phẩm' THEN 'sm_fd51e1e3895899233428f6b4'
  WHEN 'Đặt thẻ hàng loạt' THEN 'sm_7dc759fc5f5718cd7f1a7645'
  WHEN 'Điều chỉnh số dư điểm' THEN 'sm_0c9d2ba546647078ede04f03'
  WHEN 'Diy模板数据详情' THEN 'sm_17e7fae1a9357a35de611982'
  WHEN 'Đơn đặt hàng của thu ngân' THEN 'sm_d229e757cf01179f997c48a7'
  WHEN 'Đơn đặt hàng sau bán hàng' THEN 'sm_afe8df2e7abfe8aad9929eb6'
  WHEN 'Dữ liệu kết hợp' THEN 'sm_a77c6b2e60219ee4558e4088'
  WHEN 'Finance' THEN 'sm_c482980d384a9d0e7bc39e11'
  WHEN 'Giao diện bên ngoài' THEN 'sm_552fed94c6874623573a8698'
  WHEN 'Gửi danh sách phiếu giảm giá' THEN 'sm_d6c4533bcd89411bfff53a08'
  WHEN 'Gửi phiếu giảm giá' THEN 'sm_98d52da29afe873e461a49c9'
  WHEN 'Hồ sơ xác minh' THEN 'sm_22a1622a06cdf580a0208578'
  WHEN 'Lấy biểu mẫu chỉnh sửa người dùn' THEN 'sm_c347f5ef2d9edd624aa9e45d'
  WHEN 'Lấy biểu mẫu danh mục cấu hình h' THEN 'sm_3597a246d62d50f72ec33dc0'
  WHEN 'Lấy biểu mẫu để tạo danh mục tuy' THEN 'sm_7bc31302f4f6ee0359cd82e5'
  WHEN 'Lấy danh sách các loại tuyến đườ' THEN 'sm_deb8d0fe5c31df5850165ee9'
  WHEN 'Lấy danh sách danh mục cấu hình ' THEN 'sm_8eab6f912f80f9bdacd442f1'
  WHEN 'Lấy danh sách dữ liệu kết hợp' THEN 'sm_7f423fe3ff34c3b26acaabdc'
  WHEN 'Lấy thẻ người dùng' THEN 'sm_82b0fc417f3d64ece536606c'
  WHEN 'Lấy thông tin chi tiết người dùn' THEN 'sm_8f44e2b47406a153d97c79db'
  WHEN 'Lấy thông tin của một người dùng' THEN 'sm_1c7f5ddefe443a9c7dbfd968'
  WHEN 'Lưu bình luận ảo' THEN 'sm_6f109acc1b8ccb211999e424'
  WHEN 'Lưu các danh mục cấu hình hệ thố' THEN 'sm_34f357eb903e689d27b8a790'
  WHEN 'Lưu danh mục tuyến đường' THEN 'sm_a85285b4bde628b49831b814'
  WHEN 'Lưu dữ liệu biểu mẫu nhóm' THEN 'sm_85d1ae33ce77a0572c7327b3'
  WHEN 'Lưu dữ liệu kết hợp' THEN 'sm_a51adbee91f32c76f4cbfbc3'
  WHEN 'Lưu người dùng' THEN 'sm_980affada1cecf11e511634c'
  WHEN 'Lưu thẻ người dùng' THEN 'sm_8e997fc6ee526031cc96af9a'
  WHEN 'Marketing' THEN 'sm_7cb15e416d62919b1b402983'
  WHEN 'Nhãn sản phẩm' THEN 'sm_0ace9ff4bca157ce3cd99790'
  WHEN 'Nhiệm vụ theo lịch trình' THEN 'sm_22a7b0eeb3dc174fa0811f59'
  WHEN 'Nhóm người dùng' THEN 'sm_be8fc4a1c83cfaa0d6617a55'
  WHEN 'Order' THEN 'sm_a240fa27925a635b08dc28c9'
  WHEN 'PC端' THEN 'sm_07a4262f562ec8915f862f91'
  WHEN 'PC端装修' THEN 'sm_2b30f1aa67a23d5852e031d3'
  WHEN 'PC端配置' THEN 'sm_d5e043952e0b2d3f1c30caf0'
  WHEN 'Phân loại giao diện' THEN 'sm_bea8d472fbaf7060c51fb674'
  WHEN 'Phân nhóm theo lô' THEN 'sm_46eed0314afb69cd1abb6713'
  WHEN 'Promotion' THEN 'sm_626a54d37d402d449d6d7541'
  WHEN 'Quản lý cơ sở dữ liệu' THEN 'sm_abbd0ae0ba2df1d63bbf7fcc'
  WHEN 'Quản lý đơn hàng' THEN 'sm_751cea6d4e6e310fb48fb3e2'
  WHEN 'Quản lý giao diện' THEN 'sm_86c41f0e6a220b5725f44189'
  WHEN 'Quản lý người dùng' THEN 'sm_7a37fc86f99edf5d3472faa7'
  WHEN 'Quản lý sản phẩm' THEN 'sm_332c1caa3b343bdee797b1dd'
  WHEN 'Quản lý tập tin' THEN 'sm_ea78409bc49b4b74a3080b3d'
  WHEN 'Settings' THEN 'sm_f4f70727dc34561dfde1a3c5'
  WHEN 'Shop' THEN 'sm_9f82518d468b9fee614fcc92'
  WHEN 'Số điểm còn lại' THEN 'sm_6cb70e386dc93ea2d7cf772e'
  WHEN 'Sự kiện tùy chỉnh' THEN 'sm_cb9c481099054b5788dfed19'
  WHEN 'Supplier' THEN 'sm_ec136b444eede3bc85639fac'
  WHEN 'System' THEN 'sm_a45da96d0bf6575970f2d27a'
  WHEN 'systemMenu' THEN 'systemMenus'
  WHEN 'Thành viên miễn phí' THEN 'sm_6703d8f6c141e47362e73717'
  WHEN 'Thẻ người dùng' THEN 'sm_3919aea2614d120c1bd3feb5'
  WHEN 'Thêm hoặc chỉnh sửa thẻ người dù' THEN 'sm_060e7ac2f1b0f63c84a0a37e'
  WHEN 'Thêm hoặc sửa đổi biểu mẫu thẻ n' THEN 'sm_67d1e7aad444c847a5d15eea'
  WHEN 'Thêm mới' THEN 'sm_77f6903f0ac02331b5a7001a'
  WHEN 'Thêm người dùng' THEN 'sm_2093dda8952890778dd5f132'
  WHEN 'Thêm nhóm' THEN 'sm_d938f1e146ecce473b2beaa6'
  WHEN 'Thêm nhóm dữ liệu' THEN 'sm_3cae4908e3787768bc925e0a'
  WHEN 'Thêm phần tự đánh giá' THEN 'sm_1090b68c14863a758c3c3883'
  WHEN 'Thiết lập nhóm người dùng' THEN 'sm_bb0840dcbcfb1f7d43f9a77a'
  WHEN 'Thời gian thành viên trả phí miễ' THEN 'sm_3ade0de429716d9aecf469ed'
  WHEN 'Thông số sản phẩm' THEN 'sm_33b59f085830c6de6395fce3'
  WHEN 'Thông tin cần lưu ý khi thêm hoặ' THEN 'sm_b0f1bc6420472dca98b7af37'
  WHEN 'Thông tin hệ thống' THEN 'sm_71b4d792d1eb46447532d660'
  WHEN 'Thông tin người dùng' THEN 'sm_0915a8397ff53125b3aa3385'
  WHEN 'Thuộc tính sản phẩm' THEN 'sm_0455f99329697b10080c957c'
  WHEN 'Trả lời bình luận' THEN 'sm_ca23f10a22278003f771cd2e'
  WHEN 'Trang chủ' THEN 'sm_03f0c66427dc00033958e15d'
  WHEN 'User' THEN 'sm_8f9bfe9d1345237cb3b2b205'
  WHEN 'Xóa bình luận' THEN 'sm_0e12a1ffe7285a76120d55e2'
  WHEN 'Xuất danh sách người dùng' THEN 'sm_68a33e75bcee5dd8768e694d'
  WHEN 'Xuất dữ liệu người dùng' THEN 'sm_5ebe30a4b33351657574938f'
  WHEN '一号通' THEN 'sm_d3aacbf83882e2d93651c8e2'
  WHEN '一号通配置' THEN 'yihaotong_config'
  WHEN '一号通页面' THEN 'sm_be3e99459d5a03f0f0b0be4e'
  WHEN '一键同步模版消息' THEN 'sm_c2bc7a7e0fa8f672e4354dbe'
  WHEN '一键同步订阅消息' THEN 'sm_2815433ef0d1cfec4ecdf68f'
  WHEN '一键复制优惠券' THEN 'sm_edbb3eebd3fa71d442e22a73'
  WHEN '上传图片' THEN 'sm_ce68551bc5329b923a1a6213'
  WHEN '上传视频密钥接口' THEN 'sm_0911db5e751b602bf1440a64'
  WHEN '上传类型' THEN 'sm_9e15a1c642639f8e23204efa'
  WHEN '上传素材' THEN 'sm_fc5b616e15184908858c69b2'
  WHEN '下载二维码' THEN 'sm_feea9211d32cb4ed1cccbad9'
  WHEN '下载代码' THEN 'sm_86774cec2d7252c76c14d99a'
  WHEN '下载小程序包' THEN 'sm_d7469bfafc3973fbc0f9e5b0'
  WHEN '下载小程序模版' THEN 'sm_600710e1789ac293a0b61b80'
  WHEN '下载小程序码' THEN 'sm_6d650314c96dd74fdcb41d6c'
  WHEN '下载小程序页面数据' THEN 'sm_ccbf812b49b7a2123fd820cc'
  WHEN '下载生成的文件' THEN 'sm_9454051c99e399cf75988a58'
  WHEN '下载账单' THEN 'sm_d6ea5c914328fc21ac9f6eb2'
  WHEN '不退款' THEN 'sm_5926d63c5328411b0b6c8593'
  WHEN '专题页面' THEN 'sm_9aacc428773657441e9be520'
  WHEN '个人中心' THEN 'sm_409120b562eac58d04bbca0f'
  WHEN '个人中心保存' THEN 'sm_18afab96740167e076aa16bb'
  WHEN '个人中心详情' THEN 'sm_91b7308556f2ce28a6699d2e'
  WHEN '主播管理' THEN 'sm_685f70da173bb4f904571108'
  WHEN '主题风格' THEN 'themeStyle'
  WHEN '二维码统计' THEN 'sm_c52a66c7565ce637b41889e5'
  WHEN '付费会员' THEN 'sm_bc08835591d683da4afbc830'
  WHEN '代码生成' THEN 'systemCodeGeneration'
  WHEN '优惠券' THEN 'sm_2f36359e06018b483b13c5aa'
  WHEN '优惠券列表' THEN 'sm_42c28cd7b211c2f1bee46f39'
  WHEN '会员卡修改状态' THEN 'sm_eddda1a502750499f849338c'
  WHEN '会员卡列表' THEN 'sm_9d5cc2ff6e0b02cb2d8fb339'
  WHEN '会员卡导出' THEN 'sm_92b6a5d7dcf7a1327d7a0610'
  WHEN '会员卡批次快速修改' THEN 'sm_0769ab32a379c3cb9ab48635'
  WHEN '会员卡类型编辑' THEN 'sm_72663d58d91854d6bca0ec16'
  WHEN '会员权益' THEN 'right'
  WHEN '会员权益修改' THEN 'sm_ccc54c535c759447eae1c0b5'
  WHEN '会员权益状态' THEN 'sm_83be69bed897ebf0c9c0d07d'
  WHEN '会员类型' THEN 'sm_ac2eb8294a01409ef8953cfa'
  WHEN '会员类型修改状态' THEN 'sm_f1701b78bf487562c1fa078c'
  WHEN '会员类型列表' THEN 'sm_bcb1d7843cfea80a6e6765ce'
  WHEN '会员类型删除' THEN 'sm_a3a706c99ab52e95416f0012'
  WHEN '会员类型状态' THEN 'sm_5737c042df42bf72b1ea8ff2'
  WHEN '会员记录' THEN 'sm_41582bc9f5940c2e77b97d0d'
  WHEN '会员配置' THEN 'sm_b32fb2c4458e9d5907d2bdcd'
  WHEN '会员领取记录' THEN 'sm_51633dae0d6841cce5af4e3b'
  WHEN '余额记录' THEN 'sm_f9bd7ff0fdb564f312c4024e'
  WHEN '佣金记录' THEN 'sm_d59e6e4bd0736df3a462d6cb'
  WHEN '使用DIY模板' THEN 'sm_26fb9c098553a3d9357a0a8e'
  WHEN '保存CRUD修改的文件' THEN 'sm_5e846a85720e481691823f1b'
  WHEN '保存个人中心' THEN 'sm_58a97f685dced5ccd10606b4'
  WHEN '保存主播数据' THEN 'sm_8697047d4e20e6c63ebb7ddb'
  WHEN '保存修改语言' THEN 'sm_95360a88effd5825dd54b9f5'
  WHEN '保存修改门店信息' THEN 'sm_2a07b154d83e730d812fda77'
  WHEN '保存关键字回复' THEN 'sm_ea1b09a8e40f3bf544660709'
  WHEN '保存分组表单数据' THEN 'sm_53dd1c285ec7e1b76d560e4f'
  WHEN '保存分销员等级' THEN 'sm_e86bf843dd0838f28a176347'
  WHEN '保存分销员等级任务' THEN 'sm_9267207d48b5584034fb4c44'
  WHEN '保存协议' THEN 'sm_6518b61b36a56f6fe86ac1ab'
  WHEN '保存图文' THEN 'sm_ebf23ad10e277061c913ad07'
  WHEN '保存客服话术' THEN 'sm_aaa1e3edd768ac0b32445f4a'
  WHEN '保存客服话术分类' THEN 'sm_a2178e00bd23c0901f3d1c04'
  WHEN '保存并发布' THEN 'sm_14b0ced8b76986eca32920f4'
  WHEN '保存并回复' THEN 'sm_6fae10b0c1750d1ba68ae564'
  WHEN '保存店员' THEN 'sm_ff987d7724d1d210e4889e11'
  WHEN '保存微信公众号菜单' THEN 'sm_0c0d6acc6464743d75de6aa8'
  WHEN '保存文章' THEN 'sm_03474a36ba8552397519d366'
  WHEN '保存文章分类' THEN 'sm_3d723e80493697b197228cf4'
  WHEN '保存新增修改语言' THEN 'sm_05398d073cd5aaef0eb9ec62'
  WHEN '保存新建的配送员' THEN 'sm_5398906431c67be001a83558'
  WHEN '保存权限菜单' THEN 'sm_06718a5fe7b73bf231543834'
  WHEN '保存标签分类' THEN 'sm_82e5e5708786f19421f5a77a'
  WHEN '保存渠道码' THEN 'sm_f074fde0c2b8d4c185b6c478'
  WHEN '保存生成CRUD' THEN 'sm_e20852107b52ec4c5abee4da'
  WHEN '保存管理员' THEN 'sm_e54bb755651f8b5e8b52978d'
  WHEN '保存系统配置' THEN 'sm_1e2e2de9520215d98b994d9b'
  WHEN '保存组合数据子数据' THEN 'sm_cc8cabc935626ddde1f53478'
  WHEN '保存语言地区' THEN 'sm_52ea80923af64348be5b8c35'
  WHEN '保存路由权限' THEN 'sm_334c8b6986ebe753106cf936'
  WHEN '保存还未提交数据' THEN 'sm_e2452c64db098bff54c452b0'
  WHEN '保存通知设置' THEN 'sm_6aaec86fe984ab8d450db0ef'
  WHEN '保存采集商品数据' THEN 'sm_8d7a20082dcd7f127858815b'
  WHEN '保存附件分类管理' THEN 'sm_61d9adc24d1800b0d8b81573'
  WHEN '修改上级推广人' THEN 'sm_b2eb06f97856b626d7ade115'
  WHEN '修改不退款理由' THEN 'sm_39c9a7997e9bacfc5b27b9d2'
  WHEN '修改主播' THEN 'sm_e52371928df80673d941180a'
  WHEN '修改关键字回复状态' THEN 'sm_f4ce647e3de20dd2ff4c0ce7'
  WHEN '修改分类' THEN 'sm_535754c7ccaf0d785dddec83'
  WHEN '修改分组' THEN 'sm_2bd33294d695a88f47a1b606'
  WHEN '修改分销员等级' THEN 'sm_579c22641a924b21e979f05b'
  WHEN '修改分销员等级任务' THEN 'sm_e3756008590e7caf04331399'
  WHEN '修改分销等级' THEN 'sm_6059448b36ffc4b622a0f657'
  WHEN '修改分销等级任务状态' THEN 'sm_369599ce2d1a5aeae1347fcb'
  WHEN '修改分销等级状态' THEN 'sm_710cf9f52336a434c9e5b512'
  WHEN '修改商品状态' THEN 'sm_69576d09240df2d7d89fd747'
  WHEN '修改图片名称' THEN 'sm_9b87831e1209e9ee950956be'
  WHEN '修改城市数据表单' THEN 'sm_7cb1e86574a5777c2f229703'
  WHEN '修改备注信息' THEN 'sm_181b7c4504ad4098d16bd2be'
  WHEN '修改客服' THEN 'sm_e3612ade83c9de376ad8a0a2'
  WHEN '修改客服状态' THEN 'sm_9365a4e392d14b65e0e8f7d5'
  WHEN '修改客服表单' THEN 'sm_af4b6af035a5db7869030bb9'
  WHEN '修改客服话术' THEN 'sm_883ca9e3640314cf0e4dcc32'
  WHEN '修改客服话术分类' THEN 'sm_f2ce31fa9a82d33fcb69cc31'
  WHEN '修改店员状态' THEN 'sm_a6fa82f345f9886022b46dff'
  WHEN '修改店员表单' THEN 'sm_5aba379a84f5f391d6ff81e8'
  WHEN '修改或者保存字典数据' THEN 'sm_9c28adbce1760b54f2f54f56'
  WHEN '修改拼团商品状态' THEN 'sm_45764efa614de2eed45fc089'
  WHEN '修改提货点' THEN 'sm_8000e153281e9a618a71da5b'
  WHEN '修改数据组' THEN 'sm_9e83909d8b1a2859cb361cf4'
  WHEN '修改文章' THEN 'sm_3f4b86006592a46601a9e140'
  WHEN '修改文章分类' THEN 'sm_49b1e0b38e93a819b2986f09'
  WHEN '修改权限菜单' THEN 'sm_58c61df07c8c1d2961857441'
  WHEN '修改标签' THEN 'sm_8185550a621461b4a83dd4d2'
  WHEN '修改标签分类' THEN 'sm_fa1849d815efa08aa7717f48'
  WHEN '修改核销员' THEN 'sm_bda21b85483fab232868ad08'
  WHEN '修改消息状态' THEN 'sm_048fdb79d8b665d00e0f5c2d'
  WHEN '修改物流公司' THEN 'sm_1b9e80f7597b98c28ed7a27b'
  WHEN '修改用户反馈' THEN 'sm_2b379affbbeedd75421e3c37'
  WHEN '修改砍价商品状态' THEN 'sm_c86af28adc38afa6b6a0f95a'
  WHEN '修改秒杀商品状态' THEN 'sm_090598be1311163b3c1d875b'
  WHEN '修改积分商品状态' THEN 'sm_be1fe7e389654c47163e863d'
  WHEN '修改积分订单配送信息' THEN 'sm_3f101d3b7fa96d683810e6e3'
  WHEN '修改等级' THEN 'sm_735e7ba91cb3bcf1a955347e'
  WHEN '修改管理员' THEN 'sm_f9bfcb81d16d27601f50d330'
  WHEN '修改管理员状态' THEN 'sm_ec7bb693405cef841467ebc8'
  WHEN '修改管理员身份状态' THEN 'sm_11e2db986491342ceb714c7b'
  WHEN '修改系统配置' THEN 'sm_a31157e05061f2c54e0723fa'
  WHEN '修改系统配置分类' THEN 'sm_97c95f46376377196a0b3140'
  WHEN '修改组合数据' THEN 'sm_fb7f287c4c17c9bbd2e69a6e'
  WHEN '修改组合数据子数据' THEN 'sm_9b54f7b60acea4b97e31e3d2'
  WHEN '修改订单' THEN 'sm_897dbdee8fc07833c22047c6'
  WHEN '修改语言列表' THEN 'sm_395e4d29f21a5765e24ff704'
  WHEN '修改语言类型状态' THEN 'sm_0eb288e37b97697aa67096e9'
  WHEN '修改路由分类' THEN 'sm_30625b8b50066a11576c8cf1'
  WHEN '修改运费模板数据' THEN 'sm_983e8e8b1580e7431d9877bc'
  WHEN '修改运费模版' THEN 'sm_79b82953b3ad84365c3af1cd'
  WHEN '修改配置' THEN 'sm_2e4b9b00af2cd725702b51d8'
  WHEN '修改配置分类' THEN 'sm_5c2717d40190946348c73034'
  WHEN '修改配置状态' THEN 'sm_dba4b251c40d4a88beb821fc'
  WHEN '修改配送员' THEN 'sm_47d7ad966f11b12b158c718f'
  WHEN '修改配送员状态' THEN 'sm_068e18ab77e49b18706f9920'
  WHEN '修改附件分类管理' THEN 'sm_541c6bb867802959aee15747'
  WHEN '充值删除' THEN 'sm_950bbe90e83cf8d4b26be760'
  WHEN '充值记录' THEN 'sm_415b289e41d2148209b5edc8'
  WHEN '充值记录列表' THEN 'sm_32e0da8f3618488055aede00'
  WHEN '充值退款' THEN 'sm_421ce6f1f48562cfc6de83ec'
  WHEN '充值退款表单' THEN 'sm_82497f0c9a7a3b01e980c412'
  WHEN '充值配置' THEN 'recharge_config'
  WHEN '兑换会员卡二维码' THEN 'sm_d2506e4082c65cf1df9a1487'
  WHEN '兑换记录' THEN 'sm_c600691c66b115178741a20d'
  WHEN '公众号' THEN 'sm_215feec56c2ae2ebc5c15e0b'
  WHEN '公众号配置' THEN 'wechat_config'
  WHEN '关注回复' THEN 'sm_c3b2c1748db69da6c593d591'
  WHEN '关联商品' THEN 'sm_ec16970414d886c7cd61fe49'
  WHEN '关键字回复' THEN 'sm_6a3c572e01d621d64ccd6aa8'
  WHEN '关键字回复列表' THEN 'sm_7154325aad3471bedd5107d8'
  WHEN '关键字回复详情' THEN 'sm_5a4aece16b4f629e44041c7c'
  WHEN '内容设置' THEN 'sm_c6b063773d68674101f1433b'
  WHEN '分片上传本地视频' THEN 'sm_4012e99d05b66bb10b3ac706'
  WHEN '分类状态' THEN 'sm_8f0f963230e01c099336e3e8'
  WHEN '分销员申请' THEN 'sm_756fe4943dbb0d21f2c1d483'
  WHEN '分销员管理' THEN 'sm_a602f8c9c7db7e009f0cb888'
  WHEN '分销等级' THEN 'membershipLevel'
  WHEN '分销等级任务' THEN 'sm_612d7804049d88df46ad2cb0'
  WHEN '分销设置' THEN 'sm_891aa3e994c259d04fdf5753'
  WHEN '切换主题' THEN 'sm_583547cc494de95be746d113'
  WHEN '切换分类页面' THEN 'sm_6eefad55bd21f8d9be29f5db'
  WHEN '删除CRUD' THEN 'sm_c781333238b31a9f2d4e6604'
  WHEN '删除DIY模板' THEN 'sm_fa204eb556e1821c7f6c359f'
  WHEN '删除上级推广人' THEN 'sm_f9b0065ad457a45ab8e2a2d4'
  WHEN '删除主播' THEN 'sm_d5ea117de62470e7447dbb18'
  WHEN '删除二维码' THEN 'sm_88706378f446d6e35ca443cd'
  WHEN '删除优惠券' THEN 'sm_a2c96bc2826cf4b45fd687c1'
  WHEN '删除会员类型' THEN 'sm_4a9efebfe1d9252389468bc4'
  WHEN '删除充值记录' THEN 'sm_80a1ce8139b4a368d2094bb4'
  WHEN '删除关键字回复' THEN 'sm_90c3de2e81b35c02cb566c61'
  WHEN '删除分类' THEN 'sm_a1997d1d1da7d56e3fe0bbdb'
  WHEN '删除分组' THEN 'sm_febf19255afa70422accd7c4'
  WHEN '删除分销员等级' THEN 'sm_c790fc60a0fdcf9a1c955211'
  WHEN '删除分销员等级任务' THEN 'sm_880262c594393103178c7d4f'
  WHEN '删除分销等级' THEN 'sm_f7af435cb63683ed57e9a1dd'
  WHEN '删除功能' THEN 'sm_64596f9a77d031697ae451b4'
  WHEN '删除商品分类' THEN 'sm_628f2e586fc592bbc7c196b1'
  WHEN '删除商品规则' THEN 'sm_a4075848c81a688f2c769f47'
  WHEN '删除商品评论' THEN 'sm_e30f0057b5bda59118499a71'
  WHEN '删除图文' THEN 'sm_88e64aa17cc145111bd37ca2'
  WHEN '删除图片' THEN 'sm_3875ccae23ffd33f78e6f41f'
  WHEN '删除城市数据' THEN 'sm_4e97fdb2fa106c22674dcd6c'
  WHEN '删除定时任务' THEN 'sm_0b198eb3d304b820b2f5a704'
  WHEN '删除客服' THEN 'sm_a8aa57c209a2096aee99b0c1'
  WHEN '删除客服话术' THEN 'sm_306ec6e3b37f5ebfbd024bf2'
  WHEN '删除对外账号' THEN 'sm_5ba3e522180efd092cfdd585'
  WHEN '删除店员' THEN 'sm_e44be7d5262ff10c3b64c142'
  WHEN '删除拼团' THEN 'sm_29d5e9021f8f129ebae9387e'
  WHEN '删除拼团商品' THEN 'sm_a34d47231b44e880d2a4a6a1'
  WHEN '删除提货点' THEN 'sm_aa9ad6132f8a0be3b414149c'
  WHEN '删除数据字典' THEN 'sm_a856e22e99cdaf8e5c4f5d36'
  WHEN '删除数据组' THEN 'sm_079cd2166643dd41a796508c'
  WHEN '删除文章' THEN 'sm_f1ce042a27aa18b121377bea'
  WHEN '删除文章分类' THEN 'sm_02c2db69548d56bdb4809d5b'
  WHEN '删除权限' THEN 'sm_5d79e4368068e958c30fdd0f'
  WHEN '删除权限菜单' THEN 'sm_77c90f077614f1ba53ef94f1'
  WHEN '删除标签' THEN 'sm_8c3fcd483410517540ca0463'
  WHEN '删除标签分类' THEN 'sm_e86a7f66cf5a29b3c8361a1b'
  WHEN '删除核销员' THEN 'sm_4202d22ad924a371d0721ca9'
  WHEN '删除渠道码' THEN 'sm_f851883fc7a473f10f45c603'
  WHEN '删除用户分组数据' THEN 'sm_dedfea85cb49c801c8bd2256'
  WHEN '删除用户反馈' THEN 'sm_274ef7c250df1b11eb03ce15'
  WHEN '删除用户标签' THEN 'sm_1a92f03773d0e7fef7f4aec1'
  WHEN '删除用户等级' THEN 'sm_4c67323e1bd3c26b9bec9f6e'
  WHEN '删除留言' THEN 'sm_90042129726f3b59a1aaa74a'
  WHEN '删除直播商品' THEN 'sm_525cf5e1dba6d34d76bbc99d'
  WHEN '删除直播间' THEN 'sm_8cb7677da2c31c07e0103f89'
  WHEN '删除砍价' THEN 'sm_0a06772a24b44ec1835c224c'
  WHEN '删除砍价商品' THEN 'sm_b47d34d578ba1494bbddc35b'
  WHEN '删除秒杀' THEN 'sm_5976150a4b4d4ff07793936f'
  WHEN '删除秒杀商品' THEN 'sm_7af1624a9c6ffd6a53ca9cbf'
  WHEN '删除积分商品' THEN 'sm_8c46f28d4ffbbfc344f8bc34'
  WHEN '删除等级' THEN 'sm_3b685c9b762ebc25dacd390a'
  WHEN '删除管理员' THEN 'sm_db634c9ef9eece6ea610b00f'
  WHEN '删除管理员身份' THEN 'sm_5f4be5835407a947e7835196'
  WHEN '删除系统配置' THEN 'sm_eddf3e785e382c0630fa11a7'
  WHEN '删除系统配置分类' THEN 'sm_6f1e0c6b267d9a3291702016'
  WHEN '删除素材' THEN 'sm_0615dd617005e8afd481d0af'
  WHEN '删除素材分类' THEN 'sm_727ecbc5a4359227e071ab17'
  WHEN '删除组合数据' THEN 'sm_f5582babf6cdcdbc4afb62ba'
  WHEN '删除组合数据子数据' THEN 'sm_791f59d685808cc8b35685e9'
  WHEN '删除规格' THEN 'sm_119769791e3ca44da41be657'
  WHEN '删除角色' THEN 'sm_ca40c5a58552939845220c20'
  WHEN '删除订单' THEN 'sm_09936f8643e1cb7bb0962388'
  WHEN '删除订单单个' THEN 'sm_925dfea719cdb3e9de2c489c'
  WHEN '删除话术' THEN 'sm_21c8fa59234ef9de2c3be34c'
  WHEN '删除语言' THEN 'sm_a89a230c46606ea074c0e9cc'
  WHEN '删除语言列表' THEN 'sm_650b6d00f0c13d92b918c941'
  WHEN '删除语言地区' THEN 'sm_e88de5df3e6ff3d2937f6856'
  WHEN '删除语言详情' THEN 'sm_143f5fc6d8c41cdda95c4592'
  WHEN '删除账号' THEN 'sm_35021d5411717c5c9ffdb810'
  WHEN '删除路由分类' THEN 'sm_33a26d787cad7fe74ce09a4b'
  WHEN '删除运费模板' THEN 'sm_58b5c7ef7441c9a85d73a79c'
  WHEN '删除运费模版' THEN 'sm_4eac9c9555eb6560f6099c94'
  WHEN '删除配置' THEN 'sm_26c7538e9988580639653693'
  WHEN '删除配置分类' THEN 'sm_c753f2688f17b7a815046d2c'
  WHEN '删除配送员' THEN 'sm_824a21b82b9a4b3441341a50'
  WHEN '删除附件分类管理' THEN 'sm_b43087f3202237077a9a6b55'
  WHEN '删除页面' THEN 'sm_f633a294848859b1b166b3c3'
  WHEN '刷新缓存' THEN 'systemRefreshCache'
  WHEN '协议设置' THEN 'agreement'
  WHEN '卡密会员' THEN 'sm_a7529da644321f5a649ddf31'
  WHEN '参与拼团列表' THEN 'sm_23d2712f7afe3b8a999cd77d'
  WHEN '参与砍价列表' THEN 'sm_e4af1013dea8c6955290f9aa'
  WHEN '发票管理' THEN 'sm_37ec4f8aa27d2633406d821a'
  WHEN '发货设置' THEN 'sm_f47b693ad59d76bcfb9d1238'
  WHEN '取消推广资格' THEN 'sm_fb3985195edba1c54dde8598'
  WHEN '取消文章关联商品' THEN 'sm_512b58fb71f629c07fd656c2'
  WHEN '同步接口' THEN 'sm_9fc3dbf8ad6106300b604249'
  WHEN '同步消息' THEN 'sm_475a9f0f50699b99c0790231'
  WHEN '同步物流公司' THEN 'sm_901007b33ea2d93daab1eb94'
  WHEN '同步直播间' THEN 'sm_db6402a5af4ff4598c79001d'
  WHEN '同步直播间状态' THEN 'sm_23a6110e5ce2323c7b5c9483'
  WHEN '同步路由' THEN 'sm_f1a1782db893c9357fd8117e'
  WHEN '售后备注' THEN 'sm_3495617f149ed8e25ce97feb'
  WHEN '售后订单备注' THEN 'sm_93e6e1429cffb517a394b39a'
  WHEN '售后订单退款' THEN 'sm_cc0c67c139e54adf95c8ee98'
  WHEN '售后订单退款表单' THEN 'sm_1b7da1070e608017343d342e'
  WHEN '商品上下架' THEN 'sm_19dba884f4f3afd6808e9712'
  WHEN '商品分类' THEN 'cate'
  WHEN '商品分类修改状态' THEN 'sm_80c39e47e5b6e7f0ef9f9bcf'
  WHEN '商品分类新增' THEN 'sm_113e9403321b33108dad55d4'
  WHEN '商品分类新增表单' THEN 'sm_780ab2a8f94b764a49ff9f80'
  WHEN '商品分类编辑' THEN 'sm_ec687ed9e4889ef1efcf1023'
  WHEN '商品分类编辑表单' THEN 'sm_0626bce6486d36419124482b'
  WHEN '商品列表导出' THEN 'sm_8216ac11b74203ba59824a78'
  WHEN '商品回复评论' THEN 'sm_1fafdcf4451cd6743368e552'
  WHEN '商品回收站' THEN 'sm_2053bc1e1aa0cf581103c8a4'
  WHEN '商品导出' THEN 'sm_f831333835ec81281a6a1865'
  WHEN '商品快速编辑' THEN 'sm_74dffb39fd9d60d5b12f1e3a'
  WHEN '商品批量设置' THEN 'sm_53f6fbb8fbe298c11fde100f'
  WHEN '商品放入回收站' THEN 'sm_cefa233e0de9b68d8d54dcf4'
  WHEN '商品添加' THEN 'sm_80e069a52aefb839127375db'
  WHEN '商品规则列表' THEN 'sm_a5c428901a8f01cc3a01f78d'
  WHEN '商品规则详情' THEN 'sm_46d5a024dee1013252bb1c20'
  WHEN '商品评论' THEN 'sm_b261c2e77f5b632c7afbb9e7'
  WHEN '商品详情' THEN 'sm_b4f5dba9a0f9077d4bf0d97e'
  WHEN '商品配置' THEN 'sm_7b6d8bf94748ca9acbf278cc'
  WHEN '商品采集' THEN 'sm_725d85b83614a8a58fc18e15'
  WHEN '商品采集配置' THEN 'other_copy'
  WHEN '商城主题' THEN 'store_theme'
  WHEN '商城支付配置' THEN 'other_pay'
  WHEN '商城首页' THEN 'sm_971adb83ecf1695d8b9e3258'
  WHEN '商家同意退款，等待用户退货' THEN 'sm_19269678ae4673ea266d6616'
  WHEN '回复留言' THEN 'sm_c72244f4afbf1a6a4d1bca79'
  WHEN '图文列表' THEN 'sm_3474d48e9ead75533eaebe32'
  WHEN '图文管理' THEN 'sm_35824ff5c3cf4028f1c49afd'
  WHEN '图文详情' THEN 'sm_7db2d657ca03d393a978024a'
  WHEN '图片附件列表' THEN 'sm_9fa4e80885351dc647b92f6c'
  WHEN '地区列表' THEN 'sm_5308c74bd6db3696004f5ffa'
  WHEN '城市数据' THEN 'cityDada'
  WHEN '城市数据接口' THEN 'sm_85088b214711829294746147'
  WHEN '复制其他平台商品' THEN 'sm_a7be22f7603d54cf58ad6684'
  WHEN '定时任务列表' THEN 'sm_68fe7127cbae5889ef30db9b'
  WHEN '定时任务是否开启开关' THEN 'sm_f0b4d7142524acea5af3c612'
  WHEN '定时任务添加编辑' THEN 'sm_efcd1f99c2bade1511cc3eb1'
  WHEN '定时任务状态' THEN 'sm_b96ee1efa8b1948a2bd72ccf'
  WHEN '定时任务类型' THEN 'sm_810a9bb5190a2ac4131d062a'
  WHEN '定时任务详情' THEN 'sm_d3f236020177a383ed438c6c'
  WHEN '审核提现' THEN 'sm_5a233b897f6f6cda42132e4b'
  WHEN '客服列表' THEN 'sm_8d51d1537ba8dea092fec577'
  WHEN '客服登录' THEN 'sm_1f766e5b172ec1e611419ee8'
  WHEN '客服话术' THEN 'speechcraft'
  WHEN '客服配置' THEN 'kefu_config'
  WHEN '对外接口账号信息' THEN 'sm_bea4459187498ea074f99cfe'
  WHEN '对外接口账号修改' THEN 'sm_3228e3edd2949c38e93dcaf8'
  WHEN '对外接口账号添加' THEN 'sm_6d49a443c0ea5f9d9018e728'
  WHEN '导入虚拟商品卡密' THEN 'sm_5f714c618a391232f9a2a999'
  WHEN '导出会员卡密' THEN 'sm_bcb26dad8a1ae3476aad9e2f'
  WHEN '导出拼团' THEN 'sm_65b19ac2875d30fa49138329'
  WHEN '导出砍价' THEN 'sm_dd2d5a5f9ec4cc00ee291578'
  WHEN '导出秒杀' THEN 'sm_85fd15bb820778bf8f82e2fe'
  WHEN '小票打印' THEN 'sm_bf34e49de7bca111a3e9b0cb'
  WHEN '小票配置' THEN 'content'
  WHEN '小程序' THEN 'sm_0ed5103f88f9b3417c8cba24'
  WHEN '小程序上传' THEN 'sm_a3dfea4e9a3bf619b4b93c41'
  WHEN '小程序下载' THEN 'sm_64f661b48753512b8fda846a'
  WHEN '小程序配置' THEN 'routine_config'
  WHEN '小程序链接' THEN 'sm_00b3e3520a7355ab6d54cd01'
  WHEN '已发布优惠券删除' THEN 'sm_62181e0aa4d3ead676f40953'
  WHEN '已发布优惠券领取记录' THEN 'sm_6eaa3e46fd533919471b6ffe'
  WHEN '开票订单详情' THEN 'sm_137c86d7c1b393b8a4ebfb32'
  WHEN '微信公众号菜单列表' THEN 'sm_a7f069681b34f054ee646087'
  WHEN '微信菜单' THEN 'sm_075798f9ed40e75ab9143c59'
  WHEN '快递公司电子面单模版' THEN 'sm_2890858d83ac751caf4fe951'
  WHEN '快递面单打印' THEN 'sm_1046e3740c7f55a985d92372'
  WHEN '我的主题' THEN 'my_theme'
  WHEN '打印积分订单' THEN 'sm_38753fb495028481429ea54a'
  WHEN '打印订单' THEN 'sm_b79de2d45710361b7ee712ef'
  WHEN '扫码用户列表' THEN 'sm_66354431dacbdb9cef14f04d'
  WHEN '批量修改' THEN 'sm_0f61da949d2b45534967e197'
  WHEN '批量删除订单' THEN 'sm_eb144cb4be5bfc2d09ec837e'
  WHEN '抽奖列表' THEN 'sm_2fdfb4470b8b298b24807778'
  WHEN '抽奖管理' THEN 'sm_2423b96b71cf19c921ce5e01'
  WHEN '抽奖配置' THEN 'sm_51c33bd8490d39dda0efb0b5'
  WHEN '拆单发送货' THEN 'sm_73916723d1e85310f9e19424'
  WHEN '拒绝提现申请' THEN 'sm_ebb23d21dbf7d351f0eb2718'
  WHEN '拼团人列表' THEN 'sm_5dd47e17263ebb997d2ba781'
  WHEN '拼团列表' THEN 'sm_964ee3ecd533532d194b38cb'
  WHEN '拼团商品' THEN 'sm_f951011760eeae4d0a405678'
  WHEN '拼团商品列表导出' THEN 'sm_377ca2e5e50c8ed2893ba86d'
  WHEN '拼团商品详情' THEN 'sm_858a1c327039fa32eb383135'
  WHEN '拼团添加' THEN 'sm_ad1ce552b39a827e33db6bbd'
  WHEN '拼团管理' THEN 'sm_53cfbc11e7448818c0505454'
  WHEN '拼团统计' THEN 'sm_33fbb29bad6ff8be1b646e06'
  WHEN '拼团详情' THEN 'sm_93c2f496d603af0fbe7568ff'
  WHEN '换色和分类保存' THEN 'sm_3eb57f483e43798eff7a3cd2'
  WHEN '接口配置' THEN 'sm_6f6f1e6feb9fa966acaddae6'
  WHEN '推广二维码' THEN 'sm_099df7755a0b2f6dba329194'
  WHEN '推广人列表' THEN 'sm_422058acaede7335e9f6699a'
  WHEN '推广订单' THEN 'sm_04cb7e1a9c47478840fe6ad9'
  WHEN '推广订单列表' THEN 'sm_0b338fa28f181df5803a6d5a'
  WHEN '提现申请' THEN 'sm_33011e0fdcc4ae154ba2894d'
  WHEN '提现记录修改' THEN 'sm_3f541c68b54130c8a9e87a5c'
  WHEN '提现记录修改表单' THEN 'sm_0639013b40f5aa74ead65a5b'
  WHEN '提货点' THEN 'storeList'
  WHEN '提货点状态' THEN 'sm_85fadd61bbe438e6e712cb06'
  WHEN '提货点设置' THEN 'sm_fc87dd666c7d30e009decb3f'
  WHEN '收款二维码' THEN 'sm_c378ffb184ff8b9050b77e7d'
  WHEN '数据字典' THEN 'systemDataDictionary'
  WHEN '数据配置' THEN 'systemGroupData'
  WHEN '文件管理入口' THEN 'systemFileManagementEntry'
  WHEN '文章关联商品' THEN 'sm_a8046cd7d49004102c77e840'
  WHEN '文章分类' THEN 'sm_c1ed55ca6935d341d2972a65'
  WHEN '文章添加' THEN 'sm_85c3f21a03aed2023225f3a5'
  WHEN '文章管理' THEN 'sm_f435708a59edb82e6f368cb2'
  WHEN '新人礼' THEN 'sm_213861611bf5b163be578add'
  WHEN '新增/修改城市数据' THEN 'sm_3e8d94b6f8741d96a998414d'
  WHEN '新增修改语言类型表单' THEN 'sm_8cfbc810f25d3e3f189cac59'
  WHEN '新增客服选择用户列表' THEN 'sm_6c3110b1ae05425f933d0572'
  WHEN '新增或修改运费模版' THEN 'sm_12e150112ccc83a626d38f67'
  WHEN '新增或编辑拼团商品' THEN 'sm_19af1159d0a07d05996da32a'
  WHEN '新增或编辑砍价商品' THEN 'sm_ea30a1a7a368e4548d983eee'
  WHEN '新增或编辑秒杀商品' THEN 'sm_cef70d44e0f631bad06d9364'
  WHEN '新增配送表单' THEN 'sm_e206af906ba9c6980d03e7e5'
  WHEN '新建二维码' THEN 'sm_26cb46377f2f1a14f5c97a30'
  WHEN '新建或修改商品' THEN 'sm_01d33a461cafe4602f4bbf6d'
  WHEN '新建或编辑商品规则' THEN 'sm_40c59c83f04ecc516a66f7e3'
  WHEN '新建或编辑管理员' THEN 'sm_16f0867befb36aab2a17e66e'
  WHEN '无效词回复' THEN 'sm_dcbb1fc556fff408fe692fcb'
  WHEN '更多操作打印电子面单' THEN 'sm_263ce03f1b94e3d8be6a6318'
  WHEN '机器翻译' THEN 'sm_ad2c8ff6f6634450c1953d2a'
  WHEN '查看CRUD' THEN 'sm_e18ecd0f61b341469e96ecee'
  WHEN '查看H5推广二维码' THEN 'sm_f180bd41d61fffae78c24d6f'
  WHEN '查看代码' THEN 'sm_fef87fb791e0cc3e548c4938'
  WHEN '查看公众号推广二维码' THEN 'sm_028e83e416731bdaa6352a2f'
  WHEN '查看卡列表' THEN 'sm_998c092d437cea9595cc9a1a'
  WHEN '查看商品' THEN 'sm_f13684cee3c72f2d6f70a0f1'
  WHEN '查看小程序推广二维码' THEN 'sm_f9196cfb6c878e4d25e4dda8'
  WHEN '查看数据列表' THEN 'sm_35d6ce7d2cc96c796ced6b11'
  WHEN '查看数据字典' THEN 'sm_ab9a86c06bcf574d8679242c'
  WHEN '查看文章' THEN 'sm_a9ba72a74a6d09c97b04e51c'
  WHEN '查看权限菜单信息' THEN 'sm_b1e02441675c68259b8a9585'
  WHEN '查看详情' THEN 'sm_5b48dbb8dc710cffe6313bb5'
  WHEN '查看路由权限' THEN 'sm_99b122e5a22db22aa963277e'
  WHEN '查看配置列表' THEN 'sm_911f136bb974bb8cca87d20c'
  WHEN '标签分类' THEN 'sm_faabe1bef1f041d488a92140'
  WHEN '核销员' THEN 'clerkList'
  WHEN '核销员状态' THEN 'sm_27ed93932bcd591c73e6b94d'
  WHEN '每日签到' THEN 'sm_c0775b42f2bf85c3c308668d'
  WHEN '消息状态' THEN 'sm_675e6cd11eef982235b50ea9'
  WHEN '消息管理' THEN 'notification'
  WHEN '添加DIY' THEN 'sm_844b4d070b99eebe899bbd7f'
  WHEN '添加DIY模板' THEN 'sm_7529658d949e503fb79bf15f'
  WHEN '添加专题页' THEN 'sm_ee6afbd6ad2e46f86e281923'
  WHEN '添加主播' THEN 'sm_b6072cc4e559d2e1bf2e7107'
  WHEN '添加优惠券' THEN 'sm_ae8c83d8d3f58e6b34ade341'
  WHEN '添加会员卡批次' THEN 'sm_bd8d2694210de5a4797a2982'
  WHEN '添加会员类型' THEN 'sm_93af7a3eff694c372fe040ae'
  WHEN '添加修改主播表单' THEN 'sm_847388fd8d99510ada55d8ec'
  WHEN '添加修改分组表单' THEN 'sm_22d99646e235a0575a5810e8'
  WHEN '添加修改直播商品' THEN 'sm_df3053ac29a333b8da493156'
  WHEN '添加分类' THEN 'sm_9811bee53d62eeb473f8e3c5'
  WHEN '添加分组' THEN 'sm_ddceab0399c8b1e9dc531e5e'
  WHEN '添加分销等级' THEN 'sm_7f618a29e83ed3ca4cfcfb69'
  WHEN '添加功能' THEN 'sm_aabce1603402afe4e44ae602'
  WHEN '添加商品' THEN 'sm_fa3aee9875a00c4c69b39033'
  WHEN '添加图文' THEN 'sm_72b536b347e745b726d7ec35'
  WHEN '添加图文消息' THEN 'sm_b922d7beba41d6311b5353ba'
  WHEN '添加城市数据' THEN 'sm_bd623572a32382fe5fc6ac62'
  WHEN '添加城市数据表单' THEN 'sm_d8652d0330284dcfd90eaabd'
  WHEN '添加复制优惠券' THEN 'sm_91446e3415989a657113b2e4'
  WHEN '添加子菜单' THEN 'sm_70fe215b78ef2cf44fbb7492'
  WHEN '添加定时任务' THEN 'sm_27aa3b5a3b3bf50f9715f055'
  WHEN '添加客服' THEN 'sm_ece41addec49ccc8d61e8804'
  WHEN '添加客服表单' THEN 'sm_eb59c477347f7331a596b927'
  WHEN '添加对外账号' THEN 'sm_b0b7c5df98423737c3c24615'
  WHEN '添加或修改用户标签' THEN 'sm_f7642dca5ef8df2af9ba8645'
  WHEN '添加或修改用户标签表单' THEN 'sm_936c5706bbef5b750e6e1842'
  WHEN '添加或修改用户等级' THEN 'sm_2452fd73ca0b5a65122aaa78'
  WHEN '添加批次' THEN 'sm_b3354da0f945d0989fdfd08b'
  WHEN '添加拼团' THEN 'sm_79434a935ac82bdd390b2036'
  WHEN '添加提货点' THEN 'sm_dfcd3523bbd165602d923a54'
  WHEN '添加文章' THEN 'sm_768d472a984d209d3f5dbbc8'
  WHEN '添加权限' THEN 'sm_85f9a2c668dd74c56a79ef31'
  WHEN '添加标签' THEN 'sm_736eaaaec55f2486882a2490'
  WHEN '添加核销员' THEN 'sm_5591fbc14b50a2e0a28103dc'
  WHEN '添加用户等级表单' THEN 'sm_8ad156a3475885090854a3c6'
  WHEN '添加直播商品' THEN 'sm_2e2bef7d58e160879e4f9123'
  WHEN '添加直播间' THEN 'sm_13585062a0aeae4b43784d12'
  WHEN '添加砍价' THEN 'sm_478a6ab480a48caeab5d66d6'
  WHEN '添加秒杀' THEN 'sm_f996667a322a0e0712b30ea5'
  WHEN '添加积分商品' THEN 'sm_d48a3ad41cc25a7e5667b7f2'
  WHEN '添加等级' THEN 'sm_86bf1ad2ea7153cc253f2bc0'
  WHEN '添加管理员' THEN 'sm_19070dc22b669a8b6a547486'
  WHEN '添加素材分类' THEN 'sm_051e7fd9b80bdfd90bb4ac6a'
  WHEN '添加组合数据' THEN 'sm_be5cc597e90b5a865dc981d7'
  WHEN '添加表单' THEN 'sm_5871b8f9761d3b3e63e86c3a'
  WHEN '添加规格' THEN 'sm_4592452e1529160ee768a517'
  WHEN '添加角色' THEN 'sm_596b046350ddc30097024df3'
  WHEN '添加话术' THEN 'sm_6dea2394c79872f35fe7575a'
  WHEN '添加语言列表' THEN 'sm_5d5d2b4469b5e27672bdfc5b'
  WHEN '添加语言地区' THEN 'sm_bdaab66b05aaa909b827562d'
  WHEN '添加语言地区表单' THEN 'sm_4738d148496163e8469dcbc6'
  WHEN '添加语言详情' THEN 'sm_05c51822eeb2dc6086b22932'
  WHEN '添加运费模版' THEN 'sm_801075cfc94674b84688dc48'
  WHEN '添加配置' THEN 'sm_11e5e642fd416cd66f25eaa2'
  WHEN '添加配送员' THEN 'sm_193fedbd07ac3749912c4a91'
  WHEN '添加门店店员表单' THEN 'sm_05b85a22e26d2f7fa584ded1'
  WHEN '清除上级推广人' THEN 'sm_3bd83b7833fa4b945e5f5a32'
  WHEN '清除城市数据缓存' THEN 'sm_6de56a7fe907dcd748ca1ca4'
  WHEN '清除数据' THEN 'systemClearData'
  WHEN '渠道码' THEN 'sm_5cd2b1532e12c22c64074c81'
  WHEN '渠道码分类保存' THEN 'sm_a5b8e311e31bce97744695f6'
  WHEN '渠道码分类列表' THEN 'sm_2a2f45dd58f2bc635793b84e'
  WHEN '渠道码分类删除' THEN 'sm_9790b6b38a56389930e5f3f4'
  WHEN '渠道码分类添加编辑表单' THEN 'sm_252d1118cbbc6cc744a915da'
  WHEN '渠道码列表' THEN 'sm_1f7bfbc08f7b04105149280a'
  WHEN '渠道码添加' THEN 'sm_dd3f5d4b37d18914eaf0ea8b'
  WHEN '渠道码用户列表' THEN 'sm_b18d1a627761509da843c37f'
  WHEN '渠道码统计' THEN 'sm_ba40fac207e9886c8911b94c'
  WHEN '渠道码详情' THEN 'sm_b83582860ce913e3953d51e6'
  WHEN '版本管理' THEN 'sm_fe86275d4d69065bf0eaeca4'
  WHEN '物流公司' THEN 'freight'
  WHEN '物流查询配置' THEN 'other_logistics'
  WHEN '生成商品规格列表' THEN 'sm_e721ed20424f624eb4d5684f'
  WHEN '生成直播商品' THEN 'sm_13c4b9eb86735f11207fb99a'
  WHEN '用户充值' THEN 'sm_31e4a75286cf03de3a91924c'
  WHEN '用户分组表单' THEN 'sm_5248168173a75d960e299a6c'
  WHEN '用户留言' THEN 'feedback'
  WHEN '用户领取记录' THEN 'sm_6a01d945a7776f566635a1d8'
  WHEN '申请发票列表' THEN 'sm_fc869ce39180001b35d31c73'
  WHEN '电子发票' THEN 'sm_ac84ee557c81e6c39e3ca56f'
  WHEN '电子面单打印' THEN 'sm_12f02d103e44125e25249b0c'
  WHEN '电子面单模板列表' THEN 'sm_ba375af47dc5fcdf62e46202'
  WHEN '电子面单配置' THEN 'other_electronic'
  WHEN '直播商品管理' THEN 'sm_317bd3302d8451d5cd9f5f13'
  WHEN '直播商品详情' THEN 'sm_3799ba4ad7614d82647edf26'
  WHEN '直播管理' THEN 'sm_9bd3cd2fe37d1983ab949df0'
  WHEN '直播间添加' THEN 'sm_26fd56d669213311cdf4e1c4'
  WHEN '直播间添加商品' THEN 'sm_4d36acdb4f2d311b8bcec4ba'
  WHEN '直播间状态' THEN 'sm_eec54eb07a3e1b033837c77f'
  WHEN '直播间管理' THEN 'sm_f805cb612e0a7bbfe891c93c'
  WHEN '直播间详情' THEN 'sm_7c587c6a30e16955e75b6042'
  WHEN '短信接口配置' THEN 'sm_bfe77618fa9a923707f5f6a5'
  WHEN '砍价人列表' THEN 'sm_7cd544b47a3979f2e6dfd104'
  WHEN '砍价列表' THEN 'sm_b3eb86c049c57ea94b67a16c'
  WHEN '砍价商品' THEN 'sm_78b361128971668b91a530ef'
  WHEN '砍价商品列表导出' THEN 'sm_773abe23b132f27f08257ad3'
  WHEN '砍价商品详情' THEN 'sm_b4a64767bb72cc19c001fc83'
  WHEN '砍价添加' THEN 'sm_478208345b6995e174e4e3b9'
  WHEN '砍价管理' THEN 'sm_7491d58420b9644e7a14fb4d'
  WHEN '砍价统计' THEN 'sm_bf91278cc2e0ac8b0b7b69d7'
  WHEN '确认收货' THEN 'sm_775b01c08f3058060ca857e6'
  WHEN '秒杀列表' THEN 'sm_917117449718fdc0443bb5a7'
  WHEN '秒杀参与人' THEN 'sm_d7a79c9e66bec43586a79f5c'
  WHEN '秒杀商品' THEN 'sm_523beff1465879d15269ae7f'
  WHEN '秒杀商品列表导出' THEN 'sm_21d62d10576a8e1b33d236d3'
  WHEN '秒杀商品详情' THEN 'sm_c3dd7f5248c4cabb169cd57d'
  WHEN '秒杀添加' THEN 'sm_b5b32588758cad9902667164'
  WHEN '秒杀管理' THEN 'sm_db7a3ac808d89426b708d9ba'
  WHEN '秒杀统计' THEN 'sm_340fc39009354fff5008fd28'
  WHEN '秒杀配置' THEN 'sm_54ac60eb75b60cd97c3d414a'
  WHEN '积分商品' THEN 'sm_fe0ea19b4a368c98180234b6'
  WHEN '积分商品删除' THEN 'sm_7ff4ef09b075f3a9e13970b6'
  WHEN '积分商品新增或编辑' THEN 'sm_b704dfa1c18cf799b42ce320'
  WHEN '积分商品添加' THEN 'sm_25a9efdb59372c583855c72f'
  WHEN '积分商品状态' THEN 'sm_31bf06dc00dd158c5fc5e63c'
  WHEN '积分商品详情' THEN 'sm_ba8965f31d4785e1e34f103c'
  WHEN '积分商城订单详情数据' THEN 'sm_485027a1d3b456745606e32d'
  WHEN '积分管理' THEN 'sm_46e7aa2b4d29c38ddd719e12'
  WHEN '积分统计' THEN 'sm_ac15f2e85dbf7b5337fff273'
  WHEN '积分订单' THEN 'sm_b2f2ee5aac406f7d9009e9c6'
  WHEN '积分订单列表获取配送员' THEN 'sm_0de7619b8bb6d706ae5b9ff5'
  WHEN '积分订单发送货' THEN 'sm_3e73a692050cc34259e37a98'
  WHEN '积分订单快递公司电子面单模版' THEN 'sm_22df2e771408d291dc3db73e'
  WHEN '积分订单确认收货' THEN 'sm_ffbd4e93f728604d3b28737a'
  WHEN '积分订单获取物流信息' THEN 'sm_b80e7ebf2ba32bdeddd22485'
  WHEN '积分订单获取物流公司' THEN 'sm_bfa4d1fb6da276c646ebd812'
  WHEN '积分订单获取面单默认配置信息' THEN 'sm_685837a7889defb2e5516f92'
  WHEN '积分记录' THEN 'sm_dc8c3737da5acb0b0658305e'
  WHEN '积分记录列表备注' THEN 'sm_3e729dc5c4c520830ff49ef4'
  WHEN '积分记录备注' THEN 'sm_d07c02e3934113908b92338f'
  WHEN '积分配置' THEN 'sm_35f445066e3c124a9150dbc7'
  WHEN '移动图片分类' THEN 'sm_a6ba4279cb36ff7e162fef6a'
  WHEN '移动图片分类表单' THEN 'sm_ef8850219a34b3a593eeaa41'
  WHEN '签到奖励' THEN 'sm_58d2a9f76c128f7d802ce215'
  WHEN '签到配置' THEN 'sign_config'
  WHEN '管理员列表' THEN 'systemAdmin'
  WHEN '管理员状态' THEN 'sm_fc18d8c86b623d3c82806dda'
  WHEN '管理员身份列表' THEN 'sm_8d10bbc660348fe8e176b1f6'
  WHEN '管理员身份权限列表' THEN 'sm_244b11adb03269cac1d9f2e4'
  WHEN '管理权限' THEN 'sm_d5e8dbf8a5c70fd11cec3a4b'
  WHEN '系统存储配置' THEN 'sm_34b49b362c829620f6810161'
  WHEN '系统日志' THEN 'systemLog'
  WHEN '系统设置' THEN 'system'
  WHEN '系统通知列表' THEN 'sm_b396950d9f45ac5e325e2914'
  WHEN '素材管理' THEN 'sm_b49e2f455cbed41d794352ef'
  WHEN '线下支付' THEN 'sm_f48b8a694d78d7b72e02b4c0'
  WHEN '线下确认付款' THEN 'sm_ce94a51df5b0d461ed9632ed'
  WHEN '编辑主题' THEN 'sm_e04b2baa90f077088d56e363'
  WHEN '编辑二维码' THEN 'sm_7a91626d160ebe09304f414f'
  WHEN '编辑会员权益' THEN 'sm_362d314a1867ddc617795112'
  WHEN '编辑会员类型' THEN 'sm_6316282e9ab325ba12d358d5'
  WHEN '编辑功能' THEN 'sm_8a243a050dd9541d952a3b7a'
  WHEN '编辑发票' THEN 'sm_bca2db17b48daf76f79d7160'
  WHEN '编辑商品' THEN 'sm_7d3792aad09531f6050be47f'
  WHEN '编辑图文' THEN 'sm_0a0b900a888f292f30a51ba4'
  WHEN '编辑城市数据' THEN 'sm_de14ff37335c651df0c7ac74'
  WHEN '编辑定时任务' THEN 'sm_01f33389c09f7ac685387e78'
  WHEN '编辑客服' THEN 'sm_b04b58521166a08835a6e33d'
  WHEN '编辑对外账号' THEN 'sm_bc193375a3aa6235cdd869bf'
  WHEN '编辑批次名' THEN 'sm_d2b2cb5ccad974068ad77861'
  WHEN '编辑拼团' THEN 'sm_bf6947db7e91a7218b7dc91d'
  WHEN '编辑接口' THEN 'sm_ea56ca3dac0d39e463a8233f'
  WHEN '编辑提现' THEN 'sm_7eace40cab2d87d376a6b672'
  WHEN '编辑文章' THEN 'sm_d0ce1305d4b22feaa1490459'
  WHEN '编辑权限' THEN 'sm_94a1b35b2f070a66d2299591'
  WHEN '编辑物流公司' THEN 'sm_f6c91b81897c382171e839c4'
  WHEN '编辑砍价' THEN 'sm_3788eb772abe09e924ba7c38'
  WHEN '编辑秒杀' THEN 'sm_7a075e5b75474e3c2bd9cadc'
  WHEN '编辑积分商品' THEN 'sm_eac0b16dc6c1c91fdf4ff290'
  WHEN '编辑管理员详情' THEN 'sm_ee6cb9b3698bf7fe70ff92cf'
  WHEN '编辑菜单' THEN 'sm_ca34725aabc1275bc0b63834'
  WHEN '编辑规格' THEN 'sm_6dccdf8c1e91d6bd6f4e6011'
  WHEN '编辑角色' THEN 'sm_ac775e9a517940bf1d5ecb31'
  WHEN '编辑话术' THEN 'sm_28400f197ad9c03c9f9435b0'
  WHEN '编辑语言地区' THEN 'sm_d15c544a51f766f42f7d2727'
  WHEN '编辑语言详情' THEN 'sm_5f043075f4f80cd8fbc901ac'
  WHEN '编辑配送员' THEN 'sm_eee2bb6fad71b01d1b733716'
  WHEN '编辑配送员表单' THEN 'sm_97e5f68eb769ce781474f85b'
  WHEN '编辑页面' THEN 'sm_49bcb8aa03b0e413ae9f7519'
  WHEN '翻译配置' THEN 'lang_config'
  WHEN '自动回复' THEN 'auto_reply'
  WHEN '获取CRUD列表' THEN 'sm_0efcdde5c7153c26fa6cb416'
  WHEN '获取CRUD文件存放' THEN 'sm_add81e361f49564c91a87019'
  WHEN '获取CRUD配置' THEN 'sm_162b3a83003ab0c44fc581c5'
  WHEN '获取不退款表单' THEN 'sm_58cacc88ef773431f74b69ad'
  WHEN '获取修改分销员等级任务表单' THEN 'sm_6b7e2f62acf544c744bd6b19'
  WHEN '获取修改分销员等级表单' THEN 'sm_3f537118a50ba6099cba7756'
  WHEN '获取修改客服话术分类表单' THEN 'sm_0784e4788f9ec219cc1eab1b'
  WHEN '获取修改客服话术表单' THEN 'sm_add278f256aafd71ab60593d'
  WHEN '获取修改文章分类表单' THEN 'sm_0ded5cf942db4753c17d5ca2'
  WHEN '获取修改文章表单' THEN 'sm_e70115bd38b3064cf07b9aec'
  WHEN '获取修改权限菜单表单' THEN 'sm_1732f100014e4b95e143e089'
  WHEN '获取修改标签分类表单' THEN 'sm_1a4f71ec8edf16edf212d4ba'
  WHEN '获取修改物流公司表单' THEN 'sm_f0c5a745d587f08ecc4e9ff7'
  WHEN '获取修改用户反馈表单' THEN 'sm_5571fce6c8f92a923245e79c'
  WHEN '获取修改管理员表单' THEN 'sm_28acd62b4fbb65e376eaad87'
  WHEN '获取修改系统配置分类表单' THEN 'sm_36de65cac9aa7421a5ab7f1d'
  WHEN '获取修改系统配置表单' THEN 'sm_434aef699756aac8090a214f'
  WHEN '获取修改组合数据子数据表单' THEN 'sm_ff855af63b149c0e7e33a0a9'
  WHEN '获取修改组合数据表单' THEN 'sm_c1b65305e195df1c3fe9bdc9'
  WHEN '获取修改路由分类表单' THEN 'sm_035e564313c1e8772cbabdd2'
  WHEN '获取修改附件分类管理表单' THEN 'sm_c813e2a56610fc3dc63bceb2'
  WHEN '获取分销员等级任务列表' THEN 'sm_f830642c4773cab90b3f71e7'
  WHEN '获取分销员等级任务表单' THEN 'sm_7f41525da408ecb3bd1634b6'
  WHEN '获取分销员等级表单' THEN 'sm_af40fcca825f531a1705fb65'
  WHEN '获取前端页面路径' THEN 'sm_bc5a55c2ecb83a41eff8ce7a'
  WHEN '获取协议内容' THEN 'sm_f95556aba1d1af1153ceadab'
  WHEN '获取单条通知数据' THEN 'sm_db5dc7235fc589ebcc7b481c'
  WHEN '获取可以进行关联的表名' THEN 'sm_feb064318e2df637b53d4333'
  WHEN '获取商品分类' THEN 'sm_d3d93902c52f59fe2c08da08'
  WHEN '获取商品列表' THEN 'sm_dd8f0fc44ac42c22424ebadf'
  WHEN '获取商品规则属性模板' THEN 'sm_7ccbabf2ac01373b40f92b3a'
  WHEN '获取商品规格' THEN 'sm_3f42bd2b3628e3d864ca677f'
  WHEN '获取城市数据列表' THEN 'sm_bf6c07e660e30279ef938f2c'
  WHEN '获取城市数据完整列表' THEN 'sm_0be00f371137a1ed17949816'
  WHEN '获取复制商品配置' THEN 'sm_0a535922f8a588b0fc2ec9c3'
  WHEN '获取客服话术分类列表' THEN 'sm_2ebf519e87edaec270ed8f16'
  WHEN '获取客服话术分类表单' THEN 'sm_03d4f6167a5876aa0b1a5882'
  WHEN '获取客服话术表单' THEN 'sm_576e54766c64b758305ab786'
  WHEN '获取所有二级分类' THEN 'sm_3996ce6b0f2dfeb02f88139b'
  WHEN '获取数据字典列表' THEN 'sm_9961ad2f741d379ac06f831d'
  WHEN '获取文章分类表单' THEN 'sm_51e06c1cc3d0ead1ab33e7a3'
  WHEN '获取文章表单' THEN 'sm_fd6e321ed28ddf9d2e5dfda5'
  WHEN '获取文章详细信息' THEN 'sm_1d8ae1f5fb830a92b7f3c731'
  WHEN '获取权限菜单列表' THEN 'sm_53e8cc2f1262078b61376732'
  WHEN '获取权限菜单表单' THEN 'sm_4eeb175d3ef840626b6d68fd'
  WHEN '获取标签分类表单' THEN 'sm_9b3a819a39e66733e2ef1b40'
  WHEN '获取版权信息' THEN 'sm_77125c7c2621aad6668c9930'
  WHEN '获取物流信息' THEN 'sm_536466d3257c745f6c31ae23'
  WHEN '获取物流公司' THEN 'sm_0cbc4c48ef84648475f55612'
  WHEN '获取物流公司列表' THEN 'sm_57e3502a6c24f3998c89aa9b'
  WHEN '获取用户标签' THEN 'sm_07cad68e29fa9a8ee7d3f291'
  WHEN '获取积分订单状态' THEN 'sm_0c9beeea4dbf6dd08a2b5578'
  WHEN '获取积分订单配送信息表单' THEN 'sm_253e49695c3fe5ce08840b5c'
  WHEN '获取管理员列表' THEN 'sm_d5d3385f736e30f6e811939c'
  WHEN '获取管理员表单' THEN 'sm_f2bb5f1d73629ef54571aa0a'
  WHEN '获取系统配置分类列表' THEN 'sm_f9047e004e23b785b8c56f36'
  WHEN '获取系统配置列表' THEN 'sm_ab49c2708d65bec8bcbe4029'
  WHEN '获取系统配置表单' THEN 'sm_7d9e678769f6d79781a88e0d'
  WHEN '获取线下付款二维码' THEN 'sm_48325e4d146825c7d14e5293'
  WHEN '获取组合数据列表' THEN 'sm_68adabcd0e0e7151fa191fd6'
  WHEN '获取组合数据子数据列表' THEN 'sm_aaadbc5496777e3c57173d35'
  WHEN '获取组合数据子数据表单' THEN 'sm_b48fbdb83ef1fee551665a7e'
  WHEN '获取菜单TREE形数据' THEN 'sm_2feb772c96097639e528eee5'
  WHEN '获取表的详细信息' THEN 'sm_65a4b3c7070d069e2224c816'
  WHEN '获取订单可拆分商品列表' THEN 'sm_10c7ccd11f18a0bac8570192'
  WHEN '获取订单拆分子订单列表' THEN 'sm_113600a6b814500d5b6437e0'
  WHEN '获取订单状态' THEN 'sm_2f2521bc47d2aa101132c3a1'
  WHEN '获取订单编辑表单' THEN 'sm_6d2c59725c6a1585923326a7'
  WHEN '获取路由tree' THEN 'sm_cdba5e404375f4225f7f8817'
  WHEN '获取运费模板' THEN 'sm_da86121ca20288570cb34c93'
  WHEN '获取退出未保存的数据' THEN 'sm_e3d99198002931d4fde229ad'
  WHEN '获取退款单详情' THEN 'sm_733440885247b58bb7c1f976'
  WHEN '获取配送信息表单' THEN 'sm_e23ae653d9867efa418dbaba'
  WHEN '获取采集商品数据' THEN 'sm_55de3f9fde36155201dc8fab'
  WHEN '获取门店店员列表' THEN 'sm_591967d335a23c73f52d1636'
  WHEN '获取门店自提开启状态' THEN 'sm_c9fff3f0f7baa4f05f55aec5'
  WHEN '获取附件分类管理列表' THEN 'sm_71ad46ccc4bde3ffd153a498'
  WHEN '获取附件分类管理表单' THEN 'sm_bed3c0e148e1354e0e3f8abd'
  WHEN '获取页面链接' THEN 'sm_15947e6259c428b8ee183b95'
  WHEN '获取页面链接分类' THEN 'sm_c6359bd74f3951257e8f7a8f'
  WHEN '获取风格设置' THEN 'sm_a9ac5620f48cb595ff275573'
  WHEN '装修页面' THEN 'sm_10d87bdcc30e8b1177c9e20b'
  WHEN '角色状态' THEN 'sm_418464baded5100a96331fb1'
  WHEN '角色管理' THEN 'sm_3f856ec241d67b94402699ed'
  WHEN '订单信息' THEN 'sm_a6d10d5afe0a1528f8b6c416'
  WHEN '订单列表导出' THEN 'sm_fd28beeb7e23677d6bd26de4'
  WHEN '订单列表获取配送员' THEN 'sm_24bf3002341f5eb0353f1b76'
  WHEN '订单发货' THEN 'sm_c58f778220aa2b6e44487020'
  WHEN '订单发送货' THEN 'sm_422f4e86e698acabf4edd0f0'
  WHEN '订单号核销' THEN 'sm_312bfda32e3e801800c88b1a'
  WHEN '订单备注' THEN 'sm_bb84d6eab6156242125acd22'
  WHEN '订单导出' THEN 'sm_dd7d85f9e3eee0b766838ef4'
  WHEN '订单核销' THEN 'sm_f873a27778b69226a27b720e'
  WHEN '订单编辑' THEN 'sm_648b18ece469e3ffcea917cc'
  WHEN '订单记录' THEN 'sm_bd31c4f40c16a18c32b9cf4a'
  WHEN '订单详情' THEN 'sm_8054f719f7e99b8c203e2b07'
  WHEN '设为首页' THEN 'sm_35fe6c40a1f90fc7f3fe50d0'
  WHEN '设置Diy默认数据' THEN 'sm_7d020f35315a43b4dddc4077'
  WHEN '设置分组' THEN 'sm_194cfaf4c75ffeb10083bd6b'
  WHEN '设置协议内容' THEN 'sm_660d0a93ebf9e7dd977d543a'
  WHEN '设置发票状态' THEN 'sm_6f306af5c9c12baa07e0a667'
  WHEN '设置和取消用户标签' THEN 'sm_8918db3c9b01ec8f121ab46c'
  WHEN '设置备注' THEN 'sm_9da501e364fb1a27d4d23fcd'
  WHEN '设置对外账号' THEN 'sm_d65bdacda737b7566a9faceb'
  WHEN '设置批量商品上架' THEN 'sm_cbe000857d30bc044a24558d'
  WHEN '设置批量商品下架' THEN 'sm_a9722794909517e3506ce57e'
  WHEN '设置标签' THEN 'sm_32c2f567da6d30f8ef9504a7'
  WHEN '设置消息' THEN 'sm_3a8f9458b40a22f332822669'
  WHEN '设置用户分组' THEN 'sm_aef43f58c5ea3db88bc5b1ca'
  WHEN '设置用户等级上下架' THEN 'sm_4daadcd113a10cbfddb135db'
  WHEN '设置直播间是否显示' THEN 'sm_6d2a8a669f565f463bc976e6'
  WHEN '设置等级状态' THEN 'sm_e83d0a261d375c30d3e92a60'
  WHEN '设置账号推送接口' THEN 'sm_4d06641d13deb1ce7bacd1f1'
  WHEN '语言列表' THEN 'langList'
  WHEN '语言列表状态' THEN 'sm_712aeda64d145b7ea415391d'
  WHEN '语言国家列表' THEN 'sm_5ad1c24f99fdae2397d44431'
  WHEN '语言类型列表' THEN 'sm_f0afbded25dc1d9f3c472d1a'
  WHEN '语言详情' THEN 'langInfo'
  WHEN '调试接口' THEN 'sm_dbb2f61aa32eb9a4581d2f04'
  WHEN '财务操作' THEN 'sm_4538b9cb1ada2beef8d9e243'
  WHEN '财务记录' THEN 'sm_66264e815e70898b917bcc46'
  WHEN '账单记录' THEN 'sm_931a6ffdd6510004033b5a7d'
  WHEN '账单详情' THEN 'sm_6aedf518d9b11b2a207342ab'
  WHEN '账号管理' THEN 'sm_b829fe7711eaea2445c8a050'
  WHEN '资金流水' THEN 'sm_07e455b2858aaee53cc191a8'
  WHEN '资金流水备注' THEN 'sm_872ea3b1e4009b794176c5ab'
  WHEN '运费模板' THEN 'shippingTemplates'
  WHEN '运费模板列表' THEN 'sm_3f0b73c40e4a3f5d614fceee'
  WHEN '还原Diy默认数据' THEN 'sm_238e5060ba7e23e161215377'
  WHEN '进入工作台' THEN 'sm_07c8d3d41cb36dfdbf331a1e'
  WHEN '退款' THEN 'sm_44c198c146aecc3f2ac5c5c7'
  WHEN '选择接口' THEN 'sm_d84b8d7f5f86f17347b349b5'
  WHEN '通过提现申请' THEN 'sm_c8663a973307087937e7fd07'
  WHEN '配送员列表' THEN 'deliveryService'
  WHEN '配送员状态' THEN 'sm_2f651cabe3d99ee4fe3c7ecf'
  WHEN '配送员管理' THEN 'sm_1a05d52b7b5a70bdf84163ea'
  WHEN '金额设置' THEN 'sm_739adc16774a822ea424da55'
  WHEN '链接管理' THEN 'link'
  WHEN '门店上下架' THEN 'sm_28cdd78febd8866a437b06b2'
  WHEN '门店位置选择' THEN 'sm_003447d13ebe235fb2760187'
  WHEN '门店列表' THEN 'sm_9f31029d288068471111f0d9'
  WHEN '门店删除' THEN 'sm_2208aef8ff01142ef0d47813'
  WHEN '门店搜索列表' THEN 'sm_6665b47f7eefbca69f641bc2'
  WHEN '门店详情' THEN 'sm_862c34dd9a774c4413456ff3'
  WHEN '面单默认配置信息' THEN 'sm_ebc940c8e91be105870fe26c'
  WHEN '领取记录' THEN 'sm_709c4cf9497f487661e8784b'
  WHEN '首页装修' THEN 'sm_8542c114c98a3a3259c68a5f'
  ELSE menu_name
END
WHERE menu_name IN (
  'App',
  'APP',
  'APP配置',
  'Bảo hành sản phẩm',
  'Bảo trì an toàn',
  'Bảo trì dữ liệu',
  'Biểu mẫu bình luận ảo',
  'Biểu mẫu nhóm người dùng',
  'Biểu mẫu Thêm/Chỉnh sửa Nhóm',
  'Cài đặt ngôn ngữ',
  'Cấp độ người dùng',
  'Cấu hình',
  'Cấu hình đơn hàng',
  'Cấu hình mô-đun',
  'Cấu hình người dùng',
  'Chỉnh sửa biểu mẫu cân bằng điểm',
  'Chỉnh sửa người dùng',
  'CMS',
  'Công cụ phát triển',
  'Cung cấp thời gian thành viên tr',
  'Customer Serivce',
  'Đánh giá sản phẩm',
  'Danh mục cấu hình',
  'Danh mục sản phẩm',
  'Đặt thẻ hàng loạt',
  'Điều chỉnh số dư điểm',
  'Diy模板数据详情',
  'Đơn đặt hàng của thu ngân',
  'Đơn đặt hàng sau bán hàng',
  'Dữ liệu kết hợp',
  'Finance',
  'Giao diện bên ngoài',
  'Gửi danh sách phiếu giảm giá',
  'Gửi phiếu giảm giá',
  'Hồ sơ xác minh',
  'Lấy biểu mẫu chỉnh sửa người dùn',
  'Lấy biểu mẫu danh mục cấu hình h',
  'Lấy biểu mẫu để tạo danh mục tuy',
  'Lấy danh sách các loại tuyến đườ',
  'Lấy danh sách danh mục cấu hình ',
  'Lấy danh sách dữ liệu kết hợp',
  'Lấy thẻ người dùng',
  'Lấy thông tin chi tiết người dùn',
  'Lấy thông tin của một người dùng',
  'Lưu bình luận ảo',
  'Lưu các danh mục cấu hình hệ thố',
  'Lưu danh mục tuyến đường',
  'Lưu dữ liệu biểu mẫu nhóm',
  'Lưu dữ liệu kết hợp',
  'Lưu người dùng',
  'Lưu thẻ người dùng',
  'Marketing',
  'Nhãn sản phẩm',
  'Nhiệm vụ theo lịch trình',
  'Nhóm người dùng',
  'Order',
  'PC端',
  'PC端装修',
  'PC端配置',
  'Phân loại giao diện',
  'Phân nhóm theo lô',
  'Promotion',
  'Quản lý cơ sở dữ liệu',
  'Quản lý đơn hàng',
  'Quản lý giao diện',
  'Quản lý người dùng',
  'Quản lý sản phẩm',
  'Quản lý tập tin',
  'Settings',
  'Shop',
  'Số điểm còn lại',
  'Sự kiện tùy chỉnh',
  'Supplier',
  'System',
  'systemMenu',
  'Thành viên miễn phí',
  'Thẻ người dùng',
  'Thêm hoặc chỉnh sửa thẻ người dù',
  'Thêm hoặc sửa đổi biểu mẫu thẻ n',
  'Thêm mới',
  'Thêm người dùng',
  'Thêm nhóm',
  'Thêm nhóm dữ liệu',
  'Thêm phần tự đánh giá',
  'Thiết lập nhóm người dùng',
  'Thời gian thành viên trả phí miễ',
  'Thông số sản phẩm',
  'Thông tin cần lưu ý khi thêm hoặ',
  'Thông tin hệ thống',
  'Thông tin người dùng',
  'Thuộc tính sản phẩm',
  'Trả lời bình luận',
  'Trang chủ',
  'User',
  'Xóa bình luận',
  'Xuất danh sách người dùng',
  'Xuất dữ liệu người dùng',
  '一号通',
  '一号通配置',
  '一号通页面',
  '一键同步模版消息',
  '一键同步订阅消息',
  '一键复制优惠券',
  '上传图片',
  '上传视频密钥接口',
  '上传类型',
  '上传素材',
  '下载二维码',
  '下载代码',
  '下载小程序包',
  '下载小程序模版',
  '下载小程序码',
  '下载小程序页面数据',
  '下载生成的文件',
  '下载账单',
  '不退款',
  '专题页面',
  '个人中心',
  '个人中心保存',
  '个人中心详情',
  '主播管理',
  '主题风格',
  '二维码统计',
  '付费会员',
  '代码生成',
  '优惠券',
  '优惠券列表',
  '会员卡修改状态',
  '会员卡列表',
  '会员卡导出',
  '会员卡批次快速修改',
  '会员卡类型编辑',
  '会员权益',
  '会员权益修改',
  '会员权益状态',
  '会员类型',
  '会员类型修改状态',
  '会员类型列表',
  '会员类型删除',
  '会员类型状态',
  '会员记录',
  '会员配置',
  '会员领取记录',
  '余额记录',
  '佣金记录',
  '使用DIY模板',
  '保存CRUD修改的文件',
  '保存个人中心',
  '保存主播数据',
  '保存修改语言',
  '保存修改门店信息',
  '保存关键字回复',
  '保存分组表单数据',
  '保存分销员等级',
  '保存分销员等级任务',
  '保存协议',
  '保存图文',
  '保存客服话术',
  '保存客服话术分类',
  '保存并发布',
  '保存并回复',
  '保存店员',
  '保存微信公众号菜单',
  '保存文章',
  '保存文章分类',
  '保存新增修改语言',
  '保存新建的配送员',
  '保存权限菜单',
  '保存标签分类',
  '保存渠道码',
  '保存生成CRUD',
  '保存管理员',
  '保存系统配置',
  '保存组合数据子数据',
  '保存语言地区',
  '保存路由权限',
  '保存还未提交数据',
  '保存通知设置',
  '保存采集商品数据',
  '保存附件分类管理',
  '修改上级推广人',
  '修改不退款理由',
  '修改主播',
  '修改关键字回复状态',
  '修改分类',
  '修改分组',
  '修改分销员等级',
  '修改分销员等级任务',
  '修改分销等级',
  '修改分销等级任务状态',
  '修改分销等级状态',
  '修改商品状态',
  '修改图片名称',
  '修改城市数据表单',
  '修改备注信息',
  '修改客服',
  '修改客服状态',
  '修改客服表单',
  '修改客服话术',
  '修改客服话术分类',
  '修改店员状态',
  '修改店员表单',
  '修改或者保存字典数据',
  '修改拼团商品状态',
  '修改提货点',
  '修改数据组',
  '修改文章',
  '修改文章分类',
  '修改权限菜单',
  '修改标签',
  '修改标签分类',
  '修改核销员',
  '修改消息状态',
  '修改物流公司',
  '修改用户反馈',
  '修改砍价商品状态',
  '修改秒杀商品状态',
  '修改积分商品状态',
  '修改积分订单配送信息',
  '修改等级',
  '修改管理员',
  '修改管理员状态',
  '修改管理员身份状态',
  '修改系统配置',
  '修改系统配置分类',
  '修改组合数据',
  '修改组合数据子数据',
  '修改订单',
  '修改语言列表',
  '修改语言类型状态',
  '修改路由分类',
  '修改运费模板数据',
  '修改运费模版',
  '修改配置',
  '修改配置分类',
  '修改配置状态',
  '修改配送员',
  '修改配送员状态',
  '修改附件分类管理',
  '充值删除',
  '充值记录',
  '充值记录列表',
  '充值退款',
  '充值退款表单',
  '充值配置',
  '兑换会员卡二维码',
  '兑换记录',
  '公众号',
  '公众号配置',
  '关注回复',
  '关联商品',
  '关键字回复',
  '关键字回复列表',
  '关键字回复详情',
  '内容设置',
  '分片上传本地视频',
  '分类状态',
  '分销员申请',
  '分销员管理',
  '分销等级',
  '分销等级任务',
  '分销设置',
  '切换主题',
  '切换分类页面',
  '删除CRUD',
  '删除DIY模板',
  '删除上级推广人',
  '删除主播',
  '删除二维码',
  '删除优惠券',
  '删除会员类型',
  '删除充值记录',
  '删除关键字回复',
  '删除分类',
  '删除分组',
  '删除分销员等级',
  '删除分销员等级任务',
  '删除分销等级',
  '删除功能',
  '删除商品分类',
  '删除商品规则',
  '删除商品评论',
  '删除图文',
  '删除图片',
  '删除城市数据',
  '删除定时任务',
  '删除客服',
  '删除客服话术',
  '删除对外账号',
  '删除店员',
  '删除拼团',
  '删除拼团商品',
  '删除提货点',
  '删除数据字典',
  '删除数据组',
  '删除文章',
  '删除文章分类',
  '删除权限',
  '删除权限菜单',
  '删除标签',
  '删除标签分类',
  '删除核销员',
  '删除渠道码',
  '删除用户分组数据',
  '删除用户反馈',
  '删除用户标签',
  '删除用户等级',
  '删除留言',
  '删除直播商品',
  '删除直播间',
  '删除砍价',
  '删除砍价商品',
  '删除秒杀',
  '删除秒杀商品',
  '删除积分商品',
  '删除等级',
  '删除管理员',
  '删除管理员身份',
  '删除系统配置',
  '删除系统配置分类',
  '删除素材',
  '删除素材分类',
  '删除组合数据',
  '删除组合数据子数据',
  '删除规格',
  '删除角色',
  '删除订单',
  '删除订单单个',
  '删除话术',
  '删除语言',
  '删除语言列表',
  '删除语言地区',
  '删除语言详情',
  '删除账号',
  '删除路由分类',
  '删除运费模板',
  '删除运费模版',
  '删除配置',
  '删除配置分类',
  '删除配送员',
  '删除附件分类管理',
  '删除页面',
  '刷新缓存',
  '协议设置',
  '卡密会员',
  '参与拼团列表',
  '参与砍价列表',
  '发票管理',
  '发货设置',
  '取消推广资格',
  '取消文章关联商品',
  '同步接口',
  '同步消息',
  '同步物流公司',
  '同步直播间',
  '同步直播间状态',
  '同步路由',
  '售后备注',
  '售后订单备注',
  '售后订单退款',
  '售后订单退款表单',
  '商品上下架',
  '商品分类',
  '商品分类修改状态',
  '商品分类新增',
  '商品分类新增表单',
  '商品分类编辑',
  '商品分类编辑表单',
  '商品列表导出',
  '商品回复评论',
  '商品回收站',
  '商品导出',
  '商品快速编辑',
  '商品批量设置',
  '商品放入回收站',
  '商品添加',
  '商品规则列表',
  '商品规则详情',
  '商品评论',
  '商品详情',
  '商品配置',
  '商品采集',
  '商品采集配置',
  '商城主题',
  '商城支付配置',
  '商城首页',
  '商家同意退款，等待用户退货',
  '回复留言',
  '图文列表',
  '图文管理',
  '图文详情',
  '图片附件列表',
  '地区列表',
  '城市数据',
  '城市数据接口',
  '复制其他平台商品',
  '定时任务列表',
  '定时任务是否开启开关',
  '定时任务添加编辑',
  '定时任务状态',
  '定时任务类型',
  '定时任务详情',
  '审核提现',
  '客服列表',
  '客服登录',
  '客服话术',
  '客服配置',
  '对外接口账号信息',
  '对外接口账号修改',
  '对外接口账号添加',
  '导入虚拟商品卡密',
  '导出会员卡密',
  '导出拼团',
  '导出砍价',
  '导出秒杀',
  '小票打印',
  '小票配置',
  '小程序',
  '小程序上传',
  '小程序下载',
  '小程序配置',
  '小程序链接',
  '已发布优惠券删除',
  '已发布优惠券领取记录',
  '开票订单详情',
  '微信公众号菜单列表',
  '微信菜单',
  '快递公司电子面单模版',
  '快递面单打印',
  '我的主题',
  '打印积分订单',
  '打印订单',
  '扫码用户列表',
  '批量修改',
  '批量删除订单',
  '抽奖列表',
  '抽奖管理',
  '抽奖配置',
  '拆单发送货',
  '拒绝提现申请',
  '拼团人列表',
  '拼团列表',
  '拼团商品',
  '拼团商品列表导出',
  '拼团商品详情',
  '拼团添加',
  '拼团管理',
  '拼团统计',
  '拼团详情',
  '换色和分类保存',
  '接口配置',
  '推广二维码',
  '推广人列表',
  '推广订单',
  '推广订单列表',
  '提现申请',
  '提现记录修改',
  '提现记录修改表单',
  '提货点',
  '提货点状态',
  '提货点设置',
  '收款二维码',
  '数据字典',
  '数据配置',
  '文件管理入口',
  '文章关联商品',
  '文章分类',
  '文章添加',
  '文章管理',
  '新人礼',
  '新增/修改城市数据',
  '新增修改语言类型表单',
  '新增客服选择用户列表',
  '新增或修改运费模版',
  '新增或编辑拼团商品',
  '新增或编辑砍价商品',
  '新增或编辑秒杀商品',
  '新增配送表单',
  '新建二维码',
  '新建或修改商品',
  '新建或编辑商品规则',
  '新建或编辑管理员',
  '无效词回复',
  '更多操作打印电子面单',
  '机器翻译',
  '查看CRUD',
  '查看H5推广二维码',
  '查看代码',
  '查看公众号推广二维码',
  '查看卡列表',
  '查看商品',
  '查看小程序推广二维码',
  '查看数据列表',
  '查看数据字典',
  '查看文章',
  '查看权限菜单信息',
  '查看详情',
  '查看路由权限',
  '查看配置列表',
  '标签分类',
  '核销员',
  '核销员状态',
  '每日签到',
  '消息状态',
  '消息管理',
  '添加DIY',
  '添加DIY模板',
  '添加专题页',
  '添加主播',
  '添加优惠券',
  '添加会员卡批次',
  '添加会员类型',
  '添加修改主播表单',
  '添加修改分组表单',
  '添加修改直播商品',
  '添加分类',
  '添加分组',
  '添加分销等级',
  '添加功能',
  '添加商品',
  '添加图文',
  '添加图文消息',
  '添加城市数据',
  '添加城市数据表单',
  '添加复制优惠券',
  '添加子菜单',
  '添加定时任务',
  '添加客服',
  '添加客服表单',
  '添加对外账号',
  '添加或修改用户标签',
  '添加或修改用户标签表单',
  '添加或修改用户等级',
  '添加批次',
  '添加拼团',
  '添加提货点',
  '添加文章',
  '添加权限',
  '添加标签',
  '添加核销员',
  '添加用户等级表单',
  '添加直播商品',
  '添加直播间',
  '添加砍价',
  '添加秒杀',
  '添加积分商品',
  '添加等级',
  '添加管理员',
  '添加素材分类',
  '添加组合数据',
  '添加表单',
  '添加规格',
  '添加角色',
  '添加话术',
  '添加语言列表',
  '添加语言地区',
  '添加语言地区表单',
  '添加语言详情',
  '添加运费模版',
  '添加配置',
  '添加配送员',
  '添加门店店员表单',
  '清除上级推广人',
  '清除城市数据缓存',
  '清除数据',
  '渠道码',
  '渠道码分类保存',
  '渠道码分类列表',
  '渠道码分类删除',
  '渠道码分类添加编辑表单',
  '渠道码列表',
  '渠道码添加',
  '渠道码用户列表',
  '渠道码统计',
  '渠道码详情',
  '版本管理',
  '物流公司',
  '物流查询配置',
  '生成商品规格列表',
  '生成直播商品',
  '用户充值',
  '用户分组表单',
  '用户留言',
  '用户领取记录',
  '申请发票列表',
  '电子发票',
  '电子面单打印',
  '电子面单模板列表',
  '电子面单配置',
  '直播商品管理',
  '直播商品详情',
  '直播管理',
  '直播间添加',
  '直播间添加商品',
  '直播间状态',
  '直播间管理',
  '直播间详情',
  '短信接口配置',
  '砍价人列表',
  '砍价列表',
  '砍价商品',
  '砍价商品列表导出',
  '砍价商品详情',
  '砍价添加',
  '砍价管理',
  '砍价统计',
  '确认收货',
  '秒杀列表',
  '秒杀参与人',
  '秒杀商品',
  '秒杀商品列表导出',
  '秒杀商品详情',
  '秒杀添加',
  '秒杀管理',
  '秒杀统计',
  '秒杀配置',
  '积分商品',
  '积分商品删除',
  '积分商品新增或编辑',
  '积分商品添加',
  '积分商品状态',
  '积分商品详情',
  '积分商城订单详情数据',
  '积分管理',
  '积分统计',
  '积分订单',
  '积分订单列表获取配送员',
  '积分订单发送货',
  '积分订单快递公司电子面单模版',
  '积分订单确认收货',
  '积分订单获取物流信息',
  '积分订单获取物流公司',
  '积分订单获取面单默认配置信息',
  '积分记录',
  '积分记录列表备注',
  '积分记录备注',
  '积分配置',
  '移动图片分类',
  '移动图片分类表单',
  '签到奖励',
  '签到配置',
  '管理员列表',
  '管理员状态',
  '管理员身份列表',
  '管理员身份权限列表',
  '管理权限',
  '系统存储配置',
  '系统日志',
  '系统设置',
  '系统通知列表',
  '素材管理',
  '线下支付',
  '线下确认付款',
  '编辑主题',
  '编辑二维码',
  '编辑会员权益',
  '编辑会员类型',
  '编辑功能',
  '编辑发票',
  '编辑商品',
  '编辑图文',
  '编辑城市数据',
  '编辑定时任务',
  '编辑客服',
  '编辑对外账号',
  '编辑批次名',
  '编辑拼团',
  '编辑接口',
  '编辑提现',
  '编辑文章',
  '编辑权限',
  '编辑物流公司',
  '编辑砍价',
  '编辑秒杀',
  '编辑积分商品',
  '编辑管理员详情',
  '编辑菜单',
  '编辑规格',
  '编辑角色',
  '编辑话术',
  '编辑语言地区',
  '编辑语言详情',
  '编辑配送员',
  '编辑配送员表单',
  '编辑页面',
  '翻译配置',
  '自动回复',
  '获取CRUD列表',
  '获取CRUD文件存放',
  '获取CRUD配置',
  '获取不退款表单',
  '获取修改分销员等级任务表单',
  '获取修改分销员等级表单',
  '获取修改客服话术分类表单',
  '获取修改客服话术表单',
  '获取修改文章分类表单',
  '获取修改文章表单',
  '获取修改权限菜单表单',
  '获取修改标签分类表单',
  '获取修改物流公司表单',
  '获取修改用户反馈表单',
  '获取修改管理员表单',
  '获取修改系统配置分类表单',
  '获取修改系统配置表单',
  '获取修改组合数据子数据表单',
  '获取修改组合数据表单',
  '获取修改路由分类表单',
  '获取修改附件分类管理表单',
  '获取分销员等级任务列表',
  '获取分销员等级任务表单',
  '获取分销员等级表单',
  '获取前端页面路径',
  '获取协议内容',
  '获取单条通知数据',
  '获取可以进行关联的表名',
  '获取商品分类',
  '获取商品列表',
  '获取商品规则属性模板',
  '获取商品规格',
  '获取城市数据列表',
  '获取城市数据完整列表',
  '获取复制商品配置',
  '获取客服话术分类列表',
  '获取客服话术分类表单',
  '获取客服话术表单',
  '获取所有二级分类',
  '获取数据字典列表',
  '获取文章分类表单',
  '获取文章表单',
  '获取文章详细信息',
  '获取权限菜单列表',
  '获取权限菜单表单',
  '获取标签分类表单',
  '获取版权信息',
  '获取物流信息',
  '获取物流公司',
  '获取物流公司列表',
  '获取用户标签',
  '获取积分订单状态',
  '获取积分订单配送信息表单',
  '获取管理员列表',
  '获取管理员表单',
  '获取系统配置分类列表',
  '获取系统配置列表',
  '获取系统配置表单',
  '获取线下付款二维码',
  '获取组合数据列表',
  '获取组合数据子数据列表',
  '获取组合数据子数据表单',
  '获取菜单TREE形数据',
  '获取表的详细信息',
  '获取订单可拆分商品列表',
  '获取订单拆分子订单列表',
  '获取订单状态',
  '获取订单编辑表单',
  '获取路由tree',
  '获取运费模板',
  '获取退出未保存的数据',
  '获取退款单详情',
  '获取配送信息表单',
  '获取采集商品数据',
  '获取门店店员列表',
  '获取门店自提开启状态',
  '获取附件分类管理列表',
  '获取附件分类管理表单',
  '获取页面链接',
  '获取页面链接分类',
  '获取风格设置',
  '装修页面',
  '角色状态',
  '角色管理',
  '订单信息',
  '订单列表导出',
  '订单列表获取配送员',
  '订单发货',
  '订单发送货',
  '订单号核销',
  '订单备注',
  '订单导出',
  '订单核销',
  '订单编辑',
  '订单记录',
  '订单详情',
  '设为首页',
  '设置Diy默认数据',
  '设置分组',
  '设置协议内容',
  '设置发票状态',
  '设置和取消用户标签',
  '设置备注',
  '设置对外账号',
  '设置批量商品上架',
  '设置批量商品下架',
  '设置标签',
  '设置消息',
  '设置用户分组',
  '设置用户等级上下架',
  '设置直播间是否显示',
  '设置等级状态',
  '设置账号推送接口',
  '语言列表',
  '语言列表状态',
  '语言国家列表',
  '语言类型列表',
  '语言详情',
  '调试接口',
  '财务操作',
  '财务记录',
  '账单记录',
  '账单详情',
  '账号管理',
  '资金流水',
  '资金流水备注',
  '运费模板',
  '运费模板列表',
  '还原Diy默认数据',
  '进入工作台',
  '退款',
  '选择接口',
  '通过提现申请',
  '配送员列表',
  '配送员状态',
  '配送员管理',
  '金额设置',
  '链接管理',
  '门店上下架',
  '门店位置选择',
  '门店列表',
  '门店删除',
  '门店搜索列表',
  '门店详情',
  '面单默认配置信息',
  '领取记录',
  '首页装修'
);

-- Verify unresolved raw (non-key-like) names
-- SELECT COUNT(*) AS raw_left FROM eb_system_menus WHERE menu_name NOT REGEXP '^[A-Za-z_][A-Za-z0-9_]*$';
-- COMMIT;
-- ROLLBACK;