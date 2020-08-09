<div id="loginModal" class="login_modal">
    <div class="login_modal-content">
        <span class="login_close">&times;</span>
        <div class="login_dialog">
            <div class="book">
                <div class="book__form">
                    <div class="login_logo">
                        <img src="<?php echo get_theme_file_uri('images/logos/logo-dark.png') ?>" alt="#" />
                    </div>
                    <form name="login_form" id="login_form" class="login_form" action="" method="post">
                        <p class="status"></p>
                        <div class="form__group">
                            <input type="text" class="form__input" name="username" id="username"
                                placeholder="نام کاربری یا ایمیل" />
                            <label for="username" class="form__label">نام کاربری یا ایمیل</label>
                        </div>
                        <div class="form__group">
                            <input type="password" class="form__input" name="password" id="password"
                                placeholder="رمز عبور" />
                            <label for="password" class="form__label">رمز عبور</label>
                        </div>
                        <div class="form__group">
                            <label></label><input name="rememberme" type="checkbox" id="rememberme" value="forever">
                            به خاطرم بسپار</label>
                        </div>
                        <div class="form__group">
                            <button name="submit" id="myLoginsubmit"
                                class="btn btn__green u-margin-t-small">ورود</button>
                        </div>
                        <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
                    </form>
                </div>
            </div>
            <div class="pass_and_register" id="pass_and_register">
                <div>
                    <a class="go_to_register_link" href="<?php echo wp_registration_url(); ?>" style="">ثبت نام |</a>
                    <a class="go_to_lostpassword_link" href="<?php echo wp_lostpassword_url(); ?>">بازیابی رمز عبور</a>
                </div>
            </div>
        </div>
    </div>
</div>