Math.easeInOutQuad = function (t, b, c, d) {
  t /= d / 2;
  if (t < 1) {
    return (c / 2) * t * t + b;
  }
  t--;
  return (-c / 2) * (t * (t - 2) - 1) + b;
};

//requestAnimationFramecho hoạt hình thông minh http://goo.gl/sx5sts
var requestAnimFrame = (function () {
  return (
    window.requestAnimationFrame ||
    window.webkitRequestAnimationFrame ||
    window.mozRequestAnimationFrame ||
    function (callback) {
      window.setTimeout(callback, 1000 / 60);
    }
  );
})();

/**
 * Vì quá khó để phát hiện các phần tử cuộn nên chỉ cần di chuyển tất cả chúng
 * @param {number} amount
 */
function move(amount) {
  document.documentElement.scrollTop = amount;
  document.body.parentNode.scrollTop = amount;
  document.body.scrollTop = amount;
}

function position() {
  return document.documentElement.scrollTop || document.body.parentNode.scrollTop || document.body.scrollTop;
}

/**
 * @param {number} to
 * @param {number} duration
 * @param {Function} callback
 */
export function scrollTo(to, duration, callback) {
  const start = position();
  const change = to - start;
  const increment = 20;
  let currentTime = 0;
  duration = typeof duration === 'undefined' ? 500 : duration;
  var animateScroll = function () {
    // Tăng số lần
    currentTime += increment;
    // Tìm giá trị này bằng hàm Math
    var val = Math.easeInOutQuad(currentTime, start, change, duration);
    // di chuyển phần tử này
    move(val);
    // Hoạt ảnh có kết thúc hay không
    if (currentTime < duration) {
      requestAnimFrame(animateScroll);
    } else {
      if (callback && typeof callback === 'function') {
        //Hoạt ảnh đã hoàn thành, gọi lại
        callback();
      }
    }
  };
  animateScroll();
}
