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
                                placeholder="Username" />
                        </div>
                        <div class="form__group">
                            <input type="password" class="form__input" name="password" id="password"
                                placeholder="Password" />
                        </div>
                        <div class="form__group">
                            <button name="submit" id="myLoginsubmit"
                                class="btn btn__green u-margin-t-small">ورود</button>
                        </div>
                        <a class="lost" href="<?php echo wp_lostpassword_url(); ?>">Lost your password?</a>
                        <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
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