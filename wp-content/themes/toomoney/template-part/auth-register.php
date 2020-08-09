<div id="registerModal" class="register_modal">
    <div class="register_modal-content">
        <span class="register_close">&times;</span>
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
                                placeholder="نام کاربری" />
                            <label for="username" class="form__label">نام کاربری</label>
                        </div>
                        <div class="form__group">
                            <input type="text" class="form__input" name="email" id="email" placeholder="ایمیل" />
                            <label for="email" class="form__label">ایمیل</label>
                        </div>
                        <div class="form__group">
                            <input type="text" class="form__input" name="name" id="name"
                                placeholder="نام و نام خانوادگی" />
                            <label for="name" class="form__label">نام و نام خانوادگی</label>
                        </div>
                        <div class="form__group">
                            <input type="password" class="form__input" name="password" id="password"
                                placeholder="رمز عبور" />
                            <label for="password" class="form__label">رمز عبور</label>
                        </div>
                        <div class="form__group">
                            <input type="password" class="form__input" name="password" id="confirmPassword"
                                placeholder="تکرار رمز عبور" />
                            <label for="confirmPassword" class="form__label">تکرار رمز عبور</label>
                        </div>
                        <div class="form__group">
                            <button name="submit" id="myLoginsubmit" class="btn btn__green u-margin-t-small">ثبت
                                نام</button>
                        </div>
                        <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
                    </form>
                </div>
            </div>
            <div class="pass_and_register" id="pass_and_register">
                <div>
                    <a id="redirectToLogin" class="go_to_register_link" style="">ورود |</a>
                    <a class="go_to_lostpassword_link" href="<?php echo wp_lostpassword_url(); ?>">بازیابی رمز عبور</a>
                </div>
            </div>
        </div>
    </div>
</div>