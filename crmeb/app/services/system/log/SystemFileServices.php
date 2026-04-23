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

namespace app\services\system\log;


use app\dao\system\log\SystemFileDao;
use app\services\BaseServices;
use app\services\system\admin\SystemAdminServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\AuthException;
use crmeb\services\CacheService;
use crmeb\services\FileService as FileClass;
use crmeb\services\FormBuilder as Form;
use crmeb\utils\JwtAuth;
use Firebase\JWT\ExpiredException;
use think\facade\Log;
use think\facade\Route as Url;

/**
 * Xác minh tập tin
 * Class SystemFileServices
 * @package app\services\system\log
 */
class SystemFileServices extends BaseServices
{
    /**
     * Người xây dựng
     * SystemFileServices constructor.
     * @param SystemFileDao $dao
     */
    public function __construct(SystemFileDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param array $admin
     * @param string $password
     * @param string $type
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @date 2022/09/07
     * @author yyw
     */
    public function Login(string $password, string $type)
    {
        if (config('filesystem.password') !== $password) {
            throw new AdminException('Tài khoản hoặc mật khẩu không chính xác');
        }
        $md5Password = md5($password);
        /** @var JwtAuth $jwtAuth */
        $jwtAuth = app()->make(JwtAuth::class);
        $tokenInfo = $jwtAuth->createToken($md5Password, $type, ['pwd' => $md5Password]);
        CacheService::set(md5($tokenInfo['token']), $tokenInfo['token'], 3600);
        return [
            'token' => md5($tokenInfo['token']),
            'expires_time' => $tokenInfo['params']['exp'],
        ];

    }

    /**
     * Nhận thông tin ủy quyền của quản trị viên
     * @param string $token
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function parseToken(string $token): bool
    {
        /** @var CacheService $cacheService */
        $cacheService = app()->make(CacheService::class);

        if (!$token || $token === 'undefined') {
            throw new AuthException('Đăng nhập đã hết hạn,Vui lòng đăng nhập lại', [], 403);
        }

        /** @var JwtAuth $jwtAuth */
        $jwtAuth = app()->make(JwtAuth::class);
        //Thiết lập phân tích cú pháptoken
        [$id, $type, $pwd] = $jwtAuth->parseToken($token);

        //Kiểm tra xem mã thông báo đã hết hạn chưa
        $md5Token = md5($token);
        if (!$cacheService->has($md5Token) || !($cacheService->get($md5Token))) {
            throw new AuthException('Đăng nhập đã hết hạn,Vui lòng đăng nhập lại', [], 403);
        }

        //xác minhtoken
        try {
            $jwtAuth->verifyToken();
        } catch (\Throwable $e) {
            if (!request()->isCli()) {
                $cacheService->delete($md5Token);
            }
            throw new AuthException('Đăng nhập đã hết hạn,Vui lòng đăng nhập lại', [], 403);
        }

        if ($id !== md5(config('filesystem.password'))) {
            throw new AuthException('Đăng nhập đã hết hạn,Vui lòng đăng nhập lại', [], 403);
        }

        if ($pwd !== md5(config('filesystem.password'))) {
            throw new AuthException('Đăng nhập đã hết hạn,Vui lòng đăng nhập lại', [], 403);
        }

        return true;
    }


    /**
     * Nhận danh sách xác minh tập tin
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getFileList()
    {
        $rootPath = app()->getRootPath();
        $key = 'system_file_app_crmeb_public';
        $arr = CacheService::get(md5($key));
        if (!$arr) {
            $app = $this->getDir($rootPath . 'app');
            $extend = $this->getDir($rootPath . 'crmeb');
            $arr = array_merge($app, $extend);
            CacheService::set(md5($key), $arr, 3600 * 24);
        }
        $fileAll = [];//tập tin cục bộ
        $cha = [];//các tập tin khác nhau
        $len = strlen($rootPath);
        $file = $this->dao->getAll();//tập tin trong cơ sở dữ liệu
        if (empty($file)) {
            foreach ($arr as $k => $v) {
                $update_time = stat($v);
                $fileAll[$k]['cthash'] = md5_file($v);
                $fileAll[$k]['filename'] = substr($v, $len);
                $fileAll[$k]['atime'] = $update_time['atime'];
                $fileAll[$k]['mtime'] = $update_time['mtime'];
                $fileAll[$k]['ctime'] = $update_time['ctime'];
            }
            $data_num = array_chunk($fileAll, 100);
            $res = true;
            $res = $this->transaction(function () use ($data_num, $res) {
                foreach ($data_num as $k => $v) {
                    $res = $res && $this->dao->saveAll($v);
                }
                return $res;
            });
            if ($res) {
                $cha = [];//các tập tin khác nhau
            } else {
                $cha = $fileAll;
            }
        } else {
            $file = array_combine(array_column($file, 'filename'), $file);
            foreach ($arr as $ko => $vo) {
                $update_time = stat($vo);
                $cthash = md5_file($vo);
                $cha[] = [
                    'filename' => str_replace($rootPath, '', $vo),
                    'cthash' => $cthash,
                    'atime' => date('Y-m-d H:i:s', $update_time['atime']),
                    'mtime' => date('Y-m-d H:i:s', $update_time['mtime']),
                    'ctime' => date('Y-m-d H:i:s', $update_time['ctime']),
                    'type' => 'Mới',
                ];
                if (isset($file[$vo]) && $file[$vo] != $cthash) {
                    $cha[] = [
                        'type' => 'Đã sửa đổi',
                    ];
                    unset($file[$vo]);
                }
            }
            foreach ($file as $k => $v) {
                $cha[] = [
                    'filename' => $v['filename'],
                    'cthash' => $v['cthash'],
                    'atime' => date('Y-m-d H:i:s', $v['atime']),
                    'mtime' => date('Y-m-d H:i:s', $v['mtime']),
                    'ctime' => date('Y-m-d H:i:s', $v['ctime']),
                    'type' => 'Đã xóa',

                ];
            }
        }
        $ctime = array_column($cha, 'ctime');
        array_multisort($ctime, SORT_DESC, $cha);
        return $cha;
    }

    /**
     * Nhận các tập tin trong một thư mục bao gồm các tập tin con
     * @param $dir
     * @return array
     */
    public function getDir($dir)
    {
        $data = [];
        $this->searchDir($dir, $data);
        return $data;
    }

    /**
     * Lấy các tập tin trong thư mục, bao gồm cả các tập tin con. Nó không thể được sử dụng trực tiếp. Sử dụng nó trực tiếp.  $this->getDir()phương pháp P156
     * @param $path
     * @param $data
     */
    public function searchDir($path, &$data)
    {
        if (is_dir($path) && !strpos($path, 'uploads')) {
            $files = scandir($path);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    $this->searchDir($path . '/' . $file, $data);
                }
            }
        }
        if (is_file($path)) {
            $data[] = $path;
        }
    }

    //Mở thư mục
    public function opendir($dir, $fileDir, $superior)
    {
        $markList = app()->make(SystemFileInfoServices::class)->getColumn([], 'mark', 'full_path');
        $fileAll = array('dir' => [], 'file' => []);
        //thư mục gốc
        $rootDir = $this->formatPath(app()->getRootPath());
        //Ngăn chặn xem các thư mục bên ngoài trang web
        if (strpos($dir, $rootDir) === false || $dir == '') {
            $dir = $rootDir;
        }
        //Xác định xem có nên quay lại mức trước đó không
        if ($superior) {
            if (strpos(dirname($dir), $rootDir) !== false) {
                $dir = dirname($dir);
            } else {
                $dir = $rootDir;
            }
        } else {
            $dir = $dir . '/' . $fileDir;
        }
        $list = scandir($dir);
        foreach ($list as $key => $v) {
            if ($v != '.' && $v != '..') {
                if (is_dir($dir . DS . $v)) {
                    $fileAll['dir'][] = FileClass::listInfo($dir . DS . $v);
                }
                if (is_file($dir . DS . $v)) {
                    $fileAll['file'][] = FileClass::listInfo($dir . DS . $v);
                }
            }
        }
        //tương thíchwindows
        $uname = php_uname('s');
        if (strstr($uname, 'Windows') !== false) {
            $dir = ltrim($dir, '\\');
            $rootDir = str_replace('\\', '\\\\', $rootDir);
        }
        $list = array_merge($fileAll['dir'], $fileAll['file']);
        $navList = [];
        foreach ($list as $key => $value) {
            $list[$key]['real_path'] = str_replace($rootDir, '', $value['pathname']);
            $list[$key]['mtime'] = date('Y-m-d H:i:s', $value['mtime']);

            $navList[$key]['title'] = $value['filename'];
            if ($value['isDir']) $navList[$key]['loading'] = false;
            $navList[$key]['children'] = [];
            $navList[$key]['path'] = $value['path'];
            $navList[$key]['isDir'] = $value['isDir'];
            $navList[$key]['isLeaf'] = !$value['isDir'];
            $navList[$key]['pathname'] = $value['pathname'];
            $navList[$key]['contextmenu'] = true;
            $list[$key]['mark'] = $markList[str_replace(root_path(), '/', $value['pathname'])] ?? '';
            $count = app()->make(SystemFileInfoServices::class)->count(['full_path' => $list[$key]['real_path']]);
            if (!$count) app()->make(SystemFileInfoServices::class)->save([
                'name' => $value['filename'],
                'path' => str_replace('/' . $value['filename'], '', $list[$key]['real_path']),
                'full_path' => $list[$key]['real_path'],
                'type' => $value['type'],
                'create_time' => date('Y-m-d H:i:s', $value['ctime']),
                'update_time' => date('Y-m-d H:i:s', time()),
            ]);
        }
        $routeList = [['key' => 'thư mục gốc', 'route' => '']];
        $pathArray = explode('/', str_replace($rootDir, '', $dir));
        $str = '';
        foreach ($pathArray as $item) {
            if ($item) {
                $str = $str . '/' . $item;
                $routeList[] = ['key' => $item, 'route' => $rootDir . $str];
            }
        }
        return compact('dir', 'list', 'navList', 'routeList');
    }

    //đọc tập tin
    public function openfile($filepath)
    {
        //thư mục gốc
        $rootDir = $this->formatPath(app()->getRootPath());
        //Ngăn chặn việc xem các tập tin bên ngoài trang web
        if (strpos($filepath, $rootDir) === false || $filepath == '') {
            throw new AdminException('Không thể mở tập tin bên ngoài trang web');
        }

        $filepath = $this->formatPath($filepath);
        $content = FileClass::readFile($filepath);//Ngăn không cho thẻ vùng văn bản được nhúng vào trang
        $ext = FileClass::getExt($filepath);
        $encoding = mb_detect_encoding($content, mb_detect_order());
        //Các loại ngôn ngữ được hỗ trợ bởi các thành phần giao diện người dùng
        //['plaintext', 'json', 'abap', 'apex', 'azcli', 'bat', 'cameligo', 'clojure', 'coffeescript', 'c', 'cpp', 'csharp', 'csp', 'css', 'dart', 'dockerfile', 'fsharp', 'go', 'graphql', 'handlebars', 'hcl', 'html', 'ini', 'java', 'javascript', 'julia', 'kotlin', 'less', 'lexon', 'lua', 'markdown', 'mips', 'msdax', 'mysql', 'objective-c', 'pascal', 'pascaligo', 'perl', 'pgsql', 'php', 'postiats', 'powerquery', 'powershell', 'pug', 'python', 'r', 'razor', 'redis', 'redshift', 'restructuredtext', 'ruby', 'rust', 'sb', 'scala', 'scheme', 'scss', 'shell', 'sol', 'aes', 'sql', 'st', 'swift', 'systemverilog', 'verilog', 'tcl', 'twig', 'typescript', 'vb', 'xml', 'yaml']

        $extarray = [
            'js' => 'javascript'
            , 'htm' => 'html'
            , 'shtml' => 'html'
            , 'html' => 'html'
            , 'xml' => 'xml'
            , 'php' => 'php'
            , 'sql' => 'mysql'
            , 'css' => 'css'
            , 'txt' => 'plaintext'
            , 'vue' => 'html'
            , 'json' => 'json'
            , 'lock' => 'json'
            , 'md' => 'markdown'
            , 'bat' => 'bat'
            , 'ini' => 'ini'


        ];
        $mode = empty($extarray[$ext]) ? 'php' : $extarray[$ext];
        return compact('content', 'mode', 'filepath', 'encoding');
    }

    //lưu tập tin
    public function savefile($filepath, $comment)
    {
        $filepath = $this->formatPath($filepath);
        if (!FileClass::isWritable($filepath)) {
            throw new AdminException('Vui lòng kiểm tra quyền của thư mục. Tất cả các tập tin cần phải được cấp quyền 777WWW.');
        }
        return FileClass::writeFile($filepath, $comment);
    }

    // Đổi tên tập tin
    public function rename($newname, $oldname)
    {
        if (($newname != $oldname) && is_writable($oldname)) {
            return rename($oldname, $newname);
        }
        return true;
    }


    /**
     * Xóa một tập tin hoặc thư mục
     * @param string $path
     * @return bool
     *
     * @date 2022/09/20
     * @author yyw
     */
    public function delFolder(string $path)
    {
        $path = $this->formatPath($path);
        if (is_file($path)) {
            return unlink($path);
        }
        $dir = opendir($path);
        while ($fileName = readdir($dir)) {
            $file = $path . '/' . $fileName;
            if ($fileName != '.' && $fileName != '..') {
                if (is_dir($file)) {
                    self::delFolder($file);
                } else {
                    unlink($file);
                }
            }
        }
        closedir($dir);
        return rmdir($path);
    }

    /**
     * Tạo thư mục mới
     * @param string $path
     * @param string $name
     * @param int $permissions
     * @return bool
     *
     * @date 2022/09/20
     * @author yyw
     */
    public function createFolder(string $path, string $name, int $permissions = 0755)
    {
        $path = $this->formatPath($path, $name);
        /** @var FileClass $fileClass */
        $fileClass = app()->make(FileClass::class);
        return $fileClass->createDir($path, $permissions);
    }

    /**
     * Tạo tập tin mới
     * @param string $path
     * @param string $name
     * @return bool
     *
     * @date 2022/09/20
     * @author yyw
     */
    public function createFile(string $path, string $name)
    {
        $path = $this->formatPath($path, $name);
        /** @var FileClass $fileClass */
        $fileClass = app()->make(FileClass::class);
        return $fileClass->createFile($path);
    }

    public function copyFolder($surDir, $toDir)
    {
        return FileClass::copyDir($surDir, $toDir);
    }

    /**
     * Đường dẫn định dạng
     * @param string $path
     * @param string $name
     * @return string
     *
     * @date 2022/09/20
     * @author yyw
     */
    public function formatPath(string $path = '', string $name = ''): string
    {
        if ($path) {
            $path = rtrim($path, DS);
            if ($name) $path = $path . DS . $name;
            $uname = php_uname('s');
            if (strstr($uname, 'Windows') !== false)
                $path = ltrim(str_replace('\\', '\\\\', $path), '.');

        }
        return $path;
    }

    /**
     * Mẫu ghi chú tập tin
     * @param $path
     * @param $fileToken
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/04/10
     */
    public function markForm($path, $fileToken)
    {
        $full_path = str_replace(root_path(), '/', $path);
        $mark = app()->make(SystemFileInfoServices::class)->value(['full_path' => str_replace(root_path(), '/', $path)], 'mark');
        $f = [];
        $f[] = Form::hidden('full_path', $full_path);
        $f[] = Form::input('mark', 'Tập tin nhận xét', $mark);
        return create_form('Tập tin nhận xét', $f, Url::buildUrl('/system/file/mark/save?fileToken=' . $fileToken . '&type=mark'), 'POST');
    }

    /**
     * Lưu tập tin ghi chú
     * @param $full_path
     * @param $mark
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/04/10
     */
    public function fileMarkSave($full_path, $mark)
    {
        $res = app()->make(SystemFileInfoServices::class)->update(['full_path' => $full_path], ['mark' => $mark]);
        if (!$res) {
            throw new AdminException('Lưu không thành công');
        }
    }

    /**
     * ghi tập tinmd5
     * @return bool
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/2/25
     */
    public function writeMd5(){
        $rootPath = app()->getRootPath();
        $files = array_merge(
            $this->getDir($rootPath . 'app'),
            $this->getDir($rootPath . 'crmeb')
        );
        // Chỉ tìm kiếm tệp .php
        $files = array_filter($files, function ($path) {
            return pathinfo($path, PATHINFO_EXTENSION) === 'php';
        });
        $len = strlen($rootPath);
        $list = [];
        foreach ($files as $path) {
            $list[] = [
                'filename' => substr($path, $len),
                'md5' => md5_file($path),
            ];
        }

        $systemFileMd5Services = app()->make(SystemFileMd5Services::class);
        $systemFileMd5Services->clearMd5List();
        $systemFileMd5Services->saveMd5List($list);

        return true;
    }
}
