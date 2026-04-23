#!/usr/bin/env node

const chalk = require('chalk');

// In tin nhắn chào mừng
console.log(chalk.hex('#DEADED').underline('😄 Hello ~ Chào mừng bạn đến sử dụng CRMEB Standard Edition, chúng tôi sẽ phục vụ bạn tận tình！'));
console.log(chalk.yellow('info - [gợi ý] Bấm vào đây để xem nhiều sản phẩm hơn~ ') + chalk.blue.underline('https://doc.crmeb.com'));
console.log(chalk.yellow('info - [gợi ý] Bấm vào đây để xem tài liệu phát triển~ ') + chalk.blue.underline('https://www.crmeb.com'));
console.log(
  chalk.yellow('info - [gợi ý] Nhấn vào đây để xem cộng đồng diễn đàn của chúng tôi~ ') + chalk.blue.underline('https://www.crmeb.com/ask'),
);
console.log(chalk.blue('info - [bạn có biết không？] Nhấn Ctrl + C để dừng dịch vụ~'));
