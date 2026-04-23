<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
namespace app\outapi\controller;

use think\facade\Db;
use think\Request;

/**
 * MCP (Model Context Protocol) bộ điều khiển
 * Cung cấp giao diện chuẩn cho trợ lý AI để gọi API CRMEB
 *
 * Phương thức xác thực: tài khoản + mật khẩu
 * Chuyển số tài khoản và mật khẩu thông qua tiêu đề yêu cầu để xác thực
 */
class Mcp extends AuthController
{
    /**
     * khởi tạo
     * Giao diện MCP không sử dụng phần mềm trung gian Token và sử dụng trực tiếp xác thực appid + appsecret.
     */
    protected function initialize()
    {
        // Việc khởi tạo lớp cha không được gọi vì MCP không sử dụng phần mềm trung gian Token.
        $this->authByAppSecret();
    }

    /**
     * Xác thực qua appid + appsecret
     * Tham khảo quy trình xác minh của AuthTokenMiddleware
     */
    private function authByAppSecret()
    {
        $account = $this->request->header('account', '');
        $password = $this->request->header('password', '');

        if (empty($account) || empty($password)) {
            $this->authFail('Xác thực không thành công: tài khoản bị thiếu hoặc password');
            return;
        }

        try {
            // Truy vấn thông tin tài khoản
            $accountInfo = Db::name('out_account')
                ->where('appid', $account)
                ->where('is_del', 0)
                ->find();

            // Tài khoản không tồn tại
            if (!$accountInfo) {
                $this->authFail('Tài khoản không tồn tại');
                return;
            }

            // Xác minh mật khẩu
            if (!password_verify($password, $accountInfo['appsecret'])) {
                $this->authFail('Xác minh mật khẩu không thành công');
                return;
            }

            // Kiểm tra trạng thái tài khoản（status=0 hoặc status=2 Cho biết bị vô hiệu hóa）
            if ($accountInfo['status'] == 0 || $accountInfo['status'] == 2) {
                $this->authFail('Tài khoản đã bị vô hiệu hóa');
                return;
            }

            // Xác thực thành công, thiết lập thông tin tài khoản
            $this->outId = (int)$accountInfo['id'];
            $this->outInfo = $accountInfo;

            // Xác minh quyền giao diện (tham khảo AuthTokenMiddleware）
            // $this->verifyAuth();

        } catch (\crmeb\exceptions\AuthException $e) {
            // AuthException Chuyển đổi thành lỗi thân thiện
            $this->authFail('Hiện tại bạn không có quyền truy cập');
        } catch (\Exception $e) {
            // Không để lộ thông tin lỗi cụ thể
            $this->authFail('Xác thực không thành công');
        }
    }

    /**
     * Xác minh quyền giao diện
     * Tham khảo logic verifyAuth của AuthTokenMiddleware
     * Giao diện MCP yêu cầu kiểm tra quyền định tuyến
     */
    private function verifyAuth()
    {
        try {
            // Inject outId và outInfo vào request (mô phỏng hành vi của middleware）
            $outInfo = $this->outInfo;
            $this->request->macro('outId', function () use (&$outInfo) {
                return (int)$outInfo['id'];
            });
            $this->request->macro('outInfo', function () use (&$outInfo) {
                return $outInfo;
            });

            // Gọi dịch vụ xác minh quyền giao diện
            $outInterfaceServices = app()->make(\app\services\out\OutInterfaceServices::class);
            $outInterfaceServices->verifyAuth($this->request);

        } catch (\crmeb\exceptions\AuthException $e) {
            // Xác minh quyền không thành công và thông báo lỗi thân thiện được đưa ra
            throw new \crmeb\exceptions\AuthException(110000); // Không có quyền truy cập
        } catch (\Exception $e) {
            // Các trường hợp ngoại lệ khác sẽ thống nhất không được phép.
            throw new \crmeb\exceptions\AuthException(110000);
        }
    }

    /**
     * Xử lý lỗi xác thực
     * Đặt mã định danh lỗi và trả về phản hồi lỗi trong phương thức chỉ mục
     */
    private function authFail(string $message)
    {
        $this->outId = 0;
        $this->outInfo = ['error' => $message];
    }

    /**
     * Nhận danh sách định nghĩa công cụ MCP
     * Xác định tất cả các công cụ mà trợ lý AI có thể gọi và cấu trúc tham số của chúng
     *
     * @return mảng định nghĩa công cụ mảng
     */
    private function getTools(): array
    {
        return [
            // Quản lý phân loại
            [
                'name' => 'crmeb_category_list',
                'description' => 'Lấy danh sách phân loại sản phẩm, hỗ trợ hiển thị cấu trúc cây',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'page' => ['type' => 'number', 'description' => 'Số trang (hợp lệ ở chế độ không phải cây)）'],
                        'limit' => ['type' => 'number', 'description' => 'Số trang trên mỗi trang (hợp lệ ở chế độ không có cây)）'],
                        'tree' => ['type' => 'boolean', 'description' => 'Có trả về cấu trúc cây hay không, mặc định làtrue'],
                        'pid' => ['type' => 'number', 'description' => 'ID cha mẹ. Nếu được chỉ định, chỉ các danh mục dưới cấp độ gốc mới được trả về.'],
                    ],
                ],
            ],
            [
                'name' => 'crmeb_category_detail',
                'description' => 'Nhận chi tiết danh mục',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'number', 'description' => 'Phân loạiID'],
                    ],
                    'required' => ['id'],
                ],
            ],

            // Quản lý sản phẩm
            [
                'name' => 'crmeb_product_list',
                'description' => 'Nhận danh sách sản phẩm',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'page' => ['type' => 'number', 'description' => 'số trang'],
                        'limit' => ['type' => 'number', 'description' => 'Số lượng mỗi trang'],
                        'cate_id' => ['type' => 'number', 'description' => 'Phân loạiID'],
                        'keyword' => ['type' => 'string', 'description' => 'Tìm kiếm từ khóa'],
                        'stock_min' => ['type' => 'number', 'description' => 'Hàng tồn kho tối thiểu'],
                        'stock_max' => ['type' => 'number', 'description' => 'Hàng tồn kho tối đa'],
                    ],
                ],
            ],
            [
                'name' => 'crmeb_product_detail',
                'description' => 'Nhận chi tiết sản phẩm',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'number', 'description' => 'hàng hóaID'],
                    ],
                    'required' => ['id'],
                ],
            ],

            // Quản lý đơn hàng
            [
                'name' => 'crmeb_order_list',
                'description' => 'Nhận danh sách đặt hàng',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'page' => ['type' => 'number', 'description' => 'số trang'],
                        'limit' => ['type' => 'number', 'description' => 'Số lượng mỗi trang'],
                        'status' => ['type' => 'number', 'description' => 'Trạng thái đơn hàng'],
                        'keyword' => ['type' => 'string', 'description' => 'Tìm kiếm từ khóa'],
                    ],
                ],
            ],
            [
                'name' => 'crmeb_order_detail',
                'description' => 'Nhận chi tiết đơn hàng',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'order_id' => ['type' => 'string', 'description' => 'Số đơn hàng'],
                    ],
                    'required' => ['order_id'],
                ],
            ],
            [
                'name' => 'crmeb_order_express_list',
                'description' => 'Nhận danh sách các công ty logistics',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => new \stdClass(),
                ],
            ],

            // Quản lý sau bán hàng
            [
                'name' => 'crmeb_refund_list',
                'description' => 'Nhận danh sách đơn hàng sau bán hàng',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'page' => ['type' => 'number', 'description' => 'số trang'],
                        'limit' => ['type' => 'number', 'description' => 'Số lượng mỗi trang'],
                    ],
                ],
            ],
            [
                'name' => 'crmeb_refund_detail',
                'description' => 'Nhận chi tiết đơn hàng sau bán hàng',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'order_id' => ['type' => 'string', 'description' => 'Số đơn hàng sau bán hàng'],
                    ],
                    'required' => ['order_id'],
                ],
            ],

            // Quản lý phiếu giảm giá
            [
                'name' => 'crmeb_coupon_list',
                'description' => 'Nhận danh sách phiếu giảm giá',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'page' => ['type' => 'number', 'description' => 'số trang'],
                        'limit' => ['type' => 'number', 'description' => 'Số lượng mỗi trang'],
                    ],
                ],
            ],

            // Quản lý người dùng
            [
                'name' => 'crmeb_user_list',
                'description' => 'Lấy danh sách người dùng',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'page' => ['type' => 'number', 'description' => 'số trang'],
                        'limit' => ['type' => 'number', 'description' => 'Số lượng mỗi trang'],
                        'keyword' => ['type' => 'string', 'description' => 'Tìm kiếm từ khóa'],
                    ],
                ],
            ],
            [
                'name' => 'crmeb_user_detail',
                'description' => 'Nhận thông tin chi tiết người dùng',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'uid' => ['type' => 'number', 'description' => 'người dùngID'],
                    ],
                    'required' => ['uid'],
                ],
            ],
        ];
    }

    /**
     * Xử lý các cuộc gọi công cụ
     * Phân phối cho phương pháp xử lý tương ứng theo tên công cụ
     *
     * @param string $name Tên công cụ
     * @param array $args Thông số công cụ
     * @return kết quả xử lý mảng
     * @throws \Exception Ngoại lệ được đưa ra khi lỗi công cụ hoặc tham số không xác định
     */
    private function handleToolCall(string $name, array $args = [])
    {
        switch ($name) {
            // Quản lý phân loại
            case 'crmeb_category_list':
                return $this->categoryList($args);
            case 'crmeb_category_detail':
                if (!isset($args['id']) || !is_numeric($args['id'])) {
                    throw new \Exception('Lỗi tham số: id bị thiếu hoặc định dạng không chính xác');
                }
                return $this->categoryDetail((int)$args['id']);

            // Quản lý sản phẩm
            case 'crmeb_product_list':
                return $this->productList($args);
            case 'crmeb_product_detail':
                if (!isset($args['id']) || !is_numeric($args['id'])) {
                    throw new \Exception('Lỗi tham số: id bị thiếu hoặc định dạng không chính xác');
                }
                return $this->productDetail((int)$args['id']);

            // Quản lý đơn hàng
            case 'crmeb_order_list':
                return $this->orderList($args);
            case 'crmeb_order_detail':
                if (empty($args['order_id'])) {
                    throw new \Exception('Lỗi tham số: thiếu order_id');
                }
                return $this->orderDetail($args['order_id']);
            case 'crmeb_order_express_list':
                return $this->orderExpressList();

            // Quản lý sau bán hàng
            case 'crmeb_refund_list':
                return $this->refundList($args);
            case 'crmeb_refund_detail':
                if (empty($args['order_id'])) {
                    throw new \Exception('Lỗi tham số: thiếu order_id');
                }
                return $this->refundDetail($args['order_id']);

            // Quản lý phiếu giảm giá
            case 'crmeb_coupon_list':
                return $this->couponList($args);

            // Quản lý người dùng
            case 'crmeb_user_list':
                return $this->userList($args);
            case 'crmeb_user_detail':
                if (!isset($args['uid']) || !is_numeric($args['uid'])) {
                    throw new \Exception('Lỗi tham số: uid bị thiếu hoặc định dạng không chính xác');
                }
                return $this->userDetail((int)$args['uid']);

            default:
                throw new \Exception("công cụ không xác định: {$name}");
        }
    }

    // ==================== Quản lý phân loại ====================

    /**
     * Lấy danh sách danh mục sản phẩm
     *
     * @param array $args tham số truy vấn
     *   - page: Số trang, mặc định 1 (hợp lệ ở chế độ không phải cây)）
     *   - limit: Số trang trên mỗi trang, mặc định 10, tối đa 100 (hợp lệ ở chế độ không có cây)）
     *   - tree: Có trả về cấu trúc cây hay không, mặc địnhfalse
     *   - pid: ID cha mẹ. Nếu được chỉ định, chỉ các danh mục dưới cấp độ gốc mới được trả về.
     * @return danh sách danh mục mảng và tổng số
     */
    private function categoryList(array $args): array
    {
        $page = max(1, (int)($args['page'] ?? 1));
        $limit = min(100, max(1, (int)($args['limit'] ?? 10))); // Hạn chế nhất100
        $isTree = $args['tree'] ?? true;
        $pid = $args['pid'] ?? null;

        // Xây dựng một truy vấn cơ bản
        $query = Db::name('store_category')->where('is_show', 1);

        // Nếu cha mẹ được chỉ địnhID
        if ($pid !== null) {
            $query = $query->where('pid', $pid);
        }

        // Chế độ cây: lấy tất cả các danh mục và xây dựng cây
        if ($isTree) {
            $allList = Db::name('store_category')
                ->where('is_show', 1)
                ->order('sort desc, id desc')
                ->select()
                ->toArray();

            // Nếu pid được chỉ định, hãy xây dựng cây bắt đầu từ nút này
            if ($pid !== null) {
                $tree = $this->buildCategoryTree($allList, $pid);
                return ['list' => $tree, 'count' => count($tree)];
            }

            // Nếu không thì xây dựng cây hoàn chỉnh (từ nút gốcpid=0bắt đầu）
            $tree = $this->buildCategoryTree($allList, 0);
            return ['list' => $tree, 'count' => count($tree)];
        }

        // Chế độ danh sách bình thường
        $list = $query
            ->order('sort desc, id desc')
            ->page($page, $limit)
            ->select()
            ->toArray();

        $count = $query->count();

        return ['list' => $list, 'count' => $count];
    }

    /**
     * Xây dựng cấu trúc cây phân loại
     *
     * @param array $list Tất cả dữ liệu được phân loại
     * @param int $pid ID gốc
     * @return cấu trúc cây mảng
     */
    private function buildCategoryTree(array $list, int $pid): array
    {
        $tree = [];
        foreach ($list as $item) {
            if ($item['pid'] == $pid) {
                $children = $this->buildCategoryTree($list, $item['id']);
                if (!empty($children)) {
                    $item['children'] = $children;
                }
                $tree[] = $item;
            }
        }
        return $tree;
    }

    /**
     * Nhận chi tiết danh mục
     *
     * @param int $id ID danh mục
     * @return chi tiết phân loại mảng
     * @throws \Exception Ném ngoại lệ khi danh mục không tồn tại
     */
    private function categoryDetail(int $id): array
    {
        $info = Db::name('store_category')->where('id', $id)->find();
        if (!$info) {
            throw new \Exception('Danh mục không tồn tại');
        }
        return $info;
    }

    // ==================== Quản lý sản phẩm ====================

    /**
     * Nhận danh sách sản phẩm
     * Hỗ trợ lọc theo danh mục, từ khóa và phạm vi khoảng không quảng cáo
     *
     * @param array $args tham số truy vấn
     *   - page: Số trang, mặc định1
     *   - limit: Số trang trên mỗi trang, mặc định 10, tối đa100
     *   - cate_id: ID danh mục (tùy chọn）
     *   - keyword: Từ khóa tìm kiếm (tùy chọn)）
     *   - stock_min: Khoảng không quảng cáo tối thiểu (tùy chọn)）
     *   - stock_max: Khoảng không quảng cáo tối đa (tùy chọn)
     * @return danh sách sản phẩm mảng và tổng số
     */
    private function productList(array $args): array
    {
        $page = max(1, (int)($args['page'] ?? 1));
        $limit = min(100, max(1, (int)($args['limit'] ?? 10))); // Hạn chế nhất100

        $where = [['is_show', '=', 1]];

        // Lọc phân loại: Truy vấn thông qua các bảng liên quan
        if (!empty($args['cate_id'])) {
            $cateId = (int)$args['cate_id'];
            // Xác minh rằng danh mục tồn tại
            $categoryExists = Db::name('store_category')->where('id', $cateId)->where('is_show', 1)->count();
            if (!$categoryExists) {
                throw new \Exception('Danh mục không tồn tại');
            }
            // Truy vấn sản phẩm thông qua các bảng liên quanID
            $productIds = Db::name('store_product_cate')
                ->where('cate_id', $cateId)
                ->column('product_id');
            if (empty($productIds)) {
                return ['list' => [], 'count' => 0];
            }
            $where[] = ['id', 'in', $productIds];
        }

        // Tìm kiếm từ khóa: Thoát ký tự đại diện để ngăn chặn việc tiêm
        if (!empty($args['keyword'])) {
            $keyword = addcslashes($args['keyword'], '%_');
            $where[] = ['store_name', 'like', '%' . $keyword . '%'];
        }

        if (isset($args['stock_min'])) {
            $where[] = ['stock', '>=', (int)$args['stock_min']];
        }
        if (isset($args['stock_max'])) {
            $where[] = ['stock', '<=', (int)$args['stock_max']];
        }

        $list = Db::name('store_product')
            ->where($where)
            ->field('id,store_name,cate_id,price,stock,image,sales,is_show')
            ->order('id desc')
            ->page($page, $limit)
            ->select()
            ->toArray();

        $count = Db::name('store_product')->where($where)->count();

        return ['list' => $list, 'count' => $count];
    }

    /**
     * Nhận chi tiết sản phẩm
     *
     * @param int $id ID sản phẩm
     * @return chi tiết sản phẩm mảng (các trường nhạy cảm được lọc）
     * @throws \Exception Ném ra một ngoại lệ khi sản phẩm không tồn tại
     */
    private function productDetail(int $id): array
    {
        $info = Db::name('store_product')->where('id', $id)->find();
        if (!$info) {
            throw new \Exception('Sản phẩm không tồn tại');
        }

        // Lọc các trường nhạy cảm và chỉ trả về thông tin cần thiết
        return [
            'id' => $info['id'],
            'store_name' => $info['store_name'] ?? '',
            'cate_id' => $info['cate_id'] ?? 0,
            'price' => $info['price'] ?? 0,
            'stock' => $info['stock'] ?? 0,
            'image' => $info['image'] ?? '',
            'slider_image' => $info['slider_image'] ?? '',
            'sales' => $info['sales'] ?? 0,
            'unit_name' => $info['unit_name'] ?? '',
            'content' => $info['content'] ?? '',
            'is_show' => $info['is_show'] ?? 1,
        ];
    }

    // ==================== Quản lý đơn hàng ====================

    /**
     * Nhận danh sách đặt hàng
     * Hỗ trợ lọc theo trạng thái và từ khóa
     *
     * @param array $args tham số truy vấn
     *   - page: Số trang, mặc định1
     *   - limit: Số trang trên mỗi trang, mặc định 10, tối đa100
     *   - status: Trạng thái đơn hàng (tùy chọn）
     *   - keyword: Tìm kiếm từ khóa khớp với số đơn hàng/tên/số điện thoại di động (tùy chọn)
     * @return danh sách thứ tự mảng và tổng số
     */
    private function orderList(array $args): array
    {
        $page = max(1, (int)($args['page'] ?? 1));
        $limit = min(100, max(1, (int)($args['limit'] ?? 10))); // Hạn chế nhất100

        $where = [['is_del', '=', 0]];
        if (isset($args['status'])) {
            $where[] = ['status', '=', (int)$args['status']];
        }
        // Tìm kiếm từ khóa: Thoát ký tự đại diện để ngăn chặn việc tiêm
        if (!empty($args['keyword'])) {
            $keyword = addcslashes($args['keyword'], '%_');
            $where[] = ['order_id|real_name|user_phone', 'like', '%' . $keyword . '%'];
        }

        $list = Db::name('store_order')
            ->where($where)
            ->field('id,order_id,uid,total_price,pay_price,paid,status,delivery_type,add_time')
            ->order('id desc')
            ->page($page, $limit)
            ->select()
            ->toArray();

        $count = Db::name('store_order')->where($where)->count();

        return ['list' => $list, 'count' => $count];
    }

    /**
     * Nhận chi tiết đơn hàng
     *
     * @param string $orderId Số đơn hàng
     * @return chi tiết thứ tự mảng (các trường nhạy cảm được lọc）
     * @throws \Exception Ném ra một ngoại lệ khi đơn hàng không tồn tại
     */
    private function orderDetail(string $orderId): array
    {
        $info = Db::name('store_order')->where('order_id', $orderId)->find();
        if (!$info) {
            throw new \Exception('Đơn hàng không tồn tại');
        }

        // Lọc các trường nhạy cảm và chỉ trả về thông tin cần thiết
        return [
            'id' => $info['id'],
            'order_id' => $info['order_id'],
            'uid' => $info['uid'],
            'real_name' => $info['real_name'] ?? '',
            'user_phone' => $info['user_phone'] ?? '',
            'user_address' => $info['user_address'] ?? '',
            'total_price' => $info['total_price'] ?? 0,
            'pay_price' => $info['pay_price'] ?? 0,
            'pay_type' => $info['pay_type'] ?? '',
            'paid' => $info['paid'] ?? 0,
            'status' => $info['status'] ?? 0,
            'delivery_type' => $info['delivery_type'] ?? '',
            'delivery_name' => $info['delivery_name'] ?? '',
            'delivery_id' => $info['delivery_id'] ?? '',
            'refund_status' => $info['refund_status'] ?? 0,
            'add_time' => $info['add_time'] ?? 0,
        ];
    }

    /**
     * Nhận danh sách các công ty logistics
     * Trả lại tất cả thông tin công ty chuyển phát nhanh được kích hoạt
     *
     * @return mảng Danh sách công ty Logistics
     */
    private function orderExpressList(): array
    {
        $list = Db::name('express')->where('is_show', 1)->field('id,name,code')->select()->toArray();
        return ['list' => $list];
    }

    // ==================== Quản lý sau bán hàng ====================

    /**
     * Nhận danh sách đơn hàng sau bán hàng
     * Trả lại tất cả các đơn hàng có trạng thái hoàn tiền
     *
     * @param array $args tham số truy vấn
     *   - page: Số trang, mặc định1
     *   - limit: Số trên mỗi trang, mặc định 10, tối đa 100
     * @return mảng Danh sách đơn hàng sau bán hàng và tổng số
     */
    private function refundList(array $args): array
    {
        $page = max(1, (int)($args['page'] ?? 1));
        $limit = min(100, max(1, (int)($args['limit'] ?? 10))); // Hạn chế nhất100

        $list = Db::name('store_order')
            ->where('refund_status', '>', 0)
            ->field('id,order_id,uid,total_price,pay_price,refund_status,refund_reason')
            ->order('id desc')
            ->page($page, $limit)
            ->select()
            ->toArray();

        $count = Db::name('store_order')->where('refund_status', '>', 0)->count();

        return ['list' => $list, 'count' => $count];
    }

    /**
     * Nhận chi tiết đơn hàng sau bán hàng
     *
     * @param string $orderId Số đơn hàng sau bán hàng
     * mảng @return Chi tiết đơn hàng sau bán hàng (các trường nhạy cảm được lọc）
     * @throws \Exception Một ngoại lệ được đưa ra khi đơn hàng sau bán hàng không tồn tại
     */
    private function refundDetail(string $orderId): array
    {
        $info = Db::name('store_order')
            ->where('order_id', $orderId)
            ->where('refund_status', '>', 0)
            ->find();
        if (!$info) {
            throw new \Exception('Đơn đặt hàng sau bán hàng không tồn tại');
        }

        // Lọc các trường nhạy cảm và chỉ trả về thông tin cần thiết
        return [
            'id' => $info['id'],
            'order_id' => $info['order_id'],
            'uid' => $info['uid'],
            'total_price' => $info['total_price'] ?? 0,
            'pay_price' => $info['pay_price'] ?? 0,
            'refund_status' => $info['refund_status'] ?? 0,
            'refund_reason' => $info['refund_reason'] ?? '',
            'refund_price' => $info['refund_price'] ?? 0,
            'refund_explain' => $info['refund_explain'] ?? '',
            'refund_img' => $info['refund_img'] ?? '',
            'add_time' => $info['add_time'] ?? 0,
        ];
    }

    // ==================== Quản lý phiếu giảm giá ====================

    /**
     * Nhận danh sách phiếu giảm giá
     *
     * @param array $args tham số truy vấn
     *   - page: Số trang, mặc định1
     *   - limit: Số trên mỗi trang, mặc định 10, tối đa 100
     * @return danh sách phiếu giảm giá mảng và tổng số
     */
    private function couponList(array $args): array
    {
        $page = max(1, (int)($args['page'] ?? 1));
        $limit = min(100, max(1, (int)($args['limit'] ?? 10))); // Hạn chế nhất100

        $list = Db::name('store_coupon_issue')
            ->where('is_del', 0)
            ->field('id,coupon_title,coupon_price,use_min_price,start_time,end_time')
            ->order('id desc')
            ->page($page, $limit)
            ->select()
            ->toArray();

        $count = Db::name('store_coupon_issue')->where('is_del', 0)->count();

        return ['list' => $list, 'count' => $count];
    }

    // ==================== Quản lý người dùng ====================

    /**
     * Lấy danh sách người dùng
     *Hỗ trợ tìm kiếm theo biệt hiệu hoặc số điện thoại di động
     *
     * @param array $args tham số truy vấn
     *   - page: Số trang, mặc định1
     *   - limit: Số trang trên mỗi trang, mặc định 10, tối đa100
     *   - keyword: Tìm kiếm từ khóa và khớp biệt danh/số điện thoại di động (tùy chọn)
     * @return danh sách người dùng mảng và tổng số
     */
    private function userList(array $args): array
    {
        $page = max(1, (int)($args['page'] ?? 1));
        $limit = min(100, max(1, (int)($args['limit'] ?? 10))); // Hạn chế nhất100

        $where = [];
        // Tìm kiếm từ khóa: Thoát ký tự đại diện để ngăn chặn việc tiêm
        if (!empty($args['keyword'])) {
            $keyword = addcslashes($args['keyword'], '%_');
            $where[] = ['nickname|phone', 'like', '%' . $keyword . '%'];
        }

        $list = Db::name('user')
            ->where($where)
            ->field('uid,nickname,avatar,phone,balance,integral,add_time')
            ->order('uid desc')
            ->page($page, $limit)
            ->select()
            ->toArray();

        $count = Db::name('user')->where($where)->count();

        return ['list' => $list, 'count' => $count];
    }

    /**
     * Nhận thông tin chi tiết người dùng
     *
     * @param int $uid ID người dùng
     * @return chi tiết người dùng mảng (các trường nhạy cảm được lọc）
     * @throws \Exception Ném ngoại lệ khi người dùng không tồn tại
     */
    private function userDetail(int $uid): array
    {
        $info = Db::name('user')->where('uid', $uid)->find();
        if (!$info) {
            throw new \Exception('Người dùng không tồn tại');
        }

        // Lọc các trường nhạy cảm và chỉ trả về thông tin cần thiết
        return [
            'uid' => $info['uid'],
            'nickname' => $info['nickname'] ?? '',
            'avatar' => $info['avatar'] ?? '',
            'phone' => $info['phone'] ?? '',
            'now_money' => $info['now_money'] ?? 0,
            'integral' => $info['integral'] ?? 0,
            'level' => $info['level'] ?? 0,
            'add_time' => $info['add_time'] ?? 0,
            'last_time' => $info['last_time'] ?? 0,
        ];
    }

    // ==================== MCP giao diện ====================

    /**
     * MCP Phương thức nhập dịch vụ
     * Xử lý tất cả các yêu cầu giao thức MCP, bao gồm：
     * - initialize: Khởi tạo kết nối và trả lại thông tin và khả năng dịch vụ
     * - tools/list: Nhận danh sách các công cụ có sẵn
     * - tools/call: Gọi công cụ được chỉ định để thực hiện thao tác
     *
     * @param Request $request HTTPđối tượng yêu cầu
     * @return \think\response\Json JSON-RPC 2.0 phản hồi định dạng
     */
    public function index(Request $request)
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        $id = $data['id'] ?? null;

        if (!$data) {
            return json(['jsonrpc' => '2.0', 'error' => ['code' => -32700, 'message' => 'Parse error'], 'id' => null]);
        }

        // Kiểm tra chứng nhận
        if (empty($this->outId)) {
            $errorMsg = $this->outInfo['error'] ?? 'Xác thực không thành công';
            return json([
                'jsonrpc' => '2.0',
                'id' => $id,
                'error' => ['code' => -32600, 'message' => $errorMsg]
            ]);
        }

        $method = $data['method'] ?? '';
        $params = $data['params'] ?? [];

        try {
            switch ($method) {
                case 'initialize':
                    return json([
                        'jsonrpc' => '2.0',
                        'id' => $id,
                        'result' => [
                            'protocolVersion' => '2024-11-05',
                            'capabilities' => ['tools' => new \stdClass()],
                            'serverInfo' => [
                                'name' => 'crmeb-mcp-server',
                                'version' => '1.0.0',
                            ],
                        ],
                    ]);

                case 'tools/list':
                    return json([
                        'jsonrpc' => '2.0',
                        'id' => $id,
                        'result' => ['tools' => $this->getTools()],
                    ]);

                case 'tools/call':
                    $toolName = $params['name'] ?? '';
                    $toolArgs = $params['arguments'] ?? [];

                    $result = $this->handleToolCall($toolName, $toolArgs);

                    return json([
                        'jsonrpc' => '2.0',
                        'id' => $id,
                        'result' => [
                            'content' => [
                                [
                                    'type' => 'text',
                                    'text' => json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                                ],
                            ],
                        ],
                    ]);

                default:
                    return json([
                        'jsonrpc' => '2.0',
                        'id' => $id,
                        'error' => ['code' => -32601, 'message' => "Method not found: {$method}"],
                    ]);
            }
        } catch (\Exception $e) {
            // Môi trường sản xuất trả về các thông báo lỗi phổ biến để tránh rò rỉ chi tiết nội bộ
            $errorMessage = $e->getMessage();
            // Đối với các trường hợp ngoại lệ trong kinh doanh (chẳng hạn như "sản phẩm không tồn tại"), hãy trả về các lỗi cụ thể
            // Đối với các ngoại lệ của hệ thống (như lỗi SQL), trả về lỗi chung
            $safeErrors = ['Sản phẩm không tồn tại', 'Đơn hàng không tồn tại', 'Đơn đặt hàng sau bán hàng không tồn tại', 'Người dùng không tồn tại', 'Danh mục không tồn tại', 'Danh mục chính không tồn tại',
                          'Một danh mục có cùng tên đã tồn tại trong cùng danh mục', 'Tên danh mục không thể vượt quá 50 ký tự',
                          'Lỗi tham số', 'công cụ không xác định'];
            $isSafeError = false;
            foreach ($safeErrors as $safeError) {
                if (strpos($errorMessage, $safeError) !== false) {
                    $isSafeError = true;
                    break;
                }
            }

            return json([
                'jsonrpc' => '2.0',
                'id' => $id,
                'error' => ['code' => -32603, 'message' => $isSafeError ? $errorMessage : 'Lỗi nội bộ máy chủ'],
            ]);
        }
    }
}
