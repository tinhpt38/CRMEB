module.exports = {
	productionSourceMap: false, // Các tập tin bản đồ không được xuất ra trong quá trình đóng gói sản xuất để tăng tốc độ đóng gói.
	configureWebpack: config => {
		if (process.env.NODE_ENV === 'production') {
			config.optimization.minimizer[0].options.terserOptions.compress.warnings = false
			config.optimization.minimizer[0].options.terserOptions.compress.drop_console = true
			config.optimization.minimizer[0].options.terserOptions.compress.drop_debugger = true
			config.optimization.minimizer[0].options.terserOptions.compress.pure_funcs = ['console.log']
		}
	}
}
