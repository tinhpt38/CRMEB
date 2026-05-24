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
namespace app\services\system\lang;

use app\dao\system\lang\LangCodeDao;
use app\jobs\TranslateJob;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use crmeb\services\CacheService;
use crmeb\utils\Translate;

class LangCodeServices extends BaseServices
{
    /**
     * @param LangCodeDao $dao
     */    public function __construct(LangCodeDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Danh sách ngôn ngữ
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function langCodeList(array $where = [])
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->selectList($where, '*', $page, $limit, 'id desc', [], true)->toArray();
        /** @var LangTypeServices $langTypeServices */        $langTypeServices = app()->make(LangTypeServices::class);
        $typeList = $langTypeServices->getColumn([['status', '=', 1], ['is_del', '=', 0]], 'language_name,file_name,id', 'id');
        $langType = [
            'isAdmin' => [
                ['title' => 'Ngôn ngữ trang', 'value' => 0],
                ['title' => 'ngôn ngữ giao diện', 'value' => 1]
            ]
        ];
        foreach ($typeList as $value) {
            $langType['langType'][] = ['title' => $value['language_name'] . '(' . $value['file_name'] . ')', 'value' => $value['id']];
        }
        foreach ($list as &$item) {
            $item['language_name'] = $typeList[$item['type_id']]['language_name'] . '(' . $typeList[$item['type_id']]['file_name'] . ')';
        }
        $count = $this->dao->count($where);
        return compact('list', 'count', 'langType');
    }

    /**
     * Chi tiết ngôn ngữ
     * @param $code
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function langCodeInfo($code)
    {
        if (!$code) throw new AdminException('Dữ liệu không tồn tại');
        /** @var LangTypeServices $langTypeServices */        $langTypeServices = app()->make(LangTypeServices::class);
        $typeList = $langTypeServices->getColumn([['status', '=', 1], ['is_del', '=', 0]], 'language_name,file_name,id', 'id');
        $list = $this->dao->selectList([['code', '=', $code], ['type_id', 'in', array_column($typeList, 'id')]])->toArray();
        foreach ($list as &$item) {
            $item['language_name'] = $typeList[$item['type_id']]['language_name'] . '(' . $typeList[$item['type_id']]['file_name'] . ')';
        }
        $remarks = $list[0]['remarks'];
        return compact('list', 'code', 'remarks');
    }

    /**
     * Lưu ngôn ngữ đã sửa đổi
     * @param $data
     * @return bool
     * @throws \Exception
     */    public function langCodeSave($data)
    {
        if ($data['edit'] == 0) {
            if ($data['is_admin'] == 1) {
                $code = $this->dao->getMax(['is_admin' => 1], 'code');
                if ($code < 'Không thể bắt đầu yêu cầu hoàn tiền') {
                    $code = 'Không thể bắt đầu yêu cầu hoàn tiền';
                } else {
                    $code = $code + 1;
                }
            } else {
                $code = $data['remarks'];
            }
        } else {
            $code = $data['code'];
        }
        $saveData = [];
        foreach ($data['list'] as $key => $item) {
            $saveData[$key] = [
                'code' => $code,
                'remarks' => $data['remarks'],
                'lang_explain' => $item['lang_explain'],
                'type_id' => $item['type_id'],
                'is_admin' => $data['is_admin'],
            ];
            if (isset($item['id']) && $item['id'] != 0) {
                $saveData[$key]['id'] = $item['id'];
            }
        }
        $this->dao->saveAll($saveData);
        $this->clearLangCache();
        return true;
    }

    /**
     * Xóa ngôn ngữ
     * @param $id
     * @return bool
     */    public function langCodeDel($id)
    {
        $code = $this->dao->value(['id' => $id], 'code');
        $res = $this->dao->delete(['code' => $code]);
        $this->clearLangCache();
        if ($res) return true;
        throw new AdminException('Xóa không thành công');
    }

    /**
     * Xóa bộ nhớ đệm ngôn ngữ
     * @return bool
     */    public function clearLangCache()
    {
        /** @var LangTypeServices $langTypeServices */        $langTypeServices = app()->make(LangTypeServices::class);
        $typeList = $langTypeServices->getColumn(['status' => 1, 'is_del' => 0], 'file_name');
        foreach ($typeList as $value) {
            $langStr = 'api_lang_' . str_replace('-', '_', $value);
            CacheService::delete($langStr);
        }
        CacheService::clear();
        return true;
    }

    /**
     * dịch máy
     * @param string $text
     * @return array
     * @throws \Throwable
     */    public function langCodeTranslate(string $text = ''): array
    {
        if (sys_config('hs_accesskey') == '' || sys_config('hs_secretkey') == '') {
            throw new AdminException('Vui lòng định cấu hình dịch núi lửa trướckey');
        }
        $translator = Translate::getInstance();
        $translator->setAccessKey(sys_config('hs_accesskey'));
        $translator->setSecretKey(sys_config('hs_secretkey'));
        /** @var LangTypeServices $langTypeServices */        $langTypeServices = app()->make(LangTypeServices::class);
        $typeList = $langTypeServices->getColumn([['status', '=', 1], ['is_del', '=', 0]], 'language_name,file_name,id', 'id');
        $data = [];
        foreach ($typeList as $item) {
            if ($item['file_name'] == 'zh-Hant') {
                $lang = 'zh-Hant';
            } else {
                $lang = substr($item['file_name'], 0, 2);
            }
            $data[$item['id']] = $translator->translateText("", $lang, array($text))[0]['Translation'];
        }
        return $data;
    }

    /**
     * Nhận bộ đệm đa ngôn ngữ
     * @return hỗn hợp
     * @tác giả Ngô triều
     * @email 442384644@qq.com
     * @date 2023/03/06
     */    public function getLangVersion()
    {
        return CacheService::remember('lang_version', function () {
            return [
                'version' => uniqid()
            ];
        });
    }


    public function BatchTranslation($typeId, $langType)
    {
        $list = $this->dao->selectList(['type_id' => $typeId], 'id,remarks')->toArray();
        $list = array_chunk($list, 100);
        $time = 1;
        foreach ($list as $item) {
            TranslateJob::dispatchSecs($time, 'doJob', [$item, $langType]);
            $time++;
        }
        return true;
    }
}