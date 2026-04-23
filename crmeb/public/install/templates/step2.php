<!doctype html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title><?php echo $Title; ?> - <?php echo $Powered; ?></title>
    <link rel="stylesheet" href="./css/install.css?v=9.0"/>
    <link rel="stylesheet" href="./css/step2.css"/>
    <!-- Giới thiệu phong cách -->
    <link rel="stylesheet" href="./css/theme-chalk.css">
    <!-- import Vue before Element -->
    <script src="./js/vue2.6.11.js"></script>
    <!-- import JavaScript -->
    <script src="./js/element-ui.js?v=9.0"></script>
</head>
<body>
<div class="wrap" id="step2">
    <!--    --><?php //require './templates/header.php'; ?>
    <div class="title">
        Phát hiện cài đặt
    </div>
    <div class="content">
        <div class="menu">
            <div class="head">
                <h1>Phát hiện cài đặt</h1>
                <a class="again" href="<?php echo $_SERVER['PHP_SELF']; ?>?step=2">Kiểm tra lại
                    <img class="upload" src="./images/install/upload.png" alt="">
                </a>
            </div>
            <div class="p8">Môi trường cài đặt phải đáp ứng yêu cầu vận hành hệ thống</div>
            <div>
                <div class="tab" :class="{'on': index === 0}" @click="index = 0">
                    <div class="left-img">
                        <img class="env" src="./images/install/environment.png" alt="">
                        <img v-if="`<?php echo $passOne; ?>` == 'no'" class="warring"
                             src="./images/install/warring.png" alt="">
                        <img v-else class="warring" src="./images/install/sure.png" alt="">

                    </div>
                    <div>
                        <div>Môi trường và cấu hình</div>
                        <div class="p8">Môi trường vận hành hệ thống cơ bản</div>
                    </div>
                </div>
                <div class="tab" :class="{'on': index === 1}" @click="index = 1">
                    <div class="left-img">
                        <img class="jur" src="./images/install/jurisdiction.png" alt="">
                        <img v-if="`<?php echo $passTwo; ?>` == 'no'" class="warring btn-warning"
                             src="./images/install/warring.png" alt="">
                        <img v-else class="warring btn-warning" src="./images/install/sure.png" alt="">
                    </div>
                    <div>
                        <div>Phát hiện quyền</div>
                        <div class="p8">Phát hiện quyền thư mục và tập tin</div>
                    </div>
                </div>

            </div>
        </div>
        <section class="config-list">
            <!--        <div class="step">-->
            <!--            <ul>-->
            <!--                <li class="current"><em>1</em>Môi trường phát hiện</li>-->
            <!--                <li><em>2</em>Tạo dữ liệu</li>-->
            <!--                <li><em>3</em>Hoàn tất cài đặt</li>-->
            <!--            </ul>-->
            <!--        </div>-->
            <div class="server">
                <table width="100%" v-if="index === 0">
                    <tr>
                        <td class="td1">Kiểm tra môi trường</td>
                        <td class="td1" width="25%">Cấu hình đề xuất</td>
                        <td class="td1" width="25%">Yêu cầu tối thiểu</td>
                        <td class="td1" width="25%">Tình trạng hiện tại</td>
                    </tr>
                    <tr>
                        <td>hệ điều hành</td>
                        <td>loạiUNIX</td>
                        <td>không có giới hạn</td>
                        <td><div class="ls-td"><img class="yes" src="./images/install/yes.png" alt="Phải"><?php echo $os; ?></div></td>

                    </tr>
                    <tr>
                        <td>Môi trường máy chủ</td>
                        <td>apache/nginx</td>
                        <td>apache2.0Trên/nginx1.6 trở lên</td>
                        <td><div class="ls-td"><img class="yes" src="./images/install/yes.png" alt="Phải"><?php echo $server; ?></div></td>

                    </tr>
                    <tr>
                        <td>PHPPhiên bản</td>
                        <td>><?php echo PHP_EDITION; ?></td>
                        <td><?php echo PHP_EDITION; ?>bên trên</td>
                        <td><div class="ls-td"><img class="yes" src="./images/install/yes.png" alt="Phải"><?php echo $phpv; ?></div></td>
                    </tr>
                    <tr>
                        <td>Tải lên tệp đính kèm</td>
                        <td>>2M</td>
                        <td>không có giới hạn</td>
                        <td><div class="ls-td"><?php echo $uploadSize; ?></div></td>

                    </tr>
                    <tr>
                        <td>session</td>
                        <td>cho phép</td>
                        <td>cho phép</td>
                        <td><div class="ls-td"><?php echo $session; ?></div></td>

                    </tr>
                    <tr>
                        <td>safe_mode</td>
                        <td>Cấu hình cơ bản</td>
                        <td>cho phép</td>
                        <td><div class="ls-td"><?php echo $safe_mode; ?></div></td>

                    </tr>
                    <tr>
                        <td>GDThư viện</td>
                        <td>Phải được bật</td>
                        <td>1.0bên trên</td>
                        <td><div class="ls-td"><?php echo $gd; ?></div></td>

                    </tr>
                    <tr>
                        <td>mysqli</td>
                        <td>Phải được bật</td>
                        <td>cho phép</td>
                        <td><div class="ls-td"><?php echo $mysql; ?></div></td>

                    </tr>
                    <tr>
                        <td>curl_init</td>
                        <td>phải được gia hạn</td>
                        <td>cho phép</td>
                        <td><div class="ls-td"><?php echo $curl; ?></div></td>

                    </tr>
                    <tr>
                        <td>bcmath</td>
                        <td>phải được gia hạn</td>
                        <td>cho phép</td>
                        <td><div class="ls-td"><?php echo $bcmath; ?></div></td>
                    </tr>
                    <tr>
                        <td>openssl</td>
                        <td>phải được gia hạn</td>
                        <td>cho phép</td>
                        <td><div class="ls-td"><?php echo $openssl; ?></div></td>
                    </tr>
                </table>


                <table width="100%" v-else>
                    <tr>
                        <td class="td1">Kiểm tra quyền</td>
                        <td class="td1" width="25%">Cấu hình đề xuất</td>
                        <td class="td1" width="25%">viết</td>
                        <td class="td1" width="25%">đọc</td>
                    </tr>
                    <?php
                    foreach ($folder as $dir) {
                        $Testdir = APP_DIR . $dir;
                        if (!is_file($Testdir)) {
                            if (!is_dir($Testdir)) {
                                dir_create($Testdir);
                            }
                        }

                        if (testwrite($Testdir)) {
                            $w = '<img class="yes" src="./images/install/yes.png" alt="Phải">có thể ghi được ';
                        } else {
                            $w = '<img class="no" src="./images/install/warring.png" alt="sai">Không thể ghi ';
                        }


                        if (is_readable($Testdir)) {
                            $r = '<img class="yes" src="./images/install/yes.png" alt="Phải">có thể đọc được';
                        } else {
                            $r = '<img class="no" src="./images/install/warring.png" alt="sai">Không thể đọc được';
                        }
                        ?>
                        <tr>
                            <td><?php echo $dir; ?></td>
                            <td>Đọc và viết</td>
                            <td>
                                <div class="ls-td"><?php echo $w; ?></div>
                            </td>
                            <td>
                                <div class="ls-td"><?php echo $r; ?></div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <?php
                    foreach ($file as $filename) {
                        $filedir = APP_DIR . $filename;
                        if (is_writeable($filedir)) {
                            $w = '<img class="yes" src="./images/install/yes.png" alt="Phải">có thể ghi được ';
                        } else {
                            $w = '<img class="no" src="./images/install/warring.png" alt="sai">Không thể ghi ';
                        }
                        if (is_readable($filedir)) {
                            $r = '<img class="yes" src="./images/install/yes.png" alt="Phải">có thể đọc được';
                        } else {
                            $r = '<img class="no" src="./images/install/warring.png" alt="sai">Không thể đọc được';
                        }
                        ?>

                        <tr>
                            <td><?php echo $filename; ?></td>
                            <td>Đọc và viết</td>
                            <td>
                                <div class="ls-td"><?php echo $w; ?></div>
                            </td>
                            <td>
                                <div class="ls-td"><?php echo $r; ?></div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>


                </table>
                <!--            <table width="100%">-->
                <!--                <tr>-->
                <!--                  <td class="td1" width="25%">Tính năng phát hiện chức năng phải được bật</td>-->
                <!--                  <td class="td1" width="25%">Tình trạng hiện tại</td>-->
                <!--                  <td class="td1" width="25%">Tính năng phát hiện chức năng phải được bật</td>-->
                <!--                  <td class="td1" width="25%">Tình trạng hiện tại</td>-->
                <!--                </tr>-->
                <!--              <tr>-->
                <!--                    <td>file_put_contents</td>-->
                <!--                    <td>--><?php //echo $file_put_contents; ?><!--</td>-->
                <!--                <td>imagettftext</td>-->
                <!--                <td>--><?php //echo $imagettftext; ?><!--</td>-->
                <!--                </tr>-->
                <!--                <tr>-->
                <!--                    <td>proc_open</td>-->
                <!--                    <td>--><?php //echo $proc_open; ?><!--</td>-->
                <!--                    <td>pcntl_signal</td>-->
                <!--                    <td>--><?php //echo $pcntl_signal; ?><!--</td>-->
                <!--                </tr>-->
                <!--                <tr>-->
                <!--                    <td>pcntl_signal_dispatch</td>-->
                <!--                    <td>--><?php //echo $pcntl_signal_dispatch; ?><!--</td>-->
                <!--                    <td>pcntl_fork</td>-->
                <!--                    <td>--><?php //echo $pcntl_fork; ?><!--</td>-->
                <!--                </tr>-->
                <!--                <tr>-->
                <!--                    <td>pcntl_wait</td>-->
                <!--                    <td>--><?php //echo $pcntl_wait; ?><!--</td>-->
                <!--                    <td>pcntl_alarm</td>-->
                <!--                    <td>--><?php //echo $pcntl_alarm; ?><!--</td>-->
                <!--                </tr>-->
                <!--            </table>-->
            </div>

        </section>
    </div>
    <div class="trip mid">
        <img src="./images/install/trip-icon.png" alt="">
        Lời nhắc ấm áp: Cần có cấu hình giả tĩnh để chạy chương trình, nếu không nó sẽ không sử dụng được sau khi cài đặt.
    </div>
    <div class="bottom-btn">
        <div class="bottom tac up-btn">
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?step=1" class="btn">Bước trước</a>
        </div>
        <div class="bottom tac">
            <?php if ($passOne == 'no' || $passTwo == 'no') { ?>
                <span class="next" @click="next" class="btn">Bước tiếp theo</span>
            <?php } else { ?>
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>?step=3" class="btn next">Bước tiếp theo</a>
            <?php } ?>
        </div>
    </div>

</div>
<?php require './templates/footer.php'; ?>
</body>
<script>
    new Vue({
        el: '#step2',
        data() {
            return {index: 0}
        },
        methods: {
            next() {
                this.$message({
                    message: 'Kiểm tra môi trường cài đặt không thành công, vui lòng kiểm tra',
                    type: 'warning'
                });
            }
        }
    })
</script>
</html>
