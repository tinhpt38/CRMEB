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
namespace crmeb\services;

use crmeb\exceptions\AdminException;
use think\facade\Db;

class MysqlBackupService
{
    /**
     * con trỏ tập tin
     * @var resource
     */
    private $fp;
    /**
     * Phần thông tin file backup - số ổ, tên - tên file
     * @var array
     */
    private $file;
    /**
     * Kích thước tệp đang mở hiện tại
     * @var integer
     */
    private $size = 0;

    /**
     * Cấu hình cơ sở dữ liệu
     * @var integer
     */
    private $dbconfig = array();
    /**
     * Cấu hình dự phòng
     * @var integer
     */
    private $config = array(
        'path' => './Data/',
        //Đường dẫn sao lưu cơ sở dữ liệu
        'part' => 20971520,
        //Kích thước khối lượng sao lưu cơ sở dữ liệu
        'compress' => 0,
        //Có bật tính năng nén tệp sao lưu cơ sở dữ liệu hay không 0 Không nén 1 Nén
        'level' => 9,
    );

    /**
     * Phương pháp xây dựng sao lưu cơ sở dữ liệu
     *
     * @param array $file Thông tin tập tin được sao lưu hoặc khôi phục
     * @param array $config Sao lưu thông tin cấu hình
     */
    public function __construct($config = [])
    {
        $this->config['path'] = app()->getRootPath() . 'backup/';
        $this->config = array_merge($this->config, $config);
        //Tên tập tin khởi tạo
        $this->setFile();
        //Khởi tạo các tham số kết nối cơ sở dữ liệu
        $this->setDbConn();
        //Kiểm tra xem tập tin có thể ghi được không
        if (!$this->checkPath($this->config['path'])) {
            throw new AdminException('Không thể ghi tập tin');
        }
    }

    /**
     * Đặt thời gian chờ chạy tập lệnh
     * 0 nghĩa là không có giới hạn và hỗ trợ hoạt động liên tục
     */
    public function setTimeout($time = null)
    {
        if (!is_null($time)) {
            set_time_limit($time) || ini_set("max_execution_time", $time);
        }
        return $this;
    }

    /**
     * Đặt các tham số cần thiết cho kết nối cơ sở dữ liệu
     *
     * @param array $dbconfig Thông tin cấu hình kết nối cơ sở dữ liệu
     * @return $this
     */
    public function setDbConn($dbconfig = [])
    {
        if (empty($dbconfig)) {
            $this->dbconfig = config('database.connections.' . config('database.default'));
            //$this->dbconfig = Config::get('database');
        } else {
            $this->dbconfig = $dbconfig;
        }
        return $this;
    }

    /**
     * Đặt tên tập tin sao lưu
     *
     * @param null $file
     * @return $this
     */
    public function setFile($file = null)
    {
        if (is_null($file)) {
            $this->file = ['name' => date('Ymd-His'), 'part' => 1];
        } else {
            if (!array_key_exists("name", $file) && !array_key_exists("part", $file)) {
                $this->file = $file['1'];
            } else {
                $this->file = $file;
            }
        }
        return $this;
    }

    //Kết nối lớp dữ liệu
    public static function connect()
    {
        return Db::connect();
    }

    /**
     * Danh sách bảng cơ sở dữ liệu
     *
     * @param null $table
     * @param int $type
     * @return array
     * @throws \think\db\exception\BindParamException
     * @throws \think\exception\PDOException
     */
    public function dataList(?string $table = null, int $type = 1)
    {
        $db = self::connect();
        if (is_null($table)) {
            $list = $db->query("SHOW TABLE STATUS");
        } else {
            if ($type) {
                $list = $db->query("SHOW FULL COLUMNS FROM {$table}");
            } else {
                $list = $db->query("show columns from {$table}");
            }
        }
        return array_map('array_change_key_case', $list);
        //$list;
    }

    /**
     * Danh sách tập tin sao lưu cơ sở dữ liệu
     *
     * @return array
     */
    public function fileList()
    {
        if (!is_dir($this->config['path'])) {
            mkdir($this->config['path'], 0755, true);
        }
        $path = realpath($this->config['path']);
        $flag = \FilesystemIterator::KEY_AS_FILENAME;
        $glob = new \FilesystemIterator($path, $flag);
        $list = array();
        foreach ($glob as $name => $file) {
            if (preg_match('/^\\d{8,8}-\\d{6,6}-\\d+\\.sql(?:\\.gz)?$/', $name)) {
                $info['filename'] = $name;
                $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');
                $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                $part = $name[6];
                if (isset($list["{$date} {$time}"])) {
                    $info = $list["{$date} {$time}"];
                    $info['part'] = max($info['part'], $part);
                    $info['size'] = $info['size'] + $file->getSize();
                } else {
                    $info['part'] = $part;
                    $info['size'] = $file->getSize();
                }
                $extension = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                $info['compress'] = $extension === 'SQL' ? '-' : $extension;
                $info['time'] = strtotime("{$date} {$time}");
                $list["{$date} {$time}"] = $info;
            }
        }
        return $list;
    }

    /**
     * @param string $type
     * @param int $time
     * @return array|false|string
     * @throws \Exception
     */
    public function getFile($type = '', $time = 0)
    {
        //
        if (!is_numeric($time)) {
            throw new AdminException('Định dạng thời gian không chính xác');
        }
        switch ($type) {
            case 'time':
                $name = date('Ymd-His', $time) . '-*.sql*';
                $path = realpath($this->config['path']) . DIRECTORY_SEPARATOR . $name;
                return glob($path);
            case 'timeverif':
                $name = date('Ymd-His', $time) . '-*.sql*';
                $path = realpath($this->config['path']) . DIRECTORY_SEPARATOR . $name;
                $files = glob($path);
                $list = array();
                foreach ($files as $name) {
                    $basename = basename($name);
                    $match = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
                    $gz = preg_match('/^\\d{8,8}-\\d{6,6}-\\d+\\.sql.gz$/', $basename);
                    $list[$match[6]] = array($match[6], $name, $gz);
                }
                $last = end($list);
                if (count($list) === $last[0]) {
                    return $list;
                } else {
                    throw new AdminException('Có thể file bị lỗi, bạn kiểm tra lại nhé');
                }
            case 'pathname':
                return "{$this->config['path']}{$this->file['name']}-{$this->file['part']}.sql";
            case 'filename':
                return "{$this->file['name']}-{$this->file['part']}.sql";
            case 'filepath':
                return $this->config['path'];
            default:
                $arr = array('pathname' => "{$this->config['path']}{$this->file['name']}-{$this->file['part']}.sql", 'filename' => "{$this->file['name']}-{$this->file['part']}.sql", 'filepath' => $this->config['path'], 'file' => $this->file);
                return $arr;
        }
    }

    /**
     * Xóa tập tin sao lưu
     * @param $time
     * @return mixed
     * @throws \Exception
     */
    public function delFile($time)
    {
        if ($time) {
            $file = $this->getFile('time', $time);
            array_map("unlink", $this->getFile('time', $time));
            if (count($this->getFile('time', $time))) {
                throw new AdminException('Xóa không thành công');
            } else {
                return $time;
            }
        } else {
            throw new AdminException('Định dạng thời gian không chính xác');
        }
    }

    /**
     * Tải xuống bản sao lưu
     *
     * @param $time
     * @param int $part
     * @throws \Exception
     */
    public function downloadFile($time, int $part = 0, bool $isFile = false)
    {
        $file = $this->getFile('time', $time);
        $fileName = $file[$part];
        if (file_exists($fileName)) {
            if ($isFile) {
                $key = password_hash(time() . $fileName, PASSWORD_DEFAULT);
                CacheService::set($key, ['path' => $fileName, 'fileName' => substr(strstr($fileName, 'backup'), 7)], 300);
                return $key;
            }
            ob_end_clean();
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header('Content-Description: File Transfer');
            header('Access-Control-Allow-Origin: ' . request()->domain());
            header('Content-Type: application/octet-stream');
            header('Content-Length: ' . filesize($fileName));
            header('Content-Disposition: attachment; filename=' . basename($fileName));
            return readfile($fileName);
        } else {
            throw new AdminException('Có thể file bị lỗi, bạn kiểm tra lại nhé');
        }
    }

    public function import($start)
    {
        //Khôi phục dữ liệu
        $db = self::connect();
        if ($this->config['compress']) {
            $gz = gzopen($this->file[1], 'r');
            $size = 0;
        } else {
            $size = filesize($this->file[1]);
            $gz = fopen($this->file[1], 'r');
        }
        $sql = '';
        if ($start) {
            $this->config['compress'] ? gzseek($gz, $start) : fseek($gz, $start);
        }
        for ($i = 0; $i < 1000; $i++) {
            $sql .= $this->config['compress'] ? gzgets($gz) : fgets($gz);
            if (preg_match('/.*;$/', trim($sql))) {
                if (false !== $db->execute($sql)) {
                    $start += strlen($sql);
                } else {
                    return false;
                }
                $sql = '';
            } elseif ($this->config['compress'] ? gzeof($gz) : feof($gz)) {
                return 0;
            }
        }
        return array($start, $size);
    }

    /**
     * Ghi dữ liệu ban đầu
     *
     * @return boolean true - viết thành công, sai - viết không thành công
     */
    public function Backup_Init()
    {
        $sql = "-- -----------------------------\n";
        $sql .= "-- Think MySQL Data Transfer \n";
        $sql .= "-- \n";
        $sql .= "-- Host     : " . $this->dbconfig['hostname'] . "\n";
        $sql .= "-- Port     : " . $this->dbconfig['hostport'] . "\n";
        $sql .= "-- Database : " . $this->dbconfig['database'] . "\n";
        $sql .= "-- \n";
        $sql .= "-- Part : #{$this->file['part']}\n";
        $sql .= "-- Date : " . date("Y-m-d H:i:s") . "\n";
        $sql .= "-- -----------------------------\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";
        return $this->write($sql);
    }

    /**
     * Cấu trúc bảng sao lưu
     *
     * @param string $table
     * @param int $start
     * @return bool|int
     * @throws \think\db\exception\BindParamException
     * @throws \think\exception\PDOException
     */
    public function backup(string $table, int $start, $sql = '')
    {
        $db = self::connect();
        // Cấu trúc bảng sao lưu
        if (0 == $start) {
            $result = $db->query("SHOW CREATE TABLE `{$table}`");
            $sql .= "\n";
            $sql .= "-- -----------------------------\n";
            $sql .= "-- Table structure for `{$table}`\n";
            $sql .= "-- -----------------------------\n";
            $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $sql .= trim($result[0]['Create Table']) . ";\n\n";
        }
        //Tổng số dữ liệu
        $result = $db->query("SELECT COUNT(*) AS count FROM `{$table}`");
        $count = $result['0']['count'];
        //Sao lưu dữ liệu bảng
        if ($count) {
            //Viết bình luận dữ liệu
            if (0 == $start) {
                $sql .= "-- -----------------------------\n";
                $sql .= "-- Records of `{$table}`\n";
                $sql .= "-- -----------------------------\n";
            }
            //Sao lưu hồ sơ dữ liệu
            $result = $db->query("SELECT * FROM `{$table}` LIMIT :MIN, 1000", ['MIN' => intval($start)]);
            foreach ($result as $row) {
                $row = array_map('addslashes', $row);
                $sql .= "INSERT INTO `{$table}` VALUES ('" . str_replace(array("\r", "\n"), array('\\r', '\\n'), implode("', '", $row)) . "');\n";
            }
            if (false === $this->write($sql)) {
                return false;
            }
            //Có nhiều dữ liệu hơn
            if ($count > $start + 1000) {
                //return array($start + 1000, $count);
                return $this->backup($table, $start + 1000);
            }
        }
        //Sao lưu bảng tiếp theo
        return 0;
    }

    /**
     * Bảng tối ưu hóa
     *
     * @param array|string $tables
     * @throws \think\db\exception\BindParamException
     * @throws \think\exception\PDOException
     */
    public function optimize($tables)
    {
        if ($tables) {
            $db = self::connect();
            if (is_array($tables)) {
                $tables = implode('`,`', $tables);
                $list = $db->query("OPTIMIZE TABLE `{$tables}`");
            } else {
                $list = $db->query("OPTIMIZE TABLE {$tables}");
            }
            if (!$list) {
                throw new AdminException('Đã sửa lỗi, vui lòng thử lại');
            }
            return $list;
        } else {
            throw new AdminException('Hãy chỉ định bảng cần sửa chữa');
        }
    }

    /**
     * bàn sửa chữa
     *
     * @param string|null $tables
     * @return array
     * @throws \think\db\exception\BindParamException
     * @throws \think\exception\PDOException
     */
    public function repair(?string $tables = null)
    {
        if ($tables) {
            $db = self::connect();
            if (is_array($tables)) {
                $tables = implode('`,`', $tables);
                $list = $db->query("REPAIR TABLE `{$tables}`");
            } else {
                $list = $db->query("REPAIR TABLE {$tables}");
            }
            if ($list) {
                return $list;
            } else {
                throw new AdminException('Đã sửa lỗi, vui lòng thử lại');
            }
        } else {
            throw new AdminException('Hãy chỉ định bảng cần sửa chữa');
        }
    }

    /**
     * Viết câu lệnh SQL
     *
     * @param string $sql Câu lệnh SQL để viết
     * @return boolean true - viết thành công, sai - viết không thành công！
     */
    private function write(string $sql)
    {
        $size = strlen($sql);
        //Vì lý do nén nên không thể tính được độ dài đã nén. Ở đây, giả định rằng tỷ lệ nén là 50%.
        // Nói chung tốc độ nén sẽ cao hơn50%；
        $size = $this->config['compress'] ? $size / 2 : $size;
        $this->open($size);
        return $this->config['compress'] ? @gzwrite($this->fp, $sql) : @fwrite($this->fp, $sql);
    }

    /**
     * Mở một ổ đĩa để ghi dữ liệu
     *
     * @param integer $size Kích thước của dữ liệu được viết
     */
    private function open(int $size)
    {
        if ($this->fp) {
            $this->size += $size;
            if ($this->size > $this->config['part']) {
                $this->config['compress'] ? @gzclose($this->fp) : @fclose($this->fp);
                $this->fp = null;
                $this->file['part']++;
                session('backup_file', $this->file);
                $this->Backup_Init();
            }
        } else {
            $backuppath = $this->config['path'];
            $filename = "{$backuppath}{$this->file['name']}-{$this->file['part']}.sql";
            if ($this->config['compress']) {
                $filename = "{$filename}.gz";
                $this->fp = @gzopen($filename, "a{$this->config['level']}");
            } else {
                $this->fp = @fopen($filename, 'a');
            }
            $this->size = filesize($filename) + $size;
        }
    }

    /**
     * Kiểm tra xem thư mục có thể ghi được không
     *
     * @param string $path
     * @return bool
     */
    protected function checkPath(string $path)
    {
        if (is_dir($path)) {
            return true;
        }
        if (mkdir($path, 0755, true)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Phương thức hủy, được sử dụng để đóng tài nguyên tệp
     */
    public function __destruct()
    {
        $this->config['compress'] ? @gzclose($this->fp) : @fclose($this->fp);
    }
}
