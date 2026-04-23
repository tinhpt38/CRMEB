<!doctype html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title><?php echo $Title; ?> - <?php echo $Powered; ?></title>
    <link rel="stylesheet" href="./css/install.css?v=9.0"/>
    <link rel="stylesheet" href="./css/step3.css"/>
    <!-- Giới thiệu phong cách -->
    <link rel="stylesheet" href="./css/theme-chalk.css">
    <!-- import Vue before Element -->
    <script src="./js/vue2.6.11.js"></script>
    <!-- import JavaScript -->
    <script src="./js/element-ui.js?v=9.0"></script>
</head>
<body>
<div class="wrap" id="step3">
    <div class="title">
        Tạo dữ liệu
    </div>
    <section class="section">
        <form id="J_install_form" action="index.php?step=4" method="post">
            <div class="server"  ref="mianscroll">
                <table width="100%">
                    <tr>
                        <td class="td1" width="100">Thông tin cơ sở dữ liệu</td>
                        <td class="td1" width="200">&nbsp;</td>
                        <td class="td1">&nbsp;</td>
                    </tr>

                    <tr>
                        <td class="tar">Tên người dùng cơ sở dữ liệu：</td>
                        <td><input type="text" name="dbuser" id="dbuser" value="<?php echo $MYSQL_USER; ?>" class="input"></td>
                        <td>
                            <div id="J_install_tip_dbuser"></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="tar">Mật khẩu cơ sở dữ liệu：</td>
                        <td><input type="password" name="dbpw" id="dbpw" value="<?php echo $MYSQL_PASSWORD; ?>" class="input" autoComplete="off"></td>
                        <td>
                            <div id="J_install_tip_dbpw"></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="tar">Tên cơ sở dữ liệu：</td>
                        <td><input type="text" name="dbname" id="dbname" value="<?php echo $MYSQL_DATABASE; ?>" class="input"></td>
                        <td>
                            <div id="J_install_tip_dbname"></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="tar">Cài đặt nâng cao：</td>
                        <td colspan="2">
                            <el-switch
                                    v-model="value"
                                    active-color="#37CA71"
                                    inactive-color="#575869">
                            </el-switch>
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr v-show="value">
                        <td class="tar">máy chủ cơ sở dữ liệu：</td>
                        <td><input type="text" name="dbhost" id="dbhost" value="<?php echo $MYSQL_HOST_IP; ?>" class="input"></td>
                        <td>
                            <div id="J_install_tip_dbhost"></div>
                        </td>
                    </tr>
                    <tr v-show="value">
                        <td class="tar">Cổng cơ sở dữ liệu：</td>
                        <td><input type="text" name="dbport" id="dbport" value="<?php echo $MYSQL_PORT; ?>" class="input"
                                   onBlur="mysqlDbPwd(0)"></td>
                        <td>
                            <div id="J_install_tip_dbport"></div>
                        </td>
                    </tr>

                    <tr v-show="value">
                        <td class="tar">Tiền tố bảng cơ sở dữ liệu：</td>
                        <td><input type="text" name="dbprefix" id="dbprefix" value="eb_" class="input"></td>
                        <td></td>
                    </tr>

                </table>
                <table width="100%">
                    <tr>
                        <td class="td1" width="100">Thông tin quản trị viên</td>
                        <td class="td1" width="200">&nbsp;</td>
                        <td class="td1">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="tar">Tài khoản quản trị viên：</td>
                        <td><input type="text" name="manager" id="manager" value="admin" class="input"
                                   onblur="checkForm()"></td>
                        <td>
                            <div id="J_install_tip_manager"></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="tar">Mật khẩu quản trị viên：</td>
                        <td><input type="password" name="manager_pwd" id="manager_pwd" class="input" autoComplete="off"
                                   placeholder="Vui lòng nhập mật khẩu(Ít nhất 6 ký tự)"  placeholder-class="pl-style" onblur="checkForm()">
                        </td>
                        <td>
                            <div id="J_install_tip_manager_pwd"><span class="gray">Vui lòng nhập mật khẩu ít nhất 6 ký tự</span></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="tar">Lặp lại mật khẩu：</td>
                        <td><input type="password" name="manager_ckpwd" id="manager_ckpwd" class="input"
                                   autoComplete="off" placeholder="Vui lòng nhập lại mật khẩu" onkeyup="checkForm()"></td>
                        <td>
                            <div id="J_install_tip_manager_ckpwd"></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="tar">Dữ liệu demo：</td>
                        <td colspan="2"><input style="width:14px;height:14px;" type="checkbox" id="demo" name="demo"
                                               value="demo" checked></td>
                    </tr>

                </table>
                <table>
                    <tr>
                        <td class="td1" width="100">Cài đặt bộ đệm</td>
                        <td class="td1" width="200">&nbsp;</td>
                        <td class="td1">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="tar">Phương pháp lưu vào bộ nhớ đệm：</td>
                        <td>
                            <el-radio v-model="radio" :label="0" name="cache_type" id="cache_type1">Bộ đệm tệp</el-radio>
                            <el-radio v-model="radio" :label="1" name="cache_type" id="cache_type2">redisbộ nhớ đệm</el-radio>
                        </td>
                        <td></td>
                    </tr>
                    <tr v-show="radio == 1">
                        <td class="tar">Địa chỉ máy chủ：</td>
                        <td><input type="text" name="rbhost" id="rbhost" value="<?php echo $REDIS_HOST_IP; ?>" class="input"></td>
                        <td>
                            <div id="J_install_redis_host"><span class="gray">redisĐịa chỉ máy chủ, thường127.0.0.1</span></div>
                        </td>
                    </tr>
                    <tr v-show="radio == 1">
                        <td class="tar">số cổng：</td>
                        <td><input type="text" name="rbport" id="rbport" value="<?php echo $REDIS_PORT; ?>" class="input" autoComplete="off">
                        </td>
                        <td>
                            <div id="J_install_redis_port"><span class="gray">redishải cảng,Mặc định là6379</span></div>
                        </td>
                    </tr>
                    <tr v-show="radio == 1">
                        <td class="tar">cơ sở dữ liệu：</td>
                        <td><input type="text" name="rbselect" id="rbselect" value="<?php echo $REDIS_DATABASE; ?>" class="input" autoComplete="off">
                        </td>
                        <td>
                            <div id="J_install_redis_select"><span class="gray">redisCơ sở dữ liệu, mặc định là0,Nói chung không có thay đổi nào được thực hiện</span></div>
                        </td>
                    </tr>
                    <tr v-show="radio == 1" id="scrollBtn">
                        <td class="tar">Mật khẩu cơ sở dữ liệu：</td>
                        <td><input type="text" name="rbpw" id="rbpw" value="<?php echo $REDIS_PASSWORD; ?>" class="input" autoComplete="off"></td>
                        <td>
                            <div id="J_install_redis_dbpw"><span class="gray">redisMật khẩu cơ sở dữ liệu</span></div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="bottom-btn">
                <div class="bottom tac up-btn">
                    <a href="./index.php?step=2" class="btn">Bước trước</a>
                </div>
                <div class="bottom tac next">
                    <a @click="submitForm();" class="btn">Bước tiếp theo</a>
                </div>
            </div>
        </form>
    </section>
    <div style="width:0;height:0;overflow:hidden;"><img src="./images/install/pop_loading.gif"></div>
    <script src="./js/jquery.js?v=9.0"></script>
    <script src="./js/validate.js?v=9.0"></script>
    <script src="./js/ajaxForm.js?v=9.0"></script>
    <script>
        //Xác minh thông tin quản trị viên
        function checkForm() {
            let manager = $.trim($('#manager').val());				//mẫu tên người dùng
            let manager_pwd = $.trim($('#manager_pwd').val());				//mẫu mật khẩu
            let manager_ckpwd = $.trim($('#manager_ckpwd').val());		//Khu vực nhắc mật khẩu
            if (manager.length == 0) {
                $('#J_install_tip_manager').html('<span for="dbname" generated="true" class="tips_error" style="">Vui lòng nhập tài khoản quản lý</span>');
                return false;
            }
            if (!(/^[a-zA-Z0-9]{0,32}$/.test(manager))) {
                $('#J_install_tip_manager').html('<span generated="true" class="tips_error" style="">Số tài khoản phải bằng tiếng Anh hoặc số</span>');
                return false;
            } else {
                $('#J_install_tip_manager').html('<span generated="true" class="tips_success" style="">Tên người dùng có sẵn</span>');
            }
            if (manager_pwd.length < 6) {
                $('#J_install_tip_manager_pwd').html('<span for="dbname" generated="true" class="tips_error" style="">Mật khẩu quản trị viên phải có nhiều hơn 5 chữ số</span>');
                return false;
            } else {
                $('#J_install_tip_manager_pwd').html('<span generated="true" class="tips_success" style="">Mật khẩu có sẵn</span>');
            }
            if (manager_ckpwd != manager_pwd) {
                $('#J_install_tip_manager_ckpwd').html('<span for="dbname" generated="true" class="tips_error" style="">Hai mật khẩu không nhất quán</span>');
                return false;
            } else {
                $('#J_install_tip_manager_ckpwd').html('<span generated="true" class="tips_success" style="">Mật khẩu đúng</span>');
            }
            return true;
        }
        new Vue({
            el: '#step3',
            data() {
                return {value: false, radio: 0}
            },
            created() {

            },
            methods: {
                mysqlDbPwd() {
                    let data = {
                        'dbHost': $('#dbhost').val(),
                        'dbUser': $('#dbuser').val(),
                        'dbPwd': $('#dbpw').val(),
                        'dbName': $('#dbname').val(),
                        'dbport': $('#dbport').val(),
                        'demo': $('#demo').val()
                    };
                    let url = "<?php echo $_SERVER['PHP_SELF']; ?>?step=3&mysqldbpwd=1";
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: data,
                            dataType: 'JSON',
                            success: (msg) => {
                                resolve(msg);
                            },
                            error: (err) => {
                                reject(err)
                            }
                        });
                    })

                },

                redisDbPwd() {
                    let data = {
                        rbhost: $('#rbhost').val(),
                        rbport: $("#rbport").val(),
                        rbselect: $("#rbselect").val(),
                        rbpw: $('#rbpw').val(),
                    };
                    let url = "<?php echo $_SERVER['PHP_SELF']; ?>?step=3&redisdbpwd=1";
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: data,
                            dataType: 'JSON',
                            success: function (msg) {
                                resolve(msg)
                            },
                            error: function () {
                                reject()
                            }
                        });
                    })

                },


                jumpButton(){
                    this.$refs.mianscroll.scrollTop = this.$refs.mianscroll.clientHeight
                },
                submitForm() {
                    this.mysqlDbPwd().then(res => {
                        if (res == 2002) {
                            this.value = true
                            $('#J_install_tip_dbhost').html('<span for="dbname" generated="true" class="tips_error" >Địa chỉ hoặc cổng sai</span>');
                            $('#J_install_tip_dbport').html('<span for="dbname" generated="true" class="tips_error" >Địa chỉ hoặc cổng sai</span>');
                            return false;
                        } else if (res == -1) {
                            $('#J_install_tip_dbhost').html('');
                            $('#J_install_tip_dbport').html('');
                            $('#J_install_tip_dbname').html('<span for="dbname" generated="true" class="tips_error" >Cấu hình liên kết cơ sở dữ liệu không thành công</span>');
                            return false;
                        } else if (res == -2) {
                            $('#J_install_tip_dbhost').html('');
                            $('#J_install_tip_dbport').html('');
                            $('#J_install_tip_dbname').html('<span for="dbname" generated="true" class="tips_error" >Vui lòng sửa đổi sql-mode hoặc sql_mode trong tệp cấu hình mysql thànhNO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION</span><a href="https://doc.crmeb.com/web/single/crmeb_v4/936" target="_blank">Xem tài liệu</a>');
                            return false;
                        } else if (res == 1045) {
                            $('#J_install_tip_dbhost').html('');
                            $('#J_install_tip_dbport').html('');
                            $('#J_install_tip_dbname').html('');
                            $('#J_install_tip_dbuser').html('<span for="dbname" generated="true" class="tips_error" >Tên người dùng hoặc mật khẩu sai</span>');
                            $('#J_install_tip_dbpw').html('<span for="dbname" generated="true" class="tips_error" >Tên người dùng hoặc mật khẩu sai</span>');
                            return false;
                        } else if (res == -4) {
                            $('#J_install_tip_dbhost').html('');
                            $('#J_install_tip_dbport').html('');
                            $('#J_install_tip_dbuser').html('');
                            $('#J_install_tip_dbpw').html('');
                            $('#J_install_tip_dbname').html('<span for="dbname" generated="true" class="tips_error" >Không có quyền tạo dữ liệu, vui lòng tạo cơ sở dữ liệu theo cách thủ công trước</span>');
                            return false;
                        } else if (res == -3) {
                            $('#J_install_tip_dbhost').html('');
                            $('#J_install_tip_dbport').html('');
                            $('#J_install_tip_dbuser').html('');
                            $('#J_install_tip_dbpw').html('');
                            $('#J_install_tip_dbname').html('<span for="dbname" generated="true" class="tips_error" >Cơ sở dữ liệu không trống, vui lòng thay đổi cơ sở dữ liệu</span>');
                            return false;
                        } else if (res == -5) {
                            $('#J_install_tip_dbhost').html('');
                            $('#J_install_tip_dbport').html('');
                            $('#J_install_tip_dbuser').html('');
                            $('#J_install_tip_dbpw').html('');
                            $('#J_install_tip_dbname').html('<span for="dbname" generated="true" class="tips_error" >MySqlCơ sở dữ liệu phải là phiên bản 5.6 trở lên</span>');
                            return false;
                        } else if (res == 1) {
                            $('#J_install_tip_dbhost').html('');
                            $('#J_install_tip_dbport').html('');
                            $('#J_install_tip_dbuser').html('');
                            $('#J_install_tip_dbpw').html('');
                            $('#J_install_tip_dbname').html('<span generated="true" class="tips_success" style="">Cấu hình cơ sở dữ liệu thành công</span>');
                        } else {
                            $('#J_install_tip_dbhost').html('');
                            $('#J_install_tip_dbport').html('');
                            $('#J_install_tip_dbuser').html('');
                            $('#J_install_tip_dbpw').html('');
                            $('#J_install_tip_dbname').html('<span for="dbname" generated="true" class="tips_error" >lỗi không xác định</span>');
                            return false;
                        }
                        let redisStatus = $("input[name='cache_type']:checked").val();
                        if (redisStatus == 1) {
                            this.redisDbPwd().then(msg => {
                                if (msg == -1) {
                                    $('#J_install_redis_host').html('<span for="dbname" generated="true" class="tips_error" style="">RedisTiện ích mở rộng chưa được cài đặt</span>');
                                    this.$nextTick(() => {this.jumpButton()});
                                    return false;
                                } else if (msg == -3) {
                                    $('#J_install_redis_host').html('');
                                    $('#J_install_redis_dbpw').html('<span for="dbname" generated="true" class="tips_error" style="">RedisCơ sở dữ liệu chưa được khởi động hoặc được cấu hình không chính xác</span>');
                                    this.$nextTick(() => {this.jumpButton()});

                                    return false;
                                } else if (msg == 1) {
                                    $('#J_install_redis_host').html('');
                                    $('#J_install_redis_dbpw').html('<span generated="true" class="tips_success" style="">RedisCấu hình thành công</span>');
                                } else {
                                    $('#J_install_redis_host').html('');
                                    $('#J_install_redis_dbpw').html('<span for="dbname" generated="true" class="tips_error" style="">RedisCấu hình không thành công</span>');
                                    this.$nextTick(() => {this.jumpButton()});
                                    return false;
                                }
                                if (checkForm()) {
                                    $("#J_install_form").submit(); // ajax Gửi biểu mẫu sau khi xác minh được thông qua
                                }
                            }).catch(err => {
                                $('#J_install_redis_host').html('');
                                $('#J_install_redis_dbpw').html('<span for="dbname" generated="true" class="tips_error" >lỗi không xác định</span>');
                                this.$nextTick(() => {this.jumpButton()});
                                return false;
                            })
                        } else {
                            if (checkForm()) {
                                $("#J_install_form").submit(); // ajax Gửi biểu mẫu sau khi xác minh được thông qua
                            }
                        }
                    }).catch(err => {
                        $('#J_install_tip_dbhost').html('');
                        $('#J_install_tip_dbport').html('');
                        $('#J_install_tip_dbuser').html('');
                        $('#J_install_tip_dbpw').html('');
                        $('#J_install_tip_dbname').html('<span for="dbname" generated="true" class="tips_error" >lỗi không xác định1</span>');
                        return false;
                    })
                }
            }
        })


    </script>
</div>
<?php require './templates/footer.php'; ?>
</body>
</html>
