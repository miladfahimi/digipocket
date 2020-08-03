<?php /* Template Name: AboutUs */ 
get_header();

while(have_posts()) {
    the_post(); 
    get_template_part('template-part/content');
?>

<!-- section -->
<div class="padding_0 info_coins">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="full">
                    <div class="coin_formation">
                        <ul>
                            <li>
                                <span class="curr_name">Bitcoin Price</span>
                                <span class="curr_price">2395.00 USD</span>
                            </li>
                            <li>
                                <span class="curr_name">Bitcoin Price</span>
                                <span class="curr_price">2321.68 EUR</span>
                            </li>
                            <li>
                                <span class="curr_name">24H Volume</span>
                                <span class="curr_price">1,957.25 BTC</span>
                            </li>
                            <li>
                                <span class="curr_name">Active Traders</span>
                                <span class="curr_price">1,169,857 EUR</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end section -->


<!-- section -->
<section class="layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="full our_work_type">
                    <div class="center"><img src="<?php echo get_theme_file_uri('images/icon_1_b.png')?>" alt="#" />
                    </div>
                    <div class="center">
                        <h4>Licensed in Luxembourg</h4>
                    </div>
                    <div class="center">
                        <p>Taking the time to manage your money really pay off.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="full our_work_type">
                    <div class="center"><img src="<?php echo get_theme_file_uri('images/icon_2_b.png')?>" alt="#" />
                    </div>
                    <div class="center">
                        <h4>No Hidden Fees</h4>
                    </div>
                    <div class="center">
                        <p>Taking the time to manage your money really pay off.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="full our_work_type">
                    <div class="center"><img src="<?php echo get_theme_file_uri('images/icon_3_b.png')?>" alt="#" />
                    </div>
                    <div class="center">
                        <h4>Instant Trading</h4>
                    </div>
                    <div class="center">
                        <p>Taking the time to manage your money really pay off.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="full our_work_type">
                    <div class="center"><img src="<?php echo get_theme_file_uri('images/icon_4_b.png')?>" alt="#" />
                    </div>
                    <div class="center">
                        <h4>Secure and Transparent</h4>
                    </div>
                    <div class="center">
                        <p>Taking the time to manage your money really pay off.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section -->

<!-- section -->
<section class="layout_padding about_bg_compu">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-sm-12 col-xs-12">
                <div class="full">
                    <h2 class="heading_style2">Looking for a First-Class Cryptocurrency Expert?</h2>
                    <p class="left_text">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown
                        printer took a galley of type and scrambled it to make a type
                        specimen book.</p>
                    <p class="left_text">It has survived not only five centuries, but also the leap into electronic
                        typesetting, remaining essentially unchanged.</p>
                    <p class="left_text">There are many variations of passages of Lorem Ipsum available, but the
                        majority have suffered alteration in some form, by injected humour, or randomised words which
                        don't look even slightly believable.</p>
                    <a class="btn main_btn" href="#">Read More</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section -->

<!-- section -->
<section class="layout_padding dark_bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="full">
                    <div class="heading_main">
                        <h2><span>Our Pricing Plan</span></h2>
                        <p>Exchange Transactions of your referred users Bitcoin</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="acf-map" style="background:gray;height:200px">
                    <div class="marker" data-lat="35.700736" data-lng="51.419435"></div>
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
                        <h2><span>Our Brands</span></h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod<br>tempor incididunt
                            ut labore et dolore magna aliqua.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="full">
                    <ul class="brand-list">
                        <li><img src="<?php echo get_theme_file_uri('images/brand1.png') ?>" alt="#"></li>
                        <li><img src="<?php echo get_theme_file_uri('images/brand2.png') ?>" alt="#"></li>
                        <li><img src="<?php echo get_theme_file_uri('images/brand3.png') ?>" alt="#"></li>
                        <li><img src="<?php echo get_theme_file_uri('images/brand4.png') ?>" alt="#"></li>
                        <li><img src="<?php echo get_theme_file_uri('images/brand5.png') ?>" alt="#"></li>
                        <li><img src="<?php echo get_theme_file_uri('images/brand6.png') ?>" alt="#"></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section -->
<?php
}
get_footer();

?>