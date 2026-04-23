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
namespace crmeb\services\easywechat\open3rd;


/**
 * Class ProgramWechatLive
 * @package crmeb\services\wechatlive
 */
class ProgramOpen3rd
{
    /**
     * @var AccessToken
     */
    protected $accessToken;

    /**
     * Mã ủy quyền trước
     */
    const PRE_AUTH_CODE = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode';

    /**
     * Lấy thông tin tài khoản cơ bản của bên được ủy quyền
     */
    const GET_AUTHORIZER_INFO = 'https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info';
    /**
     * Nhận danh sách trải nghiệm thực tế
     */
    const MEMBER_AUTH_LIST = 'https://api.weixin.qq.com/wxa/memberauth';
    /**
     * Ràng buộc người trải nghiệm
     */
    const BIND_MEMBER_AUTH = 'https://api.weixin.qq.com/wxa/bind_tester';
    /**
     * Bỏ ràng buộc người trải nghiệm
     */
    const UNBIND_MEMBER_AUTH = 'https://api.weixin.qq.com/wxa/unbind_tester';
    /**
     * Nhận danh sách các bản nháp mã
     */
    const DRAFT_LIST = 'https://api.weixin.qq.com/wxa/gettemplatedraftlist';
    /**
     * Thêm bản nháp vào thư viện mẫu mã
     */
    const ADD_TO_TEMPLATE = 'https://api.weixin.qq.com/wxa/addtotemplate';
    /**
     * Nhận danh sách các mẫu mã
     */
    const TEMPLATE_LIST = 'https://api.weixin.qq.com/wxa/gettemplatelist';
    /**
     * Xóa mẫu mã được chỉ định
     */
    const DEL_TEMPLATE = 'https://api.weixin.qq.com/wxa/deletetemplate';
    /**
     * Tải mã lên
     */
    const COMMIT = 'https://api.weixin.qq.com/wxa/commit';
    /**
     * Lấy danh sách các trang mã đã tải lên
     */
    const GET_PAGE = 'https://api.weixin.qq.com/wxa/get_page';
    /**
     * Nhận mã QR trải nghiệm
     */
    const GET_QRCODE = 'https://api.weixin.qq.com/wxa/get_qrcode';
    /**
     * Gửi mã để xem xét
     */
    const SUBMIT_AUDIT = 'https://api.weixin.qq.com/wxa/submit_audit';
    /**
     * Truy vấn trạng thái xem xét của một phiên bản được chỉ định
     */
    const GET_AUDIT_STATUS = 'https://api.weixin.qq.com/wxa/get_auditstatus';
    /**
     * Kiểm tra trạng thái xem xét gửi mới nhất
     */
    const GET_LATEST_AUDIT_STATUS = 'https://api.weixin.qq.com/wxa/get_latest_auditstatus';
    /**
     * Rút lại đánh giá
     */
    const UNDO_CODE_AUDIT = 'https://api.weixin.qq.com/wxa/undocodeaudit';
    /**
     * Xuất bản các chương trình nhỏ đã qua đánh giá
     */
    const RELEASE = 'https://api.weixin.qq.com/wxa/release';
    /**
     * Phát hành theo giai đoạn
     */
    const GRAY_RELEASE = 'https://api.weixin.qq.com/wxa/grayrelease';
    /**
     * Khôi phục phiên bản
     */
    const REVERT_CODE_RELEASE = 'https://api.weixin.qq.com/wxa/revertcoderelease';

    /**
     * ProgramOpen3rd constructor.
     * @param AccessToken $accessToken
     */
    public function __construct(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
        $this->config = $accessToken->getConfig();
    }


    /**
     * Nhận mã ủy quyền trước
     * @return array|bool|mixed
     */
    public function getPreAuthCode()
    {
        return $this->accessToken->httpRequest(self::PRE_AUTH_CODE, [], false);
    }

    /**
     * Nhận ủy quyền
     * @param $authorization_code
     * @return authorizer_appid
     */
    public function getAuth($authorization_code)
    {
        return $this->accessToken->getAuthorizationInfo($authorization_code);
    }

    /**
     * Lấy thông tin cơ bản của tài khoản của người ủy quyền
     * @param $authorizer_appid
     * @return array|bool|mixed
     */
    public function getAuthorizerinfo(string $authorizer_appid)
    {
        return $this->accessToken->httpRequest(self::GET_AUTHORIZER_INFO, ['authorizer_appid' => $authorizer_appid], false);
    }

    /**
     * Nhận danh sách người trải nghiệm được ủy quyền
     * @return array|bool|mixed
     */
    public function getMemberAuthList()
    {
        return $this->accessToken->httpRequest(self::MEMBER_AUTH_LIST, ['action' => 'get_experiencer']);
    }

    /**
     * Ràng buộc người trải nghiệm
     * @param string $wechatid
     * @return array|bool|mixed
     */
    public function bindMemberAuth(string $wechatid)
    {
        return $this->accessToken->httpRequest(self::BIND_MEMBER_AUTH, ['wechatid' => $wechatid]);
    }

    /**
     * Bỏ ràng buộc người trải nghiệm
     * @param string $wechatid
     * @param string $userstr
     * @return array|bool|mixed
     */
    public function unBindMemberAuth(string $wechatid, string $userstr = '')
    {
        $data = ['wechatid' => $wechatid];
        if ($userstr) $data['userstr'] = $userstr;
        return $this->accessToken->httpRequest(self::UNBIND_MEMBER_AUTH, $data);
    }

    /**
     * Nhận danh sách dự thảo
     * @return array|bool|mixed
     */
    public function getDraftList()
    {
        return $this->accessToken->httpRequest(self::DRAFT_LIST);
    }

    /**
     * Thêm bản nháp vào mẫu mã
     * @param $draft_id
     * @return array|bool|mixed
     */
    public function addToTemplate($draft_id)
    {
        return $this->accessToken->httpRequest(self::ADD_TO_TEMPLATE, ['draft_id' => $draft_id]);
    }

    /**
     * Nhận danh sách các mẫu mã
     * @return array|bool|mixed
     */
    public function getTemplateList()
    {
        return $this->accessToken->httpRequest(self::TEMPLATE_LIST);
    }

    /**
     * Xóa mẫu đã chỉ định
     * @param $template_id
     * @return array|bool|mixed
     */
    public function delTemplate($template_id)
    {
        return $this->accessToken->httpRequest(self::DEL_TEMPLATE, ['template_id' => $template_id]);
    }

    /**
     * Tải lên mã
     * @param $template_id
     * @param string $ext_json
     * @param string $user_version
     * @param string $user_desc
     * @return array|bool|mixed
     */
    public function commit($template_id, string $ext_json, string $user_version, string $user_desc = '')
    {
        return $this->accessToken->httpRequest(self::COMMIT, ['template_id' => $template_id, 'ext_json' => $ext_json, 'user_version' => $user_version, 'user_desc' => $user_desc]);
    }

    /**
     * Lấy danh sách mã đã tải lên
     * @return array|bool|mixed
     */
    public function getPage()
    {
        return $this->accessToken->httpRequest(self::GET_PAGE, []);
    }

    /**
     * Nhận mã QR trải nghiệm
     * @return array|bool|mixed
     */
    public function getQrcode($path = '')
    {
        return $this->accessToken->httpRequest(self::GET_QRCODE, ['path' => $path], true, 'GET');
    }

    /**
     * Gửi để xem xét
     * @param array $data
     * @param data = [
     * 'item_list' => [],//Danh sách các mục kiểm tra (không bắt buộc, điền tối đa 5 mục)）
     * 'preview_info' => (object)[],//Thông tin xem trước (ảnh chụp màn hình trang chương trình nhỏ và bản ghi màn hình hoạt động）
     * 'version_desc' => '',//Mô tả phiên bản chương trình nhỏ và giải thích chức năng
     * 'feedback_info' => '',//Nội dung phản hồi, tối đa 200 từ
     * 'feedback_stuff' => '',//sử dụng | Chia danh sách media_ids, tối đa 5 hình ảnh, Nó có thể thu được bằng cách tải lên thông qua giao diện vật liệu tạm thời mới.
     * 'ugc_declare' => (object)[],//Tuyên bố bảo mật thông tin cho các kịch bản nội dung do người dùng tạo (UGC)
     * ];
     * @return array|bool|mixed
     */
    public function submitAudit($data = [])
    {
        $base = [
            'item_list' => [],
            'preview_info' => (object)[],
            'version_desc' => '',
            'feedback_info' => '',
            'feedback_stuff' => '',
            'ugc_declare' => (object)[],
        ];
        $data = array_merge($base, $data);
        return $this->accessToken->httpRequest(self::SUBMIT_AUDIT, $data);
    }

    /**
     * Truy vấn trạng thái xem xét của một phiên bản được chỉ định
     * @param string $auditid
     * @return array|bool|mixed
     */
    public function getAuditStatus(string $auditid)
    {
        return $this->accessToken->httpRequest(self::GET_AUDIT_STATUS, ['auditid' => $auditid]);
    }

    /**
     * Nhận trạng thái đánh giá lần gửi cuối cùng
     * @return array|bool|mixed
     */
    public function getLatestAuditStatus()
    {
        return $this->accessToken->httpRequest(self::GET_LATEST_AUDIT_STATUS, [], true, 'GET');
    }

    /**
     * Xem lại việc rút tiền
     * @return array|bool|mixed
     */
    public function undoAudit()
    {
        return $this->accessToken->httpRequest(self::UNDO_CODE_AUDIT, [], true, 'GET');
    }

    /**
     * Xuất bản chương trình mini đã vượt qua đánh giá
     * @return array|bool|mixed
     */
    public function release()
    {
        return $this->accessToken->httpRequest(self::RELEASE);
    }

    /**
     * Phát hành theo giai đoạn
     * @param int $gray_percentage 1-100số nguyên
     * @return mixed
     */
    public function grayRelease(int $gray_percentage)
    {
        return $this->accessToken->httpRequest(self::GRAY_RELEASE, ['gray_percentage' => $gray_percentage]);
    }

    /**
     * Khôi phục phiên bản
     * @return array|bool|mixed
     */
    public function revertCodeRelease()
    {
        return $this->accessToken->httpRequest(self::REVERT_CODE_RELEASE, [], true, 'GET');
    }
}