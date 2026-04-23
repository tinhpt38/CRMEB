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


use app\dao\system\SystemCrudDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use crmeb\services\crud\Controller;
use crmeb\services\crud\Dao;
use crmeb\services\crud\enum\FormTypeEnum;
use crmeb\services\crud\enum\SearchEnum;
use crmeb\services\crud\Make;
use crmeb\services\crud\Model;
use crmeb\services\crud\Route;
use crmeb\services\crud\Service;
use crmeb\services\crud\Validate;
use crmeb\services\crud\ViewApi;
use crmeb\services\crud\ViewPages;
use crmeb\services\crud\ViewRouter;
use crmeb\services\FileService;
use Phinx\Db\Adapter\AdapterFactory;
use think\facade\Db;
use think\helper\Str;
use think\migration\db\Column;
use think\migration\db\Table;

/**
 * Class SystemCrudServices
 * @author Chờ gió tới
 * @email 136327134@qq.com
 * @date 2023/4/6
 * @package app\services\system
 */
class SystemCrudServices extends BaseServices
{

    //Không thể tạo bảng riêng của hệ thống
    const NOT_CRUD_TABANAME = [
        'system_config', 'system_attachment', 'system_attachment_category', 'system_config_tab',
        'system_admin', 'eb_system_city', 'system_log', 'system_menus', 'system_notice',
        'system_notice_admin', 'system_notification', 'system_role', 'system_route',
        'system_route_cate', 'system_storage', 'system_timer', 'system_user_level',
        'system_crud', 'wechat_key', 'user_label_relation', 'user_brokerage_frozen',
        'user_brokerage', 'store_product_cate', 'store_bargain_user_help', 'shipping_templates_region',
        'shipping_templates_no_delivery', 'shipping_templates_free', 'other_order_status', 'lang_code',
        'lang_country', 'app_version', 'user', 'wechat_user', 'template_message', 'store_order',
        'other_order', 'store_order_cart_info', 'store_order_economize', 'store_order_invoice', 'store_order_refund',
        'store_order_status', 'store_pink', 'agent_level', 'agent_level_task', 'agent_level_task_record',
        'agreement', 'app_version', 'article', 'article_category', 'article_content', 'auxiliary', 'cache',
        'capital_flow', 'category', 'diy', 'express', 'lang_type', 'live_anchor', 'live_goods', 'live_room',
        'live_room_goods', 'luck_lottery', 'luck_lottery_record', 'luck_prize', 'member_card',
        'member_card_batch', 'member_right', 'member_ship', 'message_system', 'other_order',
        'other_order_status', 'out_account', 'out_interface', 'page_categroy', 'page_link', 'qrcode',
        'shipping_templates', 'shipping_templates_free', 'shipping_templates_no_delivery',
        'shipping_templates_region', 'sms_record', 'store_advance', 'store_bargain', 'store_bargain_user',
        'store_bargain_user_help', 'store_cart', 'store_category', 'store_combination', 'store_coupon_issue',
        'store_coupon_issue_user', 'store_coupon_product', 'store_coupon_user', 'store_integral',
        'store_integral_order', 'store_integral_order_status', 'store_order', 'store_order_cart_info',
        'store_order_economize', 'store_order_invoice', 'store_order_refund', 'store_order_status',
        'store_pink', 'store_product', 'store_product_attr', 'store_product_attr_result',
        'store_product_attr_value', 'store_product_cate', 'store_product_coupon', 'store_product_description',
        'store_product_log', 'store_product_relation', 'store_service', 'store_service_feedback',
        'store_product_reply', 'store_product_rule', 'store_product_virtual', 'store_seckill', 'store_seckill_time',
        'store_service_log', 'store_service_record', 'store_service_speechcraft', 'store_visit',
        'system_attachment', 'system_attachment_category', 'system_city', 'system_config',
        'system_config_tab', 'system_file', 'system_file_info', 'system_group', 'system_group_data',
        'system_log', 'system_notice', 'system_notice_admin', 'system_notification',
        'system_role', 'system_route', 'system_route_cate', 'system_storage', 'system_store',
        'system_store_staff', 'system_timer', 'system_user_level', 'template_message', 'upgrade_log',
        'user', 'user_address', 'user_bill', 'user_brokerage', 'user_brokerage_frozen', 'user_cancel',
        'user_enter', 'user_extract', 'user_friends', 'user_group', 'user_invoice', 'user_label',
        'user_label_relation', 'user_level', 'user_money', 'user_notice', 'user_notice_see',
        'user_recharge', 'user_search', 'user_sign', 'user_spread', 'user_visit', 'wechat_key',
        'wechat_media', 'wechat_message', 'wechat_news_category', 'wechat_qrcode', 'wechat_qrcode_cate',
        'wechat_qrcode_record', 'wechat_reply', 'wechat_user', 'system_crud_data', 'admins',
    ];

    //bộ ký tự bảng
    const TABLR_COLLATION = 'utf8mb4_general_ci';

    /**
     * SystemCrudServices constructor.
     * @param SystemCrudDao $dao
     */
    public function __construct(SystemCrudDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @return array
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/11
     */
    public function getList()
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->selectList([], 'add_time,id,name,table_name,table_comment,table_collation', $page, $limit, 'id desc');
        $count = $this->dao->count();

        return compact('list', 'count');
    }

    /**
     * Loại trường cơ sở dữ liệu
     * @return \string[][]
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/11
     */
    public function getTabelRule()
    {
        $rule = [
            'varchar' => 'string',
            'int' => 'integer',
            'biginteger' => 'bigint',
            'tinyint' => 'boolean',
        ];

        return [
            'types' => [
                'varchar',
                'char',
                'text',
                'longtext',
                'tinytext',
                'enum',
                'blob',
                'binary',
                'varbinary',

                'datetime',
                'timestamp',
                'time',
                'date',
                'year',

                'boolean',
                'tinyint',
                'int',
                'decimal',
                'float',

                'json',
            ],
            'form' => [
                [
                    'value' => FormTypeEnum::INPUT,
                    'label' => 'Hộp nhập liệu',
                    'field_type' => 'varchar',
                    'limit' => 255
                ],
                [
                    'value' => FormTypeEnum::NUMBER,
                    'label' => 'Hộp nhập số',
                    'field_type' => 'int',
                    'limit' => 11
                ],
                [
                    'value' => FormTypeEnum::TEXTAREA,
                    'label' => 'hộp văn bản nhiều dòng',
                    'field_type' => 'text',
                    'limit' => null
                ],
                [
                    'value' => FormTypeEnum::DATE_TIME,
                    'label' => 'Ngày và giờ duy nhất',
                    'field_type' => 'varchar',
                    'limit' => 200
                ],
                [
                    'value' => FormTypeEnum::DATE_TIME_RANGE,
                    'label' => 'Lựa chọn phạm vi ngày và giờ',
                    'field_type' => 'varchar',
                    'limit' => 200
                ],
                [
                    'value' => FormTypeEnum::CHECKBOX,
                    'label' => 'hộp kiểm',
                    'field_type' => 'varchar',
                    'limit' => 200
                ],
                [
                    'value' => FormTypeEnum::RADIO,
                    'label' => 'nút radio',
                    'field_type' => 'int',
                    'limit' => 11
                ],
                [
                    'value' => FormTypeEnum::SWITCH,
                    'label' => 'công tắc',
                    'field_type' => 'int',
                    'limit' => 11
                ],
                [
                    'value' => FormTypeEnum::SELECT,
                    'label' => 'hộp thả xuống',
                    'field_type' => 'int',
                    'limit' => 11
                ],
                [
                    'value' => FormTypeEnum::FRAME_IMAGE_ONE,
                    'label' => 'Lựa chọn hình ảnh duy nhất',
                    'field_type' => 'varchar',
                    'limit' => 200
                ],
                [
                    'value' => FormTypeEnum::FRAME_IMAGES,
                    'label' => 'Lựa chọn nhiều hình ảnh',
                    'field_type' => 'varchar',
                    'limit' => 200
                ],
            ],
            'search_type' => [
                [
                    'value' => SearchEnum::SEARCH_TYPE_EQ,
                    'label' => 'tương đương với tìm kiếm',
                ],
                [
                    'value' => SearchEnum::SEARCH_TYPE_LTEQ,
                    'label' => 'Nhỏ hơn hoặc bằng tìm kiếm',
                ],
                [
                    'value' => SearchEnum::SEARCH_TYPE_GTEQ,
                    'label' => 'Lớn hơn hoặc bằng tìm kiếm',
                ],
                [
                    'value' => SearchEnum::SEARCH_TYPE_NEQ,
                    'label' => 'Không bằng tìm kiếm',
                ],
                [
                    'value' => SearchEnum::SEARCH_TYPE_LIKE,
                    'label' => 'tìm kiếm mờ',
                ],
                [
                    'value' => SearchEnum::SEARCH_TYPE_BETWEEN,
                    'label' => 'Được sử dụng để tìm kiếm khoảng thời gian',
                ],
            ],
            'default_type' => [
                [
                    'value' => '-1',
                    'label' => 'không có',
                    'disabled' => false,
                ],
                [
                    'value' => '1',
                    'label' => 'Tùy chỉnh',
                    'disabled' => true,
                ],
                [
                    'value' => '2',
                    'label' => 'NULL',
                    'disabled' => false,
                ],
                [
                    'value' => '3',
                    'label' => 'CURRENT_TIMESTAMP',
                    'disabled' => false,
                ],
            ],
            'rule' => $rule
        ];
    }

    /**
     * Thay đổi loại cơ sở dữ liệu
     * @param string $type
     * @return string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/13
     */
    public function changeTabelRule(string $type)
    {

        if (!in_array($type, $this->getTabelRule()['types'])) {
            throw new AdminException('Loại trường không tồn tại');
        }

        return $this->getTabelRule()['rule'][$type] ?? $type;
    }

    /**
     * @param string $tableName
     * @return mixed
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/14
     */
    public function getTableInfo(string $tableName)
    {
        $sql = 'SELECT * FROM `information_schema`.`TABLES` WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?';

        $tableInfo = Db::query($sql, [config('database.connections.mysql.database'), $this->getTableName($tableName)]);

        return $tableInfo[0] ?? [];
    }

    /**
     * Nhận các trường bảng
     * @param string $tableName
     * @return mixed
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function getColumnNamesList(string $tableName)
    {
        $sql = 'SELECT * FROM `information_schema`.`columns` WHERE TABLE_SCHEMA = ? AND table_name = ? ORDER BY ORDINAL_POSITION';

        $column = Db::query($sql, [config('database.connections.mysql.database'), $this->getTableName($tableName)]);

        $columns = [];
        foreach ($column as $item) {
            $column = [
                'name' => $item['COLUMN_NAME'],
                'type' => $item['DATA_TYPE'],
                'dataType' => stripos($item['COLUMN_TYPE'], '(') !== false ? substr_replace($item['COLUMN_TYPE'], '', stripos($item['COLUMN_TYPE'], ')') + 1) : $item['COLUMN_TYPE'],
                'default' => $item['COLUMN_DEFAULT'],
                'null' => $item['IS_NULLABLE'] == 'YES',
                'primaryKey' => $item['COLUMN_KEY'] == 'PRI',
                'unsigned' => (bool)stripos($item['COLUMN_TYPE'], 'unsigned'),
                'autoIncrement' => stripos($item['EXTRA'], 'auto_increment') !== false,
                'comment' => $item['COLUMN_COMMENT'],
                'limit' => $item['CHARACTER_MAXIMUM_LENGTH'] ?: $item['NUMERIC_PRECISION'],
            ];
            $columns[$item['COLUMN_NAME']] = $column;
        }

        return $columns;
    }

    /**
     * Nhận tất cả tên bảng trong cơ sở dữ liệu hiện tại
     * @return hỗn hợp
     * @author Chờ gió về
     * @email 136327134@qq.com
     * @date 2023/8/2
     */
    public function getTableAll()
    {
        $sql = "SELECT TABLE_NAME, TABLE_COMMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = ?";

        $tableAll = Db::query($sql, [config('database.connections.mysql.database')]);

        $data = [];
        foreach ($tableAll as $item) {
            $item['TABLE_NAME'] = str_replace(config('database.connections.mysql.prefix'), '', $item['TABLE_NAME']);
//            if (!in_array($item['TABLE_NAME'], self::NOT_CRUD_TABANAME)) {
            $data[] = [
                'value' => $item['TABLE_NAME'],
                'label' => $item['TABLE_COMMENT'] ?: $item['TABLE_NAME'],
            ];
//            }
        }

        return $data;
    }

    /**
     * @param array $data
     * @return array
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/12
     */
    public function valueReplace(array $data)
    {
        $replace = ['phar://'];
        $newData = [];
        foreach ($data as $key => $item) {
            if (is_array($item)) {
                $item = $this->valueReplace($item);
            } else {
                $item = str_replace($replace, '', $item);
            }
            $newData[str_replace($replace, '', $key)] = $item;
        }
        return $newData;
    }

    /**
     * Cập nhật các trường của bảng
     * @param string $tableName
     * @param string $field
     * @param string $changeFiled
     * @param string $type
     * @param string $limit
     * @param string $default
     * @param string $comment
     * @param array $options
     * @return mixed
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/24
     */
    protected function updateAlter(string $tableName, string $field, string $changeFiled, string $prevFiled, string $type, $limit = '', string $default = '', string $comment = '', array $options = [])
    {
        $tableName = $this->getTableName($tableName);
        $comment = addslashes($comment);
        $field = addslashes($field);
        $changeFiled = addslashes($changeFiled);
        $prevFiled = addslashes($prevFiled);
        $type = addslashes($type);
        $default = addslashes($default);
        if ($prevFiled) {
            $after = "AFTER `$prevFiled`";
        } else {
            $after = "";
        }
        if (isset($options['default_type'])) {
            switch ($options['default_type']) {
                case '-1':
                    $default = 'NULL';
                    break;
                case '1'://Tùy chỉnh
                    $default = "NOT NULL DEFAULT '$default'";
                    break;
                case '2'://vìnull
                    $default = 'NULL DEFAULT NULL';
                    break;
                case '3'://thời gian
                    $default = 'NULL DEFAULT CURRENT_TIMESTAMP';
                    break;
            }
        }
        if (in_array(strtolower($type), ['text', 'longtext', 'tinytext'])) {
            $sql = "ALTER TABLE `$tableName` CHANGE `$field` `$changeFiled` $type CHARACTER SET utf8mb4 COLLATE " . self::TABLR_COLLATION . " NULL COMMENT '$comment' $after;";
        } else if (strtolower($type) == 'enum') {
            $enum = [];
            foreach ($options['options'] as $option) {
                $enum[] = "'$option'";
            }
            $enumStr = implode(',', $enum);
            $sql = "ALTER TABLE `$tableName` CHANGE `$field` `$changeFiled` $type($enumStr) $default COMMENT '$comment' $after;";
        } else {
            $sql = "ALTER TABLE `$tableName` CHANGE `$field` `$changeFiled` $type($limit) $default COMMENT '$comment' $after;";
        }
        return Db::execute($sql);
    }

    /**
     * Thêm trường
     * @param string $tableName
     * @param string $field
     * @param string $prevFiled
     * @param string $type
     * @param string $limit
     * @param string $default
     * @param string $comment
     * @param array $options
     * @return mixed
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/24
     */
    public function addAlter(string $tableName, string $field, string $prevFiled, string $type, $limit = '', string $default = '', string $comment = '', array $options = [])
    {
        $tableName = $this->getTableName($tableName);
        $comment = addslashes($comment);
        $field = addslashes($field);
        $prevFiled = addslashes($prevFiled);
        $type = addslashes($type);
        $default = addslashes($default);
        if ($prevFiled) {
            $after = "AFTER `$prevFiled`";
        } else {
            $after = "";
        }
        if (isset($options['default_type'])) {
            switch ($options['default_type']) {
                case '-1':
                    $default = 'NULL';
                    break;
                case '1'://Tùy chỉnh
                    $default = "NOT NULL DEFAULT '$default'";
                    break;
                case '2'://vìnull
                    $default = 'NULL DEFAULT NULL';
                    break;
                case '3'://thời gian
                    $default = 'NULL DEFAULT CURRENT_TIMESTAMP';
                    break;
            }
        }
        if (in_array(strtolower($type), ['text', 'longtext', 'tinytext'])) {
            $sql = "ALTER TABLE `$tableName` ADD `$field` $type NULL COMMENT '$comment' $after;";
        } else {
            $defaultSql = $default;
            //Giá trị mặc định của trường thời gian xử lý
            if (in_array(strtolower($type), ['datetime', 'timestamp', 'time', 'date', 'year'])) {
                switch ($field) {
                    case 'delete_time':
                        $defaultSql = 'NULL DEFAULT NULL';
                        break;
                    case 'create_time':
                    case 'update_time':
                        $defaultSql = 'NOT NULL DEFAULT CURRENT_TIMESTAMP';
                        break;
                }
            }

            //Tương thích với các trường enum
            if (strtolower($type) == 'enum') {
                $enum = [];
                foreach ($options['options'] as $option) {
                    $enum[] = "'$option'";
                }
                $enumStr = implode(',', $enum);

                $limitSql = $enumStr ? '(' . $enumStr . ')' : '';
            } else {
                $limitSql = $limit ? '(' . $limit . ')' : '';
            }

            $sql = "ALTER TABLE `$tableName` ADD `$field` $type$limitSql $defaultSql COMMENT '$comment' $after;";
        }
        return Db::execute($sql);
    }

    /**
     * Xóa trường bảng
     * @param string $tableName
     * @param string $field
     * @return mixed
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/24
     */
    protected function deleteAlter(string $tableName, string $field)
    {
        $tableName = $this->getTableName($tableName);
        $field = addslashes($field);
        $sql = "ALTER TABLE `$tableName` DROP `$field`";
        return Db::execute($sql);
    }

    /**
     * Sửa đổi nhận xét bảng
     * @param string $tableName
     * @param string $common
     * @return mixed
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/24
     */
    protected function updateFromCommon(string $tableName, string $common)
    {
        $tableName = $this->getTableName($tableName);
        $common = addslashes($common);
        $sql = "ALTER TABLE `$tableName` COMMENT = '$common';";
        return Db::execute($sql);
    }

    /**
     * Trường so sánh đã thay đổi
     * @param string $tableName
     * @param array $deleteField
     * @param array $tableField
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/24
     */
    protected function diffAlter(string $tableName, array $deleteField, array $tableField)
    {
        $updateAlter = [];
        $addAlter = [];

        $columns = $this->getColumnNamesList($tableName);
        $fieldAll = array_column($columns, 'name');

        //So sánh các trường cơ sở dữ liệu
        foreach ($tableField as $i => $item) {
            if ($item['primaryKey'] || $item['field'] == 'delete_time') {
                continue;
            }

            $prevFiled = $i ? ($tableField[$i - 1]['field'] ?? 'id') : 'id';
            //Thêm trường mới ở quầy lễ tân
            if (!(isset($item['default_field']) &&
                isset($item['default_field_type']) &&
                isset($item['default_limit']) &&
                isset($item['default_comment']) &&
                isset($item['default_default']) &&
                isset($item['default_default_type']))
            ) {
                if (!in_array($item['field'], $fieldAll)) {
                    $addAlter[] = [
                        'prev_filed' => $prevFiled,
                        'field' => $item['field'],
                        'limit' => $item['limit'],
                        'type' => $item['field_type'],
                        'comment' => $item['comment'],
                        'default' => $item['default'],
                        'default_type' => $item['default_type'],
                        'values' => $item['field_type'] == 'enum' ? $item['limit'] : [],
                    ];
                }
                continue;
            } else {
                //Các trường được thêm từ cơ sở dữ liệu không được ghi vào bảng để xử lý tính tương thích;
                // Trường mặc định không có trong cơ sở dữ liệu,Cần thêm trường；
                if (!in_array($item['default_field'], $fieldAll)) {
                    $addAlter[] = [
                        'prev_filed' => $prevFiled,
                        'field' => $item['field'],
                        'limit' => $item['limit'],
                        'type' => $item['field_type'],
                        'comment' => $item['comment'],
                        'default' => $item['default'],
                        'default_type' => $item['default_type'],
                        'values' => $item['field_type'] == 'enum' ? $item['limit'] : [],
                    ];
                    continue;
                }
            }

            if ($item['default_field'] != $item['field'] && in_array($item['field_type'], ['addTimestamps', 'addSoftDelete'])) {
                throw new AdminException($item['field'] . 'Các trường không được phép thay đổi');
            }

            //Bảng cơ sở dữ liệu tồn tại,Cánh đồng,và được sửa đổi
            if (!in_array($item['field'], ['id', 'create_time', 'update_time'])) {
                $updateAlter[] = [
                    'default_field' => $item['default_field'],
                    'prev_filed' => $prevFiled,
                    'field' => $item['field'],
                    'limit' => $item['limit'],
                    'type' => $item['field_type'],
                    'comment' => $item['comment'],
                    'default' => $item['default'],
                    'default_type' => $item['default_type'],
                    'values' => $item['field_type'] == 'enum' ? $item['limit'] : [],
                ];
            }
        }
        //Thêm trường
        foreach ($addAlter as $item) {
            $this->addAlter($tableName, $item['field'], $item['prev_filed'], $item['type'], $item['limit'], $item['default'], $item['comment'], [
                'options' => $item['values'],
                'default_type' => $item['default_type'],
            ]);
        }
        //Loại bỏ các trường dư thừa
        foreach ($deleteField as $item) {
            $this->deleteAlter($tableName, $item);
        }
        //Cập nhật các trường cơ sở dữ liệu
        foreach ($updateAlter as $item) {
            $this->updateAlter($tableName, $item['default_field'], $item['field'], $item['prev_filed'], $item['type'], $item['limit'], $item['default'], $item['comment'], [
                'options' => $item['values'],
                'default_type' => $item['default_type'],
            ]);
        }
    }

    /**
     * tạo nên
     * @param array $data
     * @return mixed
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/11
     */
    public function createCrud(int $id, array $data)
    {
        $tableName = $data['tableName'];
        $tableField = $this->valueReplace($data['tableField']);
        $filePath = $this->valueReplace($data['filePath']);
        $modelName = !empty($data['modelName']) ? $data['modelName'] : $tableName;
        $tableComment = !empty($data['tableComment']) ? $data['tableComment'] : $modelName;

        //Kiểm tra xem đó có phải là bảng hệ thống không
        if (in_array($tableName, self::NOT_CRUD_TABANAME)) {
            throw new AdminException('Không thể tạo bảng dữ liệu hệ thống');
        }

        $data['softDelete'] = false;

        $tableInfo = null;
        //Đầu tiên hãy kiểm tra xem bảng có tồn tại không
        if ($id) {
            $this->updateFromCommon($tableName, $tableComment);
            //Đọc bảng cơ sở dữ liệu
            $tableInfo = $this->getTableInfo($tableName);
            if ($tableInfo) {
                //So sánh các trường để cập nhật/xóa các trường
                $this->diffAlter($tableName, $data['deleteField'], $tableField);
            }
        } else {
            if ($this->dao->count(['table_name' => $tableName])) {
                throw new AdminException('Bảng đã được tạo, vui lòng sửa đổi nó trong danh sách');
            }
        }

        //Tạo cơ sở dữ liệu
        $tableCreateInfo = null;
        if ($tableField && (!$data['isTable'] || !$tableInfo)) {
            $tableCreateInfo = $this->makeDatebase($tableName, $tableComment, $tableField);
            if ($tableCreateInfo['softDelete']) {
                $data['softDelete'] = true;
            }
        }

        //Nhận khóa chính
        foreach ($tableField as $value) {
            if ($value['primaryKey']) {
                $data['key'] = $value['field'];
                break;
            }
        }

        $routeName = 'crud/' . Str::snake($tableName);
        $uniqueAuth = Str::snake($tableName) . '-crud-list-index';
        //Thêm đường dẫn tuyệt đối để lưu
        foreach ($filePath as $k => $i) {
            if (in_array($k, ['pages', 'router', 'api'])) {
                $filePath[$k] = Make::adminTemplatePath() . $i;
            } else {
                $filePath[$k] = app()->getRootPath() . $i;
            }
        }

        //Tạo thực đơn
        if (!$data['menuName']) {
            $data['menuName'] = $tableName;
        }
        $dataMenu = [
            'pid' => $data['pid'],
            'menu_name' => $data['menuName'],
            'menu_path' => '/' . $routeName,
            'auth_type' => 1,
            'is_show' => 1,
            'is_show_path' => 1,
            'is_del' => 0,
            'unique_auth' => $uniqueAuth,
            'is_header' => $data['pid'] ? 0 : 1,
        ];

        $crudInfo = null;
        if ($id) {
            $crudInfo = $this->dao->get($id);
        }

        $res = $this->transaction(function () use ($tableComment, $tableCreateInfo, $crudInfo, $modelName, $filePath, $tableName, $routeName, $data, $dataMenu) {
            $routeService = app()->make(SystemRouteServices::class);
            $meunService = app()->make(SystemMenusServices::class);
            //Sửa đổi tên thực đơn
            if ($crudInfo) {
                //Sửa đổi menu khi nó tồn tại
                if ($crudInfo->menu_id && $meunService->value(['id' => [$crudInfo->menu_id]], 'id')) {
                    $meunService->update($crudInfo->menu_id, $dataMenu);
                    $menuInfo = (object)['id' => $crudInfo->menu_id];
                } else {
                    $menuInfo = $meunService->save($dataMenu);
                }
                //Xóa các quyền định tuyến đã thêm
                if ($crudInfo->route_ids) {
                    $routeService->deleteRoutes($crudInfo->route_ids);
                }
                //Xóa định tuyến quyền
                if ($crudInfo->menu_ids) {
                    app()->make(SystemMenusServices::class)->deleteMenus($crudInfo->menu_ids);
                }
            } else {
                $menuInfo = $meunService->save($dataMenu);
            }
            //Viết quyền tuyến đường
            $cateId = app()->make(SystemRouteServices::class)->topCateId('adminapi', 'CRUD');
            $ruleData = [
                [
                    'path' => $routeName,
                    'method' => 'GET',
                    'name' => $modelName . 'Giao diện danh sách',
                    'app_name' => 'adminapi',
                    'cate_id' => $cateId,
                    'unique_auth' => '',
                    'add_time' => date('Y-m-d H:i:s')
                ],
                [
                    'path' => $routeName . '/create',
                    'method' => 'GET',
                    'name' => $modelName . 'Nhận giao diện tạo biểu mẫu',
                    'app_name' => 'adminapi',
                    'cate_id' => $cateId,
                    'unique_auth' => Str::snake($tableName) . '-add',
                    'add_time' => date('Y-m-d H:i:s')
                ],
                [
                    'path' => $routeName,
                    'method' => 'POST',
                    'name' => $modelName . 'giao diện lưu',
                    'app_name' => 'adminapi',
                    'cate_id' => $cateId,
                    'unique_auth' => '',
                    'add_time' => date('Y-m-d H:i:s')
                ],
                [
                    'path' => $routeName . '/<id>/edit',
                    'method' => 'GET',
                    'name' => $modelName . 'Nhận giao diện biểu mẫu sửa đổi',
                    'app_name' => 'adminapi',
                    'cate_id' => $cateId,
                    'unique_auth' => '',
                    'add_time' => date('Y-m-d H:i:s')
                ],
                [
                    'path' => $routeName . '/<id>',
                    'method' => 'GET',
                    'name' => $modelName . 'Xem giao diện dữ liệu',
                    'app_name' => 'adminapi',
                    'cate_id' => $cateId,
                    'unique_auth' => '',
                    'add_time' => date('Y-m-d H:i:s')
                ],
                [
                    'path' => $routeName . '/<id>',
                    'method' => 'PUT',
                    'name' => $modelName . 'Sửa đổi giao diện',
                    'app_name' => 'adminapi',
                    'cate_id' => $cateId,
                    'unique_auth' => '',
                    'add_time' => date('Y-m-d H:i:s')
                ],
                [
                    'path' => $routeName . '/status/<id>',
                    'method' => 'PUT',
                    'name' => $modelName . 'Sửa đổi giao diện trạng thái',
                    'app_name' => 'adminapi',
                    'cate_id' => $cateId,
                    'unique_auth' => '',
                    'add_time' => date('Y-m-d H:i:s')
                ],
                [
                    'path' => $routeName . '/<id>',
                    'method' => 'DELETE',
                    'name' => $modelName . 'Xóa giao diện',
                    'app_name' => 'adminapi',
                    'cate_id' => $cateId,
                    'unique_auth' => '',
                    'add_time' => date('Y-m-d H:i:s')
                ],
            ];


            $routeList = $routeService->saveAll($ruleData);
            $routeIds = array_column($routeList->toArray(), 'id');

            //Quyền ghi được thêm vào bảng menu
            $menuData = [];
            foreach ($ruleData as $item) {
                $menuData[] = [
                    'pid' => $menuInfo->id ?: 0,
                    'methods' => $item['method'],
                    'api_url' => $item['path'],
                    'unique_auth' => $item['unique_auth'],
                    'menu_name' => $item['name'],
                    'is_del' => 0,
                    'auth_type' => 2,
                ];
            }

            $menus = app()->make(SystemMenusServices::class)->saveAll($menuData);
            $menuIds = array_column($menus->toArray(), 'id');
            //Tạo tập tin
            $make = $this->makeFile($tableName, $routeName, true, $data, $filePath);
            $makePath = [];
            foreach ($make as $key => $item) {
                $makePath[$key] = $item['path'];
            }

            if ($tableCreateInfo && isset($tableCreateInfo['table']) && $tableCreateInfo['table'] instanceof Table) {
                //Tạo cơ sở dữ liệu
                $tableCreateInfo['table']->create();
            }

            $crudDate = [
                'pid' => $data['pid'],
                'name' => $data['menuName'],
                'model_name' => $data['modelName'],
                'table_name' => $tableName,
                'table_comment' => $tableComment,
                'table_collation' => self::TABLR_COLLATION,
                'field' => json_encode($data),//Dữ liệu đã gửi
                'menu_ids' => json_encode($menuIds),//thực đơn đã tạoid
                'menu_id' => $menuInfo->id,//thực đơn đã tạoid
                'make_path' => json_encode($makePath),
                'route_ids' => json_encode($routeIds),
            ];

            if ($crudInfo) {
                $res = $this->dao->update($crudInfo->id, $crudDate);
            } else {
                $crudDate['add_time'] = time();
                //Kỷ lục thế hệ thô
                $res = $this->dao->save($crudDate);
            }

            return $res;
        });

        return $res->toArray();
    }

    /**
     * Nhận cấu hình cơ sở dữ liệu
     * @return array
     */
    protected function getDbConfig(): array
    {
        $default = app()->config->get('database.default');

        $config = app()->config->get("database.connections.{$default}");

        if (0 == $config['deploy']) {
            $dbConfig = [
                'adapter' => $config['type'],
                'host' => $config['hostname'],
                'name' => $config['database'],
                'user' => $config['username'],
                'pass' => $config['password'],
                'port' => $config['hostport'],
                'charset' => $config['charset'],
                'table_prefix' => $config['prefix'],
            ];
        } else {
            $dbConfig = [
                'adapter' => explode(',', $config['type'])[0],
                'host' => explode(',', $config['hostname'])[0],
                'name' => explode(',', $config['database'])[0],
                'user' => explode(',', $config['username'])[0],
                'pass' => explode(',', $config['password'])[0],
                'port' => explode(',', $config['hostport'])[0],
                'charset' => explode(',', $config['charset'])[0],
                'table_prefix' => explode(',', $config['prefix'])[0],
            ];
        }

        $table = app()->config->get('database.migration_table', 'migrations');

        $dbConfig['default_migration_table'] = $dbConfig['table_prefix'] . $table;

        return $dbConfig;
    }

    public function getAdapter()
    {
        $options = $this->getDbConfig();

        $adapter = AdapterFactory::instance()->getAdapter($options['adapter'], $options);

        if ($adapter->hasOption('table_prefix') || $adapter->hasOption('table_suffix')) {
            $adapter = AdapterFactory::instance()->getWrapper('prefix', $adapter);
        }

        return $adapter;
    }

    /**
     * Tạo cơ sở dữ liệu
     * @param string $tableName
     * @param string $tableComment
     * @param array $tableField
     * @return array
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function makeDatebase(string $tableName, string $tableComment, array $tableField = [], string $collation = self::TABLR_COLLATION)
    {
        $timestampsField = [];
        $softDelete = false;
        $timestamps = false;
        $indexField = [];
        //Tạo bảng
        $table = new Table($tableName, ['comment' => $tableComment, 'collation' => $collation], $this->getAdapter());
        //Tạo trường
        foreach ($tableField as $item) {
            if (isset($item['primaryKey']) && $item['primaryKey']) {
                continue;
            }
            $option = [];
            if (isset($item['limit'])) {
                $option['limit'] = (int)$item['limit'];
            }
            if (isset($item['default']) && isset($item['default_type'])) {
                switch ($item['default_type']) {
                    case '1'://Tùy chỉnh
                        $option['default'] = $item['default'];
                        break;
                    case '2'://vìnull
                        $option['null'] = true;
                        break;
                    case '3'://thời gian
                        $option['default'] = Db::raw('CURRENT_TIMESTAMP');
                        break;
                }
            }
            //Tạo giả xóa
            if ($item['field_type'] === 'addSoftDelete') {
                $table->addSoftDelete();
                $softDelete = true;
            } else if ($item['field_type'] == 'timestamp' &&
                ($item['field'] === 'create_time' || $item['field'] === 'update_time')) {
                $timestampsField[] = $item;
            } else {
                $option['comment'] = $item['comment'];
                $fieldType = $this->changeTabelRule($item['field_type']);
                if (in_array($fieldType, ['text', 'longtext', 'tinytext'])) {
                    unset($option['limit']);
                }
                //Xác định loại trường
                if ($fieldType == 'boolean' && isset($option['default']) && $option['default'] === '') {
                    unset($option['default']);
                }
                //Tương thích với các trường enum
                if ($fieldType == 'enum') {
                    unset($option['limit']);
                    $option['values'] = $item['limit'];
                }
                $table->addColumn($item['field'], $this->changeTabelRule($item['field_type']), $option);
            }
        }
        //Tạo chỉ mục
        if (!empty($data['tableIndex'])) {
            $indexField = $data['tableIndex'];
            foreach ($data['tableIndex'] as $item) {
                $table->addIndex($item);
            }
        }

        //Nếu create_time và update_time xuất hiện theo cặp, hãy trực tiếp tăng thời gian sửa đổi và bổ sung.
        if (count($timestampsField) == 2) {
            //Tạo sửa đổi và thêm thời gian
            $table->addTimestamps();
            $timestamps = true;
        } else {
            //Nếu là mảng thì thêm cột
            foreach ($timestampsField as $item) {
                $option['comment'] = $item['comment'];
                $table->addColumn($item['field'], $this->changeTabelRule($item['field_type']), $option);
            }
        }

        return compact('indexField', 'softDelete', 'timestamps', 'table');
    }

    /**
     * Tạo file trả về đường dẫn và nội dung file
     * @param string $tableName
     * @param string $routeName
     * @param bool $isMake
     * @param array $options
     * @param array $filePath
     * @param string $basePath
     * @return array[]
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function makeFile(string $tableName, string $routeName, bool $isMake = false, array $options = [], array $filePath = [], string $basePath = '')
    {
        $options['fromField'] = is_array($options['fromField']) ? $options['fromField'] : [];
        $options['columnField'] = is_array($options['columnField']) ? $options['columnField'] : [];
        //Tạo mô hình
        $model = app()->make(Model::class);
        $model->setFilePathName($filePath['model'] ?? '')->setbasePath($basePath)->handle($tableName, $options);
        //phát radao
        $dao = app()->make(Dao::class);
        $dao->setFilePathName($filePath['dao'] ?? '')->setbasePath($basePath)->handle($tableName, [
            'usePath' => $model->getUsePath(),
            'modelName' => $options['modelName'] ?? '',
            'searchField' => $options['searchField'] ?? [],
        ]);
        //phát raservice
        $service = app()->make(Service::class);
        $service->setFilePathName($filePath['service'] ?? '')->setbasePath($basePath)->handle($tableName, [
            'field' => $options['fromField'],
            'columnField' => $options['columnField'],
            'key' => $options['key'],
            'usePath' => $dao->getUsePath(),
            'modelName' => $options['modelName'] ?? '',
            'hasOneField' => $options['hasOneField'] ?? [],
        ]);
        //Tạo trình xác thực
        $validate = app()->make(Validate::class);
        $validate->setFilePathName($filePath['validate'] ?? '')->setbasePath($basePath)->handle($tableName, [
            'field' => $options['fromField'],
            'modelName' => $options['modelName'] ?? '',
        ]);
        //Tạo bộ điều khiển
        $controller = app()->make(Controller::class);
        $controller->setFilePathName($filePath['controller'] ?? '')->setbasePath($basePath)->handle($tableName, [
            'usePath' => $service->getUsePath(),
            'modelName' => $options['modelName'] ?? '',
            'searchField' => $options['searchField'] ?? [],
            'columnField' => $options['columnField'] ?? [],
            'validateName' => '\\' . str_replace('/', '\\', $validate->getUsePath()) . 'Validate::class',
            'field' => array_column($options['fromField'], 'field'),
        ]);
        //Tạo tuyến đường
        $route = app()->make(Route::class);
        $route->setFilePathName($filePath['route'] ?? '')->setbasePath($basePath)->handle($tableName, [
            'menus' => $options['modelName'] ?? $options['menuName'],
            'route' => $routeName
        ]);
        //Tạo tuyến đường giao diện người dùng
        $viewRouter = app()->make(ViewRouter::class);
        $viewRouter->setFilePathName($filePath['router'] ?? '')->setbasePath($basePath)->handle($tableName, [
            'route' => $routeName,
            'menuName' => $options['menuName'],
            'modelName' => $options['modelName'] ?? $options['menuName'],
        ]);
        //Tạo giao diện front-end
        $viewApi = app()->make(ViewApi::class);
        $viewApi->setFilePathName($filePath['api'] ?? '')->setbasePath($basePath)->handle($tableName, [
            'route' => $routeName,
        ]);

        //Tạo trang đầu
        $viewPages = app()->make(ViewPages::class);
        $viewPages->setFilePathName($filePath['pages'] ?? '')->setbasePath($basePath)->handle($tableName, [
            'field' => $options['columnField'],
            'tableFields' => $options['tableField'] ?? [],
            'searchField' => $options['searchField'] ?? [],
            'route' => $routeName,
            'key' => $options['key'],
            'pathApiJs' => '@/' . str_replace('\\', '/', str_replace([Make::adminTemplatePath(), '.js'], '', $viewApi->getPath())),
        ]);

        //Tạo tập tin
        if ($isMake) {
            FileService::batchMakeFiles([$model, $validate, $dao, $service, $controller, $route, $viewApi, $viewPages, $viewRouter]);
        }

        return [
            'controller' => [
                'path' => $this->replace($controller->getPath()),
                'content' => $controller->getContent()
            ],
            'model' => [
                'path' => $this->replace($model->getPath()),
                'content' => $model->getContent()
            ],
            'dao' => [
                'path' => $this->replace($dao->getPath()),
                'content' => $dao->getContent()
            ],
            'route' => [
                'path' => $this->replace($route->getPath()),
                'content' => $route->getContent()
            ],
            'service' => [
                'path' => $this->replace($service->getPath()),
                'content' => $service->getContent()
            ],
            'validate' => [
                'path' => $this->replace($validate->getPath()),
                'content' => $validate->getContent()
            ],
            'router' => [
                'path' => $this->replace($viewRouter->getPath()),
                'content' => $viewRouter->getContent()
            ],
            'api' => [
                'path' => $this->replace($viewApi->getPath()),
                'content' => $viewApi->getContent()
            ],
            'pages' => [
                'path' => $this->replace($viewPages->getPath()),
                'content' => $viewPages->getContent()
            ],
        ];
    }

    protected function replace(string $path)
    {
        return str_replace([app()->getRootPath(), Make::adminTemplatePath()], '', $path);
    }

    /**
     * @param string $tableName
     * @param bool $fullName
     * @return string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function getTableName(string $tableName, bool $fullName = true)
    {
        $tablePrefix = config('database.connections.mysql.prefix');
        $pattern = '/^' . $tablePrefix . '/i';
        return ($fullName ? $tablePrefix : '') . (preg_replace($pattern, '', $tableName));
    }

}
