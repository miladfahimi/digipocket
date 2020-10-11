    <!-- footer -->
    <footer id="footer" class="footer_main">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="footer_logo">
                        <img src="<?php echo get_theme_file_uri('images/logos/logo_2.png') ?>" alt="#" />
                    </div>
                    <p class="footer_desc">تومانی پلتفرمی مبتنی بر بازار سرمایه و ارز میباشد و جهت نقل و انتقال سریع ارز کشورهای حوزه اسکاندیناوری فعالیت میکند، جهت رشد آگاهی عمومی در خصوص موارد مالی و بازارهای سرمایه مطالب مفید را دست اول و حرفه ای منتشر میکند.</p>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="main-heading">
                        <h2>دسترسی سریع</h2>
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
               
                <div class="col-md-4 col-sm-6 col-xs-12">
              <!--      <div class="main-heading left_text">
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
                    </div>-->
<div class="main-heading">
                        <h2>همراه ما باشید</h2>
                    </div>                    
                    <ul class="social_icons">
                        <li class="social-icon fb"><a href="#" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                        </li>
                        <li class="social-icon gp"><a href="#" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                        <li class="social-icon tw"><a href="https://t.me/toomoney_channel" target="_blank"><i class="fa fa-telegram" aria-hidden="true"></i></a>
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
                    <p class="text-center">&copy; 2020 All Rights Reserved.</p>
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
    <!-- end search form -->
    <!-- login form -->
    <?php get_template_part( 'template-part/auth', 'login' ); ?>
    <!-- end login form -->
    <!-- register form -->
    <?php get_template_part( 'template-part/auth', 'register' ); ?>
    <!-- end register form -->
    <script type="module" src="/wp-content/themes/toomoney/js/scripts.js"></script>
    <?php wp_footer();?>
    </body>

    </html>