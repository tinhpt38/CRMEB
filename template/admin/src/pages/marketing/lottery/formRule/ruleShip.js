const ruleShip = {
  deliver_name: [
    {
      required: true,
      type: 'string',
      message: 'Hãy chọn công ty chuyển phát nhanh',
      trigger: 'select',
    },
  ],
  deliver_number: [
    {
      required: true,
      message: 'Vui lòng nhập số chuyển phát nhanh',
      trigger: 'blur',
    },
  ],
};
const ruleMark = {
  mark: [
    {
      required: true,
      message: 'Vui lòng nhập thông tin nhận xét',
      trigger: 'blur',
    },
  ],
};
export { ruleShip, ruleMark };
