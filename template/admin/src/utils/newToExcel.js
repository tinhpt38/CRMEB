// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
import { export_json_to_excel } from '../vendor/Export2Excel';

/**
 * @method exportExcel
 * @param {Array} header   tiêu đề
 * @param {Array} filterVal trường thuộc tính tiêu đề
 * @param {String} filename Tên tập tin
 * @param {Array} tableData Liệt kê dữ liệu
 **/
export default function exportExcel(header, filterVal, filename, tableData) {
  var data = formatJson(filterVal, tableData);
  export_json_to_excel(header, data, filename);
}

function formatJson(filterVal, tableData) {
  return tableData.map((v) => {
    return filterVal.map((j) => {
      return v[j];
    });
  });
}
