<?php /* Template Name: Contact */ 
get_header();
?>

<div class="templates-cntr">
    <div class="cont-post"
        style="background-image:linear-gradient(to right top,rgba(27, 51, 77, 0.6),rgba(27, 51, 77, 0.8)),url(<?php echo get_theme_file_uri('images/slider_img1.png') ?>)">
        <?php
$imagePage = new WP_Query(array(
    'posts_per_page' => 1,
    'post_type' => 'part',
    'orderby' => 'rand',
    'order'          => 'ASC'));
 while($imagePage->have_posts()) {
    $imagePage->the_post(); 
?>
        <ul class="rows">
            <li class="toomoney-logo"> <img src="<?php echo get_theme_file_uri('images/logos/logo_2.png') ?>" alt="">
            </li>
            <li class="columns">
                <div class="cells-date">
                    <?php echo get_the_date('d MY  G:i');?></div>
            </li>
            <li class="columns">
                <div class="cells-full-title">
                    <p> <?php the_title(); ?> </p>
                </div>
            </li>
            <!-- <li class="columns">
                <div class="cells-content-img">
                    <img src="<?php echo get_the_post_thumbnail_url('','slider')?>" alt="">
                </div>

            </li> -->
            <li class=" columns">
                <div class="cells-content"
                    style="background-image:linear-gradient(to right top,rgba(233, 209, 111, 0.8),rgba(255, 209, 111, 1)),url(<?php echo get_the_post_thumbnail_url('','insta')?>)">
                    <?php echo wp_trim_words( get_the_excerpt(), 40 ); ?> </div>
            </li>

            <li class="columns">
                <div class="cells-full-sub">
                    www.toomoney.se </div>
            </li>
        </ul>
        <?php
}
?>
    </div>
    <div class="cont"
        style="background-image:linear-gradient(to right top,rgba(27, 51, 77, 0.6),rgba(27, 51, 77, 0.8)),url(<?php echo get_theme_file_uri('images/slider_img1.png') ?>)">
        <ul class="rows">
            <li class="toomoney-logo"> <img src="<?php echo get_theme_file_uri('images/logos/logo_2.png') ?>" alt="">
            </li>
            <li class="columns">
                <div class="cells-date">
                    تاریخ: <?php echo date('F j, Y');?> ساعت: <?php echo date('G:i');?></div>
            </li>
            <li class="columns">
                <div class="cells-full">
                    <p> نرخ لحظه ای ارز و طلا بیتکوین</p>
                </div>
            </li>
            <li class="columns">
                <div class="cells"> <img src="<?php echo get_theme_file_uri('images/sweden.jpg') ?>"
                        alt=""><span>2630</span>
                    <p>
                        تومان</p>

                </div>
                <div class="cells">
                    <img src="<?php echo get_theme_file_uri('images/BTC.png') ?>" alt=""><span
                        style="font-size:17px">11397</span>
                    <p>
                        دلار</p>
                </div>
            </li>
            <li class="columns">
                <div class="cells"> <img src="<?php echo get_theme_file_uri('images/norwegin.jpg') ?>"
                        alt=""><span>2450</span>
                    <p>
                        تومان</p>
                </div>
                <div class="cells"> <img src="<?php echo get_theme_file_uri('images/Etherium.png') ?>" alt=""><span
                        style="font-size:17px">11397</span>
                    <p>
                        دلار</p>
                </div>
            </li>
            <li class="columns">
                <div class="cells"> <img src="<?php echo get_theme_file_uri('images/denmark.jpg') ?>"
                        alt=""><span>3620</span>
                    <p>
                        تومان</p>
                </div>
                <div class="cells"> <img src="<?php echo get_theme_file_uri('images/ripple.png') ?>" alt=""><span
                        style="font-size:17px">11397</span>
                    <p>
                        دلار</p>
                </div>
            </li>
            <li class="columns">
                <div class="cells-full-sub">
                    قیمت ها با تغییرات لحظه ای بازار ارز تغییر می کنند. </div>
            </li>
        </ul>
    </div>
</div>
<?php
get_footer();
?>