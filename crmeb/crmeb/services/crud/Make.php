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

namespace crmeb\services\crud;

use think\App;
use think\helper\Str;

/**
 * Tạo lớp cơ sở thô
 *Lớp học
 * @author Chờ gió về
 * @email 136327134@qq.com
 * @date 2023/3/13
 * @package crmeb\services\crud
 */
abstract class Make
{

    /**
     * tên
     * @var string
     */
    protected $name = '';

    /**
     * Loại tệp
     * @var string
     */
    protected $fileMime = 'php';

    /**
     * Đường dẫn tập tin đầy đủ
     * @var string
     */
    protected $filePathName = null;

    /**
     * @var string
     */
    protected $fileBasePath;

    /**
     * Nội dung tập tin
     * @var string
     */
    protected $content = '';

    /**
     * Lưu trữ tập tin thực tế
     * @var string
     */
    protected $pathname = '';

    /**
     * đường dẫn không gian tên
     * @var string
     */
    protected $usePath = '';

    /**
     * tên biến
     * @var array
     */
    protected $var = [];

    /**
     * nội dung
     * @var array
     */
    protected $value = [];

    /**
     * tham số
     * @var array
     */
    protected $options = [];

    /**
     * Hậu tố thu thập cơ sở dữ liệu
     * @var string
     */
    protected $attrPrefix = '_label';

    /**
     * Hàm tạo mã tạo đường dẫn đến tệp giao diện người dùng
     * @var string
     */
    protected $adminTemplatePath;

    /**
     * Đường dẫn lưu mặc định
     * @var string
     */
    protected $basePath;

    /**
     * thư mục mặc định
     * @var string
     */
    protected $baseDir;

    /**
     * @var
     */
    protected $app;


    /**
     * Make constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->adminTemplatePath = self::adminTemplatePath();
        $this->basePath = $this->app->getRootPath();
        $this->baseDir = $this->setBaseDir();
        $this->var = $this->authDrawVar();
        $this->value = $this->drawValueKeys();
        $this->setDefaultValue();
    }

    /**
     * Đặt đường dẫn mặc định
     * @param string $basePath
     * @return $this
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/18
     */
    public function setbasePath(string $basePath)
    {
        if ($basePath) {
            $this->basePath = $basePath;
        }
        return $this;
    }

    /**
     * Nhận hậu tố trường
     * @return chuỗi
     * @author Chờ gió về
     * @email 136327134@qq.com
     * @date 2023/5/22
     */
    public function getAttrPrefix()
    {
        return $this->attrPrefix;
    }

    /**
     * @return string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/11
     */
    public static function adminTemplatePath()
    {
        return config('app.admin_template_path');
    }

    /**
     * Đặt thư mục lưu mặc định
     * @author Chờ gió về
     * @email 136327134@qq.com
     * @date 2023/4/4
     */
    protected function setBaseDir(): string
    {
        return 'crud';
    }

    /**
     * Lấy thư mục chứa tập tin
     * @param string $path
     * @return string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/4
     */
    protected function getBasePath(string $path = '')
    {
        //Thay thế bằng định dạng đường dẫn cục bộ
        $path = str_replace('/', DS, $path);
        $pathAttr = explode(DS, $path);
        $basePathAttr = explode(DS, $this->baseDir);
        //Thay thế giống như thư mục cơ sở
        if (count($pathAttr) > 1) {
            $newsPath = array_merge(array_diff($basePathAttr, $pathAttr))[0] ?? '';
            if ($newsPath !== 'crud') {
                $path = $newsPath;
            } else {
                $this->baseDir = '';
            }
        }
        //Thay thế nhiều dấu gạch chéo bằng một dấu gạch chéo
        $this->fileBasePath = str_replace(DS . DS, DS, $this->basePath . ($this->baseDir ? $this->baseDir . DS : '') . ($path ? $path . DS : ''));

        return $this->fileBasePath;
    }

    /**
     * Đặt tên đường dẫn nơi lưu file
     * @param string $filePathName
     * @return $this
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function setFilePathName(string $filePathName = '')
    {
        if ($filePathName) {
            $this->filePathName = $filePathName;
        }
        return $this;
    }

    /**
     * phát ratab
     * @param int $num
     * @return string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/3/29
     */
    public function tab(int $num = 1): string
    {
        return str_pad('', 4 * $num);
    }

    /**
     * Thực hiện tạo
     * @return Thực hiện
     * @author Chờ gió về
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    public function handle(string $name, array $options = [])
    {
        $path = $options['path'] ?? '';
        [$nameData, $content] = $this->getStubContent($name);

        $this->value['NAME'] = $nameData;
        if (isset($this->value['NAME_CAMEL']) && !$this->value['NAME_CAMEL']) {
            $this->value['NAME_CAMEL'] = Str::studly($name);
        }
        if (isset($this->value['PATH'])) {
            $this->value['PATH'] = $this->getfolderPath($path);
        }
        if (isset($this->value['USE_PHP']) && !empty($options['usePath'])) {
            $this->value['USE_PHP'] = "use " . str_replace('/', '\\', $options['usePath']) . ";\n";
        }
        if (isset($this->value['MODEL_NAME']) && !$this->value['MODEL_NAME'] && !empty($options['modelName'])) {
            $this->value['MODEL_NAME'] = $options['modelName'];
        }

        $contentStr = str_replace($this->var, $this->value, $content);
        $filePath = $this->getFilePathName($path, $this->value['NAME_CAMEL']);

        $this->usePath = $this->baseDir . '\\' . $this->value['NAME_CAMEL'];
        $this->setPathname($filePath);
        $this->setContent($contentStr);

        return $this;
    }

    /**
     * Cấu hình tệp mẫu
     * @param string $type
     * @return mixed
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    abstract protected function getStub(string $type = '');

    /**
     * Tự động lấy các biến mẫu
     * @author Chờ gió về
     * @email 136327134@qq.com
     * @date 2023/3/29
     */
    protected function authDrawVar(): array
    {
        $content = file_get_contents($this->getStub());
        $pattern = '/\{\%+[a-zA-Z0-9_-]+\%\}/';
        preg_match_all($pattern, $content, $var);
        $varData = $var[0] ?? [];
        $varData = array_unique($varData);
        return $varData;
    }

    /**
     * Trích xuất khóa giá trị
     * @author Chờ gió về
     * @email 136327134@qq.com
     * @date 2023/3/29
     */
    protected function drawValueKeys(): array
    {
        $data = [];
        foreach ($this->var as $value) {
            $data[str_replace(['{%', '%}'], '', $value)] = '';
        }
        return $data;
    }

    /**
     * Đặt giá trị mặc định
     * @author Chờ gió về
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function setDefaultValue()
    {
        if (isset($this->value['YEAR'])) {
            $this->value['YEAR'] = date('Y');
        }
        if (isset($this->value['TIME'])) {
            $this->value['TIME'] = date('Y/m/d H:i:s');
        }
        if (isset($this->value['DATE'])) {
            $this->value['DATE'] = date('Y/m/d');
        }
    }

    /**
     * Trích xuất tập tin mẫu
     * @param string $name
     * @return array
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function getStubContent(string $name, string $type = '')
    {
        $stub = file_get_contents($this->getStub($type));

        $namespace = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');

        $class = str_replace($namespace . '\\', '', $name);

        return [$class, $stub];
    }

    /**
     * Nhận đường dẫn tập tin
     * @param string $path
     * @param string $name
     * @return string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function getFilePathName(string $path, string $name): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');

        return $this->getBasePath($path) . $name . ucwords($this->name) . '.' . $this->fileMime;
    }

    /**
     * @param string $path
     * @return mixed|string|null
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function getfolderPath(string $path)
    {
        $path = $path ?: $this->filePathName;
        $path = str_replace([$this->basePath, $this->baseDir], '', $path);
        $path = ltrim(str_replace('\\', '/', $path), '/');
        $pathArr = explode('/', $path);
        array_pop($pathArr);
        if ($pathArr) {
            return '\\' . implode('\\', $pathArr);
        } else {
            return '';
        }
    }

    /**
     * Nhận đường dẫn lưu file
     * @param string $name
     * @return string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function getPathName(string $name): string
    {
        $name = str_replace('app\\', '', $name);

        return $this->app->getBasePath() . ltrim(str_replace('\\', '/', $name), '/') . '.php';
    }

    /**
     * Lấy tên lớp
     * @param string $name
     * @return string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function getClassName(string $name): string
    {
        if (strpos($name, '\\') !== false) {
            return $name;
        }

        if (strpos($name, '@')) {
            [$app, $name] = explode('@', $name);
        } else {
            $app = '';
        }

        if (strpos($name, '/') !== false) {
            $name = str_replace('/', '\\', $name);
        }

        return $this->getNamespace($app) . '\\' . $name;
    }

    /**
     * Lấy tên không gian tên
     * @param string $app
     * @return string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function getNamespace(string $app): string
    {
        return 'app' . ($app ? '\\' . $app : '');
    }

    /**
     * Đặt nội dung
     * @param string $content
     * @return array|string|string[]
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/14
     */
    protected function setContent(string $content)
    {
        $this->content = str_replace('﻿', '', $content);
        return $this->content;
    }

    /**
     * @param string $pathname
     * @return $this
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/18
     */
    protected function setPathname(string $pathname)
    {
        $this->pathname = $this->filePathName ?: $pathname;
        return $this;
    }

    /**
     * @param string $key
     * @return mixed|null
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/18
     */
    public function getValue(string $key)
    {
        return $this->value[$key] ?? null;
    }

    /**
     * Nhận đường dẫn không gian tên
     * @return chuỗi
     * @author Chờ gió về
     * @email 136327134@qq.com
     * @date 2023/4/18
     */
    public function getUsePath()
    {
        return $this->usePath;
    }

    /**
     * Nhận nội dung
     * @return chuỗi
     * @author Chờ gió về
     * @email 136327134@qq.com
     * @date 2023/4/18
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/18
     */
    public function getPath()
    {
        return $this->pathname;
    }

    /**
     * @return array
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/18
     */
    public function toArray()
    {
        return [
            'path' => $this->pathname,
            'content' => $this->content,
            'value' => $this->value,
            'var' => $this->var,
            'usePath' => $this->usePath,
        ];
    }

    public function __destruct()
    {
        $this->content = '';
        $this->pathname = '';
        $this->usePath = '';
    }
}
