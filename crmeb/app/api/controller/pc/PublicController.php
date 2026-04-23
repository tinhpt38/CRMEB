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

namespace app\api\controller\pc;


use app\Request;
use app\services\article\ArticleCategoryServices;
use app\services\article\ArticleServices;
use app\services\pc\PublicServices;
use crmeb\services\CacheService;

class PublicController
{
    protected $services;

    public function __construct(PublicServices $services)
    {
        $this->services = $services;
    }

    /**
     * Nhận dữ liệu thành phố
     * @param Request $request
     * @return mixed
     */
    public function getCity(Request $request)
    {
        list($pid) = $request->getMore([
            [['pid', 'd'], 0],
        ], true);
        return app('json')->success($this->services->getCity($pid));
    }

    /**
     * Nhận thông tin công ty
     * @return mixed
     */
    public function getCompanyInfo()
    {
        $data['contact_number'] = sys_config('contact_number');
        $data['company_address'] = sys_config('company_address');
        $data['copyright'] = sys_config('nncnL_crmeb_copyright', '');
        $data['record_No'] = sys_config('record_No');
        $data['site_name'] = sys_config('site_name');
        $data['site_keywords'] = sys_config('site_keywords');
        $data['site_description'] = sys_config('site_description');
        $data['network_security'] = sys_config('network_security');
        $data['network_security_url'] = sys_config('network_security_url');
        $data['icp_url'] = sys_config('icp_url');
        $data['pc_home_menus'] = sys_data('pc_home_menus');
        $data['pc_home_links'] = sys_data('pc_home_links');
        $logoUrl = sys_config('pc_logo');
        if (strstr($logoUrl, 'http') === false && $logoUrl) {
            $logoUrl = sys_config('site_url') . $logoUrl;
        }
        $logoUrl = str_replace('\\', '/', $logoUrl);
        $data['logoUrl'] = $logoUrl;
        return app('json')->success($data);
    }

    /**
     * Thu hút sự chú ý Mã QR WeChat
     * @return mixed
     */
    public function getWechatQrcode()
    {
        $data['wechat_qrcode'] = sys_config('wechat_qrcode');
        return app('json')->success($data);
    }

    /**
     * Phân loại bài viết
     * @param ArticleCategoryServices $services
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/5/6
     */
    public function getNewsCategory(ArticleCategoryServices $services)
    {
        $cateInfo = CacheService::remember('ARTICLE_CATEGORY_PC', function () use ($services) {
            return $services->getArticleCategory();
        });
        return app('json')->success($cateInfo);
    }

    /**
     * pcDanh sách bài viết
     * @param Request $request
     * @param ArticleServices $services
     * @return \think\Response
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/5/6
     */
    public function getNewsList(Request $request, ArticleServices $services)
    {
        list($cid, $page, $limit) = $request->getMore([
            [['cid', 'd'], 0],
            [['page', 'd'], 0],
            [['limit', 'd'], 0],
        ], true);
        if ($cid == 0) {
            $where = ['is_hot' => 1];
        } else {
            $where = ['cid' => $cid];
        }
        $data = $services->getList($where, $page, $limit);
        foreach ($data['list'] as &$item) {
            $item['add_time'] = date('Y-m-d H:i', $item['add_time']);
        }
        return app('json')->success($data);
    }

    /**
     * Chi tiết bài viết
     * @param ArticleServices $services
     * @param $id
     * @return \think\Response
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/5/6
     */
    public function getNewsDetail(ArticleServices $services, $id)
    {
        $info = $services->getInfo($id);
        return app('json')->success($info);
    }
}
