/**
 * @Nén các phương thức công khai
 * Tệp @params
 * @return file nén, hỗ trợ 2 loại file và blob
 */
export default function compressImg(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    // readAsDataURL Phương thức này đọc đối tượng Blob hoặc File được chỉ định. Khi thao tác đọc hoàn tất, trạng thái sẵn sàng sẽ thay đổi thành DONE đã hoàn thành và kích hoạt loadend (en-US) sự kiện,
    // Đồng thời, thuộc tính kết quả sẽ chứa mộtdata:URLChuỗi định dạng (được mã hóa base64) để thể hiện nội dung của tệp đang được đọc。
    reader.readAsDataURL(file);
    reader.onload = () => {
      const img = new Image();
      img.src = reader.result;
      img.onload = () => {
        // Chiều rộng và chiều cao của hình ảnh
        const w = img.width;
        const h = img.height;
        const canvas = document.createElement('canvas');
        // canvasCắt ảnh, đặt ở đây về kích thước ban đầu của ảnh
        canvas.width = w;
        canvas.height = h;
        const ctx = canvas.getContext('2d');
        // canvasKhi chuyển đổi png sang jpg, nền sẽ chuyển sang màu đen, vì vậy trước tiên hãy đặt nền trắng trên khung vẽ.
        ctx.fillStyle = '#fff';
        // fillRect()Phương thức vẽ một hình chữ nhật có điểm bắt đầu (điểm trên bên trái) của hình chữ nhật tại
        // (x, y) ，Chiều rộng và chiều cao của nó được xác định bởi chiều rộng và chiều cao tương ứng và kiểu điền được xác định bởi fillStyle hiện tại。
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        // vẽ hình ảnh
        ctx.drawImage(img, 0, 0, w, h);

        // canvasChuyển đổi hình ảnh để đạt được hiệu ứng nén hình ảnh
        // Trả về một URI dữ liệu base64 chứa hình ảnh hiển thị. Khi định dạng hình ảnh được chỉ định là image/jpeg hoặc image/webp,
        // Bạn có thể chọn chất lượng hình ảnh từ 0 đến 1. Nếu giá trị nằm ngoài phạm vi, giá trị mặc định là 0,92 sẽ được sử dụng. Các thông số khác sẽ bị bỏ qua。
        const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
        let newFile = dataURLtoFile(dataUrl, file.name);
        resolve(newFile);
      };
    };
  });
}
//  base64->file
function dataURLtoFile(dataurl, fileName) {
  let arr = dataurl.split(','),
    mime = arr[0].match(/:(.*?);/)[1],
    bstr = atob(arr[1]),
    n = bstr.length,
    u8arr = new Uint8Array(n);
  while (n--) {
    u8arr[n] = bstr.charCodeAt(n);
  }
  return new File([u8arr], fileName, { type: mime });
}
// base64->blob
function dataURLtoBlob(dataurl) {
  const arr = dataurl.split(','),
    mime = arr[0].match(/:(.*?);/)[1],
    bstr = atob(arr[1]);
  let n = bstr.length;
  const u8arr = new Uint8Array(n);
  while (n--) {
    u8arr[n] = bstr.charCodeAt(n);
  }
  return new Blob([u8arr], { type: mime });
}
