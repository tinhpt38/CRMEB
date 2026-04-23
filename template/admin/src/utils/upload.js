import md5 from 'js-md5'; //Giới thiệu mã hóa MD5
import { upload } from '@/api/upload.js'; // Điều này đề cập đến phương thức api của giao diện gọi giao diện người dùng
export const uploadByPieces = ({ file, pieceSize = 2, success, error, uploading }) => {
  // Nếu tệp được truyền vào trống, hãy quay lại trực tiếp.
  if (!file) return;
  let fileMD5 = ''; // Tổng danh sách tập tin
  const chunkSize = pieceSize * 1024 * 1024; // 5MBcái
  const chunkCount = Math.ceil(file.size / chunkSize); // Tổng số mảnh
  // lấymd5
  const readFileMD5 = () => {
    // Đọc tập tin videomd5
    let fileRederInstance = new FileReader();
    fileRederInstance.readAsBinaryString(file);
    fileRederInstance.addEventListener('load', (e) => {
      let fileBolb = e.target.result;
      fileMD5 = md5(fileBolb);
      readChunkMD5();
    });
  };
  const getChunkInfo = (file, currentChunk, chunkSize) => {
    let start = currentChunk * chunkSize;
    let end = Math.min(file.size, start + chunkSize);
    let chunk = file.slice(start, end);
    return { start, end, chunk };
  };
  // Thực hiện xử lý chunk cho mỗi tệp
  const readChunkMD5 = async () => {
    // Tải lên từng đoạn cho một tệp
    for (var i = 0; i < chunkCount; i++) {
      const { chunk } = getChunkInfo(file, i, chunkSize);
      await uploadChunk({ chunk, currentChunk: i, chunkCount });
    }
  };
  const uploadChunk = (chunkInfo) => {
    // progressFun()
    return new Promise((resolver, reject) => {
      let config = {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      };
      // Tạo một đối tượng formData. Sau đây là các đối tượng được chuyển đến phần phụ trợ kết hợp với các dự án khác nhau.。
      let fetchForm = new FormData();
      fetchForm.append('chunkNumber', chunkInfo.currentChunk + 1); // Phim nào
      fetchForm.append('chunkSize', chunkSize); // Giới hạn về kích thước phân đoạn, ví dụ: giới hạn 5M
      fetchForm.append('currentChunkSize', chunkInfo.chunk.size); // kích thước của mỗi mảnh
      fetchForm.append('file', chunkInfo.chunk); //tập tin cho mỗi phần
      fetchForm.append('filename', file.name); // tên tập tin
      fetchForm.append('totalChunks', chunkInfo.chunkCount); //Tổng số mảnh
      fetchForm.append('md5', fileMD5);
      upload(fetchForm, config)
        .then((res) => {
          if (res.data.code == 1) {
            // // Kết hợp các dự án khác nhau và trả về thông tin thành công
            // Nếu phần sau không được sử dụng trong dự án, bạn không cần phải mở bình luận.
            uploading(chunkInfo.currentChunk + 1, chunkInfo.chunkCount);
            resolver(true);
          } else if (res.data.code == 2) {
            if (chunkInfo.currentChunk < chunkInfo.chunkCount - 1) {
            } else {
              // Khi tổng số lớn hơn hoặc bằng số mảnh
              if (chunkInfo.currentChunk + 1 == chunkInfo.chunkCount) {
                success(res.data);
              }
            }
          }
        })
        .catch((e) => {
          error && error(e);
        });
    });
  };
  readFileMD5(); // Bắt đầu thực thi mã
};
