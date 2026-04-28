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

use think\facade\Route;

/**
 * Duy trì các tuyến đường liên quan
 */
Route::group('system', function () {

    /** Cấu hình lưu trữ */
    Route::group(function () {
        //Danh sách lưu trữ đám mây
        Route::get('config/storage/save_type/:type', 'v1.setting.SystemStorage/uploadType')->name('SystemStorageUploadType')->option(['real_name' => 'Chọn phương pháp lưu trữ']);
        //Danh sách lưu trữ đám mây
        Route::get('config/storage', 'v1.setting.SystemStorage/index')->name('SystemStorageIndex')->option(['real_name' => 'Danh sách lưu trữ đám mây']);
        //Nhận biểu mẫu tạo lưu trữ đám mây
        Route::get('config/storage/create/:type', 'v1.setting.SystemStorage/create')->name('SystemStorageCreate')->option(['real_name' => 'Nhận biểu mẫu tạo lưu trữ đám mây']);
        //Nhận biểu mẫu cấu hình lưu trữ đám mây
        Route::get('config/storage/form/:type', 'v1.setting.SystemStorage/getConfigForm')->name('getConfigForm')->option(['real_name' => 'Nhận biểu mẫu cấu hình lưu trữ đám mây']);
        //Nhận cấu hình lưu trữ đám mây
        Route::get('config/storage/config', 'v1.setting.SystemStorage/getConfig')->name('SystemStorageConfig')->option(['real_name' => 'Nhận cấu hình lưu trữ đám mây']);
        //Lưu cấu hình lưu trữ đám mây
        Route::post('config/storage/config', 'v1.setting.SystemStorage/saveConfig')->name('SystemStorageSaveConfig')->option(['real_name' => 'Lưu cấu hình lưu trữ đám mây']);
        //Đồng bộ danh sách lưu trữ đám mây
        Route::put('config/storage/synch/:type', 'v1.setting.SystemStorage/synch')->name('SystemStorageSynch')->option(['real_name' => 'Đồng bộ danh sách lưu trữ đám mây']);
        //Nhận biểu mẫu để sửa đổi tên miền lưu trữ đám mây
        Route::get('config/storage/domain/:id', 'v1.setting.SystemStorage/getUpdateDomainForm')->name('getUpdateDomainForm')->option(['real_name' => 'Nhận biểu mẫu để sửa đổi tên miền lưu trữ đám mây']);
        //Sửa đổi tên miền lưu trữ đám mây
        Route::post('config/storage/domain/:id', 'v1.setting.SystemStorage/updateDomain')->name('updateDomain')->option(['real_name' => 'Sửa đổi tên miền lưu trữ đám mây']);
        //Lưu dữ liệu lưu trữ đám mây
        Route::post('config/storage/:type', 'v1.setting.SystemStorage/save')->name('SystemStorageSave')->option(['real_name' => 'Lưu dữ liệu lưu trữ đám mây']);
        //Xóa bộ nhớ đám mây
        Route::delete('config/storage/:id', 'v1.setting.SystemStorage/delete')->name('SystemStorageDelete')->option(['real_name' => 'Xóa bộ nhớ đám mây']);
        //Sửa đổi trạng thái lưu trữ đám mây
        Route::put('config/storage/status/:id', 'v1.setting.SystemStorage/status')->name('SystemStorageStatus')->option(['real_name' => 'Sửa đổi trạng thái lưu trữ đám mây']);
    })->option(['parent' => 'system', 'cate_name' => 'Cấu hình lưu trữ']);

    /** Nhật ký hệ thống */
    Route::group(function () {
        //Nhật ký hệ thống
        Route::get('log', 'v1.system.SystemLog/index')->name('SystemLog')->option(['real_name' => 'Nhật ký hệ thống']);
        //Tiêu chí tìm kiếm của quản trị viên nhật ký hệ thống
        Route::get('log/search_admin', 'v1.system.SystemLog/search_admin')->option(['real_name' => 'Tiêu chí tìm kiếm của quản trị viên nhật ký hệ thống']);
        //Xác minh tập tin
        Route::get('file', 'v1.system.SystemFile/index')->name('SystemFile')->option(['real_name' => 'Xác minh tập tin']);
    })->option(['parent' => 'system', 'cate_name' => 'Nhật ký hệ thống']);


    /** Sao lưu dữ liệu */
    Route::group(function () {
        //Dữ liệu tất cả các bảng
        Route::get('backup', 'v1.system.SystemDatabackup/index')->option(['real_name' => 'Tất cả các bảng cơ sở dữ liệu']);
        //Chi tiết sao lưu dữ liệu
        Route::get('backup/read', 'v1.system.SystemDatabackup/read')->option(['real_name' => 'Chi tiết sao lưu dữ liệu']);
        //Cập nhật bảng dữ liệu hoặc nhận xét trường bảng
        Route::post('database/update_mark', 'v1.system.SystemDatabackup/updateMark')->option(['real_name' => 'Cập nhật bảng dữ liệu hoặc nhận xét trường bảng']);
        //Bảng tối ưu hóa sao lưu dữ liệu
        Route::put('backup/optimize', 'v1.system.SystemDatabackup/optimize')->option(['real_name' => 'Bảng tối ưu hóa sao lưu dữ liệu']);
        //Bảng sửa chữa sao lưu dữ liệu
        Route::put('backup/repair', 'v1.system.SystemDatabackup/repair')->option(['real_name' => 'Bảng sao lưu và sửa chữa dữ liệu']);
        //Bảng sao lưu dự phòng dữ liệu
        Route::put('backup/backup', 'v1.system.SystemDatabackup/backup')->option(['real_name' => 'Bảng sao lưu dự phòng dữ liệu']);
        //Sao lưu hồ sơ
        Route::get('backup/file_list', 'v1.system.SystemDatabackup/fileList')->option(['real_name' => 'Bản ghi sao lưu cơ sở dữ liệu']);
        //Xóa bản ghi sao lưu
        Route::delete('backup/del_file', 'v1.system.SystemDatabackup/delFile')->option(['real_name' => 'Xóa bản ghi sao lưu cơ sở dữ liệu']);
        //Nhập bảng bản ghi dự phòng
        Route::post('backup/import', 'v1.system.SystemDatabackup/import')->option(['real_name' => 'Nhập bản ghi sao lưu cơ sở dữ liệu']);
        //Tải xuống bảng ghi bản sao lưu
        //Route::get('backup/download', 'v1.system.SystemDatabackup/downloadFile');
    })->option(['parent' => 'system', 'cate_name' => 'Sao lưu dữ liệu']);

    /** Xóa dữ liệu */
    Route::group(function () {
        //Xóa dữ liệu người dùng
        Route::get('clear/:type', 'v1.system.SystemClearData/index')->option(['real_name' => 'Xóa dữ liệu người dùng']);
        //xóa bộ nhớ đệm
        Route::get('refresh_cache/cache', 'v1.system.Clear/refresh_cache')->option(['real_name' => 'Xóa bộ nhớ đệm hệ thống']);
        //xóa nhật ký
        Route::get('refresh_cache/log', 'v1.system.Clear/delete_log')->option(['real_name' => 'Xóa nhật ký hệ thống']);
        //Giao diện thay thế tên miền
        Route::post('replace_site_url', 'v1.system.SystemClearData/replaceSiteUrl')->option(['real_name' => 'Thay thế tên miền']);
        //Nhận danh sách phiên bản APP
        Route::get('version_list', 'v1.system.AppVersion/list')->option(['real_name' => 'Nhận danh sách phiên bản APP']);
        //Thêm thông tin phiên bản
        Route::get('version_crate/:id', 'v1.system.AppVersion/crate')->option(['real_name' => 'Thêm phiên bản']);
        //Thêm thông tin phiên bản
        Route::post('version_save', 'v1.system.AppVersion/save')->option(['real_name' => 'lưu phiên bản']);
        //Xóa thông tin phiên bản
        Route::delete('version_del/:id', 'v1.system.AppVersion/del')->option(['real_name' => 'xóa phiên bản']);
    })->option(['parent' => 'system', 'cate_name' => 'Xóa dữ liệu']);

    /** Nâng cấp trực tuyến */
    Route::group(function () {
        //Trạng thái nâng cấp
        Route::get('upgrade_status', 'UpgradeController/upgradeStatus')->option(['real_name' => 'Trạng thái nâng cấp']);
        //Danh sách gói nâng cấp
        Route::get('upgrade/list', 'UpgradeController/upgradeList')->option(['real_name' => 'Danh sách gói nâng cấp']);
        //Danh sách các gói có thể nâng cấp
        Route::get('upgradeable/list', 'UpgradeController/upgradeableList')->option(['real_name' => 'Danh sách các gói có thể nâng cấp']);
        //thỏa thuận nâng cấp
        Route::get('upgrade/agreement', 'UpgradeController/agreement')->option(['real_name' => 'thỏa thuận nâng cấp']);
        //Bản ghi nâng cấp
        Route::get('upgrade_log/list', 'UpgradeController/upgradeLogList')->option(['real_name' => 'Bản ghi nâng cấp']);
        //tập tin phát hiện
        Route::get('upgrade/check_file', 'UpgradeController/checkFile')->option(['real_name' => 'tập tin phát hiện']);
        //Thực hiện lại
        Route::get('upgrade/reExecute', 'UpgradeController/reExecute')->option(['real_name' => 'Thực hiện lại']);
        //Tải xuống gói nâng cấp
        Route::post('package_download/:package_key', 'UpgradeController/packageDownload')->option(['real_name' => 'Tải xuống gói nâng cấp']);
        //Tiến trình tải xuống gói nâng cấp
        Route::get('upgrade_download/progress', 'UpgradeController/downloadProgress')->option(['real_name' => 'Tiến trình tải xuống gói nâng cấp']);
        //Tiến độ nâng cấp
        Route::get('upgrade_progress', 'UpgradeController/progress')->option(['real_name' => 'Tiến độ nâng cấp']);
        //Xuất dự án sao lưu
        Route::get('upgrade_export/:id/:type', 'UpgradeController/export')->option(['real_name' => 'Xuất bản sao lưu']);

        // Giao diện nâng cấp đa phiên bản
        //Nhận thông tin tổng quan về các nâng cấp trên nhiều phiên bản
        Route::get('cross_version/overview', 'UpgradeController/crossVersionOverview')->option(['real_name' => 'Tổng quan về nâng cấp nhiều phiên bản']);
        //Lấy danh sách các phiên bản sẽ được nâng cấp
        Route::get('cross_version/pending', 'UpgradeController/pendingVersions')->option(['real_name' => 'Danh sách các phiên bản sẽ được nâng cấp']);
        //Nhận các bản nâng cấp đang chờ xử lýSQL
        Route::get('cross_version/pending_sql', 'UpgradeController/pendingUpgradeSql')->option(['real_name' => 'Danh sách SQL sẽ được thực thi']);
        //Thực hiện nâng cấp nhiều phiên bản(bước đơn)
        Route::post('cross_version/execute', 'UpgradeController/executeCrossVersionUpgrade')->option(['real_name' => 'Thực hiện nâng cấp nhiều phiên bản']);
        //Thực hiện tất cả các nâng cấp trên nhiều phiên bản chỉ bằng một cú nhấp chuột
        Route::post('cross_version/execute_all', 'UpgradeController/executeAllCrossVersionUpgrade')->option(['real_name' => 'Nâng cấp bằng một cú nhấp chuột']);
        //Kiểm tra xem có cần nâng cấp nhiều phiên bản không
        Route::get('cross_version/check', 'UpgradeController/checkCrossVersionUpgrade')->option(['real_name' => 'Kiểm tra nâng cấp phiên bản chéo']);
        //Nhận trạng thái sao lưu
        Route::get('cross_version/backup_status', 'UpgradeController/backupStatus')->option(['real_name' => 'Trạng thái sao lưu']);
        //Nhận tiến trình nâng cấp
        Route::get('cross_version/progress', 'UpgradeController/upgradeProgress')->option(['real_name' => 'Tiến độ nâng cấp']);
        //Nhận danh sách các phiên bản có thể được khôi phục
        Route::get('rollback/versions', 'UpgradeController/rollbackVersions')->option(['real_name' => 'Danh sách các phiên bản khôi phục']);
        //Thực hiện khôi phục phiên bản
        Route::post('rollback/execute', 'UpgradeController/executeRollback')->option(['real_name' => 'Thực hiện khôi phục phiên bản']);
    })->option(['parent' => 'system', 'cate_name' => 'Nâng cấp trực tuyến']);

    /** nhiệm vụ theo lịch trình */
    Route::group(function () {
        //Danh sách nhiệm vụ theo lịch trình
        Route::get('crontab/list', 'v1.system.SystemCrontab/getTimerList')->option(['real_name' => 'Danh sách nhiệm vụ theo lịch trình']);
        //Loại nhiệm vụ theo lịch trình
        Route::get('crontab/mark', 'v1.system.SystemCrontab/getMarkList')->option(['real_name' => 'Loại nhiệm vụ theo lịch trình']);
        //Chi tiết nhiệm vụ theo lịch trình
        Route::get('crontab/info/:id', 'v1.system.SystemCrontab/getTimerInfo')->option(['real_name' => 'Chi tiết nhiệm vụ theo lịch trình']);
        //Thêm và chỉnh sửa các tác vụ đã lên lịch
        Route::post('crontab/save', 'v1.system.SystemCrontab/saveTimer')->option(['real_name' => 'Thêm và chỉnh sửa các tác vụ đã lên lịch']);
        //Xóa nhiệm vụ đã lên lịch
        Route::delete('crontab/del/:id', 'v1.system.SystemCrontab/delTimer')->option(['real_name' => 'Xóa nhiệm vụ đã lên lịch']);
        //Tác vụ theo lịch trình có được bật hay không
        Route::get('crontab/set_open/:id/:is_open', 'v1.system.SystemCrontab/setTimerStatus')->option(['real_name' => 'Tác vụ theo lịch trình có được bật hay không']);
    })->option(['parent' => 'system', 'cate_name' => 'nhiệm vụ theo lịch trình']);

    /** sự kiện tùy chỉnh */
    Route::group(function () {
        //Danh sách nhiệm vụ theo lịch trình
        Route::get('event/list', 'v1.system.SystemEvent/getEventList')->option(['real_name' => 'Danh sách sự kiện tùy chỉnh']);
        //Loại nhiệm vụ theo lịch trình
        Route::get('event/mark', 'v1.system.SystemEvent/getMarkList')->option(['real_name' => 'Loại sự kiện tùy chỉnh']);
        //Chi tiết nhiệm vụ theo lịch trình
        Route::get('event/info/:id', 'v1.system.SystemEvent/getEventInfo')->option(['real_name' => 'Chi tiết sự kiện tùy chỉnh']);
        //Thêm và chỉnh sửa các tác vụ đã lên lịch
        Route::post('event/save', 'v1.system.SystemEvent/saveEvent')->option(['real_name' => 'Thêm và chỉnh sửa sự kiện tùy chỉnh']);
        //Xóa nhiệm vụ đã lên lịch
        Route::delete('event/del/:id', 'v1.system.SystemEvent/delEvent')->option(['real_name' => 'Xóa sự kiện tùy chỉnh']);
        //Tác vụ theo lịch trình có được bật hay không
        Route::get('event/set_open/:id/:is_open', 'v1.system.SystemEvent/setEventStatus')->option(['real_name' => 'Liệu sự kiện tùy chỉnh có bật nút chuyển hay không']);
    })->option(['parent' => 'system', 'cate_name' => 'sự kiện tùy chỉnh']);

    /** Định tuyến hệ thống */
    Route::group(function () {
        //giao diện định tuyến đồng bộ
        Route::get('route/sync_route/[:appName]', 'v1.setting.SystemRoute/syncRoute')->option(['real_name' => 'Định tuyến đồng bộ']);
        //Nhận dữ liệu hàng cây định tuyến
        Route::get('route/tree', 'v1.setting.SystemRoute/tree')->option(['real_name' => 'Nhận tuyến đườngtree']);
        //Định tuyến quyền
        Route::delete('route/:id', 'v1.setting.SystemRoute/delete')->option(['real_name' => 'Xóa quyền định tuyến']);
        //Xem quyền định tuyến
        Route::get('route/:id', 'v1.setting.SystemRoute/read')->option(['real_name' => 'Xem quyền định tuyến']);
        //Lưu quyền định tuyến
        Route::post('route/:id', 'v1.setting.SystemRoute/save')->option(['real_name' => 'Lưu quyền định tuyến']);
        //Phân loại tuyến đường
        Route::resource('route_cate', 'v1.setting.SystemRouteCate')->except(['read'])->option([
            'real_name' => [
                'index' => 'Nhận danh sách phân loại tuyến đường',
                'create' => 'Nhận biểu mẫu phân loại tạo tuyến đường',
                'save' => 'Lưu phân loại tuyến đường',
                'edit' => 'Nhận biểu mẫu phân loại tuyến đường sửa đổi',
                'update' => 'Sửa danh mục tuyến đường',
                'delete' => 'Xóa danh mục định tuyến'
            ],
        ]);
    })->option(['parent' => 'system', 'cate_name' => 'Định tuyến hệ thống']);

    /** tạo mã */
    Route::group(function () {
        //Lưu các tệp đã sửa đổi CRUD
        Route::post('crud/save_file/:id', 'v1.setting.SystemCrud/savefile')->option(['real_name' => 'Lưu các tệp đã sửa đổi CRUD']);
        //Nhận cấu hình CRUD
        Route::get('crud/config/:tableName', 'v1.setting.SystemCrud/getRouteList')->option(['real_name' => 'Nhận cấu hình CRUD']);
        //Tải xuống tập tin được tạo
        Route::get('crud/download/:id', 'v1.setting.SystemCrud/download')->option(['real_name' => 'Tải xuống tập tin được tạo']);
        //Nhận danh sách CRUD
        Route::get('crud/column_type', 'v1.setting.SystemCrud/columnType')->option(['real_name' => 'Nhận danh sách CRUD']);
        //Nhận menu dữ liệu hình TREE
        Route::get('crud/menus', 'v1.setting.SystemCrud/getMenus')->option(['real_name' => 'Nhận menu dữ liệu hình TREE']);
        //Nhận bộ nhớ tệp CRUD
        Route::post('crud/file_path', 'v1.setting.SystemCrud/getFilePath')->option(['real_name' => 'Nhận bộ nhớ tệp CRUD']);

        //Lấy danh sách từ điển dữ liệu
        Route::get('crud/data_dictionary_list', 'v1.setting.SystemCrud/dataDictionaryList')->option(['real_name' => 'Lấy danh sách từ điển dữ liệu']);
        //Lấy từ điển dữ liệu để thêm và sửa đổi biểu mẫu
        Route::get('crud/data_dictionary_list/create/:id', 'v1.setting.SystemCrud/dataDictionaryListCreate')->option(['real_name' => 'Lấy từ điển dữ liệu để thêm và sửa đổi biểu mẫu']);
        //Lưu từ điển dữ liệu
        Route::post('crud/data_dictionary_list/save/:id', 'v1.setting.SystemCrud/dataDictionaryListSave')->option(['real_name' => 'Lưu từ điển dữ liệu']);
        //Xóa từ điển dữ liệu
        Route::delete('crud/data_dictionary_list/del/:id', 'v1.setting.SystemCrud/dataDictionaryListDel')->option(['real_name' => 'Xóa từ điển dữ liệu']);
        //Xem danh sách nội dung từ điển dữ liệu
        Route::get('crud/data_dictionary/info_list/:cid', 'v1.setting.SystemCrud/dataDictionaryInfoList')->option(['real_name' => 'Xem danh sách nội dung từ điển dữ liệu']);
        //Xem nội dung từ điển dữ liệu và thêm và sửa đổi biểu mẫu
        Route::get('crud/data_dictionary/info_create/:cid/:id/:pid', 'v1.setting.SystemCrud/dataDictionaryInfoCreate')->option(['real_name' => 'Xem nội dung từ điển dữ liệu và thêm và sửa đổi biểu mẫu']);
        //Sửa đổi hoặc lưu nội dung dữ liệu từ điển
        Route::post('crud/data_dictionary/info_save/:cid/:id', 'v1.setting.SystemCrud/dataDictionaryInfoSave')->option(['real_name' => 'Sửa đổi hoặc lưu nội dung dữ liệu từ điển']);
        //Xóa nội dung từ điển dữ liệu
        Route::delete('crud/data_dictionary/info_del/:id', 'v1.setting.SystemCrud/dataDictionaryInfoDel')->option(['real_name' => 'Xóa nội dung từ điển dữ liệu']);

        //Lấy danh sách từ điển dữ liệu
        Route::get('crud/data_dictionary', 'v1.setting.SystemCrud/getDataDictionary')->option(['real_name' => 'Lấy danh sách từ điển dữ liệu']);
        //Xem từ điển dữ liệu
        Route::get('crud/data_dictionary/:id', 'v1.setting.SystemCrud/getDataDictionaryOne')->option(['real_name' => 'Xem từ điển dữ liệu']);
        //Sửa đổi hoặc lưu dữ liệu từ điển
        Route::post('crud/data_dictionary/[:id]', 'v1.setting.SystemCrud/saveDataDictionary')->option(['real_name' => 'Sửa đổi hoặc lưu dữ liệu từ điển']);
        //Xóa từ điển dữ liệu
        Route::delete('crud/data_dictionary/:id', 'v1.setting.SystemCrud/deleteDataDictionary')->option(['real_name' => 'Xóa từ điển dữ liệu']);
        //Lấy tên bảng có thể được liên kết
        Route::get('crud/association_table', 'v1.setting.SystemCrud/getAssociationTable')->option(['real_name' => 'Lấy tên bảng có thể được liên kết']);
        //Nhận chi tiết bảng
        Route::get('crud/association_table/:tableName', 'v1.setting.SystemCrud/getAssociationTableInfo')->option(['real_name' => 'Nhận chi tiết bảng']);
        //xóa bỏCRUD
        Route::delete('crud/:id', 'v1.setting.SystemCrud/delete')->option(['real_name' => 'XóaCRUD']);
        //Kiểm traCRUD
        Route::get('crud/:id', 'v1.setting.SystemCrud/read')->option(['real_name' => 'Kiểm traCRUD']);
        //Nhận danh sách CRUD
        Route::get('crud', 'v1.setting.SystemCrud/index')->option(['real_name' => 'Nhận danh sách CRUD']);
        //Lưu đã tạoCRUD
        Route::post('crud', 'v1.setting.SystemCrud/save')->option(['real_name' => 'Lưu đã tạoCRUD']);
    })->option(['parent' => 'system', 'cate_name' => 'tạo mã']);

    /** In biên lai */
    Route::group(function () {
        Route::get('ticket/list', 'v1.system.SystemTicket/ticketList')->option(['real_name' => 'Danh sách in hóa đơn']);
        Route::get('ticket/form/:id', 'v1.system.SystemTicket/ticketForm')->option(['real_name' => 'Thêm và sửa đổi mẫu in biên lai']);
        Route::post('ticket/save/:id', 'v1.system.SystemTicket/ticketSave')->option(['real_name' => 'Thêm và sửa đổi in biên lai']);
        Route::post('ticket/set_status/:id/:status', 'v1.system.SystemTicket/ticketSetStatus')->option(['real_name' => 'Sửa đổi trạng thái in biên lai']);
        Route::delete('ticket/del/:id', 'v1.system.SystemTicket/ticketDel')->option(['real_name' => 'Xóa in hóa đơn']);
        Route::get('ticket/content/:id', 'v1.system.SystemTicket/ticketContent')->option(['real_name' => 'Nhận chi tiết in hóa đơn']);
        Route::post('ticket/save_content/:id', 'v1.system.SystemTicket/ticketContentSave')->option(['real_name' => 'Lưu chi tiết in hóa đơn']);
    })->option(['parent' => 'system', 'cate_name' => 'In phiếu giao hàng']);

    /** Quản lý tập tin */
    Route::group(function () {
        //Đăng nhập quản lý tập tin
        Route::post('file/login', 'v1.system.SystemFile/login')->option(['real_name' => 'Đăng nhập quản lý tập tin']);
        //Thực thi và ghi các giá trị md5 của tất cả các file trong hai thư mục app và crmeb vào cơ sở dữ liệu.
        Route::get('write_md5', 'v1.system.SystemFile/writeMd5')->option(['real_name' => 'Thực hiện ghi giá trị md5']);
    })->option(['parent' => 'system', 'cate_name' => 'Quản lý tập tin']);

})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'system', 'mark_name' => 'Bảo trì hệ thống']);

Route::group('system', function () {

    //Mở thư mục
    Route::get('file/opendir', 'v1.system.SystemFile/opendir')->option(['real_name' => 'Mở thư mục']);
    //đọc tập tin
    Route::get('file/openfile', 'v1.system.SystemFile/openfile')->option(['real_name' => 'đọc tập tin']);
    //lưu tập tin
    Route::post('file/savefile', 'v1.system.SystemFile/savefile')->option(['real_name' => 'lưu tập tin']);
    //Tạo thư mục
    Route::get('file/createFolder', 'v1.system.SystemFile/createFolder')->option(['real_name' => 'Tạo thư mục']);
    //Tạo tập tin
    Route::get('file/createFile', 'v1.system.SystemFile/createFile')->option(['real_name' => 'Tạo tập tin']);
    //Xóa thư mục hoặc tập tin
    Route::get('file/delFolder', 'v1.system.SystemFile/delFolder')->option(['real_name' => 'xóa thư mục']);
    //Đổi tên tập tin
    Route::get('file/rename', 'v1.system.SystemFile/rename')->option(['real_name' => 'Đổi tên thư mục']);
    //Biểu mẫu nhận xét tệp thư mục
    Route::get('file/mark', 'v1.system.SystemFile/fileMark')->option(['real_name' => 'Biểu mẫu nhận xét tệp thư mục']);
    //Lưu ghi chú tập tin thư mục
    Route::post('file/mark/save', 'v1.system.SystemFile/fileMarkSave')->option(['real_name' => 'Lưu ghi chú tập tin thư mục']);
})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class,
    \app\adminapi\middleware\AdminEditorTokenMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
])->option(['mark' => 'system_file', 'mark_name' => 'Quản lý tập tin']);

