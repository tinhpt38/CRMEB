CRMEB-KY v6.0.0 Thư mục chương trình phụ trợ
===============

## dockerMột cú nhấp chuột để chạy

### Bắt đầu nhanh

```bash
# Kéo hình ảnh
docker pull ccr.ccs.tencentyun.com/crmebky_php/crmebky:latest

# Chạy vùng chứa
docker run -d --name crmeb \
  -p 8080:80 \
  -p 3306:3306 \
  -p 6379:6379 \
  ccr.ccs.tencentyun.com/crmebky_php/crmebky:latest
```

### truy cập dịch vụ
- **Trang web**: http://localhost:8080 
- **Hậu trường**: http://localhost:8080/admin （tài khoản: admin，mật khẩu: crmeb.com）
- **MySQL**: localhost:3306（tài khoản: root，mật khẩu: 123456）
- **Redis**: localhost:6379
> Hướng dẫn chi tiết vui lòng truy cập [Tài liệu trợ giúp](https://gitee.com/ZhongBangKeJi/CRMEB/blob/master/help/docker/README.md) Kiểm tra。


> Môi trường hoạt động yêu cầu PHP7.1-7.4. 

## Cài đặt

## Cài đặt bằng một cú nhấp chuột
Tải mã của bạn lên và đặt thư mục truy cập trang web thành /public
Nhập tên miền hoặc IP của bạn vào trình duyệt của bạn (ví dụ:：www.yourdomain.com）,
Trình cài đặt sẽ tự động thực hiện cài đặt. Trong khoảng thời gian này hệ thống sẽ nhắc bạn nhập thông tin cơ sở dữ liệu để hoàn tất quá trình cài đặt. Sau khi cài đặt hoàn tất, nên xóa thư mục cài đặt.

Địa chỉ truy cập phụ trợ:
1.Tên miền/quản trị viên
Tài khoản chính thức và địa chỉ truy cập trang chủ H5:
1.Tên miền/

Hãy nhớ tài khoản và mật khẩu của bạn trong quá trình cài đặt!

## Cài đặt lại
1. Xóa cơ sở dữ liệu
2. Xóa tệp /public/install.lock

## Cài đặt thủ công
1. Tạo cơ sở dữ liệu và nhập tệp cơ sở dữ liệu
Thư mục tệp cơ sở dữ liệu/public/install/crmeb.sql
2. Sửa đổi file kết nối cơ sở dữ liệu
Đường dẫn tập tin cấu hình/.env
~~~
APP_DEBUG = true

[APP]
DEFAULT_TIMEZONE = Asia/Shanghai

[DATABASE]
TYPE = mysql
HOSTNAME = 127.0.0.1 #Địa chỉ kết nối cơ sở dữ liệu
HOSTPORT = 3306 #Cổng cơ sở dữ liệu
DATABASE = test #Tên cơ sở dữ liệu
USERNAME = username #Tài khoản đăng nhập cơ sở dữ liệu
PASSWORD = password #Mật khẩu đăng nhập cơ sở dữ liệu
PREFIX = eb_
CHARSET = utf8mb4
DEBUG = true

[LANG]
default_lang = zh-cn

[CACHE]
DRIVER = file #loại bộ đệm，redis/file
CACHE_PREFIX = cache_xxxx: #tiền tố bộ đệm
CACHE_TAG_PREFIX = cache_tag_xxxx: #Tiền tố loại bộ đệm

[REDIS]
REDIS_HOSTNAME = 127.0.0.1 #redisĐịa chỉ liên kết
PORT = 6379 #số cổng
REDIS_PASSWORD = 123456 #mật khẩu
SELECT = 0 #cơ sở dữ liệu

[QUEUE]
QUEUE_NAME = xxxx #tiền tố hàng đợi
~~~
3.Sửa đổi quyền thư mục (hệ thống linux) 777
/crmeb
/mẫu

4. Đăng nhập phụ trợ：
http://Tên miền/quản trị viên
Tài khoản mặc định: quản trị viên Mật khẩu: crmeb.com


## Nhiệm vụ theo lịch trình
Trong nhận tự động,Cảnh báo hàng tồn kho và các chức năng khác được sử dụng
```sh
php think timer [ status ] [ --d ]
```
tham số
- status: tình trạng
  - start: khởi động
  - stop: đóng cửa
  - restart: Khởi động lại
- --d : thực thi nền

##Dịch vụ kết nối dài
Trò chuyện trong h5,Thông báo tin nhắn của quản trị viên phụ trợ và các chức năng khác được sử dụng
```sh
php think workerman [ status ]  [ --d ]
```
windowsMôi trường cần được thực hiện theo ba bước
```sh
# Dịch vụ truyền thông nội bộ
php think workerman start --d
```
tham số
- status: tình trạng
  - start: khởi động
  - stop: đóng cửa
  - restart: Khởi động lại
- --d : thực thi nền

## Thông số kỹ thuật phát triển
#### Quy ước đặt tên
ThinkPHP6.0 tuân theo thông số kỹ thuật đặt tên PSR-2 và thông số tải tự động PSR-4, đồng thời chú ý đến các thông số kỹ thuật sau:

1. Thư mục và tập tin
2. Sử dụng chữ thường + gạch chân cho các thư mục;
3. Các thư viện lớp và tệp hàm đều phải có hậu tố .php;
4. Tên tệp của các lớp được xác định trong không gian tên và đường dẫn của không gian tên nhất quán với đường dẫn của tệp thư viện lớp;
5. Các tệp lớp (bao gồm giao diện và đặc điểm) được đặt tên bằng chữ hoa lạc đà (chữ cái đầu tiên được viết hoa) và các tệp khác được đặt tên bằng chữ thường + gạch chân;
6. Tên lớp (bao gồm giao diện và đặc điểm) và tên tệp phải nhất quán và sử dụng thống nhất cách đặt tên kiểu lạc đà (chữ cái đầu tiên được viết hoa);

#### Đặt tên hàm, lớp, thuộc tính

1. Cách đặt tên lớp sử dụng kiểu chữ lạc đà (chữ cái đầu tiên viết hoa), chẳng hạn như User, UserType;
2. Sử dụng chữ cái viết thường và dấu gạch dưới (bắt đầu bằng chữ cái viết thường) để đặt tên cho các hàm phổ biến, chẳng hạn như get_client_ip;
3. Các phương thức trong bộ điều khiển sử dụng chữ cái viết thường và dấu gạch dưới (bắt đầu bằng chữ cái viết thường), chẳng hạn như get_client_ip
4. Các phương thức được đặt tên bằng cách viết hoa lạc đà (chữ cái đầu tiên là chữ thường), chẳng hạn như getUserName;
5. Sử dụng kiểu dáng lạc đà để đặt tên cho các thuộc tính (chữ cái đầu tiên là chữ thường), chẳng hạn như tableName, instance;
6. Các trường hợp đặc biệt: các hàm hoặc phương thức bắt đầu bằng dấu gạch dưới kép __ được sử dụng làm phương thức kỳ diệu, chẳng hạn như __call và __autoload;

#### Hằng số và cấu hình
1. Các hằng số được đặt tên bằng chữ in hoa và dấu gạch dưới, chẳng hạn như APP_PATH;
2. Các tham số cấu hình được đặt tên bằng chữ cái viết thường và dấu gạch dưới, chẳng hạn như url_route_on và url_convert;
3. Các định nghĩa biến môi trường được đặt tên bằng chữ in hoa và dấu gạch dưới, chẳng hạn như APP_DEBUG;

#### Bảng và trường dữ liệu
1. Các bảng và trường dữ liệu được đặt tên bằng chữ thường và gạch chân, đồng thời tên trường không được bắt đầu bằng dấu gạch dưới, chẳng hạn như bảng think_user và trường user_name. Không nên sử dụng kiểu chữ lạc đà và tiếng Trung làm tên trường và bảng dữ liệu.

Lưu ý: Hãy hiểu và cố gắng tuân theo các quy ước đặt tên trên để giảm bớt những sai sót không đáng có trong quá trình phát triển.

#### Đặc điểm ngữ pháp
1. Thử sử dụng cú pháp mới của php7
2. Một dòng trống phải được chèn sau mỗi câu lệnh khai báo vùng tên và khối khai báo use.
3. Niềng răng mở lớp（{） Phải được viết trên dòng riêng sau khi khai báo lớp, kết thúc bằng dấu ngoặc nhọn（}）Nó cũng phải được viết trên dòng riêng sau nội dung lớp.
4. Dấu ngoặc mở của phương thức（{） Phải được viết trên dòng riêng sau khi khai báo hàm, kết thúc bằng dấu ngoặc nhọn（}）Nó cũng phải được viết trên dòng riêng sau phần thân hàm.
5. Các thuộc tính và phương thức của lớp phải thêm các công cụ sửa đổi truy cập (riêng tư, được bảo vệ và công khai), trừu tượng và cuối cùng phải được khai báo trước công cụ sửa đổi truy cập và tĩnh phải được khai báo sau công cụ sửa đổi truy cập.
6. Sau từ khóa của cấu trúc điều khiển phải có khoảng trắng, khi gọi phương thức, hàm không được có.
7. Nẹp mở cơ cấu điều khiển（{） phải được viết trên cùng dòng với phần khai báo và dấu ngoặc nhọn đóng（}） Phải viết trên dòng riêng sau phần nội dung chính
8. Các tệp mã PHP thuần túy phải bỏ qua phần cuối cùng ?> thẻ kết thúc
9. Tất cả các phương thức, lớp và lớp trình điều khiển phải thêm công cụ sửa đổi truy cập
    ~~~

    /**
     * chú thích tiếng Trung
     * @param string $str Loại khai báo
     * @param array $arr
     * @return bool
     */
    public function action(string $str, array $arr)
    {
         return true;
    }
    ~~~
10. Trong danh sách tham số phải có dấu cách sau mỗi dấu phẩy và không được có dấu cách trước dấu phẩy.
    ~~~
     function foo($arg1, &$arg2, $arg3 = [])
     {
            // method body
     }
    ~~~
11. Các thông số có thể được chia thành nhiều dòng. Trong trường hợp này, mỗi tham số, kể cả tham số đầu tiên, phải ở trên một dòng riêng biệt càng nhiều càng tốt.。
    ~~~
    <?php
    $foo->bar(
        $longArgument,
        $longerArgument,
        $muchLongerArgument
    );
    ~~~
12. Cấu trúc if tiêu chuẩn như trong đoạn mã sau, vui lòng chú ý đến「dấu ngoặc đơn」、「không gian」cũng như「niềng răng xoăn」vị trí,
    Lưu ý rằng cả else và elseif đều nằm trên cùng một dòng với dấu ngoặc nhọn đóng trước đó
    ~~~
    <?php
    if ($expr1) {
        // if body
    } elseif ($expr2) {
        // elseif body
    } else {
        // else body;
    }
    ~~~
13. Phải thêm dấu cách trước và sau phép gán bằng dấu bằng
    ~~~
    <?php
    $arr = [];
    ~~~


#### PHP 7.1+ Cú pháp mới thường dùng

1. Toán tử bậc ba
   ~~~
   <?php

   $arr = ['crmeb'=>true];
   Trước
   echo isset($arr['crmeb']) ? $arr['crmeb'] : '';
   sau đó
   echo $arr['crmeb'] ?? '';
   ~~~
2.  define() Xác định mảng không đổi
   ~~~
   <?php
    define('ARR',['a','b']);
   ~~~
3.  Tối ưu hóa không gian tên
   ~~~
    <?php
    //PHP7Ngữ pháp trước
    use FooLibrary\Bar\Baz\ClassA;
    use FooLibrary\Bar\Baz\ClassB;
    // PHP7viết ngữ pháp mới
    use FooLibrary\Bar\Baz\{ ClassA, ClassB};

   ~~~
#### CRMEB PROquy phạm
 1. Tất cả xác thực dữ liệu được đặt trong thư mục xác thực trong mô-đun
 2. JSON trả về khi thành công và thất bại trong lớp AuthController gốc
 3. Phán đoán lỗi sẽ đưa ra một ngoại lệ và lớp lỗi sẽ kiểm soát đầu ra.
    ~~~
    <?php

        throw new AuthException('thông báo lỗi',400);
    ~~~
 4. Mã lỗi và lời nhắc lỗi phải được quản lý thống nhất để tạo điều kiện chuyển đổi giữa nhiều ngôn ngữ.
 5. Thao tác với cơ sở dữ liệu sử dụng các lớp mô hình và không thể sử dụngDb::table()
 6. Nhận dữ liệu biểu mẫu bằng cách sử dụng app\Request
    ~~~
    <?php
    use app\Request;


    public function index(Request $request) {

        //Nhận dữ liệu đã gửi và trả về dưới dạng mảng hai chiều
        $arr = $request->getMore([
            'name',
            'nickname'
        ]);
        //Nhận dữ liệu đã gửi và trả về dưới dạng mảng hai chiều với các giá trị mặc định được thêm vào
        $arr = $request->getMore([
           ['name','123'],
           ['nickname','0']
        ]);
        //Nhận dữ liệu đã gửi,và trả về dưới dạng mảng một chiều với giá trị mặc định được thêm vào
        [$name, $nickname] = $request->getMore([
           ['name','123'],
           ['nickname','0']
        ],true);

    }
    ~~~
 7. Tất cả các lệnh của lớp trình điều khiển đều tương ứng với tên bảng và tuân theo quy ước đặt tên kiểu chữ lạc đà.
 8. Tất cả tên thư mục được xác định bằng chữ thường và gạch chân.
 9. Tất cả tên thuộc tính và tên biến phải tuân theo quy ước đặt tên kiểu chữ lạc đà.
 10. Đối với logic phức tạp và nhiều trạng thái, cần thêm các nhận xét nội tuyến một cách thích hợp
 11. Chỉ có thể viết các câu lệnh điều kiện tìm kiếm trong mô hình,Sự kết hợp của dữ liệu được phát hiện được ghi vào lớp dịch vụ để xử lý.,servicesTạo lệnh:php make:services api@user/User


## tài liệu

[Hướng dẫn sử dụng](https://doc.crmeb.com)
[TP6Hướng dẫn phát triển](https://www.kancloud.cn/manual/thinkphp6_0/content)


## Tham gia phát triển

Xem [CRMEB](https://github.com/crmeb/CRMEB)。

## Thông tin bản quyền


Thông tin bản quyền của mã nguồn và tệp nhị phân của bên thứ ba có trong dự án này được đánh dấu riêng.

mọi quyền được bảo lưuCopyright © 2017-2026 by CRMEB (http://www.crmeb.com)

All rights reserved。

CRMEB® Chủ sở hữu nhãn hiệu và bản quyền là Xi'an Zhongbang Network Technology Co., Ltd.。
