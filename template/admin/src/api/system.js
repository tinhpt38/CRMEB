// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

import request from '@/libs/request';

/**
 * @description Phân loại cấu hình--danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function classListApi(data) {
  return request({
    url: 'setting/config_class',
    method: 'get',
    params: data,
  });
}

/**
 * @description Định cấu hình phân loại--thêm biểu mẫu
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function classAddApi(data) {
  return request({
    url: 'setting/config_class/create',
    method: 'get',
  });
}

/**
 * @description Định cấu hình phân loại--chỉnh sửa biểu mẫu
 * @param {Number} param id {Number} Phân loại cấu hìnhid
 */
export function classEditApi(id) {
  return request({
    url: `setting/config_class/${id}/edit`,
    method: 'get',
  });
}

/**
 * @description Phân loại cấu hình--trạng thái sửa đổi
 * @param {Number} param id {Number} bài báoid
 */
export function setStatusApi(data) {
  return request({
    url: `setting/config_class/set_status/${data.id}/${data.status}`,
    method: 'PUT',
  });
}

/**
 * @description Cấu hình--Danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function configTabListApi(data) {
  return request({
    url: 'setting/config',
    method: 'get',
    params: data,
  });
}

/**
 * @description Cấu hình--Thêm biểu mẫu
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function configTabAddApi(data) {
  return request({
    url: 'setting/config/create',
    method: 'get',
    params: data,
  });
}

/**
 * @description Cấu hình--chỉnh sửa biểu mẫu
 * @param {Number} param id {Number} Cấu hìnhid
 */
export function configTabEditApi(id) {
  return request({
    url: `/setting/config/${id}/edit`,
    method: 'get',
  });
}

/**
 * @description Cấu hình--Sửa đổi trạng thái
 * @param {Number} param id {Number} bài báoid
 */
export function configSetStatusApi(id, status) {
  return request({
    url: `setting/config/set_status/${id}/${status}`,
    method: 'PUT',
  });
}

/**
 * @description Dữ liệu kết hợp--danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function groupListApi(data) {
  return request({
    url: 'setting/group',
    method: 'get',
    params: data,
  });
}

/**
 * @description Dữ liệu kết hợp--mới
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function groupAddApi(data) {
  return request({
    url: data.url,
    method: data.method,
    data: data.datas,
  });
}

/**
 * @description Dữ liệu kết hợp--Chi tiết
 * @param {Number} param id {Number} Dữ liệu kết hợpid
 */
export function groupInfoApi(id) {
  return request({
    url: `setting/group/${id}`,
    method: 'get',
  });
}

/**
 * @description Danh sách dữ liệu kết hợp
 * @param {Number} param id {Number} Dữ liệu kết hợpid
 */
export function groupDataListApi(id, url) {
  return request({
    url: url,
    method: 'get',
    params: id,
  });
}

/**
 * @description Danh sách dữ liệu kết hợp - biểu mẫu mới
 * @param {Number} param id {Number} Dữ liệu kết hợpid
 */
export function groupDataAddApi(id, url) {
  return request({
    url: url,
    method: 'get',
    params: id,
  });
}

/**
 * @description Danh sách dữ liệu tổng hợp - chỉnh sửa biểu mẫu
 * @param {Number} param id {Number} Danh sách dữ liệu kết hợpid
 * @param {Object} param data {Object} Đối tượng id dữ liệu kết hợp
 */
export function groupDataEditApi(data, url) {
  return request({
    url: url,
    method: 'get',
    params: data,
  });
}

/**
 * @description Danh sách dữ liệu tổng hợp - chỉnh sửa biểu mẫu
 * @param {Number} param id {Number} Dữ liệu kết hợpid
 */
export function groupDataHeaderApi(data, url) {
  return request({
    url: url,
    method: 'get',
    params: data,
  });
}

/**
 * @description Danh sách dữ liệu kết hợp - sửa đổi trạng thái
 * @param {Object} param data {Object} Giá trị truyền danh sách dữ liệu kết hợp
 */
export function groupDataSetApi(url) {
  return request({
    url: url,
    method: 'PUT',
  });
}

/**
 * @description Nhật ký hệ thống - tiêu chí tìm kiếm
 */
export function searchAdminApi(data) {
  return request({
    url: `system/log/search_admin`,
    method: 'GET',
  });
}

/**
 * @description Nhật ký hệ thống - tiêu chí tìm kiếm
 */
export function systemListApi(params) {
  return request({
    url: `system/log`,
    method: 'GET',
    params,
  });
}

/**
 * @description Xác minh tập tin - danh sách
 */
export function fileListApi() {
  return request({
    url: `system/file`,
    method: 'GET',
  });
}

/**
 * @description Sao lưu dữ liệu - danh sách cơ sở dữ liệu
 */
export function backupListApi() {
  return request({
    url: `system/backup`,
    method: 'GET',
  });
}

/**
 * @description Sao lưu dữ liệu - xem chi tiết cấu trúc bảng
 */
export function backupReadListApi(params) {
  return request({
    url: `system/backup/read`,
    method: 'GET',
    params,
  });
}

/**
 * @description Sao lưu dữ liệu - bảng sao lưu
 */
export function backupBackupApi(data) {
  return request({
    url: `system/backup/backup`,
    method: 'put',
    data,
  });
}

/**
 * @description Sao lưu dữ liệu -- Bảng tối ưu hóa
 */
export function backupOptimizeApi(data) {
  return request({
    url: `system/backup/optimize`,
    method: 'put',
    data,
  });
}

/**
 * @description Sao lưu dữ liệu - bảng sửa chữa
 */
export function backupRepairApi(data) {
  return request({
    url: `system/backup/repair`,
    method: 'put',
    data,
  });
}

/**
 * @description Sao lưu dữ liệu - sao lưu bảng ghi
 */
export function filesListApi(data) {
  return request({
    url: `system/backup/file_list`,
    method: 'GET',
  });
}

/**
 * @description Sao lưu dữ liệu - tải xuống bảng ghi bản sao lưu
 */
export function filesDownloadApi(params) {
  return request({
    url: `backup/download`,
    method: 'get',
    params,
  });
}

/**
 * @description Sao lưu dữ liệu - nhập khẩu
 */
export function filesImportApi(data) {
  return request({
    url: `system/backup/import`,
    method: 'POST',
    data,
  });
}

/**
 * @description Quản lý tập tin -- Đăng nhập
 */
export function opendirLoginApi(data) {
  return request({
    url: `system/file/login`,
    method: 'POST',
    data,
  });
}

/**
 * @description Quản lý tập tin - danh sách
 */
export function opendirListApi(params) {
  return request({
    url: `system/file/opendir`,
    method: 'GET',
    params,
    file_edit: true,
  });
}

/**
 * @description Quản lý tập tin - đọc tập tin
 */
export function openfileApi(params) {
  return request({
    url: `system/file/openfile`,
    method: 'GET',
    params,
    file_edit: true,
  });
}

/**
 * @description Quản lý tập tin - lưu
 */
export function savefileApi(data) {
  return request({
    url: `system/file/savefile?fileToken=${data.fileToken}`,
    method: 'post',
    data,
    file_edit: true,
  });
}
/**
 * @description Quản lý tập tin - tạo thư mục mới
 */
export function createFolder(params) {
  return request({
    url: `system/file/createFolder`,
    method: 'GET',
    params,
    file_edit: true,
  });
}
/**
 * @description Quản lý tập tin - tạo tập tin mới
 */
export function createFile(params) {
  return request({
    url: `system/file/createFile`,
    method: 'GET',
    params,
    file_edit: true,
  });
}
/**
 * @description Quản lý tập tin - xóa tập tin hoặc thư mục
 */
export function rename(params) {
  return request({
    url: `system/file/rename`,
    method: 'GET',
    params,
    file_edit: true,
  });
}
/**
 * @description Quản lý tập tin - xóa tập tin hoặc thư mục
 */
export function delFolder(params) {
  return request({
    url: `system/file/delFolder`,
    method: 'GET',
    params,
    file_edit: true,
  });
}

/**
 * Tập tin nhận xét
 * @param {*} id
 * @param {*} params
 * @returns
 */
export function fileMark(params) {
  return request({
    url: `system/file/mark`,
    method: 'get',
    params,
    file_edit: true,
  });
}

/**
 * @description Bảo trì bảo mật - thay đổi tên miền
 */
export function replaceSiteUrlApi(data) {
  return request({
    url: `system/replace_site_url`,
    method: 'post',
    data,
  });
}

/**
 *
 */
export function auth() {
  return request({
    url: 'auth',
    method: 'get',
  });
}

/**
 * @description Nộp đơn xin ủy quyền
 * @param data
 */
export function authApply(data) {
  return request({
    url: 'auth_apply',
    method: 'post',
    data,
  });
}

/**
 * @description Nhận quảng cáo trang dịch vụ khách hàng
 * @param data
 */
export function getKfAdv() {
  return request({
    url: 'setting/get_kf_adv',
    method: 'get',
  });
}

/**
 * @description Thiết lập quảng cáo trang dịch vụ khách hàng
 * @param data
 */
export function setKfAdv(data) {
  return request({
    url: 'setting/set_kf_adv',
    method: 'post',
    data,
  });
}

/**
 * @description Cấu hình dữ liệu
 * @param data
 */
export function groupAllApi() {
  return request({
    url: 'setting/group_all',
    method: 'get',
  });
}
/**
 * APPDanh sách phiên bản
 */
export function versionList(params) {
  return request({
    url: `system/version_list`,
    method: 'get',
    params,
  });
}
/**
 * APPDanh sách phiên bản
 */
export function versionCrate(id) {
  return request({
    url: `system/version_crate/${id}`,
    method: 'get',
  });
}

/**
 * @description Lưu cấu hình dữ liệu
 */
export function groupSaveApi(data) {
  return request({
    url: `setting/group_data/save_all`,
    method: 'POST',
    data,
  });
}

/**
 * @description Lưu cấu hình dữ liệu trang khởi động
 */
export function openAdvSave(data) {
  return request({
    url: `diy/open_adv/add`,
    method: 'POST',
    data,
  });
}

/**
 * @description Lưu cấu hình dữ liệu trang khởi động
 */
export function getOpenAdv() {
  return request({
    url: `diy/open_adv/info`,
    method: 'get',
  });
}

/**
 * @description Tải phiên bản PC malllogo
 */
export function pcLogoApi(id) {
  return request({
    url: `setting/config/get_system/${id}`,
    method: 'get',
  });
}

/**
 * @description pcBên trung tâm thương mạilogo
 */
export function pcLogoSave(data) {
  return request({
    url: `setting/config/save_basics`,
    method: 'POST',
    data,
  });
}
/**
 * @description Nhận thỏa thuận về quyền riêng tư
 * @param data
 */
export function getAgreement() {
  return request({
    url: 'setting/get_user_agreement',
    method: 'get',
  });
}

/**
 * @description Đặt thỏa thuận quyền riêng tư
 * @param data
 */
export function setAgreement(data) {
  return request({
    url: 'setting/set_user_agreement',
    method: 'post',
    data,
  });
}

/**
 * @description Nhận thỏa thuận
 * @param data
 */
export function getAgreements(type) {
  return request({
    url: `setting/get_agreement/${type}`,
    method: 'get',
  });
}
/**
 * @description Đặt thỏa thuận quyền riêng tư
 * @param data
 */
export function setAgreements(data, type) {
  return request({
    url: `setting/save_agreement`,
    method: 'post',
    data,
  });
}

/**
 * @description Nhận sản phẩm ủy quyền
 */
export function crmebProduct(params) {
  return request({
    url: 'crmeb_product',
    method: 'get',
    params,
  });
}

/**
 * @description Nhận lệnh ủy quyền
 */
export function getVersion() {
  return request({
    url: `setting/get_version`,
    method: 'get',
  });
}

/**
 * @description Nhận bản quyền
 */
export function getCrmebCopyRight() {
  return request({
    url: `copyright`,
    method: 'get',
  });
}

/**
 * @description lưu bản quyền
 */
export function saveCrmebCopyRight(data) {
  return request({
    url: `copyright`,
    method: 'post',
    data,
  });
}

/**
 * @description Gói nâng cấp -- danh sách
 * @param data
 */
export function upgradeListApi(params) {
  return request({
    url: '/system/upgrade/list',
    method: 'get',
    params,
  });
}

/**
 * @description Tiến độ nâng cấp
 */
export function upgradeProgressApi() {
  return request({
    url: `/system/upgrade_progress`,
    method: 'get',
  });
}

/**
 * @description thỏa thuận nâng cấp
 */
export function upgradeAgreementApi() {
  return request({
    url: `/system/upgrade/agreement`,
    method: 'get',
  });
}

/**
 * @description Trạng thái nâng cấp
 */
export function upgradeStatusApi() {
  return request({
    url: `/system/upgrade_status`,
    method: 'get',
  });
}

/**
 * @description Tiến trình tải xuống
 */
export function downloadProgressApi(data) {
  return request({
    url: `/system/upgrade_download/progress`,
    method: 'get',
    params: data,
  });
}

export function upgradeIgnoreFileApi() {
  return request({
    url: `/system/upgrade/ignore_file`,
    method: 'get',
  });
}

/**
 * @description Gói nâng cấp - hồ sơ nâng cấp
 * @param data
 */
export function upgradeLogListApi(params) {
  return request({
    url: '/system/upgrade_log/list',
    method: 'get',
    params,
  });
}

/**
 * Xuất tập tin sao lưu
 */
export function upgradeExportApi(id) {
  return request({
    url: `system/upgrade_export/${id}`,
    method: 'get',
    responseType: 'blob',
  });
}

/**
 * @description Tải gói nâng cấp
 */
export function downloadApi(params) {
  return request({
    url: '/system/package_download/' + params,
    method: 'POST',
  });
}

/**
 * @description Gói nâng cấp - danh sách có thể nâng cấp
 * @param data
 */
export function upgradeableListApi(params) {
  return request({
    url: '/system/upgradeable/list',
    method: 'get',
    params,
  });
}

/**
 * Danh sách nhiệm vụ theo lịch trình
 * @param {*} params
 * @returns
 */
export function timerIndex(params) {
  return request({
    url: `system/crontab/list`,
    params,
  });
}

/**
 * Sửa đổi trạng thái nhiệm vụ đã lên lịch
 * @param {*} params
 * @returns
 */
export function showTimer(id, is_open) {
  return request({
    url: `system/crontab/set_open/${id}/${is_open}`,
  });
}

/**
 * Nhận thông tin nhiệm vụ theo lịch trình
 * @param {*} params
 * @returns
 */
export function timerInfo(id) {
  return request({
    url: `system/crontab/info/${id}`,
  });
}

/**
 * Lưu các nhiệm vụ theo lịch trình
 * @param {*} data
 * @returns
 */
export function saveTimer(data) {
  return request({
    url: `system/crontab/save`,
    method: 'post',
    data,
  });
}

/**
 * Cập nhật nhiệm vụ theo lịch trình
 * @param {*} id
 * @param {*} data
 * @returns
 */
export function updateTimer(id, data) {
  return request({
    url: `system/crontab/update/${id}`,
    method: 'post',
    data,
  });
}
/**
 * Cập nhật ghi chú
 * @param {*} data
 * @returns
 */
export function updateMark(data) {
  return request({
    url: `system/database/update_mark`,
    method: 'post',
    data,
  });
}
/**
 * Ghi chú cập nhật quản lý tập tin
 * @param {*} data
 * @returns
 */
export function markSave(fileToken, data) {
  return request({
    url: `system/file/mark/save?fileToken=${fileToken}`,
    method: 'post',
    data,
  });
}

/**
 * Tên nhiệm vụ theo lịch trình và mã định danh
 * @returns
 */
export function timerTask() {
  return request({
    url: `system/crontab/mark`,
  });
}

// ----Sự kiện tùy chỉnh

/**
 * Danh sách sự kiện tùy chỉnh
 * @param {*} params
 * @returns
 */
export function eventIndex(params) {
  return request({
    url: `system/event/list`,
    params,
  });
}

/**
 * Trạng thái sửa đổi sự kiện tùy chỉnh
 * @param {*} params
 * @returns
 */
export function eventShowTimer(id, is_open) {
  return request({
    url: `system/event/set_open/${id}/${is_open}`,
  });
}

/**
 * Thông tin sự kiện tùy chỉnh
 * @param {*} params
 * @returns
 */
export function eventInfo(id) {
  return request({
    url: `system/event/info/${id}`,
  });
}

/**
 * Lưu sự kiện tùy chỉnh
 * @param {*} data
 * @returns
 */
export function eventSave(data) {
  return request({
    url: `system/event/save`,
    method: 'post',
    data,
  });
}
/**
 * Cập nhật sự kiện tùy chỉnh
 * @returns
 */
export function eventTask() {
  return request({
    url: `system/event/mark`,
  });
}

/**
 * Thông tin danh sách module bản quyền
 * @returns
 */
export function copyrightList() {
  return request({
    url: `system/info`,
  });
}

// ==================== Giao diện nâng cấp đa phiên bản ====================

/**
 * Kiểm tra nâng cấp phiên bản chéo
 * @returns
 */
export function checkCrossVersionUpgradeApi() {
  return request({
    url: 'system/cross_version/check',
    method: 'get',
  });
}

/**
 * Nhận danh sách SQL nâng cấp sẽ được thực thi
 * @returns
 */
export function pendingSqlListApi() {
  return request({
    url: 'system/cross_version/pending_sql',
    method: 'get',
  });
}

/**
 * Thực hiện nâng cấp nhiều phiên bản(bước đơn)
 * @param {Number} step chỉ số bước
 * @returns
 */
export function executeCrossVersionApi(step) {
  return request({
    url: 'system/cross_version/execute',
    method: 'post',
    data: { step },
  });
}

/**
 * Thực hiện tất cả các nâng cấp trên nhiều phiên bản chỉ bằng một cú nhấp chuột
 * @returns
 */
export function executeAllCrossVersionApi() {
  return request({
    url: 'system/cross_version/execute_all',
    method: 'post',
  });
}

/**
 * Nhận tiến trình nâng cấp nhiều phiên bản
 * @returns
 */
export function crossVersionUpgradeProgressApi() {
  return request({
    url: 'system/cross_version/progress',
    method: 'get',
  });
}

/**
 * Nhận trạng thái sao lưu
 * @returns
 */
export function backupStatusApi() {
  return request({
    url: 'system/cross_version/backup_status',
    method: 'get',
  });
}

/**
 * Nhận danh sách các phiên bản rollback
 * @returns
 */
export function rollbackVersionsApi() {
  return request({
    url: 'system/rollback/versions',
    method: 'get',
  });
}
/**
 * Thực hiện nâng cấp lại
 * @returns
 */
export function reExecuteUpgradeApi(data) {
  return request({
    url: 'system/upgrade/reExecute',
    method: 'get',
    params: data,
  });
}

/**
 * Thực hiện khôi phục phiên bản
 * @param {Number} logId Nhật ký nâng cấpID
 * @returns
 */
export function executeRollbackApi(logId) {
  return request({
    url: 'system/rollback/execute',
    method: 'post',
    data: { log_id: logId },
  });
}
