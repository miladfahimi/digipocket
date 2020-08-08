    <!-- footer -->
    <footer id="footer" class="footer_main">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="footer_logo">
                        <img src="<?php echo get_theme_file_uri('images/logos/logo_2.png') ?>" alt="#" />
                    </div>
                    <p class="footer_desc">Investments and employment Blockchain Technologies. Optimize your business
                        blockchain technology and Smart Contracts.</p>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="main-heading left_text">
                        <h2>Quick links</h2>
                    </div>
                    <?php
                    $footer_menu_setting = array(
                        'theme_location' => 'footerMenuLocationOne',
                        'menu_class' => 'footer-menu',
                        'before' => '<i class="fa fa-angle-right"></i>',
                        'link_before' => '<i class="fa fa-angle-right"></i>'
                    );
                        wp_nav_menu($footer_menu_setting);
                    ?>
                    <?php
                    $footer_menu_setting = array(
                        'theme_location' => 'footerMenuLocationTwo',
                        'menu_class' => 'footer-menu',
                        'before' => '<i class="fa fa-angle-right"></i>',
                        'link_before' => '<i class="fa fa-angle-right"></i>'
                    );
                        wp_nav_menu($footer_menu_setting);
                    ?>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="main-heading left_text">
                        <h2>Contact us</h2>
                    </div>
                    <p>123 Second Street Fifth Avenue,<br>Manhattan, New York<br><span style="font-size:18px;"><a
                                href="tel:+00412584896587">+00 41 258 489 6587</a></span><br><a
                            href="emailto:info@demo.com">info@demo.com</a></p>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="main-heading left_text">
                        <h2>Newsletter Signup</h2>
                    </div>
                    <p style="font-size: 17px;line-height: 24px;margin: 0;letter-spacing: 0px;">Get latest updates,
                        news, surveys & offers</p>
                    <div class="footer_mail-section" style="width: 90%;">
                        <form>
                            <fieldset>
                                <div class="field">
                                    <input placeholder="Email" type="text">
                                    <button class="button_custom"><i class="fa fa-envelope"
                                            aria-hidden="true"></i></button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <ul class="social_icons">
                        <li class="social-icon fb"><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                        </li>
                        <li class="social-icon tw"><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li class="social-icon gp"><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 pull-left">
                    <p class="text-center">Leo.Crypto Html5 Theme by WordPressShowcase. 2018 All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- search form -->
    <!-- Modal -->
    <div class="modal fade" id="search_form" role="dialog">
        <button type="button" id="search_form_close" class="cross_btn close"><span
                aria-hidden="true">&times;</span></button>
        <div class="search_bar_inner">
            <form class="search_bar_inner_form" method="get" action="<?php echo esc_url(site_url('/'));?>">
                <div class="field_form">
                    <input id="main_search_input" type="search" placeholder="Search" name="s" />
                    <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
            </form>
        </div>
    </div>
    <!-- Login Modal -->
    <div id="loginModal" class="login_modal">

        <!-- Modal content -->
        <div class="login_modal-content">
            <span class="login_close">&times;</span>
            <div class="login_dialog">
                <div id="adminAjaxUrl" style="visibility: hidden;"><?php echo admin_url('admin-ajax.php'); ?></div>
                <form class="" style="display: flex!important;
    flex-direction: column;
    justify-content: space-around;
    align-items: center;
    margin: 115px 78px;
" id="login_form" method="post">
                    <label for="username">Username</label>
                    <input style="width:300px" id="username" type="text" name="user_login">
                    <label for="password">Password</label>
                    <input style="width:300px" id="password" type="password" name="user_pass">
                    <label></label><input name="rememberme" type="checkbox" id="rememberme" value="forever"> Remember
                    Me</label>
                    <button id="myLoginsubmit" class="btn btn-success">LOGIN</button>
                </form>
                <div class="pass_and_register" id="pass_and_register">
                    <a class="go_to_register_link" href="" style="">Register</a>
                    <span style="color: black"> </span>
                    <a class="go_to_lostpassword_link" href="">Forgot Password</a>
                    <span style="color: black"></span>
                    <a class="back_login" href="" style="display: none;">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
    <!-- end search form -->
    <script type="module" src="/wp-content/themes/toomoney/js/scripts.js"></script>
    <?php wp_footer();?>
    </body>

    </html>