<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<title><?php echo $Title; ?> - <?php echo $Powered; ?></title>
<link rel="stylesheet" href="./css/install.css?v=9.0" />
<script src="js/jquery.js"></script>
<link rel="stylesheet" href="./css/step5.css"/>

<?php
$uri = $_SERVER['REQUEST_URI'];
$root = substr($uri, 0,strpos($uri, "install"));
$admin = $root."../index.php/admin/index/";
$host = $_SERVER['HTTP_HOST'];
?>
</head>
<body>
<div class="wrap">
    <div class="title">
        Cài đặt hoàn tất
    </div>

  <section class="section">
      <div class="title">
          <img src="./images/install/success.png" alt="">
          <h1>Cài đặt thành công</h1>
      </div>
      <div class="progress">
          <div class="trip p8">
              Để bảo mật trang web của bạn, sau khi cài đặt hoàn tất, bạn có thể thay đổi“install”Xóa tất cả các file trong thư mục để tránh cài đặt lặp lại。
          </div>
      </div>
    <div class="bottom-btn">
        <div class="pre btn">
            <a href="<?php echo 'http://'.$host;?>" class="btn mid">Vào quầy lễ tân</a>
        </div>
        <div class="admin btn">
            <a href="<?php echo 'http://'.$host;?>/admin" class="btn btn_submit J_install_btn mid">Nhập nền</a>
        </div>
      </div>
    </div>
  </section>
</div>
<?php require './templates/footer.php';?>
<script>
$(function(){
	$.ajax({
	type: "POST",
	url: "http://shop.crmeb.net/index.php/admin/server.upgrade_api/updatewebinfo",
        header:{
            'Access-Control-Allow-Origin':'*',
            'Access-Control-Allow-Headers':'X-Requested-With',
            'Access-Control-Allow-Methods':'PUT,POST,GET,DELETE,OPTIONS'
        },
	data: {
	    host:'<?php echo $host;?>',
        https:'<?php echo 'http://'.$host;?>',
        version:'<?php echo $version;?>',
        uid:'<?php echo $uid;?>',
        version_code:'<?php echo $platform;?>',
        ip:<?php echo json_encode($ip);?>
    },
	dataType: 'json',
	success: function(){}
	});
});
</script>
</body>
</html>
