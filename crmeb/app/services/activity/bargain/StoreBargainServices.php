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
declare (strict_types=1);

namespace app\services\activity\bargain;

use app\dao\activity\bargain\StoreBargainDao;
use app\jobs\ProductLogJob;
use app\Request;
use app\services\BaseServices;
use app\services\order\StoreOrderServices;
use app\services\other\PosterServices;
use app\services\other\QrcodeServices;
use app\services\product\product\StoreCategoryServices;
use app\services\product\product\StoreDescriptionServices;
use app\services\product\product\StoreProductServices;
use app\services\product\sku\StoreProductAttrResultServices;
use app\services\product\sku\StoreProductAttrServices;
use app\services\product\sku\StoreProductAttrValueServices;
use app\services\system\attachment\SystemAttachmentServices;
use app\services\user\UserServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\ApiException;
use crmeb\services\CacheService;
use crmeb\services\app\MiniProgramService;
use app\services\other\UploadService;
use Guzzle\Http\EntityBody;

/**
 *
 * Class StoreBargainServices
 * @package app\services\activity
 * @method get(int $id, ?array $field) Lấy một phần dữ liệu
 * @method getBargainIdsArray(array $ids, array $field)
 * @method sum(array $where, string $field)
 * @method update(int $id, array $data)
 * @method addBargain(int $id, string $field)
 * @method value(array $where, string $field)
 * @method validWhere()
 * @method getList(array $where, int $page = 0, int $limit = 0) Nhận danh sách mặc cả
 */
class StoreBargainServices extends BaseServices
{

    /**
     * StoreCombinationServices constructor.
     * @param StoreBargainDao $dao
     */
    public function __construct(StoreBargainDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Xác định xem mặt hàng mặc cả có được kích hoạt hay không
     * @param int $bargainId
     * @return int
     */
    public function validBargain($bargainId = 0)
    {
        $where = [];
        $time = time();
        $where[] = ['is_del', '=', 0];
        $where[] = ['status', '=', 1];
        $where[] = ['start_time', '<', $time];
        $where[] = ['stop_time', '>', $time - 85400];
        if ($bargainId) $where[] = ['id', '=', $bargainId];
        return $this->dao->getCount($where);
    }

    /**
     * Nhận danh sách nền
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getStoreBargainList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getList($where, $page, $limit);
        $count = $this->dao->count($where);
        /** @var StoreBargainUserServices $storeBargainUserServices */
        $storeBargainUserServices = app()->make(StoreBargainUserServices::class);
        $ids = array_column($list, 'id');
        $countAll = $storeBargainUserServices->getAllCount([['bargain_id', 'in', $ids]]);
        $countSuccess = $storeBargainUserServices->getAllCount([
            ['status', '=', 3],
            ['bargain_id', 'in', $ids]
        ]);
        /** @var StoreBargainUserHelpServices $storeBargainUserHelpServices */
        $storeBargainUserHelpServices = app()->make(StoreBargainUserHelpServices::class);
        $countHelpAll = $storeBargainUserHelpServices->getHelpAllCount([['bargain_id', 'in', $ids]]);
        $stopIds = [];
        foreach ($list as &$item) {
            $item['count_people_all'] = $countAll[$item['id']] ?? 0;//Số lượng người tham gia
            $item['count_people_help'] = $countHelpAll[$item['id']] ?? 0;//Số người giúp thương lượng giá
            $item['count_people_success'] = $countSuccess[$item['id']] ?? 0;//Số người thương lượng thành công
            $item['stop_status'] = $item['stop_time'] < time() ? 1 : 0;
            if ($item['status']) {
                if ($item['start_time'] > time()) {
                    $item['start_name'] = 'Chưa bắt đầu';
                } else if ($item['stop_time'] < time()) {
                    $item['start_name'] = 'đã kết thúc';
                    $item['status'] = 0;
                    $stopIds[] = $item['id'];
                } else if ($item['stop_time'] > time() && $item['start_time'] < time()) {
                    $item['start_name'] = 'đang tiến hành';
                }
            } else {
                $item['start_name'] = 'đã kết thúc';
            }
            $item['start_time'] = $item['start_time'] ? date('Y-m-d H:i:s', $item['start_time']) : '';
            $item['stop_time'] = $item['stop_time'] ? date('Y-m-d 23:59:59', $item['stop_time']) : '';
        }
        if ($stopIds) {
            $this->dao->batchUpdate($stopIds, ['status' => 0]);
        }
        return compact('list', 'count');
    }

    /**
     * lưu dữ liệu
     * @param int $id
     * @param array $data
     */
    public function saveData(int $id, array $data)
    {
        $description = $data['description'];
        $detail = $data['attrs'];
        $items = $data['items'];
        $data['start_time'] = strtotime($data['section_time'][0]);
        $data['stop_time'] = strtotime($data['section_time'][1]);
        $data['image'] = $data['image'];
        $data['images'] = json_encode($data['images']);
        $data['stock'] = $detail[0]['stock'];
        $data['quota'] = $detail[0]['quota'];
        $data['quota_show'] = $detail[0]['quota'];
        $data['price'] = $detail[0]['price'];
        $data['min_price'] = $detail[0]['min_price'];
        $data['logistics'] = implode(',', $data['logistics']);
        if ($detail[0]['min_price'] < 0 || $detail[0]['price'] <= 0 || $detail[0]['min_price'] === '' || $detail[0]['price'] === '') throw new AdminException('Số tiền không thể ít hơn0');
        if ($detail[0]['min_price'] >= $detail[0]['price']) throw new AdminException('Giá thương lượng thấp nhất không thể lớn hơn hoặc bằng số tiền ban đầu');
        if ($detail[0]['quota'] > $detail[0]['stock']) throw new AdminException('Giới hạn không thể vượt quá số lượng sản phẩm tồn kho');

        //Tính toán số lượng người tối đa cần thiết dựa trên số lượng có thể cắt giảm và xác định xem số lượng người cần thương lượng có lớn hơn số lượng người tối đa cần thiết hay không.
        $bNum = bcmul(bcsub((string)$data['price'], (string)$data['min_price'], 2), '100');
        if ($data['people_num'] > $bNum) throw new AdminException('Số lượng người thương lượng không thể lớn hơn{:num}mọi người', ['num' => $bNum]);

        unset($data['section_time'], $data['description'], $data['attrs'], $data['items'], $detail[0]['min_price'], $detail[0]['_index'], $detail[0]['_rowKey']);
        /** @var StoreDescriptionServices $storeDescriptionServices */
        $storeDescriptionServices = app()->make(StoreDescriptionServices::class);
        /** @var StoreProductAttrServices $storeProductAttrServices */
        $storeProductAttrServices = app()->make(StoreProductAttrServices::class);
        /** @var StoreProductServices $storeProductServices */
        $storeProductServices = app()->make(StoreProductServices::class);
        $this->transaction(function () use ($id, $data, $description, $detail, $items, $storeDescriptionServices, $storeProductAttrServices, $storeProductServices) {
            if ($id) {
                $res = $this->dao->update($id, $data);
                $storeDescriptionServices->saveDescription((int)$id, $description, 2);
                $skuList = $storeProductServices->validateProductAttr($items, $detail, (int)$id, 2);
                $valueGroup = $storeProductAttrServices->saveProductAttr($skuList, (int)$id, 2);
                if (!$res) throw new AdminException('Sửa đổi không thành công');
            } else {
                if (!$storeProductServices->getOne(['is_del' => 0, 'id' => $data['product_id']])) {
                    throw new AdminException('Không thể thêm các mục trong thùng rác');
                }
                $data['add_time'] = time();
                $res = $this->dao->save($data);
                $storeDescriptionServices->saveDescription((int)$res->id, $description, 2);
                $skuList = $storeProductServices->validateProductAttr($items, $detail, (int)$res->id, 2, 1, true);
                $valueGroup = $storeProductAttrServices->saveProductAttr($skuList, (int)$res->id, 2);
                if (!$res) throw new AdminException('Thêm không thành công');
            }
        });
    }

    /**
     * Nhận thông tin chi tiết giá hời
     * @param int $id
     * @return array|\think\Model|null
     */
    public function getInfo(int $id)
    {
        $info = $this->dao->get($id);
        if ($info) {
            if ($info['start_time'])
                $start_time = date('Y-m-d H:i:s', $info['start_time']);

            if ($info['stop_time'])
                $stop_time = date('Y-m-d H:i:s', $info['stop_time']);
            if (isset($start_time) && isset($stop_time))
                $info['section_time'] = [$start_time, $stop_time];
            else
                $info['section_time'] = [];
            unset($info['start_time'], $info['stop_time']);
        }
        $info['give_integral'] = intval($info['give_integral']);
        $info['price'] = floatval($info['price']);
        $info['postage'] = floatval($info['postage']);
        $info['cost'] = floatval($info['cost']);
        $info['bargain_max_price'] = floatval($info['bargain_max_price']);
        $info['bargain_min_price'] = floatval($info['bargain_min_price']);
        $info['min_price'] = floatval($info['min_price']);
        $info['weight'] = floatval($info['weight']);
        $info['volume'] = floatval($info['volume']);
        $info['logistics'] = explode(',', $info['logistics']);
        /** @var StoreDescriptionServices $storeDescriptionServices */
        $storeDescriptionServices = app()->make(StoreDescriptionServices::class);
        $info['description'] = $storeDescriptionServices->getDescription(['product_id' => $id, 'type' => 2]);
        $info['attrs'] = $this->attrList($id, $info['product_id']);
        return $info;
    }

    /**
     * Nhận thông số kỹ thuật
     * @param int $id
     * @param int $pid
     * @return mixed
     */
    public function attrList(int $id, int $pid)
    {
        /** @var StoreProductAttrServices $storeProductAttrService */
        $storeProductAttrService = app()->make(StoreProductAttrServices::class);
        /** @var StoreProductAttrResultServices $storeProductAttrResultServices */
        $storeProductAttrResultServices = app()->make(StoreProductAttrResultServices::class);
        $bargainResult = $storeProductAttrResultServices->value(['product_id' => $id, 'type' => 2], 'result');
        $items = json_decode($bargainResult, true)['attr'];
        $productAttr = $storeProductAttrService->getProductAttr(['product_id' => $pid, 'type' => 0]);
        $pAttr = [];
        foreach ($productAttr as $key => $value) {
            $pAttr[$key]['value'] = $value['attr_name'];
            $pAttr[$key]['detailValue'] = '';
            $pAttr[$key]['attrHidden'] = true;
            $pAttr[$key]['detail'] = $value['attr_values'];
        }

        $bargainAttr = $storeProductAttrService->getProductAttr(['product_id' => $id, 'type' => 3]);
        $bAttr = [];
        foreach ($bargainAttr as $key => $value) {
            $bAttr[$key]['value'] = $value['attr_name'];
            $bAttr[$key]['detailValue'] = '';
            $bAttr[$key]['attrHidden'] = true;
            $bAttr[$key]['detail'] = $value['attr_values'];
        }
        $productAttr = $this->getattr($pAttr, $pid, 0);
        $bargainAttr = $this->getattr($bAttr, $id, 2);
        foreach ($productAttr as $pk => $pv) {
            foreach ($bargainAttr as &$sv) {
                if ($pv['detail'] == $sv['detail']) {
                    $productAttr[$pk] = $sv;
                    $productAttr[$pk]['r_price'] = $pv['price'];
                }
            }
            $productAttr[$pk]['detail'] = json_decode($productAttr[$pk]['detail']);
            $productAttr[$pk]['r_price'] = $productAttr[$pk]['r_price'] ?? $productAttr[$pk]['price'];
        }
        $attrs['items'] = $items;
        $attrs['value'] = $productAttr;
        foreach ($items as $key => $item) {
            $header[] = ['title' => $item['value'], 'key' => 'value' . ($key + 1), 'align' => 'center', 'minWidth' => 80];
        }
        $header[] = ['title' => 'hình ảnh', 'slot' => 'pic', 'align' => 'center', 'minWidth' => 120];
        $header[] = ['title' => 'Số tiền bắt đầu mặc cả', 'slot' => 'price', 'align' => 'center', 'minWidth' => 80];
        $header[] = ['title' => 'Mặc cả giá thấp nhất', 'slot' => 'min_price', 'align' => 'center', 'minWidth' => 80];
        $header[] = ['title' => 'giá thành', 'key' => 'cost', 'align' => 'center', 'minWidth' => 80];
        $header[] = ['title' => 'giá bán hàng ngày', 'key' => 'r_price', 'align' => 'center', 'minWidth' => 80];
        $header[] = ['title' => 'Trong kho', 'key' => 'stock', 'align' => 'center', 'minWidth' => 80];
        $header[] = ['title' => 'phiên bản giới hạn', 'slot' => 'quota', 'align' => 'center', 'minWidth' => 80];
        $header[] = ['title' => 'cân nặng(KG)', 'key' => 'weight', 'align' => 'center', 'minWidth' => 80];
        $header[] = ['title' => 'âm lượng(m³)', 'key' => 'volume', 'align' => 'center', 'minWidth' => 80];
        $header[] = ['title' => 'Mã sản phẩm', 'key' => 'bar_code', 'align' => 'center', 'minWidth' => 80];
        $header[] = ['title' => 'mã vạch', 'key' => 'bar_code_number', 'align' => 'center', 'minWidth' => 80];
        $attrs['header'] = $header;
        return $attrs;
    }

    /**
     * Nhận thông số kỹ thuật
     * @param $attr
     * @param $id
     * @param $type
     * @return array
     */
    public function getattr($attr, $id, $type)
    {
        /** @var StoreProductAttrValueServices $storeProductAttrValueServices */
        $storeProductAttrValueServices = app()->make(StoreProductAttrValueServices::class);
        list($value, $head) = attr_format($attr);
        $valueNew = [];
        $count = 0;
        if ($type == 2) {
            $min_price = $this->dao->value(['id' => $id], 'min_price');
        } else {
            $min_price = 0;
        }
        foreach ($value as $suk) {
            $detail = explode(',', $suk);

            $sukValue = $storeProductAttrValueServices->getColumn(['product_id' => $id, 'type' => $type, 'suk' => $suk], 'bar_code,bar_code_number,cost,price,ot_price,stock,image as pic,weight,volume,brokerage,brokerage_two,quota', 'suk');
            if (count($sukValue)) {
                foreach ($detail as $k => $v) {
                    $valueNew[$count]['value' . ($k + 1)] = $v;
                }
                $valueNew[$count]['detail'] = json_encode(array_combine($head, $detail));
                $valueNew[$count]['pic'] = $sukValue[$suk]['pic'] ?? '';
                $valueNew[$count]['price'] = $sukValue[$suk]['price'] ? floatval($sukValue[$suk]['price']) : 0;
                $valueNew[$count]['min_price'] = $min_price ? floatval($min_price) : 0;
                $valueNew[$count]['cost'] = $sukValue[$suk]['cost'] ? floatval($sukValue[$suk]['cost']) : 0;
                $valueNew[$count]['ot_price'] = isset($sukValue[$suk]['ot_price']) ? floatval($sukValue[$suk]['ot_price']) : 0;
                $valueNew[$count]['stock'] = $sukValue[$suk]['stock'] ? intval($sukValue[$suk]['stock']) : 0;
                $valueNew[$count]['quota'] = $sukValue[$suk]['quota'] ? intval($sukValue[$suk]['quota']) : 0;
                $valueNew[$count]['bar_code'] = $sukValue[$suk]['bar_code'] ?? '';
                $valueNew[$count]['bar_code_number'] = $sukValue[$suk]['bar_code_number'] ?? '';
                $valueNew[$count]['weight'] = $sukValue[$suk]['weight'] ? floatval($sukValue[$suk]['weight']) : 0;
                $valueNew[$count]['volume'] = $sukValue[$suk]['volume'] ? floatval($sukValue[$suk]['volume']) : 0;
                $valueNew[$count]['brokerage'] = $sukValue[$suk]['brokerage'] ? floatval($sukValue[$suk]['brokerage']) : 0;
                $valueNew[$count]['brokerage_two'] = $sukValue[$suk]['brokerage_two'] ? floatval($sukValue[$suk]['brokerage_two']) : 0;
                $valueNew[$count]['opt'] = $type != 0;
                $count++;
            }
        }
        return $valueNew;
    }

//    /**
//     * TODO Nhận bảng giáID
//     * @param int $bargainId $bargainId mặt hàng giá hời
//     * @param int $bargainUserUid $bargainUserUid  Kích hoạt ID người dùng thương lượng
//     * @param int $status $status  Trạng thái thương lượng 1 Đang tham gia 2 Việc tham gia không thành công khi kết thúc sự kiện 3 Tham gia thành công khi kết thúc sự kiện
//     * @return mixed
//     */
//    public function getBargainUserTableId($bargainId = 0, $bargainUserUid = 0)
//    {
//        return $this->dao->value(['bargain_id' => $bargainId, 'uid' => $bargainUserUid, 'is_del' => 0], 'id');
//    }

//    /**
//     * TODO Nhận mức giá mà người dùng có thể cắt giảm
//     * @param $id $id Số bảng thương lượng sự tham gia của người dùng
//     * @return float
//     * @throws \think\db\exception\DataNotFoundException
//     * @throws \think\db\exception\ModelNotFoundException
//     * @throws \think\exception\DbException
//     */
//    public function getBargainUserDiffPriceFloat($id)
//    {
//        $price = $this->dao->get($id, ['bargain_price,bargain_price_min']);
//        return (float)bcsub($price['bargain_price'], $price['bargain_price_min'], 2);
//    }

//    /**
//     * TODO Nhận được mức giá giảm bởi người dùng
//     * @param int $id $id Số bảng thương lượng sự tham gia của người dùng
//     * @return float
//     */
//    public function getBargainUserPrice($id = 0)
//    {
//        return (float)$this->dao->value(['id' => $id], 'price');
//    }

//    /**
//     * Nhận được hàng giá hời
//     * @param int $bargainId
//     * @param string $field
//     * @return array
//     */
//    public function getBargainOne($bargainId = 0, $field = 'id,product_id,title,price,min_price,image')
//    {
//        if (!$bargainId) return [];
//        $bargain = $this->dao->getOne(['id' => $bargainId], $field);
//        if ($bargain) return $bargain->toArray();
//        else return [];
//    }

    /**
     * Danh sách mặc cả
     * @return array
     */
    public function getBargainList()
    {
        /** @var StoreBargainUserServices $bargainUserService */
        $bargainUserService = app()->make(StoreBargainUserServices::class);
        [$page, $limit] = $this->getPageValue();
        $field = 'id,product_id,title,min_price,image,price';
        $list = $this->dao->BargainList($page, $limit, $field);
        foreach ($list as &$item) {
            $item['people'] = $bargainUserService->getUserIdList($item['id']);
            $item['price'] = floatval($item['price']);
            $item['product_price'] = floatval($item['product_price']);
        }
        return $list;
    }

    /**
     * Thiết kế trang phụ trợ để có được danh sách thương lượng
     * @param $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getDiyBargainList($where)
    {
        $where['status'] = 1;
        unset($where['is_show']);
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->DiyBargainList($where, $page, $limit);
        $count = $this->dao->getCount($where);
        $cateIds = implode(',', array_column($list, 'cate_id'));
        /** @var StoreCategoryServices $storeCategoryServices */
        $storeCategoryServices = app()->make(StoreCategoryServices::class);
        $cateList = $storeCategoryServices->getCateArray($cateIds);
        foreach ($list as &$item) {
            $cateName = array_filter($cateList, function ($val) use ($item) {
                if (in_array($val['id'], explode(',', $item['cate_id']))) {
                    return $val;
                }
            });
            $item['cate_name'] = implode(',', array_column($cateName, 'cate_name'));
            $item['store_name'] = $item['title'];
            $item['price'] = floatval($item['price']);
            $item['is_product_type'] = 1;
        }
        return compact('count', 'list');
    }

    /**
     * Các mặt hàng giá hời tại nhà
     * @param $where
     * @return array
     */
    public function getHomeList($where)
    {
        [$page, $limit] = $this->getPageValue();
        $where['is_del'] = 0;
        $where['is_show'] = 1;
        $data = [];
        $list = $this->dao->getHomeList($where, $page, $limit);
        foreach ($list as &$item) {
            $item['price'] = floatval($item['price']);
        }
        $data['list'] = $list;
        return $data;
    }

    /**
     * Nhận chi tiết thương lượng ở mặt trước
     * @param Request $request
     * @param int $id
     * @param int $bargainUid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getBargain(Request $request, int $id, int $bargainUid)
    {
        /** @var StoreProductAttrServices $storeProductAttrServices */
        $storeProductAttrServices = app()->make(StoreProductAttrServices::class);
        /** @var StoreOrderServices $orderService */
        $orderService = app()->make(StoreOrderServices::class);
        /** @var StoreBargainUserServices $bargainUserService */
        $bargainUserService = app()->make(StoreBargainUserServices::class);

        //Nhận thông tin sản phẩm giá hời
        $bargain = $this->dao->getOne(['id' => $id], '*', ['description']);
        if (!$bargain) throw new ApiException('Sản phẩm giá hời không tồn tại');
        if ($bargain['stop_time'] < time()) throw new ApiException('Cuộc thương lượng đã kết thúc');
        list($productAttr, $productValue) = $storeProductAttrServices->getProductAttrDetail($id, $request->uid(), 0, 2, $bargain['product_id']);
        foreach ($productValue as $v) {
            $bargain['attr'] = $v;
        }
        $bargain['time'] = time();
        $bargain = get_thumb_water($bargain);
        $bargain['small_image'] = $bargain['image'];
        $data['bargain'] = $bargain;

        //Viết xem và chia sẻ dữ liệu
        $this->dao->addBargain($id, 'look');

        //Dữ liệu người dùng
        $user = $request->user();
        $data['userInfo']['uid'] = $user['uid'];
        $data['userInfo']['nickname'] = $user['nickname'];
        $data['userInfo']['avatar'] = $user['avatar'];

        //Dữ liệu mặc cả
        $userBargainInfo = $bargainUserService->helpCount($request, $id, $bargainUid);
        //Tổng số lệnh thương lượng mà người dùng đã tạo
        $userBargainInfo['bargainOrderCount'] = $orderService->count(['bargain_id' => $id, 'uid' => $user['uid']]);
        //Tổng số người dùng thương lượng
        $userBargainInfo['bargainCount'] = $bargainUserService->count(['bargain_id' => $id, 'uid' => $user['uid'], 'is_del' => 0]);
        //Xác định tình trạng thương lượng
        if (($userBargainInfo['bargainCount'] == 0 || $userBargainInfo['bargainCount'] == $userBargainInfo['bargainOrderCount']) //Không có thương lượng nào được bắt đầu hoặc số lượng thương lượng bắt đầu bằng với số lượng đặt hàng của sản phẩm thương lượng tương ứng.
            && $bargain['people_num'] > $userBargainInfo['bargainCount'] //Số lượng món hời có thể được bắt đầu cho sản phẩm này lớn hơn số lượng món hời đã được bắt đầu.
            && $userBargainInfo['price'] > 0 //Số tiền còn lại lớn hơn0
            && $request->uid() == $bargainUid) { //Bạn đã tự mình mặc cả
            $userBargainInfo['bargainType'] = 1; //Người dùng bắt đầu thương lượng giá
        } elseif ($userBargainInfo['bargainCount'] > $userBargainInfo['bargainOrderCount'] //Số lượng thương lượng bắt đầu lớn hơn số lượng đơn đặt hàng được tạo ra.
            && $userBargainInfo['price'] > 0 //Số tiền còn lại lớn hơn0
            && $request->uid() == $bargainUid) { //Bạn đã tự mình mặc cả
            $userBargainInfo['bargainType'] = 2; //Gửi lời mời cho bạn bè để mặc cả
        } elseif ($userBargainInfo['userBargainStatus'] //Người dùng có thể mặc cả
            && $userBargainInfo['price'] > 0 //Số tiền còn lại lớn hơn0
            && $request->uid() != $bargainUid) { //Không phải sự mặc cả của riêng tôi
            $userBargainInfo['bargainType'] = 3; //Mặc cả cho bạn bè
        } elseif ($userBargainInfo['userBargainStatus'] //Người dùng có thể mặc cả
            && $userBargainInfo['price'] == 0 //Số tiền còn lại lớn hơn0
            && $request->uid() != $bargainUid) { //Không phải sự mặc cả của riêng tôi
            $userBargainInfo['bargainType'] = 4; //Bạn bè đã hoàn thành
        } elseif (!$userBargainInfo['userBargainStatus'] //Người dùng không thể mặc cả
            && $request->uid() != $bargainUid) { //Không phải sự mặc cả của riêng tôi
            $userBargainInfo['bargainType'] = 5; //Đã giúp một người bạn mặc cả
        } elseif ($userBargainInfo['price'] == 0 //Số tiền còn lại bằng0
            && $request->uid() == $bargainUid //Bạn đã tự mình mặc cả
            && $userBargainInfo['status'] != 3) { //Đơn hàng không được tạo
            $userBargainInfo['bargainType'] = 6; //Thanh toán ngay
        } else {
            $userBargainInfo['bargainType'] = 1; //Thanh toán ngay
        }
        $data['userBargainInfo'] = $userBargainInfo;
        $data['bargain']['price'] = bcsub($data['bargain']['price'], (string)$userBargainInfo['alreadyPrice'], 2);
        $data['bargain']['product_is_show'] = app()->make(StoreProductServices::class)->value($data['bargain']['product_id'], 'is_show');

        //Sự kiện truy cập của người dùng
        event('UserVisitListener', [$user['uid'], $id, 'bargain', $bargain['product_id'], 'view']);

        //Lịch sử duyệt web
        ProductLogJob::dispatch(['visit', ['uid' => $user['uid'], 'product_id' => $bargain['product_id']]]);
        return $data;
    }

    /**
     * Xác minh xem giá thương lượng có thể được thanh toán hay không
     * @param int $bargainId
     * @param int $uid
     */
    public function checkBargainUser(int $bargainId, int $uid)
    {
        /** @var StoreBargainUserServices $bargainUserServices */
        $bargainUserServices = app()->make(StoreBargainUserServices::class);
        $bargainUserInfo = $bargainUserServices->getOne(['uid' => $uid, 'bargain_id' => $bargainId, 'status' => 1, 'is_del' => 0]);
        if (!$bargainUserInfo)
            throw new ApiException('Thương lượng thất bại');
        $bargainUserTableId = $bargainUserInfo['id'];
        if ($bargainUserInfo['bargain_price_min'] < bcsub((string)$bargainUserInfo['bargain_price'], (string)$bargainUserInfo['price'], 2)) {
            throw new ApiException('Thương lượng không thành công');
        }
        if ($bargainUserInfo['status'] == 3)
            throw new ApiException('Món hời đã được trả');
        /** @var StoreProductAttrValueServices $attrValueServices */
        $attrValueServices = app()->make(StoreProductAttrValueServices::class);
        $res = $attrValueServices->getOne(['product_id' => $bargainId, 'type' => 2]);
        if (!$this->validBargain($bargainId) || !$res) {
            throw new ApiException('Sản phẩm này đã bị gỡ bỏ khỏi kệ hoặc bị xóa');
        }
        $StoreBargainInfo = $this->dao->get($bargainId);
        if (1 > $res['quota']) {
            throw new ApiException('Sản phẩm này đã hết hàng');
        }
        $product_stock = $attrValueServices->value(['product_id' => $StoreBargainInfo['product_id'], 'suk' => $res['suk'], 'type' => 0], 'stock');
        if ($product_stock < 1) {
            throw new ApiException('Sản phẩm này đã hết hàng');
        }
        //Sửa đổi trạng thái thương lượng
        $this->setBargainUserStatus($bargainId, $uid, $bargainUserTableId);
        return true;
    }

    /**
     * Sửa đổi trạng thái thương lượng
     * @param int $bargainId
     * @param int $uid
     * @param int $bargainUserTableId
     * @return bool|\crmeb\basic\BaseModel
     */
    public function setBargainUserStatus(int $bargainId, int $uid, int $bargainUserTableId)
    {
        if (!$bargainId || !$uid) return false;
        if (!$bargainUserTableId) return false;
        /** @var StoreBargainUserServices $bargainUserServices */
        $bargainUserServices = app()->make(StoreBargainUserServices::class);
        $count = $bargainUserServices->count(['id' => $bargainUserTableId, 'uid' => $uid, 'bargain_id' => $bargainId, 'status' => 1]);
        if (!$count) return false;
        $userPrice = $bargainUserServices->value(['id' => $bargainUserTableId, 'uid' => $uid, 'bargain_id' => $bargainId, 'status' => 1], 'price');
        $price = $bargainUserServices->get($bargainUserTableId, ['bargain_price', 'bargain_price_min']);
        $price = bcsub($price['bargain_price'], $price['bargain_price_min'], 2);
        if (bcsub($price, $userPrice, 2) > 0) {
            return false;
        }
        return $bargainUserServices->updateBargainStatus($bargainUserTableId);
    }

    /**
     * Bắt đầu thương lượng
     * @param int $uid
     * @param int $bargainId
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setBargain(int $uid, int $bargainId)
    {
        if (!$bargainId) throw new ApiException('Hoạt động trái phép');
        $bargainInfo = $this->dao->getOne([
            ['is_del', '=', 0],
            ['status', '=', 1],
            ['start_time', '<', time()],
            ['stop_time', '>', time()],
            ['id', '=', $bargainId],
        ]);
        if (!$bargainInfo) throw new ApiException('Cuộc thương lượng đã kết thúc');
        $bargainInfo = $bargainInfo->toArray();
        /** @var StoreBargainUserServices $bargainUserService */
        $bargainUserService = app()->make(StoreBargainUserServices::class);
        $count = $bargainUserService->count(['bargain_id' => $bargainId, 'uid' => $uid, 'is_del' => 0, 'status' => 1]);
        if ($count === false) {
            throw new ApiException('Hoạt động trái phép');
        } else {
            /** @var StoreBargainUserHelpServices $bargainUserHelpService */
            $bargainUserHelpService = app()->make(StoreBargainUserHelpServices::class);
            $count = $bargainUserService->count(['uid' => $uid, 'bargain_id' => $bargainId, 'is_del' => 0]);
            if ($count >= $bargainInfo['num']) throw new ApiException('Bạn không còn có thể bắt đầu mặc cả giá cho mặt hàng này');
            return $this->transaction(function () use ($bargainUserService, $bargainUserHelpService, $bargainId, $uid, $bargainInfo) {
                $bargainUserInfo = $bargainUserService->setBargain($bargainId, $uid, $bargainInfo);
                $price = $bargainUserHelpService->setBargainRecord($uid, $bargainUserInfo->toArray(), $bargainInfo);
                return ['bargainUserInfo' => $bargainUserInfo, 'price' => $price];
            });
        }
    }

    /**
     * Tham gia thương lượng
     * @param int $uid
     * @param int $bargainId
     * @param int $bargainUserUid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setHelpBargain(int $uid, int $bargainId, int $bargainUserUid)
    {
        if (!$bargainId || !$bargainUserUid) throw new ApiException('Lỗi tham số');
        $bargainInfo = $this->dao->getOne([
            ['is_del', '=', 0],
            ['status', '=', 1],
            ['start_time', '<', time()],
            ['stop_time', '>', time()],
            ['id', '=', $bargainId],
        ]);
        if (!$bargainInfo) throw new ApiException('Cuộc thương lượng đã kết thúc');
        $bargainInfo = $bargainInfo->toArray();
        /** @var StoreBargainUserHelpServices $userHelpService */
        $userHelpService = app()->make(StoreBargainUserHelpServices::class);
        /** @var StoreBargainUserServices $bargainUserService */
        $bargainUserService = app()->make(StoreBargainUserServices::class);
        $bargainUserTableId = $bargainUserService->getBargainUserTableId((int)$bargainId, (int)$bargainUserUid);
        if (!$bargainUserTableId) throw new ApiException('Mặc cả không được kích hoạt cho chia sẻ này');
        $bargainUserInfo = $bargainUserService->get($bargainUserTableId)->toArray();
        $count = $userHelpService->isBargainUserHelpCount($bargainId, $bargainUserTableId, $uid);
        if (!$count) throw new ApiException('Bạn đã thực hiện việc thương lượng này');
        $price = $userHelpService->setBargainRecord($uid, $bargainUserInfo, $bargainInfo);
        if ($price) {
            if (!$bargainUserService->getSurplusPrice($bargainUserTableId, 1)) {
                event('NoticeListener', [['uid' => $bargainUserUid, 'bargainInfo' => $bargainInfo, 'bargainUserInfo' => $bargainUserInfo,], 'bargain_success']);
            }
        }
        return ['bargainUserInfo' => $bargainUserInfo, 'price' => $price];
    }

    /**
     * Giảm hàng tồn kho và tăng doanh số bán hàng
     * @param int $num
     * @param int $bargainId
     * @param string $unique
     * @return bool
     */
    public function decBargainStock(int $num, int $bargainId, string $unique)
    {
        $product_id = $this->dao->value(['id' => $bargainId], 'product_id');
        if ($unique) {
            /** @var StoreProductAttrValueServices $skuValueServices */
            $skuValueServices = app()->make(StoreProductAttrValueServices::class);
            //Trừ đi hàng tồn kho của các SKU sản phẩm giá hời để tăng doanh số bán hàng
            $res = false !== $skuValueServices->decProductAttrStock($bargainId, $unique, $num, 2);
            //Trừ đi hàng tồn kho và doanh thu của các mặt hàng giá hời
            $res = $res && $this->dao->decStockIncSales(['id' => $bargainId, 'type' => 2], $num);
            //Trừ đi hàng tồn kho cộng với doanh thu của mã sản phẩm thông thường
            $suk = $skuValueServices->value(['unique' => $unique, 'product_id' => $bargainId], 'suk');
            $productUnique = $skuValueServices->value(['suk' => $suk, 'product_id' => $product_id, 'type' => 0], 'unique');
            if ($productUnique) {
                $res = $res && $skuValueServices->decProductAttrStock($product_id, $productUnique, $num);
            }
        } else {
            //Trừ đi hàng tồn kho và doanh thu của các mặt hàng giá hời
            $res = false !== $this->dao->decStockIncSales(['id' => $bargainId, 'type' => 2], $num);
        }
        /** @var StoreProductServices $services */
        $services = app()->make(StoreProductServices::class);
        //Trừ đi hàng tồn kho thông thường cộng với doanh thu
        $res = $res && $services->decProductStock($num, $product_id);
        return $res;
    }

    /**
     * Giảm doanh số bán hàng và tăng hàng tồn kho
     * @param int $num
     * @param int $bargainId
     * @param string $unique
     * @return bool
     */
    public function incBargainStock(int $num, int $bargainId, string $unique)
    {
        $product_id = $this->dao->value(['id' => $bargainId], 'product_id');
        if ($unique) {
            /** @var StoreProductAttrValueServices $skuValueServices */
            $skuValueServices = app()->make(StoreProductAttrValueServices::class);
            //Trừ đi doanh số bán hàng của sản phẩm giá hời,Tăng hàng tồn kho và số lượng mua hạn chế
            $res = false !== $skuValueServices->incProductAttrStock($bargainId, $unique, $num, 2);
            //Trừ đi doanh số bán hàng giá hời,tăng hàng tồn kho
            $res = $res && $this->dao->incStockDecSales(['id' => $bargainId, 'type' => 2], $num);
            //Giảm khối lượng bán hàng của mã sản phẩm thông thường,tăng hàng tồn kho
            $suk = $skuValueServices->value(['unique' => $unique, 'product_id' => $bargainId], 'suk');
            $productUnique = $skuValueServices->value(['suk' => $suk, 'product_id' => $product_id], 'unique');
            if ($productUnique) {
                $res = $res && $skuValueServices->incProductAttrStock($product_id, $productUnique, $num);
            }
        } else {
            //Trừ đi doanh số bán hàng giá hời,tăng hàng tồn kho
            $res = false !== $this->dao->incStockDecSales(['id' => $bargainId, 'type' => 2], $num);
        }
        /** @var StoreProductServices $services */
        $services = app()->make(StoreProductServices::class);
        //Trừ đi hàng tồn kho thông thường cộng với doanh thu
        $res = $res && $services->incProductStock($num, $product_id);
        return $res;
    }

    /**
     * Thương lượng và chia sẻ
     * @param $bargainId
     * @param $user
     * @return bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function poster($bargainId, $user, $from)
    {
        $storeBargainInfo = $this->dao->get($bargainId, ['title', 'image', 'price']);
        if (!$storeBargainInfo) {
            throw new ApiException('Không tìm thấy thông tin thương lượng');
        }
        /** @var StoreBargainUserServices $services */
        $services = app()->make(StoreBargainUserServices::class);
        $bargainUser = $services->get(['bargain_id' => $bargainId, 'uid' => $user['uid']], ['price', 'bargain_price_min']);
        if (!$bargainUser) {
            throw new ApiException('Không tìm thấy thông tin thương lượng của người dùng');
        }
        try {
            $siteUrl = sys_config('site_url');
            $data['title'] = $storeBargainInfo['title'];
            $data['image'] = $storeBargainInfo['image'];
            $data['price'] = bcsub($storeBargainInfo['price'], $bargainUser['price'], 2);
            $data['label'] = 'Đã bị cắt thành';
            $price = bcsub($storeBargainInfo['price'], $bargainUser['price'], 2);
            $data['msg'] = 'Không đủ tốt' . (bcsub($price, $bargainUser['bargain_price_min'], 2)) . 'Bạn có thể mặc cả thành công chỉ với một nhân dân tệ';
            /** @var SystemAttachmentServices $systemAttachmentServices */
            $systemAttachmentServices = app()->make(SystemAttachmentServices::class);
            if ($from == 'wechat') {
                $name = $bargainId . '_' . $user['uid'] . '_' . $user['is_promoter'] . '_bargain_share_wap.jpg';
                //Tài khoản chính thức
                $imageInfo = $systemAttachmentServices->getInfo(['name' => $name]);
                if (!$imageInfo) {
                    $codeUrl = set_http_type($siteUrl . '/pages/activity/goods_bargain_details/index?id=' . $bargainId . '&bargain=' . $user['uid'] . '&spread=' . $user['uid'], 1);//Liên kết mã QR
                    $imageInfo = PosterServices::getQRCodePath($codeUrl, $name);
                    if (is_string($imageInfo)) {
                        throw new ApiException('Tạo mã QR không thành công');
                    }
                    $systemAttachmentServices->save([
                        'name' => $imageInfo['name'],
                        'att_dir' => $imageInfo['dir'],
                        'satt_dir' => $imageInfo['thumb_path'],
                        'att_size' => $imageInfo['size'],
                        'att_type' => $imageInfo['type'],
                        'image_type' => $imageInfo['image_type'],
                        'module_type' => 2,
                        'time' => $imageInfo['time'],
                        'pid' => 1,
                        'type' => 1
                    ]);
                    $url = $imageInfo['dir'];
                } else $url = $imageInfo['att_dir'];
                $data['url'] = $url;
                if ($imageInfo['image_type'] == 1) $data['url'] = $siteUrl . $url;
                $posterImage = PosterServices::setShareMarketingPoster($data, 'wap/activity/bargain/poster');
                if (!is_array($posterImage)) {
                    throw new ApiException('Không tạo được áp phích');
                }
                $systemAttachmentServices->save([
                    'name' => $posterImage['name'],
                    'att_dir' => $posterImage['dir'],
                    'satt_dir' => $posterImage['thumb_path'],
                    'att_size' => $posterImage['size'],
                    'att_type' => $posterImage['type'],
                    'image_type' => $posterImage['image_type'],
                    'module_type' => 2,
                    'time' => $posterImage['time'],
                    'pid' => 1,
                    'type' => 1
                ]);
                if ($posterImage['image_type'] == 1) $posterImage['dir'] = $siteUrl . $posterImage['dir'];
                $wapPosterImage = set_http_type($posterImage['dir'], 1);//Áp phích quảng cáo tài khoản công cộng
                return $wapPosterImage;
            } else {
                //Chương trình nhỏ
                $name = $bargainId . '_' . $user['uid'] . '_' . $user['is_promoter'] . '_bargain_share_routine.jpg';
                $imageInfo = $systemAttachmentServices->getInfo(['name' => $name]);
                if (!$imageInfo) {
                    $valueData = 'id=' . $bargainId . '&bargain=' . $user['uid'];
                    /** @var UserServices $userServices */
                    $userServices = app()->make(UserServices::class);
                    if ($userServices->checkUserPromoter((int)$user['uid'], $user)) {
                        $valueData .= '&spread=' . $user['uid'];
                    }
                    $res = MiniProgramService::appCodeUnlimitService($valueData, 'pages/activity/goods_bargain_details/index', 280);
                    if (!$res) throw new ApiException('Tạo mã QR không thành công');
                    $uploadType = (int)sys_config('upload_type', 1);
                    $upload = UploadService::init();
                    $res = (string)EntityBody::factory($res);
                    $res = $upload->to('routine/activity/bargain/code')->validate()->setAuthThumb(false)->stream($res, $name);
                    if ($res === false) {
                        throw new ApiException($upload->getError());
                    }
                    $imageInfo = $upload->getUploadInfo();
                    $imageInfo['image_type'] = $uploadType;
                    if ($imageInfo['image_type'] == 1) $remoteImage = PosterServices::remoteImage($siteUrl . $imageInfo['dir']);
                    else $remoteImage = PosterServices::remoteImage($imageInfo['dir']);
                    if (!$remoteImage['status']) throw new ApiException('Tạo mã QR không thành công');
                    $systemAttachmentServices->save([
                        'name' => $imageInfo['name'],
                        'att_dir' => $imageInfo['dir'],
                        'satt_dir' => $imageInfo['thumb_path'],
                        'att_size' => $imageInfo['size'],
                        'att_type' => $imageInfo['type'],
                        'image_type' => $imageInfo['image_type'],
                        'module_type' => 2,
                        'time' => time(),
                        'pid' => 1,
                        'type' => 1
                    ]);
                    $url = $imageInfo['dir'];
                } else $url = $imageInfo['att_dir'];
                $data['url'] = $url;
                if ($imageInfo['image_type'] == 1)
                    $data['url'] = $siteUrl . $url;
                $posterImage = PosterServices::setShareMarketingPoster($data, 'routine/activity/bargain/poster');
                if (!is_array($posterImage)) throw new ApiException('Không tạo được áp phích');
                $systemAttachmentServices->save([
                    'name' => $posterImage['name'],
                    'att_dir' => $posterImage['dir'],
                    'satt_dir' => $posterImage['thumb_path'],
                    'att_size' => $posterImage['size'],
                    'att_type' => $posterImage['type'],
                    'image_type' => $posterImage['image_type'],
                    'module_type' => 2,
                    'time' => $posterImage['time'],
                    'pid' => 1,
                    'type' => 1
                ]);
                if ($posterImage['image_type'] == 1) $posterImage['dir'] = $siteUrl . $posterImage['dir'];
                $routinePosterImage = set_http_type($posterImage['dir'], 0);//Poster quảng cáo chương trình nhỏ
                return $routinePosterImage;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Nhận thông tin poster giá hời
     * @param int $bargainId
     * @param $user
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function posterInfo(int $bargainId, $user)
    {
        $storeBargainInfo = $this->dao->get($bargainId, ['title', 'image', 'price']);
        if (!$storeBargainInfo) {
            throw new ApiException('Không tìm thấy thông tin thương lượng');
        }
        /** @var StoreBargainUserServices $services */
        $services = app()->make(StoreBargainUserServices::class);
        $bargainUser = $services->get(['bargain_id' => $bargainId, 'uid' => $user['uid'], 'status' => 1], ['price', 'bargain_price_min']);
        if (!$bargainUser) {
            throw new ApiException('Không tìm thấy thông tin thương lượng của người dùng');
        }
        $data['url'] = '';
        $data['title'] = $storeBargainInfo['title'];
        $data['image'] = $storeBargainInfo['image'];
        $data['price'] = bcsub($storeBargainInfo['price'], $bargainUser['price'], 2);
        $data['label'] = 'Đã bị cắt thành';
        $price = bcsub($storeBargainInfo['price'], $bargainUser['price'], 2);
        $data['msg'] = 'Không đủ tốt' . (bcsub($price, $bargainUser['bargain_price_min'], 2)) . 'Bạn có thể mặc cả thành công chỉ với một nhân dân tệ';
        //Chỉ trong chương trình mini, mã QR sẽ được tạo
        if (\request()->isRoutine()) {
            try {
                /** @var SystemAttachmentServices $systemAttachmentServices */
                $systemAttachmentServices = app()->make(SystemAttachmentServices::class);
                //Chương trình nhỏ
                $name = $bargainId . '_' . $user['uid'] . '_' . $user['is_promoter'] . '_bargain_share_routine.jpg';
                $siteUrl = sys_config('site_url');
                $imageInfo = $systemAttachmentServices->getInfo(['name' => $name]);
                if (!$imageInfo) {
                    $valueData = 'id=' . $bargainId . '&bargain=' . $user['uid'];
                    /** @var UserServices $userServices */
                    $userServices = app()->make(UserServices::class);
                    if ($userServices->checkUserPromoter((int)$user['uid'], $user)) {
                        $valueData .= '&spread=' . $user['uid'];
                    }
                    $res = MiniProgramService::appCodeUnlimitService($valueData, 'pages/activity/goods_bargain_details/index', 280);
                    if (!$res) throw new ApiException('Tạo mã QR không thành công');
                    $uploadType = (int)sys_config('upload_type', 1);
                    $upload = UploadService::init();
                    $res = (string)EntityBody::factory($res);
                    $res = $upload->to('routine/activity/bargain/code')->validate()->setAuthThumb(false)->stream($res, $name);
                    if ($res === false) {
                        throw new ApiException($upload->getError());
                    }
                    $imageInfo = $upload->getUploadInfo();
                    $imageInfo['image_type'] = $uploadType;
                    if ($imageInfo['image_type'] == 1) $remoteImage = PosterServices::remoteImage($siteUrl . $imageInfo['dir']);
                    else $remoteImage = PosterServices::remoteImage($imageInfo['dir']);
                    if (!$remoteImage['status']) throw new ApiException($remoteImage['msg']);
                    $systemAttachmentServices->save([
                        'name' => $imageInfo['name'],
                        'att_dir' => $imageInfo['dir'],
                        'satt_dir' => $imageInfo['thumb_path'],
                        'att_size' => $imageInfo['size'],
                        'att_type' => $imageInfo['type'],
                        'image_type' => $imageInfo['image_type'],
                        'module_type' => 2,
                        'time' => time(),
                        'pid' => 1,
                        'type' => 1
                    ]);
                    $url = $imageInfo['dir'];
                } else $url = $imageInfo['att_dir'];
                if ($imageInfo['image_type'] == 1) {
                    $data['url'] = $siteUrl . $url;
                } else {
                    $data['url'] = $url;
                }
            } catch (\Throwable $e) {
            }
        } else {
            if (sys_config('share_qrcode', 0) && request()->isWechat()) {
                /** @var QrcodeServices $qrcodeService */
                $qrcodeService = app()->make(QrcodeServices::class);
                $data['url'] = $qrcodeService->getTemporaryQrcode('bargain-' . $bargainId . '-' . $user['uid'], $user['uid'])->url;
            }
        }
        return $data;
    }

    /**
     * Xác minh giới hạn tồn kho cho các đơn hàng mặc cả
     * @param int $uid
     * @param int $bargainId
     * @param int $cartNum
     * @param string $unique
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function checkBargainStock(int $uid, int $bargainId, int $cartNum = 1, string $unique = '')
    {
        if (!$this->validBargain($bargainId)) {
            throw new ApiException('Sản phẩm này đã bị gỡ bỏ khỏi kệ hoặc bị xóa');
        }
        /** @var StoreProductAttrValueServices $attrValueServices */
        $attrValueServices = app()->make(StoreProductAttrValueServices::class);
        $attrInfo = $attrValueServices->getOne(['product_id' => $bargainId, 'type' => 2]);
        if (!$attrInfo || $attrInfo['product_id'] != $bargainId) {
            throw new ApiException('Vui lòng chọn thuộc tính sản phẩm hợp lệ');
        }
        $productInfo = $this->dao->get($bargainId, ['*', 'title as store_name']);
        /** @var StoreBargainUserServices $bargainUserService */
        $bargainUserService = app()->make(StoreBargainUserServices::class);
        $bargainUserInfo = $bargainUserService->getOne(['uid' => $uid, 'bargain_id' => $bargainId, 'status' => 1, 'is_del' => 0]);
        if ($bargainUserInfo['bargain_price_min'] < bcsub((string)$bargainUserInfo['bargain_price'], (string)$bargainUserInfo['price'], 2)) {
            throw new ApiException('Giá mặc cả không thể thấp hơn giá thấp nhất');
        }
        $unique = $attrInfo['unique'];
        if ($cartNum > $attrInfo['quota']) {
            throw new ApiException('Sản phẩm này đã hết hàng');
        }
        return [$attrInfo, $unique, $productInfo, $bargainUserInfo];
    }

    /**
     * Thống kê mặc cả
     * @param $id
     * @return array
     */
    public function bargainStatistics($id)
    {
        /** @var StoreBargainUserServices $bargainUser */
        $bargainUser = app()->make(StoreBargainUserServices::class);
        /** @var StoreBargainUserHelpServices $bargainUserHelp */
        $bargainUserHelp = app()->make(StoreBargainUserHelpServices::class);
        /** @var StoreOrderServices $orderServices */
        $orderServices = app()->make(StoreOrderServices::class);
        $people_count = $bargainUserHelp->count(['bargain_id' => $id]);
        $spread_count = $bargainUserHelp->count(['bargain_id' => $id, 'type' => 0]);
        $start_count = $bargainUser->count(['bargain_id' => $id]);
        $success_count = $bargainUser->count(['bargain_id' => $id, 'status' => 3]);
        $pay_price = $orderServices->sum([['bargain_id', '=', $id], ['paid', '=', 1], ['refund_type', 'in', [0, 3]], ['is_del', '=', 0]], 'pay_price', false);
        $pay_count = $orderServices->getDistinctCount([['bargain_id', '=', $id], ['paid', '=', 1], ['refund_type', 'in', [0, 3]], ['is_del', '=', 0]], 'uid', false);
        $pay_rate = $start_count > 0 ? bcmul(bcdiv((string)$pay_count, (string)$start_count, 2), '100', 2) : 0;
        return compact('people_count', 'spread_count', 'start_count', 'success_count', 'pay_price', 'pay_count', 'pay_rate');
    }

    /**
     * Danh sách mặc cả
     * @param $id
     * @param array $where
     * @return array
     */
    public function bargainStatisticsList($id, $where = [])
    {
        /** @var StoreBargainUserServices $bargainUser */
        $bargainUser = app()->make(StoreBargainUserServices::class);
        $where['bargain_id'] = $id;
        return $bargainUser->bargainUserList($where);
    }

    /**
     * lệnh mặc cả
     * @param $id
     * @param array $where
     * @return array
     */
    public function bargainStatisticsOrder($id, $where = [])
    {
        /** @var StoreOrderServices $orderServices */
        $orderServices = app()->make(StoreOrderServices::class);
        [$page, $limit] = $this->getPageValue();
        $where = $where + ['paid' => 1, 'refund_status' => 0, 'is_del' => 0];
        $list = $orderServices->bargainStatisticsOrder($id, $where, $page, $limit);
        $count = $orderServices->bargainStatisticsOrderCount($id, $where);
        foreach ($list as &$item) {
            if ($item['status'] == 0) {
                if ($item['paid'] == 0) {
                    $item['status'] = 'Chưa thanh toán';
                } else {
                    $item['status'] = 'Không được vận chuyển';
                }
            } elseif ($item['status'] == 1) {
                $item['status'] = 'Đang chờ nhận';
            } elseif ($item['status'] == 2) {
                $item['status'] = 'Đang chờ đánh giá';
            } elseif ($item['status'] == 3) {
                $item['status'] = 'Hoàn thành';
            } elseif ($item['status'] == -2) {
                $item['status'] = 'Đã hoàn tiền';
            } else {
                $item['status'] = 'không rõ';
            }
            $item['add_time'] = date('Y-m-d H:i:s', $item['add_time']);
            $item['pay_time'] = $item['pay_time'] ? date('Y-m-d H:i:s', $item['pay_time']) : '';
        }
        return compact('list', 'count');
    }
}
