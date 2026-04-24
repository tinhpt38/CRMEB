var inlineTags = {
	abbr: 1,
	b: 1,
	big: 1,
	code: 1,
	del: 1,
	em: 1,
	i: 1,
	ins: 1,
	label: 1,
	q: 1,
	small: 1,
	span: 1,
	strong: 1
}
export default {
	// Lay mot so thuoc tinh style o the goc cho rich-text
	getStyle: function(style) {
		if (style) {
			var i, j, res = '';
			if ((i = style.indexOf('display')) != -1)
				res = style.substring(i, (j = style.indexOf(';', i)) == -1 ? style.length : j);
			if ((i = style.indexOf('float')) != -1)
				res += ';' + style.substring(i, (j = style.indexOf(';', i)) == -1 ? style.length : j);
			return res;
		}
	},
	getNode: function(item) {
		return [item];
	},
	// Co hien thi bang rich-text hay khong
	useRichText: function(item) {
		return !item.c && !inlineTags[item.name] && (item.attrs.style || '').indexOf('display:inline') == -1;
	}
}
