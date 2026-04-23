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
namespace app\adminapi\controller\v1\application\wechat;

use app\adminapi\controller\AuthController;
use crmeb\services\app\WechatService;
use think\facade\App;
use app\services\wechat\WechatNewsCategoryServices;
use app\services\article\ArticleServices;
use think\facade\Log;

/**
 * Thông tin đồ họa
 * Class WechatNewsCategory
 * @package app\admin\controller\wechat
 *
 */
class WechatNewsCategory extends AuthController
{
    /**
     * Người xây dựng
     * Menus constructor.
     * @param App $app
     * @param WechatNewsCategoryServices $services
     */
    public function __construct(App $app, WechatNewsCategoryServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Danh sách tin nhắn đồ họa
     * @return mixed
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['page', 1],
            ['limit', 20],
            ['cate_name', '']
        ]);
        $list = $this->services->getAll($where);
        return app('json')->success($list);
    }

    /**
     * Chi tiết hình ảnh và văn bản
     * @param $id
     * @return mixed
     */
    public function read($id)
    {
        $info = $this->services->get($id);
        /** @var ArticleServices $services */
        $services = app()->make(ArticleServices::class);
        $new = $services->articlesList($info['new_id']);
        if ($new) $new = $new->toArray();
        $info['new'] = $new;
        return app('json')->success(compact('info'));
    }

    /**
     * Xóa hình ảnh và văn bản
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        if (!$this->services->delete($id))
            return app('json')->fail('Xóa không thành công');
        else
            return app('json')->success('Xóa thành công');
    }

    /**
     * Thêm hoặc chỉnh sửa lưu
     * @return mixed
     */
    public function save()
    {
        $data = $this->request->postMore([
            ['list', []],
            ['id', 0]
        ]);
        try {
            $id = [];
            $countList = count($data['list']);
            if (!$countList) return app('json')->fail('Vui lòng thêm hình ảnh và văn bản');
            /** @var ArticleServices $services */
            $services = app()->make(ArticleServices::class);
            foreach ($data['list'] as $k => $v) {
                if ($v['title'] == '') return app('json')->fail('Tiêu đề không thể trống');
                if ($v['author'] == '') return app('json')->fail('Tác giả không thể trống');
                if ($v['content'] == '') return app('json')->fail('Văn bản không thể trống');
                if ($v['synopsis'] == '') return app('json')->fail('Tóm tắt không thể trống');
                $v['status'] = 1;
                $v['add_time'] = time();
                if ($v['id']) {
                    $idC = $v['id'];
                    $services->save($v);
                    unset($v['id']);
                    $data['list'][$k]['id'] = $idC;
                    $id[] = $idC;
                } else {
                    $res = $services->save($v);
                    unset($v['id']);
                    $id[] = $res['id'];
                    $data['list'][$k]['id'] = $res['id'];
                }
            }
            $countId = count($id);
            if ($countId != $countList) {
                if ($data['id']) return app('json')->fail('Sửa đổi không thành công');
                else return app('json')->fail('Thêm không thành công');
            } else {
                $newsCategory['cate_name'] = $data['list'][0]['title'];
                $newsCategory['new_id'] = implode(',', $id);
                $newsCategory['sort'] = 0;
                $newsCategory['add_time'] = time();
                $newsCategory['status'] = 1;
                if ($data['id']) {
                    $this->services->update($data['id'], $newsCategory, 'id');
                    return app('json')->success('Sửa đổi thành công');
                } else {
                    $this->services->save($newsCategory);
                    return app('json')->success('Đã thêm thành công');
                }
            }
        } catch (\Exception $e) {
            return app('json')->fail('Hoạt động trái phép');
        }
    }

    /**
     * Gửi tin nhắn
     */
    public function push()
    {
        $data = $this->request->postMore([
            ['id', 0],
            ['user_ids', '']
        ]);
        if (!$data['id']) return app('json')->fail('Lỗi tham số');
        $list = $this->services->getWechatNewsItem($data['id']);
        $wechatNews = [];
        if ($list) {
            if (is_array($list['new']) && count($list['new'])) {
                $wechatNews['title'] = $list['new'][0]['title'];
                $wechatNews['image_input'] = $list['new'][0]['image_input'];
                $wechatNews['date'] = date('mngày thứ', time());
                $wechatNews['description'] = $list['new'][0]['synopsis'];
                $wechatNews['id'] = $list['new'][0]['id'];
            }
        }
        if ($data['user_ids'] != '') {//Tin nhắn dịch vụ khách hàng
            $wechatNews = $this->services->wechatPush($wechatNews);
            $message = WechatService::newsMessage($wechatNews);
            $errorLog = [];//Người dùng không gửi được
            $user = $this->services->getWechatUser($data['user_ids'], 'nickname,subscribe,openid', 'uid');
            if ($user) {
                foreach ($user as $v) {
                    if ($v['subscribe'] && $v['openid']) {
                        try {
                            WechatService::staffService()->message($message)->to($v['openid'])->send();
                        } catch (\Exception $e) {
                            Log::error($v['nickname'] . 'Gửi không thành công, lý do' . $e->getMessage());
                            $errorLog[] = $v['nickname'] . 'Gửi không thành công';
                        }
                    } else {
                        $errorLog[] = $v['nickname'] . 'Không gửi sự chú ý không thành công(Không phải là người dùng tài khoản công khai WeChat)';
                    }
                }
                if (!count($errorLog)) {
                    return app('json')->success('Đã gửi thành công');
                } else {
                    return app('json')->fail('Gửi không thành công');
                }
            } else {
                return app('json')->fail('Gửi không thành công');
            }

        }

    }

    /**
     * Gửi danh sách văn bản tin nhắn
     * @return mixed
     */
    public function send_news()
    {
        $where = $this->request->getMore([
            ['cate_name', ''],
            ['page', 1],
            ['limit', 10]
        ]);
        return app('json')->success($this->services->list($where));
    }

}
