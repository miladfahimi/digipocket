<?php /* Template Name: Ticker */ ?>
<!-- market value slider -->
<div id="main-content" class="" style="direction:ltr">
    <div id="demos">
        <div id="carouselTicker" class="carouselTicker">
            <ul class="carouselTicker__list">
                <?php 
                        $args = array(
                            'post_type'     => 'ads',
                            'posts_per_page'=> -1,
                            'post_status'   => 'publish'
                        );
                        $q = new WP_Query($args);
                        if ( $q->have_posts() ) {
                            while ( $q->have_posts() ) {
                                $q->the_post(); 
                        ?>
                <li class="carouselTicker__item">
                    <div class="coin_info">
                        <div class="inner">
                            <a class="adsLink">
                                <div class="coin_name adsLink">
                                    <span class="update_change_minus"><a style="color:#4ca1f1; float:left">
                                            <?php echo get_avatar(get_the_author_email(),12)?>
                                        </a></span>
                                    <?php the_author()?>
                                    <span class="update_change_minus"><a style="color:#4ca1f1; float:right"><i
                                                class="fa fa-telegram" aria-hidden="true"></i></a></span>
                                </div>
                            </a>
                            <div class="coin_price">
                                <span style="color:#e9d16f"><?php the_field('index')?>
                                </span><?php the_field('amount')?>
                                <span class="<?php  
                                    if(get_field('buy_sale')=='خرید') { echo 'coin_price_plus'; } 
                                    ?>">
                                    <?php the_field('buy_sale')?></span>
                            </div>
                            <div class="coin_time">
                                <a><?php echo get_the_date('M d');?></a>
                            </div>
                        </div>
                    </div>
                </li>
                <?php
                    } // end while
                    wp_reset_postdata();
                } // end if
                ?>
                <li class="carouselTicker__item">
                    <div class="coin_info">
                        <div class="inner">
                            <div class="coin_name">
                                Ethereum<span class="update_change_minus">-109.12</span>
                            </div>
                            <div class="coin_price">
                                $952.98<span class="scsl__change_minus">-11.45%</span>
                            </div>
                            <div class="coin_time">
                                $92,587,551,437.00
                            </div>
                        </div>
                    </div>
                </li>
                <li class="carouselTicker__item">
                    <div class="coin_info">
                        <div class="inner">
                            <div class="coin_name">
                                Exchange Union<span class="update_change_minus">-0.33</span>
                            </div>
                            <div class="coin_price">
                                $8.16<span class="scsl__change_minus">-4.02%</span>
                            </div>
                            <div class="coin_time">
                                $16,322,520.00
                            </div>
                        </div>
                    </div>
                </li>
                <li class="carouselTicker__item">
                    <div class="coin_info">
                        <div class="inner">
                            <div class="coin_name">
                                Ripple<span class="update_change_minus">-0.14</span>
                            </div>
                            <div class="coin_price">
                                $1.25<span class="scsl__change_minus">-11.05%</span>
                            </div>
                            <div class="coin_time">
                                $48,231,782,365.00
                            </div>
                        </div>
                    </div>
                </li>
                <li class="carouselTicker__item">
                    <div class="coin_info">
                        <div class="inner">
                            <div class="coin_name">
                                Veritaseum<span class="update_change_minus">-46.70</span>
                            </div>
                            <div class="coin_price">
                                $337.46<span class="scsl__change_minus">-13.84%</span>
                            </div>
                            <div class="coin_time">
                                $687,292,480.00
                            </div>
                        </div>
                    </div>
                </li>
                <li class="carouselTicker__item">
                    <div class="coin_info">
                        <div class="inner">
                            <div class="coin_name">
                                Digitalcoin<span class="update_change_minus">-0.01</span>
                            </div>
                            <div class="coin_price">
                                $0.07<span class="scsl__change_minus">-14.89%</span>
                            </div>
                            <div class="coin_time">
                                $1,986,979.00
                            </div>
                        </div>
                    </div>
                </li>
                <li class="carouselTicker__item">
                    <div class="coin_info">
                        <div class="inner">
                            <div class="coin_name">
                                Bitcoin<span class="update_change_minus">-1,521.25</span>
                            </div>
                            <div class="coin_price">
                                $11,459.75<span class="scsl__change_minus">-12.97%</span>
                            </div>
                            <div class="coin_time">
                                $175,016,158,112.00
                            </div>
                        </div>
                    </div>
                </li>
                <li class="carouselTicker__item">
                    <div class="coin_info">
                        <div class="inner">
                            <div class="coin_name">
                                Ethereum<span class="update_change_minus">-109.12</span>
                            </div>
                            <div class="coin_price">
                                $952.98<span class="scsl__change_minus">-11.45%</span>
                            </div>
                            <div class="coin_time">
                                $92,587,551,437.00
                            </div>
                        </div>
                    </div>
                </li>
                <li class="carouselTicker__item">
                    <div class="coin_info">
                        <div class="inner">
                            <div class="coin_name">
                                Exchange Union<span class="update_change_minus">-0.33</span>
                            </div>
                            <div class="coin_price">
                                $8.16<span class="scsl__change_minus">-4.02%</span>
                            </div>
                            <div class="coin_time">
                                $16,322,520.00
                            </div>
                        </div>
                    </div>
                </li>
                <li class="carouselTicker__item">
                    <div class="coin_info">
                        <div class="inner">
                            <div class="coin_name">
                                Ripple<span class="update_change_minus">-0.14</span>
                            </div>
                            <div class="coin_price">
                                $1.25<span class="scsl__change_minus">-11.05%</span>
                            </div>
                            <div class="coin_time">
                                $48,231,782,365.00
                            </div>
                        </div>
                    </div>
                </li>
                <li class="carouselTicker__item">
                    <div class="coin_info">
                        <div class="inner">
                            <div class="coin_name">
                                Veritaseum<span class="update_change_minus">-46.70</span>
                            </div>
                            <div class="coin_price">
                                $337.46<span class="scsl__change_minus">-13.84%</span>
                            </div>
                            <div class="coin_time">
                                $687,292,480.00
                            </div>
                        </div>
                    </div>
                </li>
                <li class="carouselTicker__item">
                    <div class="coin_info">
                        <div class="inner">
                            <div class="coin_name">
                                Digitalcoin<span class="update_change_minus">-0.01</span>
                            </div>
                            <div class="coin_price">
                                $0.07<span class="scsl__change_minus">-14.89%</span>
                            </div>
                            <div class="coin_time">
                                $1,986,979.00
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>