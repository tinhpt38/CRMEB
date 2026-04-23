import CryptoJS from 'crypto-js';
/**
 * @word Những gì cần mã hóa
 * @keyWord Chuỗi từ khóa được máy chủ trả về ngẫu nhiên
 *  */
export function aesEncrypt(word, keyWord = 'XwKsGlMcdPMEhR1B') {
  var key = CryptoJS.enc.Utf8.parse(keyWord);
  var srcs = CryptoJS.enc.Utf8.parse(word);
  var encrypted = CryptoJS.AES.encrypt(srcs, key, { mode: CryptoJS.mode.ECB, padding: CryptoJS.pad.Pkcs7 });
  return encrypted.toString();
}

/**
 * @word hash256Những gì cần mã hóa
 * @keyWord Chuỗi từ khóa được máy chủ trả về ngẫu nhiên
 *  */
export function aesEncryptHash(word, keyWord = 'XwKsGlMcdPMEhR1B') {
  var key = CryptoJS.enc.Utf8.parse(keyWord);
  var srcs = CryptoJS.enc.Utf8.parse(word);
  var encrypted = CryptoJS.HmacSHA256(srcs, key);
  return CryptoJS.enc.Hex.stringify(encrypted);
}
