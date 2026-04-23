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

namespace crmeb\traits;

use Firebase\JWT\JWT;
use think\facade\Env;

/**
 * Trait JwtAuthModelTrait
 * @package crmeb\traits
 */
trait JwtAuthModelTrait
{
    /**
     * @param string $type
     * @param array $params
     * @return array
     */
    public function getToken(string $type, array $params = []): array
    {
        $id = $this->{$this->getPk()};
        $host = app()->request->host();
        $time = time();

        $params += [
            'iss' => $host,
            'aud' => $host,
            'iat' => $time,
            'nbf' => $time,
            'exp' => strtotime('+30 days'),
        ];
        $params['jti'] = compact('id', 'type');
        $token = JWT::encode($params, Env::get('app.app_key', 'default'));

        return compact('token', 'params');
    }

    /**
     * @param string $jwt
     * @return array
     *
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function parseToken(string $jwt): array
    {
        JWT::$leeway = 60;

        $data = JWT::decode($jwt, Env::get('app.app_key', 'default'), array('HS256'));

        $model = new self();
        return [$model->where($model->getPk(), $data->jti->id)->find(), $data->jti->type];
    }
}
