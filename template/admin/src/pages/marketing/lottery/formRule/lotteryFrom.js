const lotteryFrom = {
  name: [{ required: true, message: 'Vui lòng nhập tên sự kiện', trigger: 'blur' }],
  factor: [{ required: true, type: 'number', message: 'Vui lòng chọn loại hoạt động', trigger: 'change' }],
  attends_user: [{ required: true, type: 'number', message: 'Vui lòng chọn người dùng tham gia', trigger: 'change' }],
  factor_num: [{ required: true, type: 'number', message: 'Vui lòng nhập số lần rút thăm', trigger: 'blur' }],
  prize: [
    {
      required: true,
      type: 'array',
      message: 'Vui lòng thêm giải thưởng xổ số(8dải)',
      trigger: 'change',
    },
    {
      type: 'array',
      min: 8,
      message: 'Vui lòng thêm giải thưởng xổ số(8dải)',
      trigger: 'change',
    },
  ],
  lottery_num: [
    {
      required: true,
      type: 'number',
      message: 'Vui lòng nhập số lần tối đa bạn có thể mời người dùng mới để rút thăm may mắn.',
      trigger: 'blur',
    },
  ],
  spread_num: [
    {
      required: true,
      type: 'number',
      message: 'Vui lòng nhập số lần rút thêm bạn muốn theo dõi',
      trigger: 'blur',
    },
  ],
  image: [
    {
      required: true,
      message: 'Vui lòng tải lên hình nền sự kiện',
      trigger: 'change',
    },
  ],
  content: [
    {
      required: true,
      message: 'Vui lòng điền thể lệ sự kiện',
      trigger: 'blur',
    },
  ],
};
function validate(rule, value, callback) {
  if (Array.isArray(value)) {
    //Định dạng là: phát hiện phạm vi ngày, phạm vi ngày giờ
    value.map(function (item) {
      if (item === '') {
        return callback('Ngày không thể trống');
      }
    });
  } else {
    //Định dạng là: phát hiện ngày, giờ, năm, tháng
    if (value === '') {
      return callback('Ngày không thể trống');
    }
  }
  return callback();
}

export { lotteryFrom };
