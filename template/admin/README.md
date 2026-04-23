# CRMEB Admin

## thông số kỹ thuật phát triển

Sử dụng thống nhất cú pháp ES6
Chú thích phương pháp
/*
* th => tiêu đề
* data => dữ liệu
* fileName => tên tập tin
* fileType => Loại tệp
* sheetName => sheetTên trang
  */
  export default function toExcel ({ th, data, fileName, fileType, sheetName })
  Dòng chú thích //

### Đặt tên

Định dạng đặt tên thư mục trang theo danh pháp lạc đà,Ví dụ: danh sách người dùng userList
Ví dụ: mô-đun sản phẩm
sản phẩm sản phẩm
├─ product Quản lý sản phẩm
├─ productList Danh mục quản lý sản phẩm
├─ index.vue  trang đầu
├─ components  Thành lập
├─ tableFrom.vue
├─ tableList.vue
├─ handle Thư mục trang chức năng hoạt động
├─ delete.vue
├─ productCategory Danh mục sản phẩm
├─ index.vue Trang chủ phân loại sản phẩm

Định dạng đặt tên trang, tổ chức, thư mục theo danh pháp chữ lạc đà nhỏ,Ví dụ: danh sách người dùng userList

Việc đặt tên hàm tên lớp được viết theo kiểu lạc đà. Ví dụ: addUser
Đặt tên biến CamelCase Ví dụ: user hoặc userInfo _userinfo user-info
Các hằng số được đặt tên bằng tất cả các dấu gạch dưới, ví dụ: VUE_APP_API_URl

### Thông số quản lý tập tin
Mô-đun trang trang phải được tách thành các thư mục
Giao diện API là một mô-đun và một tệp
Tạo một tệp cho mỗi tệp
plugin plugin, một plugin và một thư mục
Quản lý trạng thái định tuyến Vuex, một mô-đun tạo một thư mục trong các mô-đun
Bộ định tuyến tạo một thư mục cho mỗi mô-đun trong các mô-đun.
Phong cách nên được xây dựng bằng iView càng nhiều càng tốt. Đừng dễ dàng thay đổi phong cách chung của hệ thống common.less.
Tùy chỉnh phong cách phổ quát style.less,Mỗi lần bạn thêm một bình luận, bạn phải thêm một bình luận. Kiểu độc lập của trang được viết trong trang, với định dạng ít hậu tố hơn.
Thêm thư mục soạn thảo vào các kiểu kiểu và tạo một tệp kiểu mới trong thư mục thành phần tương ứng.
Công cụ tùy chỉnh utils js được đặt tên độc lập, nói chung không cần tạo thư mục mới

## Đặt tên mô-đun
~~~
├─ product Quản lý sản phẩm
├─ user Quản lý người dùng
├─ order Quản lý đơn hàng hệ thống
├─ setting Bảo trì cài đặt hệ thống, quản lý quyền hệ thống, quản lý menu hệ thống, quản lý dịch vụ khách hàng
├─ chat Quản lý dịch vụ khách hàng (liệt kê, thêm, xóa, chỉnh sửa）
├─ application Mỗi mô-đun ứng dụng có chức năng quản lý tài khoản công cộng, chương trình nhỏ, Alipay, chương trình nhỏ Baidu và chương trình nhỏ Toutiao.
├─ system Quản lý cơ sở dữ liệu nhật ký cập nhật hệ thống
├─ finance  quản lý tài chính
├─ agent Quản lý phân phối
├─ marketing Phiếu giảm giá, điểm, mua theo nhóm, mặc cả, bán hàng chớp nhoáng
├─ echarts Phân tích thống kê dữ liệu
├─ notification  Quản lý thông báo tin nhắn, tin nhắn mẫu (danh sách, thông báo, thêm, chỉnh sửa), SMS
├─ file Quản lý tập tin đính kèm
├─ freight Công ty Logistics quản lý mẫu hàng hóa
├─ merchant Quản lý thương gia
├─ widget Tiện ích thành phần
└─ cms Quản lý bài viết
~~~
## Cấu trúc thư mục
Cấu trúc và mô tả thư mục chính：
~~~
├── public                      # Tài nguyên tĩnh
│   ├── favicon.ico            # faviconbiểu tượng
│   └── index.html             # html bản mẫu
├── src                         # mã nguồn
│   ├── api                    # Tất cả các yêu cầu
│   │    └──account.js        # Giao diện đăng nhập
│   │    └──agent.js          # Giao diện phân phối
│   │    └──app.js            # Giao diện liên quan đến ứng dụng (chương trình mini, tài khoản chính thức)
│   │    └──cms.js            # Các giao diện liên quan đến nội dung (quản lý bài viết, phân loại)
│   │    └──common.js         # Giao diện xóa bảng và nhận tin nhắn nhắc nhở
│   │    └──finance.js        # Giao diện tài chính
│   │    └──index.js          # Giao diện về trang chủ
│   │    └──marketing.js      # Giao diện tiếp thị
│   │    └──order.js          # Giao diện đặt hàng
│   │    └──product.js        # Giao diện liên quan đến sản phẩm
│   │    └──setting.js        # Giao diện cài đặt
│   │    └──system.js         # Các giao diện liên quan đến bảo trì (cấu hình phát triển, bảo trì bảo mật)
│   │    └──systemAdmin.js    # Giao diện dành cho quản trị viên (Cài đặt--Quyền quản lý--Danh sách quản trị viên)）
│   │    └──systemMenus.js    # Giao diện liên quan đến quy tắc cấp phép (Cài đặt--Quản lý quyền--Quy tắc cấp phép）
│   │    └──uploadPictures.js # Giao diện upload file đính kèm hình ảnh
│   │    └──user.js           # Giao diện thành viên
│   ├── assets                 # Tài nguyên tĩnh như hình ảnh và svg
│   ├── components             # thành phần công cộng
│   │    └──cards             # thống kê
│   │    └──copyright         # Câu lệnh dưới cùng của chân trang
│   │    └──customerInfo      # Chọn người dùng
│   │    └──echarts           # Biểu đồ thống kê
│   │    └──freightTemplate   # Mẫu vận chuyển hàng hóa
│   │    └──from              # Tạo biểu mẫu
│   │    └──goodsList         # Danh sách sản phẩm
│   │    └──iconFrom          # Biểu tượng thêm điều hướng
│   │    └──link              # akết nối
│   │    └──mde               # Nhiều hộp văn bản
│   │    └──modelSure         # Xác nhận hộp phương thức
│   │    └──newsCategory      # Trang quản lý đồ họa và văn bản
│   │    └──publicSearchFrom  # Tìm kiếm theo đầu (không được sử dụng）
│   │    └──quill             # Trình chỉnh sửa (không được sử dụng）
│   │    └──referrerInfo      # Thông tin người giới thiệu
│   │    └──searchFrom        # Tìm kiếm trang đặt hàng
│   │    └──sendCoupons       # Gửi phiếu giảm giá
│   │    └──systemStore       # Thêm điểm đón
│   │    └──uploadPictures    # Tải ảnh lên
│   │    └──uploadVideo       # Tải video lên (được sử dụng trong trình chỉnh sửa sản phẩm）
│   ├── i18n                   # đa ngôn ngữ
│   ├── layouts                # cách trình bày
│   │    └──header-breadcrumb # Kiểu đường dẫn tiêu đề
│   │    └──header-collapse   # Biểu tượng đàn accordion điều khiển đầu
│   │    └──header-fullscreen # Biểu tượng điều khiển bằng đầu cho toàn màn hình
│   │    └──header-i18n       # Điều khiển đầu đa ngôn ngữ
│   │    └──header-log        # Biểu tượng ngoại lệ của nhật ký điều khiển đầu
│   │    └──header-logo       # cái đầulogo
│   │    └──header-notice     # Tin nhắn tiêu đề
│   │    └──header-reload     # Biểu tượng làm mới điều khiển đầu
│   │    └──header-search     # tìm kiếm đầu
│   │    └──header-setting    # Đặt kiểu trang
│   │    └──header-user       # Của tôi (trung tâm cá nhân, đăng xuất）
│   │    └──menu-head         # 
│   │    └──menu-side         # Thanh điều hướng bên
│   │    └──tabs              # Nhãn điều hướng ngang tiêu đề
│   │    └──mixins            # Một để cuộn ngang để lấy tiêu đềjs
│   ├── libs                   # phương pháp công khai
│   ├── menu                   # Cấu hình thực đơn
│   ├── mixins                 # hỗn hợp phổ quát
│   ├── mock                   # Mô phỏng dữ liệu
│   ├── pages                  # Tất cả các trang
│   │    └──account           # Về trang đăng nhập
│   │         └──login        # Đăng nhập
│   │         └──register     # đăng ký
│   │    └──agent             # Phân bổ
│   │         └──agentManage  # Quản lý nhà phân phối
│   │    └──app               # ứng dụng
│   │         └──routine      # Thông báo mẫu chương trình nhỏ
│   │         └──wechat       # Tài khoản chính thức
│   │              └──menus   # Trình đơn WeChat
│   │              └──newsCategory   # Quản lý đồ họa và văn bản
│   │                   └──save      # Thêm đồ họa và văn bản
│   │              └──reply          # trả lời tự động
│   │                   └──follow    # WeChat theo dõi trả lời/trả lời từ khóa không hợp lệ
│   │                   └──keyword   # Trả lời từ khóa
│   │              └──user           # người dùng
│   │                   └──tag       # Thẻ người dùng
│   │                   └──user      # Người dùng WeChat
│   │                   └──message   # Hồ sơ hành vi người dùng
│   │    └──cms                      # nội dung
│   │         └──addArticle          # Thêm bài viết/sửa bài viết
│   │         └──article             # Quản lý bài viết
│   │         └──articleCategory     # Phân loại bài viết
│   │    └──finance                  # tài chính
│   │         └──commission          # hồ sơ ủy ban
│   │         └──financialRecords    # hồ sơ tài chính
│   │              └──bill           # Hồ sơ tài trợ
│   │              └──recharge       # Kỷ lục nạp tiền
│   │         └──userExtract         # Đơn xin rút tiền
│   │    └──index                    # Trang chủ
│   │    └──marketing                # tiếp thị
│   │         └──storeBargain        # mặt hàng giá hời
│   │         └──storeCombination    # Quản lý nhóm
│   │              └──combinaList    # Danh sách nhóm nhóm
│   │              └──create         # Thêm sản phẩm nhóm
│   │              └──index          # Nhóm sản phẩm
│   │         └──storeCoupon         # Sản xuất phiếu giảm giá
│   │         └──storeCouponIssue    # Danh sách phiếu giảm giá
│   │         └──storeCouponUser     # Hồ sơ thu thập thành viên
│   │         └──storeSeckill        # Quản lý bán hàng chớp nhoáng
│   │              └──index          # mặt hàng flash sale
│   │              └──create         # Thêm vật phẩm flash sale
│   │         └──userPoint           # Nhật ký điểm
│   │    └──notify                   # cài đặt tin nhắn SMS
│   │         └──smsConfig           # tài khoản SMS
│   │         └──smsPay              # mua hàng qua tin nhắn SMS
│   │         └──smsTemplateApply    # mẫu tin nhắn
│   │    └──order                    # Quản lý đơn hàng
│   │    └──product                  # hàng hóa
│   │         └──productAdd          # Thêm sản phẩm
│   │         └──productAttr         # Thông số sản phẩm
│   │         └──productClassify     # Phân loại sản phẩm
│   │         └──productList         # Quản lý sản phẩm
│   │         └──productReply        # Quản lý đánh giá sản phẩm
│   │    └──setting                  # cài đặt
│   │         └──cityDada            # dữ liệu thành phố
│   │         └──clerkList           # Quản lý bảo lãnh
│   │         └──freight             # Công ty hậu cần
│   │         └──setSystem           # Cài đặt hệ thống
│   │         └──shippingTemplates   # Mẫu vận chuyển hàng hóa
│   │         └──storeList           # Danh sách điểm đón
│   │         └──storeService        # Quản lý dịch vụ khách hàng
│   │         └──systemAdmin         # Danh sách quản trị viên
│   │         └──systemMenus         # Quy tắc cấp phép
│   │         └──systemRole          # Quản lý danh tính
│   │         └──systemStore         # Cài đặt cửa hàng
│   │         └──user                # Trung tâm cá nhân
│   │         └──verifyOrder         # Viết đơn đặt hàng
│   │    └──system                   # duy trì
│   │         └──auth                # ủy quyền thương mại
│   │         └──clear               # làm mới bộ đệm
│   │         └──configTab           # Cấu hình
│   │              └──index          # Phân loại cấu hình
│   │              └──list           # Danh sách cấu hình
│   │         └──error               # trang lỗi
│   │              └──403            # 403
│   │              └──404            # 404
│   │              └──500            # 500
│   │         └──group               # Dữ liệu kết hợp
│   │         └──maintain              
│   │              └──systemCleardata    # xóa dữ liệu
│   │              └──systemDatabackup   # Sao lưu dữ liệu
│   │              └──systemFile         # Xác minh tập tin
│   │                   └──opendir       # Quản lý tập tin
│   │              └──systemLog          # Nhật ký hệ thống
│   │    └──user                         # thành viên
│   │         └──group                   # Nhóm thành viên
│   │         └──label                   # thẻ thành viên
│   │         └──level                   # Cấp độ thành viên
│   │         └──list                    # Quản lý thành viên
│   ├── plugins                           # trình cắm thêm
│   ├── router                            # Cấu hình định tuyến
│   │    └──modules                      # Mô-đun định tuyến trang
│   │         └──agent.js                     # Về phân phối
│   │         └──app.js                       # Các ứng dụng liên quan (chương trình nhỏ, tài khoản chính thức）
│   │         └──cms.js                       # Nội dung liên quan (quản lý bài viết, phân loại bài viết）
│   │         └──echarts.js                   # Thống kê liên quan
│   │         └──finance.js                   # Về tài chính
│   │         └──index.js                     # Về trang chủ
│   │         └──marketing.js                 # Về tiếp thị
│   │         └──order.js                     # Về đơn hàng
│   │         └──product.js                   # Sản phẩm liên quan
│   │         └──setting.js                   # Giới thiệu về cài đặt
│   │         └──system.js                    # Về bảo trì
│   │         └──user.js                      # Thành viên liên quan
│   │    └──index.js                          # Xử lý xuất tuyến và chặn
│   │    └──routes.js                         # Tổng hợp các tuyến đường
│   ├── store                                  # Vuex Quản lý trạng thái
│   ├── utils                                  # jsdụng cụ
│   │    └──authLapse.js                      # Hộp nhắc ủy quyền
│   │    └──modalForm.js                      # hộp phương thức
│   │    └──videoCloud.js                     # Tải lên video lưu trữ đám mây (Qiniu, Tencent, Alibaba）
│   │    └──validate.js                       # Chuyển đổi dấu thời gian thành thời gian；
│   │    └──public.js                         # Hỏi hộp phương thức；
│   ├── styles            # Quản lý phong cách
│   ├── setting.env.js    # Tệp cấu hình phát triển
│   ├── setting.js        # hồ sơ doanh nghiệp
│   ├── main.js           # Tệp nhập, tải thành phần, khởi tạo, v.v.
│   └── App.vue           # Trang nhập
├── tests                  # quản lý kiểm tra
├── alias.config.js        # Bí danh, chỉ được sử dụng để định cấu hình WebStorm nhằm xác định bí danh, không có tác dụng thực tế
├── babel.config.js        # babel Cấu hình
├── jest.config.js         # jest Cấu hình
├── package.json           # package.json
└── vue.config.js          # Vue CLI 3 Cấu hình
~~~
## Phát triển các dự án trọn gói
~~~
# Nhập thư mục dự án
$ cd admin

# Cài đặt phụ thuộc
$ npm install

# Bắt đầu một dự án(Môi trường phát triển địa phương)
$ npm run dev

# Dự án trọn gói
$ npm run build
~~~

## Cấu hình tên miền yêu cầu


### Cấu hình môi trường phát triển
Địa chỉ file cấu hình:/.env.dev

*Yêu cầu cấu hình tên miền*

`$ VUE_APP_API_URL='http://tên miền riêng/adminapi'`

### môi trường sản xuất

*Địa chỉ yêu cầu giao diện (http)hoặc (https)://www.crmeb.com(Thay thế nó bằng tên miền của bạn)/adminapi Theo mặc định, triển khai không độc lập trống*

`$ VUE_APP_API_URL=''`


