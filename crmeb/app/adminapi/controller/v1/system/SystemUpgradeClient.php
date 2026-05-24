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
namespace app\adminapi\controller\v1\system;

use app\adminapi\controller\AuthController;
use app\services\system\config\SystemConfigServices;
use crmeb\services\UpgradeService as uService;
//use app\models\system\SystemConfig;
use think\facade\Db;

/**
 * Nâng cấp bộ điều khiển trực tuyến
 * Class SystemUpgradeclient
 * @package app\admin\controller\system
 *
 */class SystemUpgradeClient extends AuthController
{

    protected $serverweb = array('version' => '1.0', 'version_code' => 0);//Thông tin về trang web này

    public function initialize()
    {
        parent::initialize();
        self::snyweninfo();//Cập nhật thông tin trang web
    }

    //Cập nhật đồng bộ thông tin trang web
    public function snyweninfo()
    {
        /** @var SystemConfigServices $systemConfig */        $systemConfig = app()->make(SystemConfigServices::class);
        $this->serverweb['ip'] = $this->request->ip();
        $this->serverweb['host'] = $this->request->host();
        $this->serverweb['https'] = !empty($this->request->domain()) ? $this->request->domain() : $systemConfig->getConfigValue('site_url');
        $this->serverweb['webname'] = $systemConfig->getConfigValue('site_name');
        $local = uService::getVersion();
        if ($local['code'] == 200 && isset($local['msg']['version']) && isset($local['msg']['version_code'])) {
            $this->serverweb['version'] = uService::replace($local['msg']['version']);
            $this->serverweb['version_code'] = (int)uService::replace($local['msg']['version_code']);
        }
        uService::snyweninfo($this->serverweb);
    }

    //Cho phép hay không
    public function isauth()
    {
        return uService::isauth();
    }

    /**
     * Danh sách nâng cấp
     */    public function index()
    {
        $where = $this->request->getMore([
            ['page', 1],
            ['limit', 20]
        ]);
        $list = uService::request_post(uService::$isList, ['page' => $where['page'], 'limit' => $where['limit']]);
        if (is_array($list) && isset($list['code']) && isset($list['data']) && $list['code'] == 200) {
            $list = $list['data'];
        } else {
            $list = [];
        }
        return app('json')->success($list);
    }

    //Xóa tập tin sao lưu
    public function setcopydel()
    {
        $post = input('post.');
        if (!isset($post['id'])) app('json')->fail('Không xóa được file backup, thiếu thông sốID');
        if (!isset($post['ids'])) app('json')->fail('Không xóa được file backup, thiếu thông sốIDS');
        $fileservice = new uService;
        if (is_array($post['ids'])) {
            foreach ($post['ids'] as $file) {
                $fileservice->del_dir(app()->getRootPath() . 'public' . DS . 'copyfile' . $file);
            }
        }
        if ($post['id']) {
            $copyFile = app()->getRootPath() . 'public' . DS . 'copyfile' . $post['id'];
            $fileservice->del_dir($copyFile);
        }
        return app('json')->success('Xóa thành công');
    }

    public function get_new_version_conte()
    {
        $post = $this->request->post();
        if (!isset($post['id'])) app('json')->fail('Thiếu tham sốID');
        $versionInfo = uService::request_post(uService::$NewVersionCount, ['id' => $post['id']]);
        if (isset($versionInfo['code']) && isset($versionInfo['data']['count']) && $versionInfo['code'] == 200) {
            return app('json')->success(['count' => $versionInfo['data']['count']]);
        } else {
            return app('json')->fail('Ngoại lệ máy chủ');
        }
    }

    //Nâng cấp bằng một cú nhấp chuột
    public function auto_upgrade()
    {
        $prefix = config('database.prefix');
        $fileservice = new uService;
        $post = $this->request->post();
        if (!isset($post['id'])) return app('json')->fail('Thiếu tham sốID');
        $versionInfo = $fileservice->request_post(uService::$isNowVersion, ['id' => $post['id']]);
        if ($versionInfo === null) return app('json')->fail('Ngoại lệ máy chủ, vui lòng thử lại sau');
        if (isset($versionInfo['code']) && $versionInfo['code'] == 400) return app('json')->fail($versionInfo['msg'] ?? 'Hiện tại bạn không có quyền nâng cấp, vui lòng liên hệ với quản trị viên！');
        if (is_array($versionInfo) && isset($versionInfo['data'])) {
            $list = $versionInfo['data'];
            $id = [];
            foreach ($list as $key => $val) {
                $savefile = app()->getRootPath() . 'public' . DS . 'upgrade_lv';
                //1，Kiểm tra tệp tải xuống từ xa và tải xuống
                if (($save_path = $fileservice->check_remote_file_exists($val['zip_name'], $savefile)) === false) app('json')->fail('Gói nâng cấp từ xa không tồn tại');
                //2，Đầu tiên giải nén tập tin
                $savename = app()->getRootPath() . 'public' . DS . 'upgrade_lv' . DS . time();
                $fileservice->zipOpen($save_path, $savename);
                //3，Thực thi tệp SQL
                Db::startTrans();
                try {
                    //Tham số 3 không quan tâm đến trường hợp
                    $sqlfile = $fileservice->listDirInfo($savename . DS, true, 'sql');
                    if (is_array($sqlfile) && !empty($sqlfile)) {
                        foreach ($sqlfile as $file) {
                            if (file_exists($file)) {
                                //Để Cài đặt bằng một cú nhấp chuột, hãy nhớ thay đổi tiền tố bảng thành[#DB_PREFIX#]Ồ
                                $execute_sql = explode(";\r", str_replace(['[#DB_PREFIX#]', "\n"], [$prefix, "\r"], file_get_contents($file)));
                                foreach ($execute_sql as $_sql) {
                                    if ($query_string = trim(str_replace(array(
                                        "\r",
                                        "\n",
                                        "\t"
                                    ), '', $_sql))) Db::execute($query_string);
                                }
                                //Nhớ xóa nó sau khi thực hiện sql
                                $fileservice->unlinkFile($file);
                            }
                        }
                    }
                    Db::commit();
                } catch (\Exception $e) {
                    Db::rollback();
                    //Xóa các tập tin đã giải nén
                    $fileservice->del_dir(app()->getRootPath() . 'public' . DS . 'upgrade_lv');
                    //Xóa gói nén
                    $fileservice->unlinkFile($save_path);
                    //Nâng cấp không thành công và gửi thông báo lỗi
                    $fileservice->request_post(uService::$isInsertLog, [
                        'content' => 'Việc nâng cấp không thành công với thông báo lỗi:' . $e->getMessage(),
                        'add_time' => time(),
                        'ip' => $this->request->ip(),
                        'http' => $this->request->domain(),
                        'type' => 'error',
                        'version' => $val['version']
                    ]);
                    return app('json')->fail('Việc nâng cấp không thành công và tệp SQL được thực thi không chính xác.');
                }
                //4,Sao lưu tập tin
                $copyFile = app()->getRootPath() . 'public' . DS . 'copyfile' . $val['id'];
                $copyList = $fileservice->getDirs($savename . DS);
                if (isset($copyList['dir'])) {
                    if ($copyList['dir'][0] == '.' && $copyList['dir'][1] == '..') {
                        array_shift($copyList['dir']);
                        array_shift($copyList['dir']);
                    }
                    foreach ($copyList['dir'] as $dir) {
                        if (file_exists(app()->getRootPath() . $dir, $copyFile . DS . $dir)) {
                            $fileservice->copyDir(app()->getRootPath() . $dir, $copyFile . DS . $dir);
                        }
                    }
                }
                //5，ghi đè tập tin
                $fileservice->handleDir($savename, app()->getRootPath());
                //6,Xóa thư mục được tạo bởi bản nâng cấp
                $fileservice->del_dir(app()->getRootPath() . 'public' . DS . 'upgrade_lv');
                //7,Xóa gói nén
                $fileservice->unlinkFile($save_path);
                //8,Viết lại tập tin nâng cấp cục bộ
                $handle = fopen(app()->getRootPath() . '.version', 'w+');
                if ($handle === false) return app('json')->fail(app()->getRootPath() . '.version' . 'Không thể mở để viết');
                $content = <<<EOT
version={$val['version']}
version_code={$val['id']}
EOT;
                if (fwrite($handle, $content) === false) return app('json')->fail('Không thể ghi gói nâng cấp');
                fclose($handle);
                //9,Gửi nhật ký nâng cấp tới máy chủ
                $posts = [
                    'ip' => $this->request->ip(),
                    'https' => $this->request->domain(),
                    'update_time' => time(),
                    'content' => 'Nâng cấp bằng một cú nhấp chuột thành công, số phiên bản nâng cấp là：' . $val['version'] . '。Mã phiên bản là：' . $val['id'],
                    'type' => 'log',
                    'versionbefor' => $this->serverweb['version'],
                    'versionend' => $val['version']
                ];
                $inset = $fileservice->request_post(uService::$isInsertLog, $posts);
                $id[] = $val['id'];
            }
            //10,Nâng cấp hoàn tất
            return app('json')->success('Nâng cấp thành công', ['code' => end($id), 'version' => $val['version']]);
        } else {
            return app('json')->fail('Ngoại lệ máy chủ, vui lòng thử lại sau');
        }
    }
}
