// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2024 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

import request from "@/utils/request.js";

/**
 * Nhận chi tiết xổ số
 * 
 */
export function getLotteryData(type, lottery_id) {
	return request.get(`v2/lottery/info/${type}${lottery_id ? '/' + lottery_id : ''}`);
}

/**
 * Tham gia xổ số
 * 
 */
export function startLottery(data) {
	return request.post(`v2/lottery`, data);
}

/**
 * Nhận giải thưởng
 * 
 */
export function receiveLottery(data) {
	return request.post(`v2/lottery/receive`, data);
}

/**
 * Nhận kỷ lục chiến thắng
 * 
 */
export function getLotteryList(data) {
	return request.get(`v2/lottery/record`, data);
}