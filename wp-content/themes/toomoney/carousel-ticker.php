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
            </ul>
        </div>
    </div>
</div>