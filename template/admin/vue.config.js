// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

const path = require('path');
// Giới thiệu công cụ đóng gói js
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const MonacoWebpackPlugin = require('monaco-editor-webpack-plugin');

const resolve = (dir) => {
  return path.join(__dirname, dir);
};
// Thông tin cơ bản về triển khai dự án
module.exports = {
  // Đường dẫn đóng gói
  outputDir: 'dist',
  // Đường dẫn đóng gói--địa chỉ tệp triển khai trực tuyến
  // outputDir: '../../crmeb/public/admin',
  runtimeCompiler: true,
  productionSourceMap: false, //Đóng tệp ánh xạ SourceMap trong môi trường sản xuất
  // Nếu bạn không cần sử dụng eslint, chỉ cần đặt lintOnSave thành false
  lintOnSave: false,
  // Tối ưu hóa bao bì
  configureWebpack: (config) => {
    const pluginsPro = [];
    pluginsPro.push(
      // jsNén tập tin
      new UglifyJsPlugin({
        uglifyOptions: {
          compress: {
            drop_debugger: true,
            drop_console: true, //Môi trường sản xuất sẽ tự động bị xóaconsole
            pure_funcs: ['console.log'], //Di dờiconsole
          },
        },
        sourceMap: false,
        parallel: true, //Sử dụng nhiều quy trình để chạy song song nhằm tăng tốc độ xây dựng. Số lần chạy đồng thời mặc định：os.cpus().length - 1。
      }),
    );
    if (process.env.NODE_ENV === 'production') {
      config.plugins = [...config.plugins, ...pluginsPro];
    }
  },
  css: {
    loaderOptions: {
      scss: {
        sassOptions: {
          silenceDeprecations: ['legacy-js-api'],
        },
      },
    },
  },
  chainWebpack: (config) => {
    config.plugins.delete('prefetch');
    config.resolve.alias
      .set('@', resolve('src')) // key,valueHãy tự xác định nó, chẳng hạn như.set('@@', resolve('src/components'))
      .set('_c', resolve('src/components'));
    config.module
      .rule('vue')
      .test(/\.vue$/)
      .end();
    // cài lại alias
    config.resolve.alias.set('@api', resolve('src/api'));
    // node
    config.node.set('__dirname', true).set('__filename', true);
    config.plugin('monaco').use(new MonacoWebpackPlugin());
  },

  // Đặt thành false để không tạo tệp .map khi đóng gói
  productionSourceMap: false,
  // Viết ở đây đường dẫn cơ bản để gọi giao diện nhằm giải quyết các vấn đề giữa các miền. Nếu một proxy được đặt thì baseUrl của axios trong môi trường phát triển cục bộ của bạn sẽ được viết là '' ，tức là chuỗi trống
  devServer: {
    port: 1617, // hải cảng
  },
  publicPath: '/admin',
  assetsDir: 'system_static',
  indexPath: 'index.html',
};
