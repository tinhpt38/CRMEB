<?php


// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2026 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
namespace app\services\diy;

use app\dao\diy\ThemeDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\ApiException;

/**
 * Lớp dịch vụ chủ đề
 *
 * Tổng quan về chức năng:
 * Chịu trách nhiệm quản lý các chủ đề hệ thống, bao gồm thêm, xóa, sửa đổi và kiểm tra các chủ đề, nhập và xuất, chuyển đổi ứng dụng và các chức năng khác.
 * Cung cấp khả năng quản lý và kết hợp dữ liệu độc lập trên trang chủ, trang danh mục, trang chi tiết, trung tâm cá nhân và các trang khác.
 *
 * Chức năng chính:
 * 1. Quản lý chủ đề - truy vấn danh sách chủ đề, thu thập chi tiết, tạo và chỉnh sửa
 * 2. Ứng dụng chủ đề - chuyển đổi chủ đề hiện đang được sử dụng hoặc áp dụng riêng dữ liệu trang cụ thể của một chủ đề
 * 3. Nhập dữ liệu - hỗ trợ nhập dữ liệu cấu hình chủ đề bên ngoài
 * 4. Quản lý tài nguyên - quản lý hình ảnh, tiêu đề và các tài nguyên khác liên quan đến chủ đề
 * 5. Kiểm soát phiên bản - ghi lại thời gian cập nhật và thông tin phiên bản của dữ liệu chủ đề
 *
 * @package app\services\diy
 * @author wuhaotian
 * @email 442384644@qq.com
 * @date 2025/12/18
 */
class ThemeServices extends BaseServices
{
    /**
     * Trình xây dựng - khởi tạo các phụ thuộc
     *
     * Đưa phần phụ thuộc ThemeDao vào hoạt động của cơ sở dữ liệu。
     *
     * @param ThemeDao $dao Đối tượng truy cập dữ liệu chủ đề
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/02/03
     */
    public function __construct(ThemeDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Lấy danh sách chủ đề
     *
     * Tổng quan về chức năng:
     * Theo các điều kiện truy vấn đến, dữ liệu danh sách chủ đề được lấy trong các trang và dữ liệu trả về được định dạng.
     * Xử lý nội dung bao gồm: chuyển đổi dấu thời gian thành chuỗi ngày, chuyển đổi đường dẫn hình ảnh thành URL hoàn chỉnh, phân tích dữ liệu JSON, v.v.。
     *
     * @param array $where Mảng điều kiện truy vấn
     * @return array Một mảng chứa danh sách dữ liệu danh sách và tổng số
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/02/03
     */
    public function getThemeList($where)
    {
        [$page, $limit] = $this->getPageValue();
        $field = 'id,title,info,type,home_image,category_image,detail_image,user_image,theme_data,add_time,up_time,is_use,page_type';
        $order = 'id desc';
        if (($where['page_type'] ?? '') === 'all') {
            unset($where['page_type']);
        } else {
            $where['page_type'] = 'theme';
        }
        $list = $this->dao->themeList($where, $field, $page, $limit, $order);
        foreach ($list as &$item) {
            if (isset($item['add_time'])) $item['add_time'] = date('Y-m-d H:i', $item['add_time']);
            if (isset($item['up_time'])) $item['up_time'] = date('Y-m-d H:i', $item['up_time']);
            if (isset($item['home_data_update_time'])) $item['home_data_update_time'] = date('Y-m-d H:i', $item['home_data_update_time']);
            if (isset($item['category_data_update_time'])) $item['category_data_update_time'] = date('Y-m-d H:i', $item['category_data_update_time']);
            if (isset($item['detail_data_update_time'])) $item['detail_data_update_time'] = date('Y-m-d H:i', $item['detail_data_update_time']);
            if (isset($item['user_data_update_time'])) $item['user_data_update_time'] = date('Y-m-d H:i', $item['user_data_update_time']);
            if (isset($item['theme_data_update_time'])) $item['theme_data_update_time'] = date('Y-m-d H:i', $item['theme_data_update_time']);
            if (isset($item['type'])) $item['type'] = $item['type'] == 0 ? 'Tạo chủ đề của riêng bạn' : 'Chủ đề hình vuông';
            if (isset($item['theme_data'])) $item['theme_data'] = json_decode($item['theme_data'], true) ?? [];
            $item['home_image'] = set_file_url($item['home_image']);
            $item['category_image'] = set_file_url($item['category_image']);
            $item['detail_image'] = set_file_url($item['detail_image']);
            $item['user_image'] = set_file_url($item['user_image']);
        }
        $count = $this->dao->themeCount($where);
        return compact('list', 'count');
    }

    /**
     * Nhận số phiên bản chủ đề
     *
     * Tổng quan về chức năng:
     * Lấy số phiên bản hiện tại của chủ đề dựa trên ID chủ đề.
     * Nếu ID là 0, hãy lấy số phiên bản của chủ đề hiện đang sử dụng。
     *
     * @param int $id ID chủ đề, 0 cho biết chủ đề hiện đang được sử dụng
     * @return chuỗi số phiên bản hỗn hợp
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/02/03
     */
    public function getThemeVersion($id)
    {
        $where = $id == 0 ? ['is_use' => 1] : ['id' => $id];
        return $this->dao->value($where, 'version');
    }

    /**
     * Nhận thông tin chủ đề
     *
     * Tổng quan về chức năng:
     * Nhận chi tiết chủ đề dựa trên ID chủ đề và loại.
     * Hỗ trợ lấy tất cả thông tin hoặc dữ liệu thuộc các loại được chỉ định (chẳng hạn như trang chủ, trang danh mục, trang chi tiết, v.v.).
     * Thực hiện định dạng cần thiết và điền giá trị mặc định trên dữ liệu trả về.
     *
     * Trả về cấu trúc dữ liệu:
     * theo $type Lợi nhuận khác nhau có cấu trúc khác nhau：
     * - 'all'/'base': Trả về mảng bản ghi đầy đủ của chủ đề
     * - 'home'/'detail'/'user'/'theme': Trả về mảng cấu hình được phân tích cú pháp
     * - 'category': Trả về một mảng chứa trạng thái
     *
     * @param int $id ID chủ đề, 0 cho biết chủ đề hiện đang được sử dụng
     * @param string $type kiểu dữ liệu：all, home, category, detail, user, theme, base
     * @return array|int[]|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws AdminException Ném khi dữ liệu không tồn tại
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/02/03
     */
    public function getThemeInfo($id, $type = 'all')
    {
        $where = $id == 0 ? ['is_use' => 1] : ['id' => $id];
        $info = $this->dao->get($where);
        if (!$info) throw new AdminException('Dữ liệu không tồn tại');
        $info = $info->toArray();
        if ($type == 'home') {
            return json_decode($info['home_data'], true) ?? [];
        } elseif ($type == 'category') {
            return ['status' => $info['category_data'] ?? 1];
        } elseif ($type == 'detail') {
            return json_decode($info['detail_data'], true) ?? [];
        } elseif ($type == 'user') {
            return json_decode($info['user_data'], true) ?? [];
        } elseif ($type == 'theme') {
            if ($info['theme_data'] == '' || $info['theme_data'] == null || $info['theme_data'] == 'null') {
                $info['theme_data'] = '{"theme_color":"#E93323","gradient_color":"#FF7931","sub_color":"#FE960F","light_color":"rgba(233, 51, 35, 0.1)"}';
            }
            return json_decode($info['theme_data'], true) ?? [];
        } elseif ($type == 'base') {
            return ['id' => $info['id'], 'type' => $info['type'], 'title' => $info['title'], 'info' => $info['info']];
        } else {
            $info['home_data_update_time'] = date('Y-m-d H:i:s', $info['home_data_update_time']);
            $info['category_data_update_time'] = date('Y-m-d H:i:s', $info['category_data_update_time']);
            $info['detail_data_update_time'] = date('Y-m-d H:i:s', $info['detail_data_update_time']);
            $info['user_data_update_time'] = date('Y-m-d H:i:s', $info['user_data_update_time']);
            $info['theme_data_update_time'] = date('Y-m-d H:i:s', $info['theme_data_update_time']);
            $info['add_time'] = date('Y-m-d H:i:s', $info['add_time']);
            $info['up_time'] = date('Y-m-d H:i:s', $info['up_time']);
            $ids = [$info['home_data_id'], $info['category_data_id'], $info['detail_data_id'], $info['user_data_id'], $info['theme_data_id']];
            $titles = $this->dao->getColumn([['id', 'in', $ids]], 'title', 'id');
            $info['home_data_id_title'] = $titles[$info['home_data_id']] ?? '';
            $info['category_data_id_title'] = $titles[$info['category_data_id']] ?? '';
            $info['detail_data_id_title'] = $titles[$info['detail_data_id']] ?? '';
            $info['user_data_id_title'] = $titles[$info['user_data_id']] ?? '';
            $info['theme_data_id_title'] = $titles[$info['theme_data_id']] ?? '';
            return $info;
        }
    }

    /**
     * Lưu dữ liệu chủ đề
     *
     * Tổng quan về chức năng:
     * Tạo chủ đề mới hoặc cập nhật dữ liệu cho chủ đề hiện có.
     * Hỗ trợ sao chép dữ liệu từ các chủ đề mẫu để tạo chủ đề mới.
     * Theo các loại trang khác nhau（home, category, detail, user, theme）Xử lý logic lưu dữ liệu tương ứng.
     * Tự động cập nhật số phiên bản và thời gian sửa đổi lần cuối。
     *
     * @param int $id Khóa chính của chủ đề, 0 có nghĩa là mới
     * @param array $data Dữ liệu được lưu phải chứa loại, giá trị, tid tùy chọn, tiêu đề
     * @return int ID chủ đề mới hoặc cập nhật
     * @throws AdminException được ném ra khi tid được chỉ định nhưng chủ đề không tồn tại
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/02/03
     */
    public function saveTheme($id, $data)
    {
        // Khởi tạo mảng cần ghi
        $saveData = [];

        // Nếu ID chủ đề mẫu (tid) được chỉ định, dữ liệu của nó sẽ được sao chép trước tiên làm cơ sở
        if ($data['tid'] !== 0) {
            // Chủ đề mẫu truy vấn
            $tInfo = $this->dao->get($data['tid']);
            if (!$tInfo) {
                throw new AdminException('Chủ đề không tồn tại');
            }
            // Chuyển đổi dữ liệu chủ đề mẫu thành một mảng và xóa id khóa chính để tránh xung đột
            $saveData = $tInfo->toArray();
            // Chủ đề mới không được bật theo mặc định
            $saveData['is_use'] = 0;
            unset($saveData['id']);
        }

        // Ghi đè nếu tiêu đề được chuyển vào
        if ($data['title'] != '') {
            $saveData['title'] = $data['title'];
        }

        if ($id == 0) {
            $type = 0;
            $saveData['category_data'] = 1;
            $saveData['category_data_update_time'] = time();
            $saveData['category_image'] = '/statics/images/cate1.png';
        } else {
            $type = $this->dao->value(['id' => $id], 'type');
        }

        // Chuyển đổi giá trị đến thành chuỗi JSON
        $value = json_encode($data['value']);

        // Xử lý dữ liệu, xem trước hình ảnh và thời gian cập nhật riêng biệt theo loại module
        switch ($data['type']) {
            case 'home':
                // trang đầu
                $saveData['home_data'] = $value;
                $saveData['home_data_update_time'] = time();
                // Theme tự build cần ghi dữ liệu mặc định đồng bộ
                if ($type == 0) {
                    $saveData['home_default_data'] = $value;
                }
                break;

            case 'category':
                // Trang danh mục
                $saveData['category_data'] = $value;
                $saveData['category_data_update_time'] = time();
                // Tạo đường dẫn hình ảnh xem trước tương ứng dựa trên giá trị
                $saveData['category_image'] = '/statics/images/cate' . $value . '.png';
                if ($type == 0) {
                    $saveData['category_default_data'] = $value;
                    $saveData['category_default_image'] = '/statics/images/cate' . $value . '.png';
                }
                break;

            case 'detail':
                // Trang chi tiết
                $saveData['detail_data'] = $value;
                $saveData['detail_data_update_time'] = time();
                if ($type == 0) {
                    $saveData['detail_default_data'] = $value;
                }
                break;

            case 'user':
                // Trung tâm người dùng
                $saveData['user_data'] = $value;
                $saveData['user_data_update_time'] = time();
                if ($type == 0) {
                    $saveData['user_default_data'] = $value;
                }
                break;

            case 'theme':
                // Dữ liệu riêng của chủ đề
                $saveData['theme_data'] = $value;
                $saveData['theme_data_update_time'] = time();
                if ($type == 0) {
                    $saveData['theme_default_data'] = $value;
                }
                break;
        }

        // Số phiên bản mới được tạo mỗi khi bạn lưu
        $saveData['version'] = uniqid();

        // Thêm hoặc cập nhật
        if ($id) {
            // gia hạn
            $saveData['up_time'] = time();
            $this->dao->update($id, $saveData);
        } else {
            // Mới
            $saveData['page_type'] = $data['page_type'];
            $saveData['add_time'] = time();
            $saveData['up_time'] = time();
            $id = $this->dao->insertGetId($saveData);
        }

        // Quay lại chủ đề cuối cùng ID
        return $id;
    }

    /**
     * Lưu thông tin tiêu đề chủ đề
     *
     * Tổng quan về chức năng:
     * Cập nhật tiêu đề và thông tin giới thiệu của chủ đề hoặc tạo bản ghi chủ đề mới (chỉ chứa thông tin tiêu đề).
     * Thao tác cập nhật sẽ đồng bộ số phiên bản và thời gian sửa đổi lần cuối.。
     *
     * @param int $id ID chủ đề, 0 có nghĩa là mới
     * @param array $data Mảng dữ liệu chứa tiêu đề và thông tin
     * @return int|mixed|string chủ đềID
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/02/03
     */
    public function saveThemeTitle($id, $data)
    {
        // Nếu ID chủ đề mẫu (tid) được chỉ định, dữ liệu của nó sẽ được sao chép trước tiên làm cơ sở
        if ($data['tid'] !== 0) {
            // Chủ đề mẫu truy vấn
            $tInfo = $this->dao->get($data['tid']);
            if (!$tInfo) {
                throw new AdminException('Chủ đề không tồn tại');
            }
            // Chuyển đổi dữ liệu chủ đề mẫu thành một mảng và xóa id khóa chính để tránh xung đột
            $saveData = $tInfo->toArray();
            // Chủ đề mới không được bật theo mặc định
            $saveData['is_use'] = 0;
            unset($saveData['id']);
        }
        $saveData['title'] = $data['title'];
        $saveData['info'] = $data['info'];
        $saveData['version'] = uniqid();
        $saveData['page_type'] = $data['page_type'];
        if ($id) {
            $saveData['up_time'] = time();
            $this->dao->update($id, $saveData);
        } else {
            $saveData['add_time'] = time();
            $saveData['up_time'] = time();
            $id = $this->dao->insertGetId($saveData);
        }
        return $id;
    }

    /**
     * Lưu thông tin hình ảnh chủ đề
     *
     * Tổng quan về chức năng:
     * Cập nhật hình ảnh xem trước của từng module của chủ đề (trang chủ, trang chi tiết, trung tâm người dùng).
     * Nếu đó là chủ đề mặc định（type=0），Cấu hình hình ảnh mặc định sẽ được cập nhật đồng bộ.
     * Tự động cập nhật số phiên bản và thời gian sửa đổi lần cuối。
     *
     * @param int $id chủ đềID
     * @param array $data Bao gồm type (home/detail/user) và mảng dữ liệu hình ảnh
     * @return int|mixed|string chủ đềID
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/12/18
     */
    public function saveThemeImage($id, $data)
    {
        $type = $id ? $this->dao->value(['id' => $id], 'type') : 0;
        switch ($data['type']) {
            case 'home':
                $saveData['home_image'] = $data['image'];
                if ($type == 0) $saveData['home_default_image'] = $data['image'];
                break;
            case 'detail':
                $saveData['detail_image'] = $data['image'];
                if ($type == 0) $saveData['detail_default_image'] = $data['image'];
                break;
            case 'user':
                $saveData['user_image'] = $data['image'];
                if ($type == 0) $saveData['user_default_image'] = $data['image'];
                break;
        }
        $saveData['version'] = uniqid();
        if ($id) {
            $saveData['up_time'] = time();
            $this->dao->update($id, $saveData);
        } else {
            $saveData['page_type'] = 'theme';
            $saveData['add_time'] = time();
            $saveData['up_time'] = time();
            $id = $this->dao->insertGetId($saveData);
        }
        return $id;
    }

    /**
     * Nhập dữ liệu chủ đề
     *
     * Tổng quan về chức năng:
     * Lưu dữ liệu cấu hình chủ đề được nhập từ bên ngoài vào cơ sở dữ liệu.
     * Chứa tất cả các cấu hình trang của chủ đề (trang chủ, danh mục, chi tiết, trung tâm cá nhân) và cấu hình mặc định tương ứng của chúng。
     *
     * @param array $config Mảng dữ liệu cấu hình chủ đề
     * @return hỗn hợp chủ đề mớiID
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/02/03
     */
    public function importThemeData($config)
    {
        $data = [];
        $data['version'] = uniqid(); // số phiên bản
        $data['title'] = $config['title']; // tiêu đề
        $data['info'] = $config['info']; // Giới thiệu
        $data['type'] = 1; // kiểu
        $data['home_data'] = $data['home_default_data'] = $config['home_data']; // Dữ liệu trang chủ
        $data['home_image'] = $data['home_default_image'] = $config['home_image']; // Trang chủ
        $data['home_data_id'] = $config['home_data_id']; // Dữ liệu trang chủID
        $data['home_data_update_time'] = time(); // Thời gian cập nhật dữ liệu trang chủ
        $data['category_data'] = $data['category_default_data'] = $config['category_data']; // Dữ liệu trang danh mục
        $data['category_image'] = $data['category_default_image'] = $config['category_image']; // Bìa trang chuyên mục
        $data['category_data_id'] = $config['category_data_id']; // Dữ liệu trang danh mụcID
        $data['category_data_update_time'] = time(); // Thời gian cập nhật dữ liệu trang chuyên mục
        $data['detail_data'] = $data['detail_default_data'] = $config['detail_data']; // Dữ liệu trang chi tiết
        $data['detail_image'] = $data['detail_default_image'] = $config['detail_image']; // Bìa trang chi tiết
        $data['detail_data_id'] = $config['detail_data_id']; // Dữ liệu trang chi tiếtID
        $data['detail_data_update_time'] = time(); // Thời gian cập nhật dữ liệu trang chi tiết
        $data['user_data'] = $data['user_default_data'] = $config['user_data']; // Dữ liệu trung tâm cá nhân
        $data['user_image'] = $data['user_default_image'] = $config['user_image']; // Bìa trung tâm cá nhân
        $data['user_data_id'] = $config['user_data_id']; // Dữ liệu trung tâm cá nhânID
        $data['user_data_update_time'] = time(); // Thời gian cập nhật dữ liệu trung tâm cá nhân
        $data['theme_data'] = $data['theme_default_data'] = json_encode($config['theme_data'], JSON_UNESCAPED_UNICODE); // Dữ liệu chủ đề
        $data['theme_data_id'] = $config['theme_data_id']; // Dữ liệu chủ đềID
        $data['theme_data_update_time'] = time(); // Thời gian cập nhật dữ liệu chủ đề
        $data['page_type'] = 'theme'; // Loại trang
        $data['is_use'] = 0; // Có nên sử dụng không
        $data['is_del'] = 0; // Có nên xóa không
        $data['add_time'] = time(); // Thêm thời gian
        $data['up_time'] = time(); // Thời gian cập nhật
        $id = $this->dao->insertGetId($data);
        return $id;
    }

    /**
     * Sử dụng chủ đề
     *
     * Tổng quan về chức năng:
     * Đặt chủ đề đã chỉ định về trạng thái hiện được bật.
     * Thao tác này trước tiên sẽ tắt tất cả các chủ đề, sau đó kích hoạt chủ đề với ID được chỉ định。
     *
     * @param int $id ID chủ đề để kích hoạt
     * @return bool Thao tác trả về thành công true
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/02/03
     */
    public function useTheme(int $id)
    {
        $this->dao->update(['is_use' => 1], ['is_use' => 0]);
        $this->dao->update($id, ['is_use' => 1]);
        return true;
    }

    /**
     * Sử dụng dữ liệu chủ đề
     *
     * Tổng quan về chức năng:
     * Áp dụng dữ liệu mô-đun cụ thể của một chủ đề nhất định (chẳng hạn như trang chủ, trang chi tiết, v.v.) vào bản ghi dữ liệu chủ đề mục tiêu.
     * Thực hiện tái sử dụng một phần hoặc kết hợp dữ liệu chủ đề。
     *
     * @param int $id dữ liệu chủ đề mục tiêuID
     * @param int $theme_id chủ đề nguồnID
     * @param string $type Kiểu dữ liệu (trang chủ/danh mục/chi tiết/người dùng/chủ đề)
     * @return bool Trả về true nếu thao tác thành công
     * @throws AdminException được ném ra khi dữ liệu chủ đề nguồn không tồn tại
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/02/03
     */
    public function useThemeData(int $id, int $theme_id, string $type)
    {
        $data = $this->dao->get(['id' => $theme_id]);
        if (!$data) throw new AdminException('Dữ liệu chủ đề không tồn tại');
        $this->dao->update(['id' => $id], [$type . '_data_update_time' => time(), $type . '_image' => $data[$type . '_image'], $type . '_data' => $data[$type . '_data'], $type . '_data_id' => $theme_id]);
        return true;
    }


    /**
     * Nhận thông tin về chủ đề hiện đang được sử dụng
     *
     * Tổng quan về chức năng:
     * Truy vấn các chủ đề hiện được bật（is_use=1），Và tổng hợp dữ liệu từ các mô-đun liên quan của nó (trang chủ, danh mục, chi tiết, v.v.).
     * Nếu sử dụng chế độ kết hợp (tham chiếu các mô-đun của các chủ đề khác), thông tin tiêu đề và hình ảnh của chủ đề nguồn thực tế sẽ được phân tích cú pháp.
     *
     * Trả về cấu trúc dữ liệu:
     * - id, title, info, version: Thông tin cơ bản chủ đề
     * - confuse: Có nên trộn và kết hợp chế độ (0/1)
     * - data_info: Danh sách chi tiết của từng mô-đun (bao gồm key, title, image, update_time）
     * - theme_data: Cấu hình phong cách toàn cầu của chủ đề
     *
     * Mảng @return Trả về dữ liệu chứa thông tin cơ bản về chủ đề và cấu hình chi tiết của từng mô-đun
     * @throws AdminException được ném ra khi không sử dụng chủ đề
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/02/03
     */
    public function getUsingTheme()
    {
        // Truy vấn bản ghi chủ đề hiện đang được sử dụng（is_use = 1）
        $data = $this->dao->get(['is_use' => 1]);
        if (!$data) throw new AdminException('Không có chủ đề nào được sử dụng');

        // Thu thập ID chủ đề được liên kết với từng mô-đun và lọc ra các giá trị null
        $themeIds = array_filter([
            $data['home_data_id'],      // Các chủ đề liên quan đến mô-đun trang chủID
            $data['category_data_id'],  // Chủ đề liên quan đến mô-đun trang danh mụcID
            $data['detail_data_id'],    // Các chủ đề liên quan đến mô-đun trang chi tiếtID
            $data['user_data_id'],      // Các chủ đề liên quan đến mô-đun trung tâm người dùngID
            $data['theme_data_id'],     // Các chủ đề liên quan đến dữ liệu riêng của chủ đềID
        ]);


        // Tập hợp mảng thông tin chủ đề được trả về cuối cùng
        $theme = [];
        $theme['id'] = $data['id'];             // chủ đềID
        $theme['title'] = $data['title'];         // Tên chủ đề
        $theme['info'] = $data['info'];           // Giới thiệu chủ đề
        $theme['version'] = $data['version'];   // Số phiên bản chủ đề
        $theme['confuse'] = 0;                  // Có nên trộn và kết hợp các chủ đề hay không: 0 không 1 có
        // Nếu có ID chủ đề liên quan, hãy truy vấn tiêu đề của nó theo đợt để ghép nối tiếp theo.
        if ($themeIds) {
            $themeData = $this->dao->getColumn([['id', 'in', $themeIds]], 'title', 'id');
            $theme['confuse'] = 1;
        }
        $theme['data_info'] = [
            [
                'key' => 'home',
                'title' => $themeData[$data['home_data_id']] ?? $data['title'], // Tiêu đề mô-đun trang chủ (ưu tiên tiêu đề chủ đề liên quan)）
                'image' => set_file_url($data['home_image']), // Xem trước trang chủ
                'update_time' => date('Y-m-d H:i:s', $data['home_data_update_time']), // Thời gian cập nhật dữ liệu trang chủ
            ],
            [
                'key' => 'category',
                'title' => $themeData[$data['category_data_id']] ?? $data['title'], // Tiêu đề mô-đun trang danh mục (ưu tiên tiêu đề chủ đề liên quan)）
                'image' => set_file_url($data['category_image']), // Xem trước trang danh mục
                'update_time' => date('Y-m-d H:i:s', $data['category_data_update_time']), // Thời gian cập nhật dữ liệu trang chuyên mục
            ],
            [
                'key' => 'detail',
                'title' => $themeData[$data['detail_data_id']] ?? $data['title'], // Tiêu đề mô-đun trang chi tiết (ưu tiên tiêu đề chủ đề liên quan)）
                'image' => set_file_url($data['detail_image']), // Xem trước trang chi tiết
                'update_time' => date('Y-m-d H:i:s', $data['detail_data_update_time']), // Thời gian cập nhật dữ liệu trang chi tiết
            ],
            [
                'key' => 'user',
                'title' => $themeData[$data['user_data_id']] ?? $data['title'], // Tiêu đề mô-đun trung tâm người dùng (ưu tiên tiêu đề chủ đề liên quan)）
                'image' => set_file_url($data['user_image']), // Xem trước trung tâm người dùng
                'update_time' => date('Y-m-d H:i:s', $data['user_data_update_time']), // Thời gian cập nhật dữ liệu trung tâm người dùng
            ],
        ];
        $theme['theme_data'] = json_decode($data['theme_data'], true); // Dữ liệu riêng của chủ đề (định dạng JSON）

        return $theme;
    }

    /**
     * @description: Khôi phục chủ đề
     * @param int $id chủ đềID
     * @return void
     */
    public function restoreTheme(int $id)
    {
        $data = $this->dao->get($id);
        if (!$data) throw new AdminException('Chủ đề không tồn tại');
        $this->dao->update($id, [
            'home_data' => $data['home_default_data'],
            'home_data_id' => 0,
            'home_image' => $data['home_default_image'],
            'home_data_update_time' => time(),
            'category_data' => $data['category_default_data'],
            'category_data_id' => 0,
            'category_image' => $data['category_default_image'],
            'category_data_update_time' => time(),
            'detail_data' => $data['detail_default_data'],
            'detail_data_id' => 0,
            'detail_image' => $data['detail_default_image'],
            'detail_data_update_time' => time(),
            'user_data' => $data['user_default_data'],
            'user_data_id' => 0,
            'user_image' => $data['user_default_image'],
            'user_data_update_time' => time(),
            'theme_data' => $data['theme_default_data'],
            'theme_data_update_time' => time(),
            'version' => uniqid(), // Cập nhật số phiên bản
            'up_time' => time(), // Thời gian cập nhật
        ]);
        return true;
    }

    /**
     * Xóa chủ đề
     *
     * Tổng quan về chức năng:
     * Soft xóa chủ đề đã chỉ định (cập nhật trường is_del）。
     *
     * @param int $id Mã chủ đề
     * @return bool Trả về true nếu thao tác thành công
     * @throws AdminException được ném ra khi chủ đề không tồn tại
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/02/03
     */
    public function deleteTheme(int $id)
    {
        $data = $this->dao->get($id);
        if (!$data) throw new AdminException('Chủ đề không tồn tại');
        if ($data['is_use']) throw new AdminException('Chủ đề hiện tại đang được sử dụng và không thể xóa được');
        $this->dao->update($id, ['is_del' => 1]);
        return true;
    }

    /**
     * Nhận cấu hình điều hướng phía dưới của chủ đề hiện được bật
     *
     * Tổng quan về chức năng:
     * Phân tích dữ liệu trang chủ của chủ đề hiện được bật và trích xuất cấu hình thành phần điều hướng phía dưới (chân trang).
     *
     * @return array Trả về mảng cấu hình thành phần có tên pagefoot. Nếu không tìm thấy, trả về một mảng trống.
     * @throws ApiException bị ném khi chủ đề được bật không có dữ liệu trang chủ
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/02/03
     */
    public function themeNavigation()
    {
        // Truy vấn dữ liệu trang chủ của theme hiện đang được sử dụng (chuỗi JSON）
        $value = $this->dao->value(['is_use' => 1], 'home_data');
        if (!$value) {
            throw new ApiException('Dữ liệu không tồn tại');
        }

        // Khởi tạo dữ liệu điều hướng dưới dạng một mảng trống
        $navigation = [];

        // Nếu dữ liệu trang chủ tồn tại, hãy phân tích và duyệt qua nó
        if ($value) {
            // Giải mã chuỗi JSON thành mảng
            $value = json_decode($value, true);
            // Duyệt qua các thành phần trang chủ và tìm thành phần điều hướng phía dưới có tên pagefoot
            foreach ($value['value'] as $item) {
                if (isset($item['name']) && strtolower($item['name']) === 'pagefoot') {
                    // Sau khi tìm thấy, gán giá trị và kết thúc vòng lặp
                    $navigation = $item;
                    break;
                }
            }
        }

        // Trả về cấu hình điều hướng (có thể là mảng trống）
        return $navigation;
    }

    /**
     * Nhận danh sách các micropage
     *
     * Tổng quan về chức năng:
     * Trang vi truy vấn phân trang（page_type='micro'）Liệt kê dữ liệu.
     *
     * Chức năng chính:
     * 1. Truy vấn phân trang - Nhận dữ liệu dựa trên các tham số phân trang của hệ thống
     * 2. Lọc dữ liệu - chỉ các bản ghi truy vấn chưa bị xóa và thuộc loại trang vi mô
     * 3. Định dạng - Chuyển đổi dấu thời gian sang định dạng ngày tháng có thể đọc được
     *
     * @return array Một mảng chứa danh sách dữ liệu danh sách và tổng số
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/02/03
     */
    public function getMicroPageList()
    {
        [$page, $limit] = $this->getPageValue(); // Nhận thông số phân trang
        $field = 'id,title,info,type,add_time,up_time,page_type'; // Trường truy vấn
        $order = 'id desc'; // loại
        $where = [
            'is_del' => 0, // không bị xóa
            'page_type' => 'micro', // Loại trang vi mô
        ];
        $list = $this->dao->themeList($where, $field, $page, $limit, $order); // danh sách truy vấn
        foreach ($list as &$item) {
            // Định dạng thời gian
            if (isset($item['add_time'])) $item['add_time'] = date('Y-m-d H:i', $item['add_time']);
            if (isset($item['up_time'])) $item['up_time'] = date('Y-m-d H:i', $item['up_time']);
        }
        $count = $this->dao->themeCount($where); // Nhận tổng số
        return compact('list', 'count');
    }

    /**
     * Xuất dữ liệu chủ đề (logic cốt lõi)
     * Đóng gói cấu hình chủ đề và các hình ảnh liên quan vào tệp Zip và quay lại địa chỉ tải xuống
     *
     * @param $themeInfo
     * @return string Địa chỉ tải xuống
     * @throws    hinkdbexceptionDataNotFoundException
     * @throws    hinkdbexceptionDbException
     * @throws    hinkdbexceptionModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/3/10
     */
    public function exportThemePackage($info): string
    {
        // 1. Đặt thư mục tạm thời xuất
        $dir = public_path() . 'theme/download/' . $info['id'] . '/';

        // 2. Xử lý các hình ảnh chính (ảnh trang chủ, ảnh danh mục, ảnh chi tiết, ảnh trung tâm cá nhân)）
        $images = ['home_image', 'category_image', 'detail_image', 'user_image'];
        $defaultImages = ['home_default_image', 'category_default_image', 'detail_default_image', 'user_default_image'];
        $i = 1;
        foreach ($images as $key => $image) {
            if (isset($info[$image]) && $info[$image]) {
                $originalUrl = $info[$image];
                $isRemote = (bool)preg_match('/^https?:\/\//i', $originalUrl);
                if ($isRemote) {
                    $urlPath = parse_url($originalUrl, PHP_URL_PATH) ?: $originalUrl;
                    $extension = strtolower(pathinfo($urlPath, PATHINFO_EXTENSION)) ?: 'jpg';
                    $newPath = $dir . $i . '_' . $image . '.' . $extension;
                    $ch = curl_init();
                    curl_setopt_array($ch, [
                        CURLOPT_URL => $originalUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                        CURLOPT_REFERER => rtrim(sys_config('site_url'), '/'),
                        CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; CRMEB/1.0)',
                        CURLOPT_HTTPHEADER => ['Accept: image/webp,image/*,*/*'],
                    ]);
                    $content = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    if ($content && $httpCode === 200 && file_put_contents($newPath, $content) !== false) {
                        $info[$image] = 'theme/download/' . $i . '_' . $image . '.' . $extension;
                        $info[$defaultImages[$key]] = 'theme/download/' . $i . '_' . $image . '.' . $extension;
                    }
                } else {
                    $localPath = public_path() . ltrim(preg_replace('/^https?:\/\/[^\/]+/', '', $originalUrl), '/');
                    if (!file_exists($localPath)) {
                        $i++;
                        continue;
                    }
                    $extension = strtolower(pathinfo($localPath, PATHINFO_EXTENSION)) ?: 'jpg';
                    $newPath = $dir . $i . '_' . $image . '.' . $extension;
                    if (copy($localPath, $newPath)) {
                        $info[$image] = 'theme/download/' . $i . '_' . $image . '.' . $extension;
                        $info[$defaultImages[$key]] = 'theme/download/' . $i . '_' . $image . '.' . $extension;
                    }
                }
            }
            $i++;
        }

        // 3. Xử lý hình ảnh trong home_data/detail_data/user_data
        $imagesDir = $dir . 'images/';
        $index = 1;
        $map = [];
        $isAttachImage = function ($str) {
            if (!is_string($str) || $str === '') return false;
            if (strpos($str, 'uploads/attach') !== false) return true;
            if (preg_match('/^https?:\/\//i', $str)) return true;
            return false;
        };

        $process = function (&$value, $key) use (&$index, &$map, $imagesDir, $isAttachImage) {
            if (!is_string($value) || !$isAttachImage($value)) return;
            if (!isset($map[$value])) {
                $path = parse_url($value, PHP_URL_PATH);
                $path = $path ?: $value;
                $basename = basename($path);
                $ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'])) {
                    $ext = 'jpg';
                    $basename = md5($value) . '.' . $ext;
                }
                $dest = $imagesDir . $basename;
                $rel = 'images/' . $basename;
                $ok = false;
                $isRemote = preg_match('/^https?:\/\//i', $value);
                if ($isRemote) {
                    $ch = curl_init();
                    curl_setopt_array($ch, [
                        CURLOPT_URL => $value,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                        CURLOPT_REFERER => rtrim(sys_config('site_url'), '/'),
                        CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; CRMEB/1.0)',
                        CURLOPT_HTTPHEADER => ['Accept: image/webp,image/*,*/*'],
                    ]);
                    $content = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    if ($content && $httpCode === 200) {
                        $ok = (file_put_contents($dest, $content) !== false);
                    }
                } else {
                    $src = public_path() . ltrim($path, '/');
                    if (file_exists($src)) $ok = @copy($src, $dest);
                }
                if ($ok) {
                    $map[$value] = $rel;
                    $index++;
                } else {
                    return;
                }
            }
            $value = $map[$value] ?? $value;
        };

        $homeData = json_decode($info['home_data'] ?? '[]', true);
        $detailData = json_decode($info['detail_data'] ?? '[]', true);
        $userData = json_decode($info['user_data'] ?? '[]', true);

        if (is_array($homeData)) array_walk_recursive($homeData, $process);
        if (is_array($detailData)) array_walk_recursive($detailData, $process);
        if (is_array($userData)) array_walk_recursive($userData, $process);

        $info['home_data'] = json_encode($homeData, JSON_UNESCAPED_UNICODE);
        $info['detail_data'] = json_encode($detailData, JSON_UNESCAPED_UNICODE);
        $info['user_data'] = json_encode($userData, JSON_UNESCAPED_UNICODE);
        $info['home_default_data'] = json_encode($homeData, JSON_UNESCAPED_UNICODE);
        $info['detail_default_data'] = json_encode($detailData, JSON_UNESCAPED_UNICODE);
        $info['user_default_data'] = json_encode($userData, JSON_UNESCAPED_UNICODE);
        $info['theme_data'] = $info['theme_default_data'] = json_decode($info['theme_data'], true);

        // 4. viết config.json
        file_put_contents($dir . 'config.json', json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // 7. đóng gói vào zip
        $zip = new \ZipArchive();
        $zip->open($dir . $info['title'] . '.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $rootPath = realpath($dir);
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS));
        foreach ($files as $file) {
            if ($file->isDir()) continue;
            $filePath = $file->getRealPath();
            if (basename($filePath) === $info['title'] . '.zip') continue;
            $relativePath = ltrim(str_replace($rootPath, '', $filePath), DIRECTORY_SEPARATOR);
            $zip->addFile($filePath, $relativePath);
        }
        $zip->close();

        return sys_config('site_url') . '/theme/download/' . $info['id'] . '/' . $info['title'] . '.zip';
    }
}
