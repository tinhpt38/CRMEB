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
namespace app\services\system\crontab;

use app\dao\system\crontab\SystemCrontabDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use think\facade\Cache;
use think\helper\Str;
use Workerman\Crontab\Crontab;

class SystemCrontabServices extends BaseServices
{
    public function __construct(SystemCrontabDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Danh sách nhiệm vụ theo lịch trình
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getTimerList(array $where = [])
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->selectList($where, '*', $page, $limit, 'id desc', [], true);
        foreach ($list as &$item) {
            $item['next_execution_time'] = date('Y-m-d H:i:s', $item['next_execution_time']);
            $item['last_execution_time'] = $item['last_execution_time'] != 0 ? date('Y-m-d H:i:s', $item['last_execution_time']) : 'Chưa triển khai';
        }
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * Chi tiết nhiệm vụ theo lịch trình
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getTimerInfo($id)
    {
        $info = $this->dao->get($id);
        $info['customCode'] = "<?php\n\n" . json_decode($info['customCode']);
        if (!$info) throw new AdminException('Dữ liệu không tồn tại');
        return $info->toArray();
    }

    /**
     * Loại nhiệm vụ theo lịch trình
     * @return string[]
     */    public function getMarkList(): array
    {
        return app()->make(CrontabRunServices::class)->markList;
    }

    /**
     * Lưu các nhiệm vụ theo lịch trình
     * @param array $data
     * @return bool
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function saveTimer(array $data = [])
    {
        if (!$data['id'] && $this->dao->getCount(['mark' => $data['mark'], 'is_del' => 0]) && $data['mark'] != 'customTimer') {
            throw new AdminException('Tác vụ theo lịch trình này đã tồn tại, vui lòng không thêm lại.');
        }
        if ($data['mark'] != 'customTimer') $data['name'] = $this->getMarkList()[$data['mark']];
        $data['customCode'] = json_encode(preg_replace('/<\?php\s*\n/', '', $data['customCode']));
        $data['timeStr'] = $this->getTimerStr([
            'type' => $data['type'],
            'month' => $data['month'],
            'week' => $data['week'],
            'day' => $data['day'],
            'hour' => $data['hour'],
            'minute' => $data['minute'],
            'second' => $data['second'],
        ]);
        if (!$data['id']) {
            unset($data['id']);
            $data['add_time'] = $data['update_time'] = time();
            $res = $this->dao->save($data);
        } else {
            $data['update_time'] = time();
            $res = $this->dao->update(['id' => $data['id']], $data);
        }
        if (!$res) throw new AdminException('Lưu không thành công');
        Cache::delete('crontabCache');
        Cache::set('crontabCache', $this->dao->selectList(['is_open' => 1, 'is_del' => 0])->toArray());
        return true;
    }

    /**
     * Xóa nhiệm vụ đã lên lịch
     * @param $id
     * @return bool
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function delTimer($id)
    {
        $data['update_time'] = time();
        $data['is_del'] = 1;
        $res = $this->dao->update(['id' => $id], $data);
        if (!$res) throw new AdminException('Xóa không thành công');
        Cache::delete('crontabCache');
        Cache::set('crontabCache', $this->dao->selectList(['is_open' => 1, 'is_del' => 0])->toArray());
        return true;
    }

    /**
     * Đặt trạng thái tác vụ theo lịch trình
     * @param $id
     * @param $is_open
     * @return bool
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function setTimerStatus($id, $is_open)
    {
        $data['update_time'] = time();
        $data['is_open'] = $is_open;
        $res = $this->dao->update(['id' => $id], $data);
        if (!$res) throw new AdminException('Thiết lập thành công');
        Cache::delete('crontabCache');
        Cache::set('crontabCache', $this->dao->selectList(['is_open' => 1, 'is_del' => 0])->toArray());
        return true;
    }

    /**
     * Tính toán thời gian thực hiện tiếp theo của các tác vụ đã lên lịch
     * @param $data
     * @param int $time
     * @return false|float|int|mixed
     */    public function getTimerCycleTime($data, $time = 0)
    {
        if (!$time) $time = time();
        switch ($data['type']) {
            case 1: // cứ sau vài giây
                $cycle_time = $time + $data['second'];
                break;
            case 2: // cứ sau vài phút
                $cycle_time = $time + ($data['minute'] * 60);
                break;
            case 3: // cứ sau vài giờ
                $cycle_time = $time + ($data['hour'] * 3600) + ($data['minute'] * 60);
                break;
            case 4: // cứ sau vài ngày
                $cycle_time = $time + ($data['day'] * 86400) + ($data['hour'] * 3600) + ($data['minute'] * 60);
                break;
            case 5: // Giờ, phút và giây mỗi ngày
                $cycle_time = strtotime(date('Y-m-d ' . $data['hour'] . ':' . $data['minute'] . ':' . $data['second'], time()));
                if ($time >= $cycle_time) {
                    $cycle_time = $cycle_time + 86400;
                }
                break;
            case 6: // Ngày trong tuần, giờ, phút và giây
                $todayStart = strtotime(date('Y-m-d 00:00:00', time()));
                $w = date("w");
                if ($w > $data['week']) {
                    $cycle_time = $todayStart + ((7 - $w + $data['week']) * 86400) + ($data['hour'] * 3600) + ($data['minute'] * 60) + $data['second'];
                } else if ($w == $data['week']) {
                    $cycle_time = $todayStart + ($data['hour'] * 3600) + ($data['minute'] * 60) + $data['second'];
                    if ($time >= $cycle_time) {
                        $cycle_time = $cycle_time + (7 * 86400);
                    }
                } else {
                    $cycle_time = $todayStart + (($data['week'] - $w) * 86400) + ($data['hour'] * 3600) + ($data['minute'] * 60) + $data['second'];
                }
                break;
            case 7: // Ngày, giờ, phút và giây của mỗi tháng
                $currentMonth = date("n");
                $currentYear = date("Y");
                if ($currentMonth == 12) {
                    $nextMonth = 1;
                    $nextYear = $currentYear + 1;
                } else {
                    $nextMonth = $currentMonth + 1;
                    $nextYear = $currentYear;
                }
                $cycle_time = mktime($data['hour'], $data['minute'], $data['second'], $nextMonth, $data['day'], $nextYear);
                break;
            case 8: // Tháng, ngày, giờ, phút, giây của mỗi năm là bao nhiêu?
                $cycle_time = mktime($data['hour'], $data['minute'], $data['second'], $data['month'], $data['day'], date("Y") + 1);
                break;
            default:
                $cycle_time = 0;
                break;
        }
        return $cycle_time;
    }

    /**
     * Nhiệm vụ thực hiện giao diện
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/02/17
     */    public function crontabApiRun()
    {
        $crontabRunServices = app()->make(CrontabRunServices::class);
        $time = time();
        file_put_contents(root_path() . 'runtime/.timer', $time); //Kiểm tra xem tác vụ đã lên lịch có bình thường không
        $list = $this->dao->selectList(['is_open' => 1, 'is_del' => 0])->toArray();
        foreach ($list as $item) {
            if ($item['next_execution_time'] < $time) {
                //Chuyển đổi tên phương thức trường hợp lạc đà
                $functionName = Str::camel($item['mark']);
                //Thực hiện các nhiệm vụ theo lịch trình
                if (strpos($functionName, 'customTimer') === 0) {
                    $crontabRunServices->customTimer(json_decode($item['customCode']));
                } else {
                    $crontabRunServices->$functionName();
                }
                //Viết thời gian thực hiện này và thời gian thực hiện tiếp theo
                $this->dao->update(['mark' => $item['mark']], ['last_execution_time' => $time, 'next_execution_time' => $this->getTimerCycleTime($item)]);
            }
        }
    }

    /**
     * Chạy các tác vụ theo lịch trình
     *
     * @param object $task Đối tượng nhiệm vụ
     * @return void
     */    public function crontabCommandRun($task)
    {
        file_put_contents(root_path() . 'runtime/.timer', time());
        // Nhận phiên bản CrontabRunServices
        $crontabRunServices = app()->make(CrontabRunServices::class);
        // Tạo một tác vụ theo lịch trình thực thi mỗi giây
        new Crontab('*/1 * * * * *', function () use ($task, $crontabRunServices) {
            // Viết dấu thời gian để phát hiện xem tác vụ đã lên lịch có được thực thi bình thường hay không
            $timerTime = file_get_contents(root_path() . 'runtime/.timer');
            if ($timerTime < (time() - 60)) {
                file_put_contents(root_path() . 'runtime/.timer', time());
            }
            // Lấy danh sách tác vụ theo lịch trình từ bộ đệm
            $list = Cache::get('crontabCache');
            if (!$list) {
                $list = $this->dao->selectList(['is_open' => 1, 'is_del' => 0])->toArray();
                Cache::set('crontabCache', $list);
            }
            // Duyệt qua danh sách nhiệm vụ theo lịch trình
            foreach ($list as &$item) {
                // Lấy tên hàm
                $functionName = Str::camel($item['mark']);
                if ($functionName == 'customTimer') {
                    $functionName = 'customTimer_' . $item['id'];
                }
                // Nếu thời gian cập nhật không tồn tại, hãy đặt nó thành thời gian thêm
                $item['update_time'] = $item['update_time'] ?: $item['add_time'];
                // Nếu tác vụ đã được thực thi thì bỏ qua vòng lặp này
                if (isset($task->task_ids[$functionName]) && $task->task_ids[$functionName]['time'] == $item['update_time']) {
                    continue;
                }
                // Nhận chuỗi hẹn giờ
                $timeStr = $item['timeStr'] != '' ? $item['timeStr'] : $this->getTimerStr($item);
                // Nhận mã tùy chỉnh
                $customCode = json_decode($item['customCode']);
                // Nếu tác vụ đã được thực thi và thời gian hiện tại khác với thời gian thực hiện cuối cùng, hãy hủy tác vụ đã lên lịch trước đó.
                if (isset($task->task_ids[$functionName]) && $task->task_ids[$functionName]['time'] != $item['update_time'] && isset($task->task_ids[$functionName]['crontab']) && $task->task_ids[$functionName]['crontab'] instanceof Crontab) {
                    $task->task_ids[$functionName]['crontab']->destroy();
                    unset($task->task_ids[$functionName]);
                }
                // Nếu tác vụ đang mở, hãy tạo một tác vụ theo lịch trình mới
                if ($item['is_open'] == 1) {
                    $crontab = new Crontab($timeStr, function () use ($crontabRunServices, $functionName, $customCode) {
                        // Gọi phương thức tương ứng theo tên hàm
                        if (strpos($functionName, 'customTimer_') === 0) {
                            $crontabRunServices->customTimer($customCode);
                        } else {
                            $crontabRunServices->$functionName();
                        }
                    });
                    $task->task_ids[$functionName] = ['crontab' => $crontab, 'time' => $item['update_time']];
                }
            }
        });
    }

    /**
     * Nhận biểu thức thời gian nhiệm vụ theo lịch trình
     * 0   1   2   3   4   5
     * |   |   |   |   |   |
     * |   |   |   |   |   +------ day of week (0 - 6) (Sunday=0)
     * |   |   |   |   +------ month (1 - 12)
     * |   |   |   +-------- day of month (1 - 31)
     * |   |   +---------- hour (0 - 23)
     * |   +------------ min (0 - 59)
     * +-------------- sec (0-59)[Có thể bỏ qua nếu không có bit 0,Sau đó, độ chi tiết thời gian tối thiểu là phút]
     * @param $data
     * @return string
     */    public function getTimerStr($data): string
    {
        $timeStr = '';
        switch ($data['type']) {
            case 1:// cứ sau vài giây
                $timeStr = '*/' . $data['second'] . ' * * * * *';
                break;
            case 2:// cứ sau vài phút
                $timeStr = '0 */' . $data['minute'] . ' * * * *';
                break;
            case 3:// Thực hiện từng giờ và từng phút
                $timeStr = '0 ' . $data['minute'] . ' */' . $data['hour'] . ' * * *';
                break;
            case 4:// Thực hiện cứ sau vài ngày, giờ và phút
                $timeStr = '0 ' . $data['minute'] . ' ' . $data['hour'] . ' */' . $data['day'] . ' * *';
                break;
            case 5:// Giờ, phút và giây mỗi ngày
                $timeStr = $data['second'] . ' ' . $data['minute'] . ' ' . $data['hour'] . ' * * *';
                break;
            case 6:// Ngày trong tuần, giờ, phút và giây
                $timeStr = $data['second'] . ' ' . $data['minute'] . ' ' . $data['hour'] . ' * * ' . ($data['week'] == 7 ? 0 : $data['week']);
                break;
            case 7:// Ngày, giờ, phút và giây của mỗi tháng
                $timeStr = $data['second'] . ' ' . $data['minute'] . ' ' . $data['hour'] . ' ' . $data['day'] . ' * *';
                break;
            case 8:// Tháng, ngày, giờ, phút, giây của mỗi năm là bao nhiêu?
                $timeStr = $data['second'] . ' ' . $data['minute'] . ' ' . $data['hour'] . ' ' . $data['day'] . ' ' . $data['month'] . ' *';
                break;
        }
        return $timeStr;
    }
}
