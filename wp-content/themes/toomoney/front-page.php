<?php /* Template Name: Home */
 get_header(); ?>
<!-- full slider parallax section -->
<section id="full_slider" class="full_slider_inner padding_0">
    <div class="main_slider">
        <div id="bootstrap-touch-slider" class="carousel bs-slider slide  control-round indicators-line" data-ride="carousel" data-pause="hover" data-interval="5000">
            <!-- Wrapper For Slides -->
            <div class="carousel-inner" role="listbox">
                <!-- first Slide -->
                <?php 
                $active = 'active';
                        $args = array(
                            'post_type' => 'slide',
                            'posts_per_page' => -1
                        );
                        $q = new WP_Query($args);
                        if ( $q->have_posts() ) {
                            while ( $q->have_posts() ) {
                                $q->the_post(); 
                        ?>
                <div class="item <?php echo $active; $active = '';?>">
                    <!-- Slide Background -->
                    <div class="bs-slider-overlay">
                    </div>
                    <img src="<?php echo get_field('back_ground')?>" alt="Bootstrap Touch Slider" class="slide-image" />
                    <div class="container">
                        <div class="row">
                            <!-- Slide Text Layer -->
                            <div class="slide-text slide_style_left white_fonts">
                                <h2 data-animation="animated"><span style="color: #e9d16f;"><?php echo get_field('header_1')?></span><br>
                                    <?php echo get_field('header_2')?><br>
                                    <?php echo get_field('header_3')?>
                                </h2>
                                <?php if (get_field('button_title_1')) {?>
                                <a href="<?php echo get_field('button_1')?>" class="btn btn-default active">
                                    <?php echo get_field('button_title_1')?>
                                </a>

                                <?php } if (get_field('button_title_2')) {?>
                                <a href="<?php echo get_field('button_2')?>" class="btn btn-default">
                                    <?php echo get_field('button_title_2')?>
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end of Slide -->
                <?php } }?>
            </div>
            <!-- End of Wrapper For Slides -->
            <!-- Left Control -->
            <a class="left carousel-control" href="#bootstrap-touch-slider" role="button" data-slide="prev">
                <span class="fa fa-angle-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <!-- Right Control -->
            <a class="right carousel-control" href="#bootstrap-touch-slider" role="button" data-slide="next">
                <span class="fa fa-angle-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <!-- End  bootstrap-touch-slider Slider -->
    </div>
</section>
<!-- end full slider parallax section -->
<!-- section -->
<?php get_template_part( 'template-part/content', 'index' ); ?>
<!-- end section -->
<?php 
?>
<!-- section boxes -->
<section class="layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-sm-8 col-xs-12">
                <div class="boxes boxes-v">
                    <?php 
                        $args = array(
                            'posts_per_page' => 1,
                            'orderby' => 'rand'
                        );
                        $req = new WP_Query($args);
                        if ( $req->have_posts() ) {
                            while ( $req->have_posts() ) {
                                // $do_not_duplicate = [];
                                // array_push($do_not_duplicate,$post->ID); 
                                $req->the_post(); 
                        ?>
                    <div class="box_img_cntr">
                        <img style="margin:0 auto" src="<?php echo get_the_post_thumbnail_url('','slider')?>" alt="">
                    </div>
                    <div class="box_text_cntr">
                        <div class="tag_container">
                            <?php
foreach((get_the_category()) as $category) { 
    ?>

                                <a class="link_button tag-red" href="#"><?php  echo $category->cat_name . ' ';  ?></a>
                                <?php
                        } 
                        ?>
                                    <a class="tag_button tag-main-blue" href="<?php  the_permalink();  ?>">اینجا</a>
                        </div>

                        <h4>
                            <?php echo wp_trim_words( get_the_title(), 8 ); ?>
                        </h4>
                        <p>
                            <?php echo wp_trim_words( get_the_content(), 20 ); ?>
                        </p>
                        <div class="box_info_cntr">
                            <a class="box_avatar" href="#">
                                <img src="<?php echo get_theme_file_uri('images/profile-test.png') ?>" alt="">
                            </a>
                            <h6>
                                <?php the_author(); ?>
                            </h6>
                        </div>
                    </div>
                    <?php }}  ?>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <?php 
                    wp_reset_query(); 
                        $args = array(
                            'posts_per_page' => 1,
                            'orderby' => 'rand'
                        );
                        $req = new WP_Query($args);
                        if ( $req->have_posts() ) {
                            while ( $req->have_posts()) {
                                $req->the_post(); 
                                // if (in_array($post->ID, $do_not_duplicate)) {
                                //      continue;
                                // }else{
                                //     array_push($do_not_duplicate,$post->ID);
                                // }
                        ?>
                <div class="boxes" style="background-image:url(<?php echo get_the_post_thumbnail_url('','slider')?>);background-repeat:
                    no-repeat;
                    background-size: auto; background-position:right">
                    <div class="box01_text_cntr light-font">
                        <div class="tag_container">
                            <?php
                            foreach((get_the_category()) as $category) { 
                            ?>
                                <a class="link_button tag-main-yellow" href="#"><?php  echo $category->cat_name . ' ';  ?></a>
                                <?php } ?>
                                <a class="tag_button tag-green" href="<?php  the_permalink();  ?>">اینجا</a>
                        </div>
                        <h3>
                            <?php echo wp_trim_words( get_the_title(), 8 ); ?>
                        </h3>
                        <p class="light-font">
                            <?php echo wp_trim_words( get_the_content(), 12 ); ?>
                        </p>
                        <div class="box_info_cntr">
                            <a class="box_avatar" href="#"><img
                                    src="<?php echo get_theme_file_uri('images/profile-test.png') ?>" alt=""></a>
                            <h6>
                                <?php the_author(); ?>
                            </h6>
                        </div>
                    </div>
                    <?php }} ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <?php 
                    wp_reset_query(); 
                        $args = array(
                            'posts_per_page' => 1,
                            'orderby' => 'rand'
                        );
                        $req = new WP_Query($args);
                        if ( $req->have_posts() ) {
                            while ( $req->have_posts() ) {
                                $req->the_post(); 
                        ?>
                <div class="boxes">
                    <div class="box01_text_cntr">
                        <div class="tag_container">
                            <a class="link_button tag-blue" href="#"><?php  echo $category->cat_name . ' ';  ?></a>
                            <a class="tag_button tag-main-blue" href="<?php  the_permalink();  ?>">اینجا</a>
                        </div>
                        <h3>
                            <?php echo wp_trim_words( get_the_title(), 8 ); ?>
                        </h3>
                        <p>
                            <?php echo wp_trim_words( get_the_content(), 20 ); ?>
                        </p>
                        <div class="box_info_cntr">
                            <a class="box_avatar" href="#"><img
                                    src="<?php echo get_theme_file_uri('images/profile-test.png') ?>" alt=""></a>
                            <h6>
                                <?php the_author(); ?>
                            </h6>
                        </div>
                        <?php }} ?>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <div class="boxes boxes-v">
                    <?php 
                    wp_reset_query(); 
                        $args = array(
                            'posts_per_page' => 1,
                            'orderby' => 'rand'
                        );
                        $req = new WP_Query($args);
                        if ( $req->have_posts() ) {
                            while ( $req->have_posts() ) {
                                $req->the_post(); 
                        ?>
                    <div class="box_img_cntr">
                        <img style="margin:0 auto" src="<?php echo get_the_post_thumbnail_url('','slider')?>" alt="">
                    </div>
                    <div class="box_text_cntr">
                        <div class="tag_container">
                            <a class="link_button tag-green" href="#"><?php  echo $category->cat_name . ' ';  ?></a>
                            <a class="tag_button tag-main-blue" href="<?php  the_permalink();  ?>">اینجا</a>
                        </div>

                        <h4>
                            <?php echo wp_trim_words( get_the_title(), 8 ); ?>
                        </h4>
                        <p>
                            <?php echo wp_trim_words( get_the_content(), 20 ); ?>
                        </p>
                        <div class="box_info_cntr">
                            <a class="box_avatar" href="#">
                                <img src="<?php echo get_theme_file_uri('images/profile-test.png') ?>" alt="">
                            </a>
                            <h6>
                                <?php the_author(); ?> 24،563 نمایش</h6>
                        </div>
                        <?php }} ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="boxes" style="background: linear-gradient(360deg, rgba(27, 51, 77, .8) 60%, rgba(24,51,87,.5) 74%,rgba(24,51,87,.1) 100%),url(<?php echo get_theme_file_uri('images/bitcoin.jpg') ?>); background-size: cover; background-position:right">
                    <div class="box01_text_cntr light-font" style="padding:0">
                        <div class="box02_tag_cntr">
                            <a class="link_button tag-red" href="#">Bitcoin</a>
                            <div class="tag_button tag-main-blue"><i class="fa fa-chevron-left"></i></div>
                        </div>
                        <?php 
                        wp_reset_query(); 
                        $args = array(
                            'post_type' => 'rate',
                            'posts_per_page' => 1,
                        );
                        $req = new WP_Query($args);
                        if ( $req->have_posts() ) {
                            while ( $req->have_posts() ) {
                                $req->the_post(); 
                        ?>
                        <h1>
                            $
                            <?php echo round(get_field('btc_usd'),2); ?>
                        </h1>
                        <?php }} ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="boxes-vertical" style="background-color: #1b334d">
                    <?php 
                    wp_reset_query(); 
                        $args = array(
                            'posts_per_page' => 1,
                            'orderby' => 'rand'
                        );
                        $req = new WP_Query($args);
                        if ( $req->have_posts() ) {
                            while ( $req->have_posts() ) {
                                $req->the_post(); 
                        ?>
                    <div class="box02_img_cntr">
                        <div class="box02_tag_cntr">
                            <a class="link_button tag-red" href="<?php  the_permalink();  ?>"><?php  echo $category->cat_name . ' ';  ?></a>
                            <div class="tag_button tag-main-blue" href="<?php  the_permalink();  ?>"><i class="fa fa-chevron-left"></i></div>
                        </div>
                        <img src="<?php echo get_theme_file_uri('images/bg_earth_inner.png') ?>" alt="">
                    </div>
                    <div class="box02_text_cntr">

                        <h3 class="light-font">
                            <?php echo wp_trim_words( get_the_title(), 8 ); ?>
                        </h3>
                        <p class="light-font">
                            <?php echo wp_trim_words( get_the_content(), 12 ); ?>
                        </p>
                        <div class="box_info_cntr">
                            <a class="box_avatar" href="#"><img
                                    src="<?php echo get_theme_file_uri('images/ser_icon_1.png') ?>" alt=""></a>
                            <h6>میلاد فهیمی</h6>
                        </div>
                        <?php }} ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="boxes" style="background-color: #1b334d">
                    <?php 
                    wp_reset_query(); 
                        $args = array(
                            'posts_per_page' => 1,
                            'orderby' => 'rand'
                        );
                        $req = new WP_Query($args);
                        if ( $req->have_posts() ) {
                            while ( $req->have_posts() ) {
                                $req->the_post(); 
                        ?>
                    <div class="box01_text_cntr light-font">
                        <div class="tag_container">
                            <a class="link_button tag-orange" href="<?php  the_permalink();  ?>"><?php  echo $category->cat_name . ' ';  ?></a>
                            <a class="tag_button tag-main-blue" href="<?php  the_permalink();  ?>">اینجا</a>
                        </div>
                        <h3>
                            <?php echo wp_trim_words( get_the_title(), 8 ); ?>
                        </h3>
                        <p class="light-font">
                            <?php echo wp_trim_words( get_the_content(), 20 ); ?>
                        </p>
                        <div class="box_info_cntr">
                            <a class="box_avatar" href="#"><img
                                    src="<?php echo get_theme_file_uri('images/ser_icon_1.png') ?>" alt=""></a>
                            <h6>میلاد فهیمی</h6>
                        </div>
                    </div>
                    <?php }} ?>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- end section -->
<!-- section -->
<section class="layout_padding light_bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="full">
                    <div class="heading_main">
                        <h2><span>مبادله ای سریع، آسان و ایمن</span></h2>
                        <p>  شما در هر کجای اسکاندیناوی باشید میتوانید به سادگی با ما در تماس باشید و وجوه ریالی خود را با ما مبادله نمایید.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="full">
                    <div class="rate_box">
                        <div class="inner_rate_box">
                            <div id="chart1-container">
                                <img src="<?php echo get_theme_file_uri('images/100-danish-kroner.jpg')?>" class="attachment-medium size-medium wp-post-image" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="full">
                    <div class="rate_box">
                        <div class="inner_rate_box">
                            <div id="chart2-container">
                                <img src="<?php echo get_theme_file_uri('images/100-swedish-kronor.jpg')?>" class="attachment-medium size-medium wp-post-image" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="full">
                    <div class="rate_box">
                        <div class="inner_rate_box">
                            <div id="chart3-container">
                                <img src="<?php echo get_theme_file_uri('images/100-norwegian-kroner.jpg')?>" class="attachment-medium size-medium wp-post-image" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="full">
                    <div class="rate_box">
                        <div class="inner_rate_box">
                            <div id="chart4-container">
                                <img src="<?php echo get_theme_file_uri('images/100-american-dollars.jpg')?>" class="attachment-medium size-medium wp-post-image" />
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section -->
<!-- section -->
<section class="layout_padding dark_bg white_fonts">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="full">
                    <div class="heading_main">
                        <h2><span>مزیت‌های سرمایه‌گذاری در پلتفرم تومانی</span></h2>
                        <p>سرمایه‌گذاری در بازارهای مختلف – بورس، طلا – راه‌حل مهمی برای حفظ ارزش سرمایه در شرایط تورمی اقتصاد است. سامانه مدیریت سرمایه تومانی، تمام فرصت‌های سرمایه‌گذاری را در اختبار شما قرار میدهد تا شما متناسب با بودجه خودتان سرمایه‌گذاری کنید.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top:20px;">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="full">
                    <div class="cryto_feature">
                        <ul>
                            <li>
                                <div class="pull-left"><img src="<?php echo get_theme_file_uri('images/f2.png')?>" alt="#" /></div>
                                <div class="txt">
                                    <h3>نقد شوندگی بالا</h3>
                                </div>
                            </li>
                            <li>
                                <div class="pull-left"><img src="<?php echo get_theme_file_uri('images/f3.png')?>" alt="#" /></div>
                                <div class="txt">
                                    <h3>مدیریت ریسک</h3>
                                </div>
                            </li>
                            <li>
                                <div class="pull-left"><img src="<?php echo get_theme_file_uri('images/f4.png')?>" alt="#" /></div>
                                <div class="txt">
                                    <h3>سرمایه گذاری شفاف</h3>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="full digital_earth">
                    <img src="<?php echo get_theme_file_uri('images/bg3_new.png')?>" alt="#" />
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="full">
                    <div class="cryto_feature right_text">
                        <ul>
                            <li>
                                <div class="txt">
                                    <h3> انتخاب بهترین ها</h3>
                                </div>
                                <div class="pull-right"><img src="<?php echo get_theme_file_uri('images/f5.png')?>" alt="#" /></div>
                            </li>
                            <li>
                                <div class="txt">
                                    <h3>24/7 پشتبانی آنلاین</h3>
                                </div>
                                <div class="pull-right"><img src="<?php echo get_theme_file_uri('images/f6.png')?>" alt="#" /></div>
                            </li>
                            <li>
                                <div class="txt">
                                    <h3>مشاوره رایگان</h3>
                                </div>
                                <div class="pull-right"><img src="<?php echo get_theme_file_uri('images/f1.png')?>" alt="#" /></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section -->

<!-- section -->
<section class="layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="full">
                    <div class="heading_main">
                        <h2><span>جملات آموزنده از افراد سرشناس درباره سرمایه گذاری</span></h2>
                        <p>سرمایه‌گذاری گاهی اوقات می‌تواند بسیار پیچیده باشد ولی با خواندن جملات بزرگان سرمایه‌گذاری و افراد سرشناس، می‌توانیم دیدگاه آنها نسبت به سرمایه‌گذاری را در کمترین زمان متوجه شویم.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-1 col-sm-12 col-xs-12"></div>
            <div class="col-md-10 col-sm-12 col-xs-12">
                <div class="full testmonial_slider">
                    <div class="carousel slide" data-ride="carousel" id="quote-carousel">
                        <!-- Carousel Slides / Quotes -->
                        <div class="carousel-inner text-center">
                            <!-- Quote 1 -->
                            <div class="item active">
                                <blockquote>
                                    <div class="row">
                                        <div class="col-sm-10 col-sm-offset-1">
                                            <div class="center">
                                                <!--
                                                <div class="client_img"><img class="img-responsive" src="<?php echo get_theme_file_uri('images/150x150.png')?>" alt="#" /></div>
-->
                                            </div>
                                            <p><span class="left_testmonial_qout"><i
                                                        class="fa fa-quote-left"></i></span> هر روز برای اینکه سعی کنید داناتر از روز قبل بیدار شوید مقداری برای اینکار هزینه کنید.
                                                <br> <br> Spend each day trying to be a little wiser than you were when you woke up


                                                <span class="right_testmonial_qout"><i
                                                        class="fa fa-quote-right"></i></span></p>
                                            <div class="center">
                                                <p class="client_name">چارلی مانگر</p>
                                            </div>
                                            <div class="center">
                                                <p class="country_name">Charlie Munger</p>
                                            </div>
                                        </div>
                                    </div>
                                </blockquote>
                            </div>
                            <!-- Quote 2 -->
                            <div class="item">
                                <blockquote>
                                    <div class="row">
                                        <div class="col-sm-10 col-sm-offset-1">

                                            <p><span class="left_testmonial_qout"><i
                                                        class="fa fa-quote-left"></i></span> تنها چیزی که برای یک عمر سرمایه‌گذاری موفق نیاز دارید تعدادی برگ برنده است تا مثبت‌های آن سرمایه‌گذاری‌های موفق، منفی‌های سرمایه‌گذاری ناموفق را پوشش دهد.
                                                <br> <br> All you need for a lifetime of successful investing is a few big winners, and the pluses from those will overwhelm the minuses from the stocks that don’t work out


                                                <span class="right_testmonial_qout"><i
                                                        class="fa fa-quote-right"></i></span></p>
                                            <div class="center">
                                                <p class="client_name">پیتر لینچ</p>
                                            </div>
                                            <div class="center">
                                                <p class="country_name">Peter Lynch</p>
                                            </div>
                                        </div>
                                    </div>
                                </blockquote>
                            </div>
                            <!-- Quote 3 -->
                            <div class="item">
                                <blockquote>
                                    <div class="row">
                                        <div class="col-sm-10 col-sm-offset-1">

                                            <p><span class="left_testmonial_qout"><i
                                                        class="fa fa-quote-left"></i></span> اینکه درست می‌گفتید یا اشتباه می‌کردید مهم نیست، این مهم است که وقتی حق با شما بود چقدر پول درآوردید و وقتی اشتباه می‌کردید چقدر ضرر کردید.
                                                <br> <br> It’s not whether you’re right or wrong that’s important, but how much money you make when you’re right and how much you lose when you’re wrong

                                                <span class="right_testmonial_qout"><i
                                                        class="fa fa-quote-right"></i></span></p>
                                            <div class="center">
                                                <p class="client_name">جورج سورس</p>
                                            </div>
                                            <div class="center">
                                                <p class="country_name">George Soros</p>
                                            </div>
                                        </div>
                                    </div>
                                </blockquote>
                            </div>
                        </div>
                        <!-- Bottom Carousel Indicators -->
                        <!-- Carousel Buttons Next/Prev -->
                        <a data-slide="prev" href="#quote-carousel" class="left carousel-control"><i
                                class="fa fa-chevron-left"></i></a>
                        <a data-slide="next" href="#quote-carousel" class="right carousel-control"><i
                                class="fa fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-12 col-xs-12"></div>
        </div>
    </div>
</section>
<!-- end section -->
<!-- section
<section class="layout_padding dark_bg time_section" style="background-image: url('<?php echo get_theme_file_uri('images/degital_img.png')?>');background-size: cover;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="full">
                    <div class="heading_main">
                        <h2><span>Time Until Ico Close</span></h2>
                        <p>Minimum purchase is 50 Coins tokens. Get a bonus from 5% to 25%<br>on every token purchase
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="countdown"></div>
        </div>
        <div class="row">
            <div class="token_infor_section">
                <p>TOTAL TOKENS BOUGHT</p>
                <h3>71, 145, 100</h3>
                <div class="center">
                    <a class="btn" href="#">Buy Coin</a>
                </div>
            </div>
        </div>
    </div>
</section> -->
<!-- end section -->
<!-- section 
<section class="layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="full">
                    <div class="heading_main">
                        <h2><span>Our Brands</span></h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod<br>tempor incididunt ut labore et dolore magna aliqua.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="full">
                    <ul class="brand-list">
                        <li><img src="<?php echo get_theme_file_uri('images/brand1.png')?>" alt="#"></li>
                        <li><img src="<?php echo get_theme_file_uri('images/brand2.png')?>" alt="#"></li>
                        <li><img src="<?php echo get_theme_file_uri('images/brand3.png')?>" alt="#"></li>
                        <li><img src="<?php echo get_theme_file_uri('images/brand4.png')?>" alt="#"></li>
                        <li><img src="<?php echo get_theme_file_uri('images/brand5.png')?>" alt="#"></li>
                        <li><img src="<?php echo get_theme_file_uri('images/brand6.png')?>" alt="#"></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>-->
<!-- end section -->
<?php get_footer(); ?>