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
use crmeb\services\crud\Make;

/**
 * Lớp thao tác tập tin
 * Class FileService
 * @package crmeb\services
 */
class FileService
{

    /**
     * Tạo thư mục
     * @param string $dir
     * @return bool
     */
    public static function mkDir(string $dir)
    {
        $dir = rtrim($dir, '/') . '/';
        if (!is_dir($dir)) {
            if (mkdir($dir, 0700) == false) {
                return false;
            }
            return true;
        }
        return true;
    }

    /**
     * @param string $filename Viết tên tập tin
     * @param string $writetext Lưu nội dung
     * @param string $openmod Phương thức mở
     * @return bool
     */
    public static function writeFile(string $filename, string $writetext, string $openmod = 'w')
    {
        if (@$fp = fopen($filename, $openmod)) {
            flock($fp, 2);
            fwrite($fp, $writetext);
            fclose($fp);
            return true;
        } else {
            return false;
        }
    }

    /**
     *  Xóa tất cả các file trong thư mục thỏa mãn điều kiện
     * @param $path Thư mục tập tin
     * @param $start thời gian bắt đầu
     * @param $end thời gian kết thúc
     *  return bool
     */
    public static function del_where_dir($path, $start = '', $end = '')
    {
        if (!file_exists($path)) {
            return false;
        }
        $dh = @opendir($path);
        if ($dh) {
            while (($d = readdir($dh)) !== false) {
                if ($d == '.' || $d == '..') {//nếu .hoặc..
                    continue;
                }
                $tmp = $path . '/' . $d;
                if (!is_dir($tmp)) {//Nếu đó là một tập tin
                    $file_time = filemtime($tmp);
                    if ($file_time) {
                        if ($start != '' && $end != '') {
                            if ($file_time >= $start && $file_time <= $end) {
                                @unlink($tmp);
                            }
                        } elseif ($start != '' && $end == '') {
                            if ($file_time >= $start) {
                                @unlink($tmp);
                            }
                        } elseif ($start == '' && $end != '') {
                            if ($file_time <= $end) {
                                @unlink($tmp);
                            }
                        } else {
                            @unlink($tmp);
                        }
                    }
                } else {//Nếu đó là một thư mục
                    self::delDir($tmp, $start, $end);
                }
            }
            //Xác định xem có còn tập tin nào trong thư mục không
            $count = count(scandir($path));
            closedir($dh);
            if ($count <= 2) @rmdir($path);
        }
        return true;
    }

    /**
     * xóa thư mục
     * @param $dirName
     * @return bool
     */
    public static function delDir($dirName)
    {
        if (!file_exists($dirName)) {
            return false;
        }

        $dir = opendir($dirName);
        while ($fileName = readdir($dir)) {
            $file = $dirName . '/' . $fileName;
            if ($fileName != '.' && $fileName != '..') {
                if (is_dir($file)) {
                    self::delDir($file);
                } else {
                    unlink($file);
                }
            }
        }
        closedir($dir);
        return rmdir($dirName);
    }


    /**
     * sao chép thư mục
     * @param string $surDir
     * @param string $toDir
     * @return bool
     */
    public function copyDir(string $surDir, string $toDir)
    {
        $surDir = rtrim($surDir, '/') . '/';
        $toDir = rtrim($toDir, '/') . '/';
        if (!file_exists($surDir)) {
            return false;
        }

        if (!file_exists($toDir)) {
            $this->createDir($toDir);
        }
        $file = opendir($surDir);
        while ($fileName = readdir($file)) {
            $file1 = $surDir . '/' . $fileName;
            $file2 = $toDir . '/' . $fileName;
            if ($fileName != '.' && $fileName != '..') {
                if (is_dir($file1)) {
                    $this->copyDir($file1, $file2);
                } else {
                    copy($file1, $file2);
                }
            }
        }
        closedir($file);
        return true;
    }


    /**
     * danh sách thư mục
     * @param $dir tên thư mục
     * @return array Liệt kê nội dung của thư mục và trả về một mảng $dirArray['dir']:lưu thư mục；$dirArray['file']：lưu tập tin
     */
    static function getDirs($dir)
    {
        $dir = rtrim($dir, '/') . '/';
        $dirArray [][] = NULL;
        if (false != ($handle = opendir($dir))) {
            $i = 0;
            $j = 0;
            while (false !== ($file = readdir($handle))) {
                // Nhảy . và .. các thư mục để tránh các vấn đề giới hạn open_basedir
                if ($file == '.' || $file == '..') {
                    continue;
                }
                if (is_dir($dir . $file)) { //Xác định xem đó có phải là một thư mục không
                    $dirArray ['dir'] [$i] = $file;
                    $i++;
                } else {
                    $dirArray ['file'] [$j] = $file;
                    $j++;
                }
            }
            closedir($handle);
        }
        return $dirArray;
    }

    /**
     * Thống kê kích thước thư mục
     * @param $dir
     * @return int Kích thước thư mục(đơn vị B)
     */
    public static function getSize($dir)
    {
        $dirlist = opendir($dir);
        $dirsize = 0;
        while (false !== ($folderorfile = readdir($dirlist))) {
            if ($folderorfile != "." && $folderorfile != "..") {
                if (is_dir("$dir/$folderorfile")) {
                    $dirsize += self::getSize("$dir/$folderorfile");
                } else {
                    $dirsize += filesize("$dir/$folderorfile");
                }
            }
        }
        closedir($dirlist);
        return $dirsize;
    }

    /**
     * Kiểm tra xem thư mục có trống không
     * @param $dir
     * @return bool
     */
    static function emptyDir($dir)
    {
        return (($files = @scandir($dir)) && count($files) <= 2);
    }

    /**
     * Tạo thư mục đa cấp
     * @param string $dir
     * @param int $mode
     * @return boolean
     */
    public function createDir(string $dir, int $mode = 0777)
    {
        return is_dir($dir) or ($this->createDir(dirname($dir)) and mkdir($dir, $mode));
    }

    /**
     * Tạo tệp được chỉ định theo đường dẫn đã chỉ định
     * @param string $path (Cần bao gồm tên tệp và hậu tố)
     * @param boolean $over_write Có ghi đè lên tập tin hay không
     * @param int $time Đặt thời gian. Mặc định là thời gian hiện tại của hệ thống
     * @param int $atime Đặt thời gian truy cập. Mặc định là thời gian hiện tại của hệ thống
     * @return boolean
     */
    public function createFile(string $path, bool $over_write = FALSE, int $time = NULL, int $atime = NULL)
    {
        $path = $this->dirReplace($path);
        $time = empty($time) ? time() : $time;
        $atime = empty($atime) ? time() : $atime;
        if (file_exists($path) && $over_write) {
            $this->unlinkFile($path);
        }
        $aimDir = dirname($path);
        $this->createDir($aimDir);
        return touch($path, $time, $atime);
    }

    /**
     * Đóng thao tác tập tin
     * @param string $path
     * @return boolean
     */
    public function close(string $path)
    {
        return fclose($path);
    }

    /**
     * Thao tác đọc tập tin
     * @param string $file
     * @return boolean
     */
    public static function readFile(string $file)
    {
        return @file_get_contents($file);
    }

    /**
     * Xác định giới hạn tải lên tối đa của máy chủ (số byte)
     * @return int Số byte tải lên tối đa được máy chủ cho phép
     */
    public function allowUploadSize()
    {
        $val = trim(ini_get('upload_max_filesize'));
        return $val;
    }

    /**
     * Định dạng byte Định dạng số byte theo kích thước được mô tả bởi B K M G T P E Z Y
     * @param int $size kích cỡ
     * @param int $dec kiểu hiển thị
     * @return int
     */
    public static function byteFormat($size, $dec = 2)
    {
        $a = ["B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
        $pos = 0;
        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }
        return round($size, $dec) . " " . $a[$pos];
    }

    /**
     * Xóa các thư mục không trống
     * Mô tả:Chỉ có thể xóa các tệp có quyền không thuộc hệ thống và cụ thể,Nếu không sẽ xảy ra lỗi
     * @param string $dirName đường dẫn thư mục
     * @param boolean $is_all Có nên xóa tất cả
     * @param boolean $delDir Có nên xóa thư mục
     * @return boolean
     */
    public function removeDir(str $dir_path, bool $is_all = FALSE)
    {
        $dirName = $this->dirReplace($dir_path);
        $handle = @opendir($dirName);
        while (($file = @readdir($handle)) !== FALSE) {
            if ($file != '.' && $file != '..') {
                $dir = $dirName . '/' . $file;
                if ($is_all) {
                    is_dir($dir) ? $this->removeDir($dir) : $this->unlinkFile($dir);
                } else {
                    if (is_file($dir)) {
                        $this->unlinkFile($dir);
                    }
                }
            }
        }
        closedir($handle);
        return @rmdir($dirName);
    }

    /**
     * Nhận tên tập tin đầy đủ
     * @param string $fn con đường
     * @return string
     */
    public function getBasename(string $file_path)
    {
        $file_path = $this->dirReplace($file_path);
        return basename(str_replace('\\', '/', $file_path));
        //return pathinfo($file_path,PATHINFO_BASENAME);
    }

    /**
     * Nhận phần mở rộng tập tin
     * @param string $file_name đường dẫn tập tin
     * @return string
     */
    public static function getExt(string $file)
    {
        $file = self::dirReplace($file);
        return pathinfo($file, PATHINFO_EXTENSION);
    }

    /**
     * Lấy tên thư mục được chỉ định
     * @param string $path đường dẫn tập tin
     * @param int $num Số lượng thư mục cấp trên cần được trả về
     * @return string
     */
    public function fatherDir(string $path, $num = 1)
    {
        $path = $this->dirReplace($path);
        $arr = explode('/', $path);
        if ($num == 0 || count($arr) < $num) return pathinfo($path, PATHINFO_BASENAME);
        return substr(strrev($path), 0, 1) == '/' ? $arr[(count($arr) - (1 + $num))] : $arr[(count($arr) - $num)];
    }

    /**
     * Xóa tập tin
     * @param string $path
     * @return boolean
     */
    public function unlinkFile(string $path)
    {
        $path = $this->dirReplace($path);
        if (file_exists($path)) {
            return unlink($path);
        }
    }

    /**
     * Thao tác với tệp(sao chép/di chuyển)
     * @param string $old_path Chỉ định đường dẫn tệp sẽ được vận hành(Yêu cầu tên tệp và hậu tố)
     * @param string $new_path Chỉ định đường dẫn tệp mới (yêu cầu tên và hậu tố tệp mới)）
     * @param string $type Kiểu thao tác tập tin
     * @param boolean $overWrite Có ghi đè lên các tập tin hiện có hay không
     * @param array $ignore Lọc theo hậu tố
     * @return boolean
     */
    public function handleFile(string $old_path, string $new_path, string $type = 'copy', bool $overWrite = FALSE, array $ignore = [])
    {
        $old_path = $this->dirReplace($old_path);
        $new_path = $this->dirReplace($new_path);
        if (file_exists($new_path) && $overWrite = FALSE) {
            return FALSE;
        } else if (file_exists($new_path) && $overWrite = TRUE) {
            $this->unlinkFile($new_path);
        }

        $extension = pathinfo($old_path, PATHINFO_EXTENSION);
        if ($ignore && $extension && in_array($extension, $ignore)) {
            return true;
        }

        $aimDir = dirname($new_path);
        $this->createDir($aimDir);
        switch ($type) {
            case 'copy':
                return copy($old_path, $new_path);
            case 'move':
                return @rename($old_path, $new_path);
        }
    }

    /**
     * Thao tác thư mục(sao chép/di chuyển)
     * @param string $old_path Chỉ định đường dẫn của thư mục sẽ được thao tác
     * @param string $aimDir Chỉ định đường dẫn thư mục mới
     * @param string $type Loại hoạt động
     * @param boolean $overWrite Có ghi đè lên tập tin và thư mục hay không
     * @param array $ignore Lọc theo tên thư mục
     * @return boolean
     */
    public function handleDir(string $old_path, string $new_path, string $type = 'copy', bool $overWrite = FALSE, array $ignore = [])
    {
        $new_path = $this->checkPath($new_path);
        $old_path = $this->checkPath($old_path);
        if (!is_dir($old_path)) return FALSE;

        if (!file_exists($new_path)) $this->createDir($new_path);

        $dirHandle = opendir($old_path);

        if (!$dirHandle) return FALSE;

        $boolean = TRUE;

        while (FALSE !== ($file = readdir($dirHandle))) {
            if ($file == '.' || $file == '..') continue;

            if (!is_dir($old_path . $file)) {
                $boolean = $this->handleFile($old_path . $file, $new_path . $file, $type, $overWrite);
            } else {
                if ($ignore && in_array($file, $ignore)) {
                    break;
                }
                $this->handleDir($old_path . $file, $new_path . $file, $type, $overWrite);
            }
        }
        switch ($type) {
            case 'copy':
                closedir($dirHandle);
                return $boolean;
            case 'move':
                closedir($dirHandle);
                return @rmdir($old_path);
        }
    }

    /**
     * Thay thế các ký tự tương ứng
     * @param string $path con đường
     * @return string
     */
    public static function dirReplace(string $path)
    {
        return str_replace('//', '/', str_replace('\\', '/', $path));
    }

    /**
     * Đọc tệp mẫu theo đường dẫn đã chỉ định
     * @param string $path Tệp theo đường dẫn đã chỉ định
     * @return string $rstr
     */
    public static function getTempltes(string $path)
    {
        $path = self::dirReplace($path);
        if (file_exists($path)) {
            $fp = fopen($path, 'r');
            $rstr = fread($fp, filesize($path));
            fclose($fp);
            return $rstr;
        } else {
            return '';
        }
    }

    /**
     * @param string $oldname tên gốc
     * @param string $newname tên mới
     * @return bool
     */
    public function rename(string $oldname, string $newname)
    {
        if (($newname != $oldname) && is_writable($oldname)) {
            return rename($oldname, $newname);
        }
    }

    /**
     * Nhận thông tin theo đường dẫn được chỉ định
     * @param string $dir con đường
     * @return ArrayObject
     */
    public function getDirInfo(string $dir)
    {
        $handle = @opendir($dir);//Mở thư mục được chỉ định
        $directory_count = 0;
        $total_size = 5;
        $file_cout = 0;
        $file_cout = 0;
        while (FALSE !== ($file_path = readdir($handle))) {
            if ($file_path != "." && $file_path != "..") {
                //is_dir("$dir/$file_path") ? $sizeResult += $this->get_dir_size("$dir/$file_path") : $sizeResult += filesize("$dir/$file_path");
                $next_path = $dir . '/' . $file_path;
                if (is_dir($next_path)) {
                    $directory_count++;
                    $result_value = self::getDirInfo($next_path);
                    $total_size += $result_value['size'];
                    $file_cout += $result_value['filecount'];
                    $directory_count += $result_value['dircount'];
                } elseif (is_file($next_path)) {
                    $total_size += filesize($next_path);
                    $file_cout++;
                }
            }
        }
        closedir($handle);//Đóng thư mục được chỉ định
        $result_value['size'] = $total_size;
        $result_value['filecount'] = $file_cout;
        $result_value['dircount'] = $directory_count;
        return $result_value;
    }

    /**
     * Chỉ định chuyển đổi mã hóa tập tin
     * @param string $path đường dẫn tập tin
     * @param string $input_code mã hóa gốc
     * @param string $out_code Mã hóa đầu ra
     * @return boolean
     */
    public function changeFileCode(string $path, string $input_code, string $out_code)
    {
        if (is_file($path))//Kiểm tra xem tập tin có tồn tại không,Nếu có, hãy thực hiện chuyển mã,Trả về đúng
        {
            $content = file_get_contents($path);
            $content = string::chang_code($content, $input_code, $out_code);
            $fp = fopen($path, 'w');
            fclose($fp);
            return (bool)fputs($fp, $content);
        }
    }

    /**
     * Chỉ định chuyển đổi mã hóa tệp có điều kiện trong thư mục được chỉ định
     * @param string $dirname đường dẫn thư mục
     * @param string $input_code mã hóa gốc
     * @param string $out_code Mã hóa đầu ra
     * @param boolean $is_all Có chuyển đổi mã hóa tập tin trong tất cả các thư mục con hay không
     * @param string $exts Loại tệp
     * @return boolean
     */
    public function changeDirFilesCode(string $dirname, string $input_code, string $out_code, bool $is_all = TRUE, string $exts = '')
    {
        if (is_dir($dirname)) {
            $fh = opendir($dirname);
            while (($file = readdir($fh)) !== FALSE) {
                if (strcmp($file, '.') == 0 || strcmp($file, '..') == 0) {
                    continue;
                }
                $filepath = $dirname . '/' . $file;

                if (is_dir($filepath) && $is_all == TRUE) {
                    $files = $this->changeDirFilesCode($filepath, $input_code, $out_code, $is_all, $exts);
                } else {
                    if ($this->getExt($filepath) == $exts && is_file($filepath)) {
                        $boole = $this->changeFileCode($filepath, $input_code, $out_code, $is_all, $exts);
                        if (!$boole) continue;
                    }
                }
            }
            closedir($fh);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Liệt kê các tập tin và thư mục đáp ứng các điều kiện trong thư mục được chỉ định
     * @param string $dirname con đường
     * @param boolean $is_all Có liệt kê các tập tin trong thư mục con hay không
     * @param string $exts Phần mở rộng tập tin sẽ được liệt kê
     * @param string $sort Sắp xếp mảng
     * @return ArrayObject
     */
    public function listDirInfo(string $dirname, bool $is_all = FALSE, string $exts = '', string $sort = 'ASC')
    {
        //Xử lý nhiều hơn một/ký hiệu
        $new = strrev($dirname);
        if (strpos($new, '/') == 0) {
            $new = substr($new, 1);
        }
        $dirname = strrev($new);

        $sort = strtolower($sort);//Chuyển ký tự thành chữ thường

        $files = [];
        $subfiles = [];

        if (is_dir($dirname)) {
            $fh = opendir($dirname);
            while (($file = readdir($fh)) !== FALSE) {
                if (strcmp($file, '.') == 0 || strcmp($file, '..') == 0) continue;

                $filepath = $dirname . '/' . $file;

                switch ($exts) {
                    case '*':
                        if (is_dir($filepath) && $is_all == TRUE) {
                            $files = array_merge($files, self::listDirInfo($filepath, $is_all, $exts, $sort));
                        }
                        array_push($files, $filepath);
                        break;
                    case 'folder':
                        if (is_dir($filepath) && $is_all == TRUE) {
                            $files = array_merge($files, self::listDirInfo($filepath, $is_all, $exts, $sort));
                            array_push($files, $filepath);
                        } elseif (is_dir($filepath)) {
                            array_push($files, $filepath);
                        }
                        break;
                    case 'file':
                        if (is_dir($filepath) && $is_all == TRUE) {
                            $files = array_merge($files, self::listDirInfo($filepath, $is_all, $exts, $sort));
                        } elseif (is_file($filepath)) {
                            array_push($files, $filepath);
                        }
                        break;
                    default:
                        if (is_dir($filepath) && $is_all == TRUE) {
                            $files = array_merge($files, self::listDirInfo($filepath, $is_all, $exts, $sort));
                        } elseif (preg_match("/\.($exts)/i", $filepath) && is_file($filepath)) {
                            array_push($files, $filepath);
                        }
                        break;
                }

                switch ($sort) {
                    case 'asc':
                        sort($files);
                        break;
                    case 'desc':
                        rsort($files);
                        break;
                    case 'nat':
                        natcasesort($files);
                        break;
                }
            }
            closedir($fh);
            return $files;
        } else {
            return FALSE;
        }
    }

    /**
     * Trả về thông tin thư mục của đường dẫn đã chỉ định, chứa các tệp và thư mục trong đường dẫn đã chỉ định
     * @param string $dir
     * @return ArrayObject
     */
    public function dirInfo(string $dir)
    {
        return scandir($dir);
    }

    /**
     * Xác định xem thư mục có trống không
     * @param string $dir
     * @return boolean
     */
    public function isEmpty(string $dir)
    {
        $handle = opendir($dir);
        while (($file = readdir($handle)) !== false) {
            if ($file != '.' && $file != '..') {
                closedir($handle);
                return true;
            }
        }
        closedir($handle);
        return false;
    }

    /**
     * Trả về thông tin về tập tin và thư mục được chỉ định
     * @param string $file
     * @return ArrayObject
     */
    public static function listInfo(string $file)
    {
        $dir = [];
        $dir['filename'] = basename($file);//Trả về phần tên file của đường dẫn。
        $dir['pathname'] = strstr(php_uname('s'), 'Windows') ? str_replace('\\', '\\\\', realpath($file)) : realpath($file);//Trả về tên đường dẫn tuyệt đối。
        $dir['owner'] = fileowner($file);//ID người dùng của tệp (chủ sở hữu）。
        $dir['perms'] = fileperms($file);//Trả về số inode của tập tin。
        $dir['inode'] = fileinode($file);//Trả về số inode của tập tin。
        $dir['group'] = filegroup($file);//Trả về nhóm của tập tin ID。
        $dir['path'] = dirname($file);//Trả về phần tên thư mục của đường dẫn。
        $dir['atime'] = fileatime($file);//Trả về thời gian truy cập cuối cùng của tệp。
        $dir['ctime'] = filectime($file);//Trả về lần cuối cùng một tập tin được thay đổi。
        $dir['perms'] = fileperms($file);//Trả về quyền của tập tin。
        $dir['size'] = self::byteFormat(filesize($file), 2);//Trả về kích thước tập tin。
        $dir['type'] = filetype($file);//Trả về loại tệp。
        $dir['ext'] = is_file($file) ? pathinfo($file, PATHINFO_EXTENSION) : '';//Trả về tên phần mở rộng của tập tin
        $dir['mtime'] = filemtime($file);//Trả về thời gian sửa đổi cuối cùng của tệp。
        $dir['isDir'] = is_dir($file);//Xác định xem tên tệp được chỉ định có phải là một thư mục không。
        $dir['isFile'] = is_file($file);//Xác định xem tệp được chỉ định có phải là tệp thông thường không。
        $dir['isLink'] = is_link($file);//Xác định xem tệp được chỉ định có phải là kết nối không。
        $dir['isReadable'] = is_readable($file);//Xác định xem tập tin có thể đọc được hay không。
        $dir['isWritable'] = is_writable($file);//Xác định xem tập tin có thể ghi được không。
        $dir['isUpload'] = is_uploaded_file($file);//Xác định xem tệp có được tải lên qua HTTP POST hay không。
        return $dir;
    }

    /**
     * Trả về thông tin về file đang mở
     * @param $file
     * @return ArrayObject
     * Chỉ số dưới dạng số Tên khóa liên kết (kể từ PHP 4.0.6) Mô tả
     * 0 tên thiết bị phát triển
     * 1 số
     * 2 chế độ bảo vệ inode
     * 3 số nlink kết nối được kết nối
     * 4 uid Id người dùng của chủ sở hữu
     * 5 id nhóm chủ sở hữu gid
     * 6 loại thiết bị rdev, nếu là thiết bị inode
     * Kích thước tệp 7 kích thước tính bằng byte
     * 8 lần Thời gian truy cập lần cuối (dấu thời gian Unix)
     * 9 mtime lần sửa đổi cuối cùng (dấu thời gian Unix)
     * 10 ctime thời gian thay đổi lần cuối (dấu thời gian Unix)
     * Kích thước khối IO của hệ thống tệp 11 blksize
     * 12 khối Số khối chiếm giữ
     */
    public function openInfo(string $file)
    {
        $file = fopen($file, "r");
        $result = fstat($file);
        fclose($file);
        return $result;
    }

    /**
     * Thay đổi các thuộc tính liên quan của tập tin và thư mục
     * @param string $file đường dẫn tập tin
     * @param string $type Loại hoạt động
     * @param string $ch_info Thông tin hoạt động
     * @return boolean
     */
    public function change_file($file, $type, $ch_info)
    {
        switch ($type) {
            case 'group' :
                $is_ok = chgrp($file, $ch_info);//Thay đổi nhóm tập tin。
                break;
            case 'mode' :
                $is_ok = chmod($file, $ch_info);//Thay đổi chế độ tập tin。
                break;
            case 'ower' :
                $is_ok = chown($file, $ch_info);//Thay đổi chủ sở hữu tập tin。
                break;
        }
    }

    /**
     * Nhận thông tin đường dẫn tập tin
     * @param $full_path đường dẫn đầy đủ
     * @return ArrayObject
     */
    public function getFileType(string $path)
    {
        //pathinfo() Hàm trả về thông tin đường dẫn file dưới dạng mảng。
        //---------$file_info = pathinfo($path); echo file_info['extension'];----------//
        //extensionNhận phần mở rộng tập tin【pathinfo($path,PATHINFO_EXTENSION)】-----dirnameNhận đường dẫn tập tin【pathinfo($path,PATHINFO_DIRNAME)】-----basenameLấy tên file đầy đủ của file【pathinfo($path,PATHINFO_BASENAME)】-----filenameLấy tên tập tin【pathinfo($path,PATHINFO_FILENAME)】
        return pathinfo($path);
    }

    /**
     * Nhận thông tin tập tin tải lên
     * @param $file fileThông tin thuộc tính
     * @return array
     */
    public function getUploadFileInfo($file)
    {
        $file_info = request()->file($file);//Nhận thông tin cơ bản về các tập tin được tải lên
        $info = [];
        $info['type'] = strtolower(trim(stripslashes(preg_replace("/^(.+?);.*$/", "\\1", $file_info['type'])), '"'));//Nhận loại tập tin
        $info['temp'] = $file_info['tmp_name'];//Lấy thư mục lưu tạm thời các file upload trên server
        $info['size'] = $file_info['size'];//Nhận kích thước tập tin tải lên
        $info['error'] = $file_info['error'];//Gặp lỗi tải tập tin lên
        $info['name'] = $file_info['name'];//Nhận tên tệp đã tải lên
        $info['ext'] = $this->getExt($file_info['name']);//Nhận hậu tố tập tin được tải lên
        return $info;
    }

    /**
     * Đặt quy tắc đặt tên file
     * @param string $type Quy tắc đặt tên
     * @param string $filename tên tập tin
     * @return string
     */
    public function setFileName(string $type)
    {
        switch ($type) {
            case 'hash' :
                $new_file = md5(uniqid(mt_rand()));//mt_srand()Được đặt tên theo mã hóa md5 số ngẫu nhiên
                break;
            case 'time' :
                $new_file = time();
                break;
            default :
                $new_file = date($type, time());//Đặt tên theo định dạng thời gian
                break;
        }
        return $new_file;
    }

    /**
     * Xử lý đường dẫn lưu file
     * @return string
     */
    public function checkPath($path)
    {
        return (preg_match('/\/$/', $path)) ? $path : $path . '/';
    }

    /**
     * Tải tập tin xuống
     * $save_dir lưu đường dẫn
     * $filename tên tập tin
     * @return array
     */
    public static function downRemoteFile(string $url, string $save_dir = '', string $filename = '', int $type = 0)
    {

        if (trim($url) == '') {
            return ['file_name' => '', 'save_path' => '', 'error' => 1];
        }
        if (trim($save_dir) == '') {
            $save_dir = './';
        }
        if (trim($filename) == '') {//Lưu tên tập tin
            $ext = strrchr($url, '.');
            //    if($ext!='.gif'&&$ext!='.jpg'){
            //        return ['file_name'=>'','save_path'=>'','error'=>3];
            //    }
            $filename = time() . $ext;
        }
        if (0 !== strrpos($save_dir, '/')) {
            $save_dir .= '/';
        }
        //Tạo thư mục lưu
        if (!file_exists($save_dir) && !mkdir($save_dir, 0777, true)) {
            return ['file_name' => '', 'save_path' => '', 'error' => 5];
        }
        //Phương pháp được sử dụng để lấy tập tin từ xa
        if ($type) {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $img = curl_exec($ch);
            curl_close($ch);
        } else {
            ob_start();
            readfile($url);
            $img = ob_get_contents();
            ob_end_clean();
        }
        //$size=strlen($img);
        //kích thước tập tin
        $fp2 = fopen($save_dir . $filename, 'a');

        fwrite($fp2, $img);
        fclose($fp2);
        unset($img, $url);
        return ['file_name' => $filename, 'save_path' => $save_dir . $filename, 'error' => 0];
    }

    /**
     * Giải nén tập tin zip
     * @param string $filename
     * @param string $savename
     * @return bool
     */
    public static function zipOpen(string $filename, string $savename)
    {
        $zip = new \ZipArchive;
        $zipfile = $filename;
        $res = $zip->open($zipfile);
        $toDir = $savename;
        if (!file_exists($toDir)) mkdir($toDir, 0777);
        $docnum = $zip->numFiles;
        for ($i = 0; $i < $docnum; $i++) {
            $statInfo = $zip->statIndex($i);
            if ($statInfo['crc'] == 0 && $statInfo['comp_size'] != 2) {
                //Tạo thư mục mới
                mkdir($toDir . '/' . substr($statInfo['name'], 0, -1), 0777);
            } else {
                //Sao chép tập tin
                copy('zip://' . $zipfile . '#' . $statInfo['name'], $toDir . '/' . $statInfo['name']);
            }
        }
        $zip->close();
        return true;
    }

    /**
     *Định dạng phông chữ
     * @param $title string Yêu cầu
     * return string
     */
    public static function setUtf8($title)
    {
        return iconv('utf-8', 'gb2312', $title);
    }

    /**
     *Kiểm tra xem tập tin được chỉ định có thể được ghi hay không
     * @param $file string Yêu cầu
     * return boole
     */
    public static function isWritable($file)
    {
        $file = str_replace('\\', '/', $file);
        if (!file_exists($file)) return false;
        return is_writable($file);
    }

    /**
     * Đọc nội dung file excel
     * @param $filePath
     * @param $type
     * @param int $row_num
     * @param string $suffix
     * @return mixed
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function readExcel($filePath, $type, int $row_num = 1, string $suffix = 'Xlsx')
    {
        if (!$filePath) return false;
        $pathInfo = pathinfo($filePath, PATHINFO_EXTENSION);
        if (!$pathInfo || ($pathInfo != "xlsx" && $pathInfo != "xls")) throw new AdminException('Các tệp ở định dạng xlsx phải được tải lên');
        //Tải mô hình đọc
        $readModel = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($suffix);
        // Tạo thao tác đọc
        //Mở file và nạp bảng excel

        try {
            $spreadsheet = $readModel->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getHighestColumn();
            $highestRow = $sheet->getHighestRow();
            $lines = $highestRow - 1;
            if ($lines <= 0) {
                throw new AdminException('Dữ liệu không thể trống');
            }
            // Dùng để lưu trữ dữ liệu dạng bảng
            $data = [];
            for ($i = $row_num; $i <= $highestRow; $i++) {
                if ($type == 'card') {
                    $t1 = $this->objToStr($sheet->getCellByColumnAndRow(1, $i)->getValue()) ?? '';
                    $t2 = $this->objToStr($sheet->getCellByColumnAndRow(2, $i)->getValue());
                    if ($t2) {
                        $data[] = [
                            'key' => $t1,
                            'value' => $t2
                        ];
                    }
                }
                if ($type == 'express') {
                    $t1 = $this->objToStr($sheet->getCellByColumnAndRow(1, $i)->getValue());
                    $t3 = $this->objToStr($sheet->getCellByColumnAndRow(3, $i)->getValue());
                    $t4 = $this->objToStr($sheet->getCellByColumnAndRow(4, $i)->getValue());
                    $t5 = $this->objToStr($sheet->getCellByColumnAndRow(5, $i)->getValue());
                    if ($t3 && $t5) {
                        $data[] = [
                            'id' => $t1,
                            'delivery_name' => $t3,
                            'delivery_code' => $t4,
                            'delivery_id' => $t5,
                        ];
                    }
                }
                if ($type == 'product') {
                    $t1 = $this->objToStr($sheet->getCellByColumnAndRow(1, $i)->getValue());
                    $t2 = $this->objToStr($sheet->getCellByColumnAndRow(2, $i)->getValue());
                    $t3 = $this->objToStr($sheet->getCellByColumnAndRow(3, $i)->getValue());
                    $t4 = $this->objToStr($sheet->getCellByColumnAndRow(4, $i)->getValue());
                    $t5 = $this->objToStr($sheet->getCellByColumnAndRow(5, $i)->getValue());
                    $t6 = $this->objToStr($sheet->getCellByColumnAndRow(6, $i)->getValue());
                    $t7 = $this->objToStr($sheet->getCellByColumnAndRow(7, $i)->getValue());
                    $t8 = $this->objToStr($sheet->getCellByColumnAndRow(8, $i)->getValue());
                    $t9 = $this->objToStr($sheet->getCellByColumnAndRow(9, $i)->getValue());
                    $t10 = $this->objToStr($sheet->getCellByColumnAndRow(10, $i)->getValue());
                    $t11 = $this->objToStr($sheet->getCellByColumnAndRow(11, $i)->getValue());
                    $t12 = $this->objToStr($sheet->getCellByColumnAndRow(12, $i)->getValue());
                    $t13 = $this->objToStr($sheet->getCellByColumnAndRow(13, $i)->getValue());
                    $t14 = $this->objToStr($sheet->getCellByColumnAndRow(14, $i)->getValue());
                    $t15 = $this->objToStr($sheet->getCellByColumnAndRow(15, $i)->getValue());
                    $t16 = $this->objToStr($sheet->getCellByColumnAndRow(16, $i)->getValue());
                    $t17 = $this->objToStr($sheet->getCellByColumnAndRow(17, $i)->getValue());
                    $t18 = $this->objToStr($sheet->getCellByColumnAndRow(18, $i)->getValue());
                    $t19 = $this->objToStr($sheet->getCellByColumnAndRow(19, $i)->getValue());
                    $t20 = $this->objToStr($sheet->getCellByColumnAndRow(20, $i)->getValue());
                    $t21 = $this->objToStr($sheet->getCellByColumnAndRow(21, $i)->getValue());
                    $t22 = $this->objToStr($sheet->getCellByColumnAndRow(22, $i)->getValue());
                    $t23 = $this->objToStr($sheet->getCellByColumnAndRow(23, $i)->getValue());
                    $t24 = $this->objToStr($sheet->getCellByColumnAndRow(24, $i)->getValue());
                    $t25 = $this->objToStr($sheet->getCellByColumnAndRow(25, $i)->getValue());
                    $t26 = $this->objToStr($sheet->getCellByColumnAndRow(26, $i)->getValue());
                    $t27 = $this->objToStr($sheet->getCellByColumnAndRow(27, $i)->getValue());
                    $t28 = $this->objToStr($sheet->getCellByColumnAndRow(28, $i)->getValue());
                    if ($i == 1) {
                        $header = [
                            $t1,
                            $t2, $t3, $t4, $t5, $t6,
                            $t7, $t8, $t9,
                            $t10, $t11,
                            $t12, $t13, $t14, $t15, $t16, $t17, $t18, $t19, $t20, $t21, $t22, $t23, $t24,
                            $t25, $t26, $t27,
                            $t28
                        ];
                        $verify = [
                            'Số mặt hàng',
                            'Tên sản phẩm', 'Loại sản phẩm', 'Phân loại sản phẩm(Cấp 1)', 'Phân loại sản phẩm(Cấp 2)', 'đơn vị hàng hóa',
                            'Hình ảnh sản phẩm', 'Video sản phẩm', 'Chi tiết sản phẩm',
                            'Số lượng bán', 'Số lượng mua tối thiểu',
                            'Loại đặc điểm kỹ thuật', 'Giá trị loại đặc điểm kỹ thuật', 'Tên đặc điểm kỹ thuật', 'Sự kết hợp giá trị đặc điểm kỹ thuật', 'hình ảnh đặc điểm kỹ thuật', 'giá bán', 'giá chéo', 'giá thành', 'trong kho', 'cân nặng', 'âm lượng', 'Mã sản phẩm', 'mã vạch',
                            'Giới thiệu sản phẩm', 'Từ khóa sản phẩm', 'Mật khẩu sản phẩm',
                            'Mua và nhận điểm'
                        ];
                        if ($header !== $verify) {
                            throw new AdminException('Cấu trúc dữ liệu không đúng');
                        }
                    } else {
                        $data[] = [
                            'id' => $t1,
                            'store_name' => $t2,
                            'virtual_type' => $t3,
                            'cate_name_one' => $t4,
                            'cate_name_two' => $t5,
                            'unit_name' => $t6,
                            'slider_image' => $t7,
                            'video_link' => $t8,
                            'description' => $t9,
                            'ficti' => $t10,
                            'min_qty' => $t11,
                            'spec_type' => $t12,
                            'sku_type_value' => $t13,
                            'sku_name' => $t14,
                            'sku_value' => $t15,
                            'pic' => $t16,
                            'price' => $t17,
                            'ot_price' => $t18,
                            'cost' => $t19,
                            'stock' => $t20,
                            'weight' => $t21,
                            'volume' => $t22,
                            'bar_code' => $t23,
                            'bar_code_number' => $t24,
                            'store_info' => $t25,
                            'keyword' => $t26,
                            'command_word' => $t27,
                            'give_integral' => $t28,
                        ];
                    }

                }
            }
            return $data;
        } catch (\Exception $e) {
            throw new AdminException($e->getMessage());
        }
    }

    /**Đối tượng với nhân vật
     * @param $value
     * @return mixed
     */
    public function objToStr($value)
    {
        return is_object($value) ? $value->__toString() : $value;
    }

    /**
     * Nén thư mục và tập tin
     * @param string $source Đường dẫn thư mục/file cần nén
     * @param string $destination Địa chỉ lưu nén
     * @param string $folder Tiền tố thư mục, thư mục mẹ cần loại bỏ khi lưu
     * @return boolean
     */
    function addZip($source, $destination, $folder = '')
    {
        if (!extension_loaded('zip') || !file_exists($source)) {
            return false;
        }

        $zip = new \ZipArchive;
        if (!$zip->open($destination, $zip::CREATE)) {
            return false;
        }
        $source = str_replace('\\', '/', $source);
        $folder = str_replace('\\', '/', $folder);
        if (is_dir($source) === true) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), \RecursiveIteratorIterator::SELF_FIRST);
            foreach ($files as $file) {
                $file = str_replace('\\', '/', $file);
                if (in_array(substr($file, strrpos($file, '/') + 1), array('.', '..'))) continue;
                if (is_dir($file) === true) {
                    $fileName = $folder ? str_replace($folder . '/', '', $file . '/') : $file . '/';
                    $zip->addEmptyDir($fileName);
                } else if (is_file($file) === true) {
                    $fileName = $folder ? str_replace($folder . '/', '', $file) : $file;
                    $zip->addFromString($fileName, file_get_contents($file));
                }
            }
        } else if (is_file($source) === true) {
            $zip->addFromString(basename($source), file_get_contents($source));
        }
        return $zip->close();
    }

    /**
     * Giải nén các thư mục và tập tin
     * @param string $source Đường dẫn file cần giải nén
     * @param string $folder Tiền tố thư mục, thư mục mẹ cần loại bỏ khi lưu
     * @return boolean
     */
    public function extractFile(string $source, string $folder): bool
    {
        if (!extension_loaded('zip') || !file_exists($source)) {
            return false;
        }

        $zip = new \ZipArchive;
        $zip->open($source);
        return $zip->extractTo($folder);
    }

    /**
     * Viết tập tin theo lô
     * @param array $make
     * @return bool
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/18
     */
    public static function batchMakeFiles(array $make)
    {

        $files = [];
        $dirnames = [];
        foreach ($make as $item) {
            if ($item instanceof Make) {
                $files[] = $item = $item->toArray();
            }
            try {
                $dirnames[] = $dirname = dirname($item['path']);
                if (!is_dir($dirname)) {
                    mkdir($dirname, 0755, true);
                }
            } catch (\Throwable $e) {
                if ($dirnames) {
                    foreach ($dirnames as $dirname) {
                        if (strstr($dirname, app()->getRootPath() . 'backup') !== false) {
                            rmdir($dirname);
                        }
                    }
                }
                throw new \RuntimeException($e->getMessage());
            }
        }
        $res = true;
        foreach ($files as $item) {
            $res = $res && file_put_contents($item['path'], $item['content'], LOCK_EX);
        }

        return $res;
    }

}
