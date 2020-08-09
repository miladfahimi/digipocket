<div id="loginModal" class="login_modal">
    <div class="login_modal-content">
        <span class="login_close">&times;</span>
        <div class="login_dialog">
            <div id="adminAjaxUrl" style="visibility: hidden;"><?php echo admin_url('admin-ajax.php'); ?></div>
            <div class="book">
                <div class="book__form">
                    <div class="login_logo">
                        <img src="<?php echo get_theme_file_uri('images/logos/logo-dark.png') ?>" alt="#" />
                    </div>
                    <form id="login_form" action="" class="form" method="post">
                        <div class="form__group">
                            <input type="text" class="form__input" id="username" placeholder="نام کاربری یا ایمیل"
                                name="user_login" required>
                            <label for="username" class="form__label">نام کاربری یا ایمیل</label>
                        </div>
                        <div class="form__group">
                            <input type="password" class="form__input" id="password" placeholder="رمز عبور"
                                name="user_pass" required>
                            <label for="password" class="form__label">رمز عبور</label>
                        </div>
                        <div class="form__group">
                            <label></label><input name="rememberme" type="checkbox" id="rememberme" value="forever">
                            به خاطرم بسپار</label>
                        </div>
                        <div class="form__group">
                            <button id="myLoginsubmit" type="submit"
                                class="btn btn__green u-margin-t-small">ورود</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="pass_and_register" id="pass_and_register">
                <a class="go_to_register_link" href="" style="">ثبت نام |</a>
                <span style="color: black"> </span>
                <a class="go_to_lostpassword_link" href="">بازیابی رمز عبور</a>
                <span style="color: black"></span>
                <a class="back_login" href="" style="display: none;">Back to Login</a>
            </div>
        </div>
    </div>
</div>