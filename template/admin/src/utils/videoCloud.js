// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

import * as qiniu from 'qiniu-js';
import Cos from 'cos-js-sdk-v5';
import axios from 'axios';
import { upload, ossUpload } from '@/api/upload';

const sign = (method, publicKey, privateKey, md5, contentType, date, bucketName, fileName) => {
  const CryptoJS = require('crypto-js'); // Thư viện thuật toán mã hóa crypto-js được sử dụng ở đây. Phương pháp cài đặt sẽ được giải thích sau.
  const CanonicalizedResource = `/${bucketName}/${fileName}`;
  const StringToSign = method + '\n' + md5 + '\n' + contentType + '\n' + date + '\n' + CanonicalizedResource; // md5 và ngày ở đây là tùy chọn, contentType là tùy chọn cho yêu cầu PUT và bắt buộc cho yêu cầu POST.
  let Signature = CryptoJS.HmacSHA1(StringToSign, privateKey);
  Signature = CryptoJS.enc.Base64.stringify(Signature);
  return 'UCloud' + ' ' + publicKey + ':' + Signature;
};
export default {
  videoUpload(config) {
    let result;
    switch (config.type) {
      case 'COS':
        result = this.cosUpload(config.evfile, config.res.data, config.uploading);
        break;
      case 'OSS':
        result = this.ossHttp(config.evfile, config.res, config.uploading);
        break;
      case 'OBS':
        result = this.obsHttp(config.evfile, config.res, config.uploading);
        break;
      case 'US3':
        result = this.us3Http(config.evfile, config.res, config.uploading);
        break;
      case 'JDOSS':
        result = this.jdHttp(config.evfile, config.res, config.uploading);
        break;
      case 'CTOSS':
        result = this.obsHttp(config.evfile, config.res, config.uploading);
        break;
      case 'QINIU':
        result = this.qiniuHttp(config.evfile, config.res, config.uploading);
        break;
      case 'local':
        result = this.uploadMp4ToLocal(config.evfile, config.res, config.uploading);
        break;
    }
    return result;
  },
  cosUpload(file, config, uploading) {
    let cos = new Cos({
      getAuthorization(options, callback) {
        callback({
          TmpSecretId: config.credentials.tmpSecretId, // khóa tạm thời tmpSecretId
          TmpSecretKey: config.credentials.tmpSecretKey, // khóa tạm thời tmpSecretKey
          XCosSecurityToken: config.credentials.sessionToken, // khóa tạm thời sessionToken
          ExpiredTime: config.expiredTime, // Dấu thời gian hết hạn của khóa tạm thời là dấu thời gian được thêm vào khi đăng ký khóa tạm thời. durationSeconds
        });
      },
    });
    let fileObject = file.target.files[0];
    let Key = fileObject.name;
    let pos = Key.lastIndexOf('.');
    let suffix = '';
    if (pos !== -1) {
      suffix = Key.substring(pos);
    }
    let filename = this.getVideoName(suffix);
    return new Promise((resolve, reject) => {
      cos.sliceUploadFile(
        {
          Bucket: config.bucket /* phải */,
          Region: config.region /* phải */,
          Key: filename /* phải */,
          Body: fileObject, // Đối tượng tập tin tải lên
          onProgress: function (progressData) {
            uploading(progressData);
          },
        },
        function (err, data) {
          if (err) {
            reject({ msg: err });
          } else {
            resolve({ url: 'http://' + data.Location, ETag: data.ETag });
          }
        },
      );
    });
  },
  cosHttp(evfile, res, videoIng) {
    // Đám mây Tencent
    // định dạng mã hóa url để mã hóa nhiều ký tự hơn
    let camSafeUrlEncode = function (str) {
      return encodeURIComponent(str)
        .replace(/!/g, '%21')
        .replace(/'/g, '%27')
        .replace(/\(/g, '%28')
        .replace(/\)/g, '%29')
        .replace(/\*/g, '%2A');
    };
    let fileObject = evfile.target.files[0];
    let Key = fileObject.name;
    let pos = Key.lastIndexOf('.');
    let suffix = '';
    if (pos !== -1) {
      suffix = Key.substring(pos);
    }
    let filename = this.getVideoName(suffix);
    let data = res.data;
    let XCosSecurityToken = data.credentials.sessionToken;
    let url = data.url + camSafeUrlEncode(filename).replace(/%2F/g, '/');
    let xhr = new XMLHttpRequest();
    xhr.open('PUT', url, true);
    XCosSecurityToken && xhr.setRequestHeader('x-cos-security-token', XCosSecurityToken);
    xhr.upload.onprogress = function (e) {
      let progress = Math.round((e.loaded / e.total) * 10000) / 100;
      videoIng(true, progress);
    };
    return new Promise((resolve, reject) => {
      xhr.onload = function () {
        if (/^2\d\d$/.test('' + xhr.status)) {
          var ETag = xhr.getResponseHeader('etag');
          videoIng(false, 0);
          resolve({ url: url, ETag: ETag });
        } else {
          reject({ msg: 'tài liệu ' + filename + ' Tải lên không thành công, mã trạng thái：' + xhr.statu });
        }
      };
      xhr.onerror = function () {
        reject({ msg: 'tài liệu ' + filename + 'Tải lên không thành công. Vui lòng kiểm tra xem quy tắc tên miền chéo CORS có được định cấu hình hay không.' });
      };
      xhr.send(fileObject);
      xhr.onreadystatechange = function () {};
    });
  },
  ossHttp(evfile, res, videoIng) {
    let that = this;
    let fileObject = evfile.target.files[0];
    let file = fileObject.name;
    let pos = file.lastIndexOf('.');
    let suffix = '';
    if (pos !== -1) {
      suffix = file.substring(pos);
    }
    let filename = this.getVideoName(suffix);
    let formData = new FormData();
    let data = res.data;
    // Hãy chú ý đến cách viết hoa của các khóa được thêm bằng cách thêm vào formData.
    formData.append('key', filename); // Đường dẫn tệp được lưu trữ trong oss
    formData.append('OSSAccessKeyId', data.accessid); // accessKeyId
    formData.append('policy', data.policy); // policy
    formData.append('Signature', data.signature); // chữ ký
    // Nếu là file base64 thì chỉ cần chuyển chuỗi base64 thành đối tượng blob và tải nó lên.
    formData.append('file', fileObject);
    formData.append('success_action_status', 200); // Mã hoạt động được trả về khi thành công
    let url = data.host;
    let fileUrl = url + '/' + filename;
    videoIng(true, 100);
    return new Promise((resolve, reject) => {
      axios.defaults.withCredentials = false;
      axios
        .post(url, formData)
        .then(() => {
          // that.progress = 0;
          videoIng(false, 0);
          resolve({ url: fileUrl });
        })
        .catch((res) => {
          reject({ msg: res });
        });
    });
  },
  obsHttp(file, res, videoIng) {
    const fileObject = file.target.files[0];
    const Key = fileObject.name;
    const pos = Key.lastIndexOf('.');
    let suffix = '';
    if (pos !== -1) {
      suffix = Key.substring(pos);
    }
    const filename = this.getVideoName(suffix);
    const formData = new FormData();
    const data = res.data;
    // Hãy chú ý đến cách viết hoa của các khóa được thêm bằng cách thêm vào formData.
    formData.append('key', filename);
    formData.append('AccessKeyId', data.accessid);
    formData.append('policy', data.policy);
    formData.append('signature', data.signature);
    formData.append('file', fileObject);
    formData.append('success_action_status', 200);
    const url = data.host;
    const fileUrl = url + '/' + filename;
    videoIng(true, 100);
    return new Promise((resolve, reject) => {
      axios.defaults.withCredentials = false;
      axios
        .post(url, formData)
        .then(() => {
          videoIng(false, 0);
          resolve({ url: data.cdn ? data.cdn + '/' + filename : fileUrl });
        })
        .catch((res) => {
          reject({ msg: res });
        });
    });
  },
  us3Http(file, res, videoIng) {
    const fileObject = file.target.files[0];
    const Key = fileObject.name;
    const pos = Key.lastIndexOf('.');
    let suffix = '';
    if (pos !== -1) {
      suffix = Key.substring(pos);
    }
    const filename = this.getVideoName(suffix);
    const data = res.data;

    const auth = sign('PUT', data.accessid, data.secretKey, '', fileObject.type, '', data.storageName, filename);
    return new Promise((resolve, reject) => {
      axios.defaults.withCredentials = false;
      const url = `https://${data.storageName}.cn-bj.ufileos.com/${filename}`;
      axios
        .put(url, fileObject, {
          headers: {
            Authorization: auth,
            'content-type': fileObject.type,
          },
        })
        .then((res) => {
          videoIng(false, 0);
          resolve({ url: data.cdn ? data.cdn + '/' + filename : url });
        })
        .catch((res) => {
          reject({ msg: res });
        });
    });
  },
  qiniuHttp(evfile, res, videoIng) {
    const uptoken = res.data.token;
    const file = evfile.target.files[0]; // Blob Đối tượng, tập tin được tải lên
    const Key = file.name; // Sau khi tải lên, tên tài nguyên tệp sẽ dựa trên khóa đã đặt. Nếu khóa là null hoặc không được xác định, tên tài nguyên tệp sẽ sử dụng giá trị băm làm tên tài nguyên.。
    const pos = Key.lastIndexOf('.');
    let suffix = '';
    if (pos !== -1) {
      suffix = Key.substring(pos);
    }
    const filename = this.getVideoName(suffix);
    const fileUrl = res.data.domain + '/' + filename;
    const config = {
      useCdnDomain: true,
    };
    const putExtra = {
      fname: '', // Tên tập tin gốc
      params: {}, // Được sử dụng để đặt các biến tùy chỉnh
      mimeType: null, // Được sử dụng để giới hạn loại tệp được tải lên. Khi nó rỗng, điều đó có nghĩa là không có giới hạn về loại tệp; loại hạn chế được đặt trong mảng.： ["image/png", "image/jpeg", "image/gif"]
    };
    const observable = qiniu.upload(file, filename, uptoken, putExtra, config);

    return new Promise((resolve, reject) => {
      observable.subscribe({
        next: (result) => {
          const progress = Math.round(result.total.loaded / result.total.size);
          videoIng(true, progress);
          // Chủ yếu được sử dụng để hiển thị sự tiến bộ
        },
        error: (errResult) => {
          // Thông báo lỗi thất bại
          reject({ msg: errResult });
        },
        complete: (result) => {
          // Thông tin được trả về sau khi tiếp nhận thành công
          videoIng(false, 0);
          resolve({ url: res.data.cdn ? res.data.cdn + '/' + filename : fileUrl });
        },
      });
    });
  },
  // Tải lên đám mây JD
  jdHttp(evfile, r, videoIng) {
    const fileObject = evfile.target.files[0]; // Đối tượng tập tin thu được
    const formData = new FormData();
    formData.append('file', fileObject);
    return new Promise((resolve, reject) => {
      ossUpload(r.data.upload_url, formData)
        .then((res) => {
          console.log(res);
        })
        .catch((err) => {
          videoIng(true, 100);
          resolve(r.data);
        });
    });
  },
  // Tải lên cục bộ
  uploadMp4ToLocal(evfile, res, videoIng) {
    const fileObject = evfile.target.files[0]; // Đối tượng tập tin thu được
    const formData = new FormData();
    formData.append('file', fileObject);
    videoIng(true, 100);
    return upload(formData);
  },
  // Lấy tên video lưu trữ đám mây đã tải lên
  getVideoName(suffix) {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const name = new Date().getTime();
    return `attach/${year}/${month}/${name}` + suffix;
  },
};
