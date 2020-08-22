<div id="registerModal" class="register_modal">
    <div class="register_modal-content">
        <span class="register_close">&times;</span>
        <div class="login_dialog">
            <div class="book">
                <div class="book__form">
                    <div class="login_logo">
                        <img src="<?php echo get_theme_file_uri('images/logos/logo-dark.png') ?>" alt="#" />
                    </div>
                    <form name="register_form" id="register_form" class="login_form" action="" method="post">
                        <p class="registration_status"></p>
                        <div class="form__group">
                            <input type="text" class="form__input" name="new-username" id="new-username"
                                placeholder="نام کاربری" required />
                            <label for="new-username" class="form__label">نام کاربری</label>
                        </div>
                        <div class="form__group">
                            <input type="email" class="form__input" name="new-useremail" id="new-useremail"
                                placeholder="ایمیل" size="30" required />
                            <label for="new-useremail" class="form__label">ایمیل</label>
                        </div>
                        <div class="form__group">
                            <input type="text" class="form__input" name="new-first-name" id="new-first-name"
                                placeholder="نام و نام خانوادگی" required />
                            <label for="new-first-name" class="form__label">نام و نام خانوادگی</label>
                        </div>
                        <div class="form__group">
                            <input type="password" class="form__input" name="new-userpassword" id="new-userpassword"
                                placeholder="رمز عبور" required />
                            <label for="new-userpassword" class="form__label">رمز عبور</label>
                        </div>
                        <div class="form__group">
                            <button type="submit" id="myRegistersubmit" class="btn btn__green u-margin-t-small">ثبت
                                نام</button>
                        </div>
                        <?php wp_nonce_field( 'ajax-register-nonce', 'security1' ); ?>
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