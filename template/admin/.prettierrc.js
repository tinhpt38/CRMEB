module.exports = {
  // Tối đa 120 ký tự trên mỗi dòng
  printWidth: 120,
  // Sử dụng 2 dấu cách để thụt lề
  tabWidth: 2,
  // Không sử dụng thụt lề tab, thay vào đó hãy sử dụng dấu cách
  useTabs: false,
  // Cần có dấu chấm phẩy ở cuối dòng
  semi: true,
  // Sử dụng dấu ngoặc đơn thay vì dấu ngoặc kép
  singleQuote: true,
  // Khóa đối tượng chỉ được trích dẫn nếu cần thiết
  quoteProps: 'as-needed',
  // jsx Thay vì sử dụng dấu ngoặc đơn, hãy sử dụng dấu ngoặc kép
  jsxSingleQuote: false,
  // Sử dụng dấu phẩy ở cuối
  trailingComma: 'all',
  // Cần có khoảng trống ở đầu và cuối dấu ngoặc nhọn { foo: bar }
  bracketSpacing: true,
  // Các hàm mũi tên cũng yêu cầu dấu ngoặc đơn khi chúng chỉ có một tham số.
  arrowParens: 'always',
  // Phạm vi định dạng của mỗi file là toàn bộ nội dung của file
  rangeStart: 0,
  rangeEnd: Infinity,
  // Không cần phải viết phần đầu của tập tin @prettier
  requirePragma: false,
  // Không cần tự động chèn vào đầu file @prettier
  insertPragma: false,
  // Sử dụng tiêu chí gói mặc định
  proseWrap: 'preserve',
  // Xác định xem html có nên được gói dựa trên kiểu hiển thị hay không
  htmlWhitespaceSensitivity: 'css',
  // Cách sử dụng ngắt dòng lf
  endOfLine: 'lf',
};
