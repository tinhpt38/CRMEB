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

namespace app\services\system;


use app\services\BaseServices;
use crmeb\services\MysqlBackupService;
use think\facade\Db;
use think\facade\Env;

/**
 * Sao lưu cơ sở dữ liệu
 * Class SystemDatabackupServices
 * @package app\services\system
 */class SystemDatabackupServices extends BaseServices
{

    /**
     *
     * @var MysqlBackupService
     */    protected $dbBackup;

    /**
     * Người xây dựng
     * SystemDatabackupServices constructor.
     */    public function __construct()
    {
        $this->dbBackup = app()->make(MysqlBackupService::class, [[
            //Kích thước khối lượng sao lưu cơ sở dữ liệu
            'compress' => 1,
            //Có bật tính năng nén tệp sao lưu cơ sở dữ liệu hay không 0 Không nén 1 Nén
            'level' => 5,
        ]]);
    }

    /**
     * Lấy danh sách cơ sở dữ liệu
     * @return array
     * @throws \think\db\exception\BindParamException
     */    public function getDataList()
    {
        $list = $this->dbBackup->dataList();
        $count = count($list);
        return compact('list', 'count');
    }

    /**
     * Nhận chi tiết bảng
     * @param string $tablename
     * @return array
     */    public function getRead(string $tablename)
    {
        $database = Env::get("database.database");
        $list = Db::query("select * from information_schema.columns where table_name = '" . $tablename . "' and table_schema = '" . $database . "'");
        $count = count($list);
        foreach ($list as $key => $f) {
            $list[$key]['EXTRA'] = ($f['EXTRA'] == 'auto_increment' ? 'Đúng' : ' ');
        }
        return compact('list', 'count');
    }

    /**
     * @return MysqlBackupService
     */    public function getDbBackup()
    {
        return $this->dbBackup;
    }

    /**
     * bảng dự phòng
     * @param string $tables
     * @return string
     * @throws \think\db\exception\BindParamException
     */    public function backup(string $tables)
    {
        $tables = explode(',', $tables);
        $data = '';
        ini_set ("memory_limit","-1");
        foreach ($tables as $t) {
            $res = $this->dbBackup->backup($t, 0);
            if ($res == false && $res != 0) {
                $data .= $t . '|';
            }
        }
        return $data;
    }

    /**
     * Nhận danh sách dự phòng
     * @return array
     */    public function getBackup()
    {
        $files = $this->dbBackup->fileList();
        $data = [];
        foreach ($files as $key => $t) {
            $data[$key]['filename'] = $t['filename'];
            $data[$key]['part'] = $t['part'];
            $data[$key]['size'] = $t['size'] . 'B';
            $data[$key]['compress'] = $t['compress'];
            $data[$key]['backtime'] = $key;
            $data[$key]['time'] = $t['time'];
        }
        krsort($data);//Đơn hàng giảm dần theo thời gian
        return ['count' => count($data), 'list' => array_values($data)];
    }

}
