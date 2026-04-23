import { Message } from 'element-ui';

/**
 * chức năng chuyển đổi màu
 * @method hexToRgb màu hex sang màu rgb
 * @method rgbToHex màu rgb thành màu Hex
 * @method getDarkColor làm sâu sắc thêm giá trị màu
 * @method getLightColor làm sáng giá trị màu
 */
export function useChangeColor() {
  // str chuỗi giá trị màu
  const hexToRgb = (str) => {
    let hexs = '';
    let reg = /^#?[0-9A-Fa-f]{6}$/;
    if (!reg.test(str)) {
      Message.warning('Đầu vào không chính xáchex');
      return '';
    }
    str = str.replace('#', '');
    hexs = str.match(/../g);
    for (let i = 0; i < 3; i++) hexs[i] = parseInt(hexs[i], 16);
    return hexs;
  };
  // r Đại diện cho màu đỏ | g đại diện cho màu xanh lá cây | b đại diện cho màu xanh
  const rgbToHex = (r, g, b) => {
    let reg = /^\d{1,3}$/;
    if (!reg.test(r) || !reg.test(g) || !reg.test(b)) {
      Message.warning('Đã nhập sai giá trị màu rgb');
      return '';
    }
    let hexs = [r.toString(16), g.toString(16), b.toString(16)];
    for (let i = 0; i < 3; i++) if (hexs[i].length == 1) hexs[i] = `0${hexs[i]}`;
    return `#${hexs.join('')}`;
  };
  // color chuỗi giá trị màu | level Mức độ nông, giới hạn ở mức 0-1
  const getDarkColor = (color, level) => {
    let reg = /^#?[0-9A-Fa-f]{6}$/;
    if (!reg.test(color)) {
      Message.warning('Nhập sai giá trị màu hex');
      return '';
    }
    let rgb = useChangeColor().hexToRgb(color);
    for (let i = 0; i < 3; i++) rgb[i] = Math.floor(rgb[i] * (1 - level));
    return useChangeColor().rgbToHex(rgb[0], rgb[1], rgb[2]);
  };
  // color chuỗi giá trị màu | level Mức độ đào sâu được giới hạn ở mức 0-1
  const getLightColor = (color, level) => {
    let reg = /^#?[0-9A-Fa-f]{6}$/;
    if (!reg.test(color)) {
      Message.warning('Nhập sai giá trị màu hex');
      return '';
    }
    let rgb = useChangeColor().hexToRgb(color);
    for (let i = 0; i < 3; i++) rgb[i] = Math.floor((255 - rgb[i]) * level + rgb[i]);
    return useChangeColor().rgbToHex(rgb[0], rgb[1], rgb[2]);
  };
  return {
    hexToRgb,
    rgbToHex,
    getDarkColor,
    getLightColor,
  };
}
