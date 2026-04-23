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

namespace app\listener\queue;


use think\console\Output;

class QueueStartListener
{

    public function handle(Output $output)
    {
        $output->writeln('Hàng đợi tin nhắn đã được bắt đầu và đang chạy. Vui lòng không đóng dòng lệnh trong Windows. Vui lòng sử dụng Trình giám sát để bảo vệ quy trình trên Linux.');
    }
}
