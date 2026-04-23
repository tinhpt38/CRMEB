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
namespace crmeb\services;


use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Exception;

class SpreadsheetExcelService
{
    //
    private static $instance = null;
    //PHPSpreadsheetkhởi tạo đối tượng
    private static $spreadsheet = null;
    //sheetkhởi tạo đối tượng
    private static $sheet = null;
    //Số lượng tiêu đề
    protected static $count;
    //Số hàng được chiếm bởi tiêu đề bảng
    protected static $topNumber = 3;
    //Bảng có thể chiếm các chữ cái tương ứng của hàng trong bảngself::$cellkey
    protected static $cells;
    //dữ liệu tiêu đề
    protected static $data = [];
    //tên tập tin
    protected static $title = 'Xuất đơn hàng';
    //chiều rộng đường
    protected static $width = 20;
    //chiều cao hàng
    protected static $height = 50;
    //Lưu thư mục tập tin
    protected static $path = './phpExcel/';
    //cài đặtstyle
    private static $styleArray = [
//         'borders' => [
//             'allBorders' => [
// //                PHPExcel_Style_BorderCó rất nhiều thuộc tính trong đó, nếu bạn muốn xem người khác, bạn có thể tự mình xem
//                // 'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,//Đường viền dày
// //                'style' => \PHPExcel_Style_Border::BORDER_DOUBLE,//gấp đôi
// //                'style' => \PHPExcel_Style_Border::BORDER_HAIR,//đường chấm chấm
// //                'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,//đường dày đặc
// //                'style' => \PHPExcel_Style_Border::BORDER_MEDIUMDASHDOT,//đường đậm nét đứt
// //                'style' => \PHPExcel_Style_Border::BORDER_MEDIUMDASHDOTDOT,//đường chấm dày
//                 'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,//Viền mỏng
//                 // 'color' => ['argb' => 'FFFF0000'],
//             ],
//         ],
        'font' => [
            'bold' => true
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER
        ]
    ];

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
            self::$spreadsheet = $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            self::$sheet = $spreadsheet->getActiveSheet();
        }
        return self::$instance;
    }

    /**
     *Định dạng phông chữ
     * @param $title string Yêu cầu
     * return string
     */
    public static function setUtf8(string $title)
    {
        return iconv('utf-8', 'gb2312', $title);
    }

    /**
     *  Tạo và lưu thư mục excel
     *  return string
     */
    public static function savePath()
    {
        if (!is_dir(self::$path)) {
            if (mkdir(self::$path, 0700) == false) {
                return false;
            }
        }
        //Thư mục cấp một năm tháng
        $mont_path = self::$path . date('Ym');
        if (!is_dir($mont_path)) {
            if (mkdir($mont_path, 0700) == false) {
                return false;
            }
        }
        //Thư mục phụ tiếng Nhật
        $day_path = $mont_path . '/' . date('d');
        if (!is_dir($day_path)) {
            if (mkdir($day_path, 0700) == false) {
                return false;
            }
        }
        return $day_path;
    }

    /**
     * Đặt tiêu đề
     * @param $title string || array ['title'=>'','name'=>'','info'=>[]]
     * @param $Name string
     * @param $info string || array;
     * @return $this
     */
    public function setExcelTile(string $title = '', string $Name = '', $info = [])
    {
        //Đặt thông số
        if (is_array($title)) {
            if (isset($title['title'])) $title = $title['title'];
            if (isset($title['name'])) $Name = $title['name'];
            if (isset($title['info'])) $info = $title['info'];
        }
        if (empty($title))
            $title = self::$title;
        else
            self::$title = $title;

        if (empty($Name)) $Name = time();
        //Đặt thuộc tính Excel
        self::$spreadsheet->getProperties()
            ->setCreator("Neo")
            ->setLastModifiedBy("Neo")
            ->setTitle(self::setUtf8($title))
            ->setSubject($Name)
            ->setDescription("")
            ->setKeywords($Name)
            ->setCategory("");
        self::$sheet->setTitle($Name);
        self::$sheet->setCellValue('A1', $title);
        self::$sheet->setCellValue('A2', self::setCellInfo($info));
        //văn bản trung tâm
        self::$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        self::$sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        //Hợp nhất các ô tiêu đề
        self::$sheet->mergeCells('A1:' . self::$cells . '1');
        self::$sheet->mergeCells('A2:' . self::$cells . '2');

        self::$sheet->getRowDimension(1)->setRowHeight(40);
        self::$sheet->getRowDimension(2)->setRowHeight(20);

        //Đặt phông chữ tiêu đề
        self::$sheet->getStyle('A1')->getFont()->setName('cơ thể màu đen');
        self::$sheet->getStyle('A1')->getFont()->setSize(20);
        self::$sheet->getStyle('A1')->getFont()->setBold(true);
        self::$sheet->getStyle('A2')->getFont()->setName('Nhà Tống');
        self::$sheet->getStyle('A2')->getFont()->setSize(14);
        self::$sheet->getStyle('A2')->getFont()->setBold(true);

        self::$sheet->getStyle('A3:' . self::$cells . '3')->getFont()->setBold(true);
        return $this;
    }

    /**
     * Đặt nội dung tiêu đề dòng thứ hai
     * @param $info
     * @return string|void
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/7
     */
    private static function setCellInfo($info)
    {
        $content = ['toán tử：', 'Ngày xuất：' . date('Y-m-d', time()), 'Địa chỉ：', 'Điện thoại：'];
        if (is_array($info) && !empty($info)) {
            if (isset($info['name'])) {
                $content[0] .= $info['name'];
            } else {
                $content[0] .= $info[0] ?? '';
            }
            if (isset($info['site'])) {
                $content[2] .= $info['site'];
            } else {
                $content[2] .= $info[1] ?? '';
            }
            if (isset($info['phone'])) {
                $content[3] .= $info['phone'];
            } else {
                $content[3] .= $info[2] ?? '';
            }
            return implode(' ', $content);
        } else if (is_string($info)) {
            return empty($info) ? implode(' ', $content) : $info;
        }
    }

    /**
     * Đặt thông tin tiêu đề
     * @param $data array
     * @return $this
     */
    public static function setExcelHeader(array $data)
    {
        $span = 'A';
        foreach ($data as $value) {
            self::$sheet->getColumnDimension($span)->setWidth(self::$width);
            self::$sheet->setCellValue($span . self::$topNumber, $value);
            $span++;
        }
        self::$sheet->getRowDimension(3)->setRowHeight(self::$height);
        self::$cells = $span;
        return new self;
    }

    /**
     *
     * exclXuất dữ liệu
     * @param  $data Định dạng của dữ liệu cần xuất vẫn giống như trước.
     *
     * Xử lý đặc biệt: việc hợp nhất các ô yêu cầu xử lý dữ liệu trước
     */
    public function setExcelContent($data = [])
    {
        if (!empty($data) && is_array($data)) {
            $span = '';
            $column = self::$topNumber + 1;
            // viết hàng
            foreach ($data as $rows) {
                $span = 'A';
                // viết cột
                foreach ($rows as $value) {
                    self::$sheet->setCellValue($span . $column, $value);
                    $span++;
                }
                $column++;
            }
            self::$sheet->getDefaultRowDimension()->setRowHeight(self::$height);
            //Đặt kiểu phông chữ nội dung
            self::$sheet->getStyle('A1:' . $span . $column)->applyFromArray(self::$styleArray);
            //Đặt đường viền
            self::$sheet->getStyle('A1:' . $span . $column)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            //Đặt ngắt dòng tự động
            self::$sheet->getStyle('A4:' . $span . $column)->getAlignment()->setWrapText(true);
        }
        return new self;
    }

    /**
     * Lưu dữ liệu biểu mẫu và tải xuống trực tiếp
     * @param string $fileName
     * @param string $suffix Phần mở rộng tập tin
     * @param bool $is_save Có lưu tập tin hay không
     * @return string string
     * @throws Exception
     */
    public function excelSave(string $fileName = '', string $suffix = 'xlsx', bool $is_save = false)
    {
        if (empty($fileName)) {
            $fileName = date('YmdHis') . time();
        }
        if (empty($suffix)) {
            $suffix = 'xlsx';
        }
        // Đổi tên bảng (bước này không bắt buộc đối với mã hóa UTF8）
        if (mb_detect_encoding($fileName) != "UTF-8") {
            $fileName = iconv("utf-8", "gbk//IGNORE", $fileName);
        }
        if ($suffix == 'xlsx') {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $class = "\PhpOffice\PhpSpreadsheet\Writer\Xlsx";
        } elseif ($suffix == 'xls') {
            header('Content-Type:application/vnd.ms-excel');
            $class = "\PhpOffice\PhpSpreadsheet\Writer\Xls";
        }
        // xóa bộ nhớ đệm
//        ob_end_clean();
        $spreadsheet = self::$spreadsheet;
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        if (!$is_save) {//Tải xuống trực tiếp

            header('Content-Disposition: attachment;filename="' . $fileName . '.' . $suffix . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            // Xóa, xóa, giải phóng bộ nhớ
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
            exit;
        } else {//lưu tập tin
            $path = self::savePath() . '/' . $fileName . '.' . $suffix;
            //$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            //$writer->save($path);
            $writer->save(public_path() . $path);
            // Xóa, xóa, giải phóng bộ nhớ
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
            return $path;
        }
    }

}
