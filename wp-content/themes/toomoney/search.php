<?php /* Template Name: Blog */ 
get_header();
?>
<!-- market value slider end -->

<section id="inner_page_infor" class="innerpage_banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="full">
                    <div class="inner_page_info">
                        <h3>Search<h3>
                                <ul>
                                    <li><a href="<?php echo get_site_url('/');?>"
                                            style="text-transform:capitalize;">home</a>
                                    </li>
                                    <li><i class="fa fa-angle-right"></i></li>
                                    <li><a href="#">All results for<span style="text-transform:capitalize;">
                                                " <?php echo esc_html(get_search_query(FALSE)) ?> "</span></a></li>
                                </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- section -->

<section class="padding_0 info_coins">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="full">
                    <h2 style="display:none;">heading</h2>
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
</section>
<!-- section -->
<section class="layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 pull-left">
                <div class="full">
                    <?php 
                            while ( have_posts() ) {
                                the_post(); 
                        ?>
                    <div class="blog_section">
                        <div class="blog_feature_img">
                            <img class="img-responsive"
                                src="<?php echo get_the_post_thumbnail_url('','my_dummy_size')?>" alt="#">
                        </div>
                        <div class="blog_feature_cantant light_silver_2">
                            <p class="blog_head"><?php the_title(); ?></p>
                            <div class="post_info">
                                <ul>
                                    <li><i class="fa fa-user" aria-hidden="true"></i> <?php the_author();?></li>
                                    <li><i class="fa fa-comment" aria-hidden="true"></i>
                                        <?php echo count(get_comments());?></li>
                                    <li><?php the_category();?></li>
                                    <li><i class="fa fa-calendar" aria-hidden="true"></i>
                                        <?php echo get_the_date();?>
                                    </li>
                                </ul>
                            </div>
                            <p><?php the_excerpt(); ?>
                            </p>
                            <div class="bottom_info">
                                <div class="pull-left"><a class="read_more" href="<?php the_permalink(); ?>">READ MORE
                                        <i class="fa fa-angle-right"></i></a></div>
                                <div class="pull-right">
                                    <div class="shr">Share: </div>
                                    <div class="social_icon">
                                        <ul>
                                            <li class="fb"><a href="#"><i class="fa fa-facebook"
                                                        aria-hidden="true"></i></a></li>
                                            <li class="twi"><a href="#"><i class="fa fa-twitter"
                                                        aria-hidden="true"></i></a></li>
                                            <li class="gp"><a href="#"><i class="fa fa-google-plus"
                                                        aria-hidden="true"></i></a></li>
                                            <li class="pint"><a href="#"><i class="fa fa-pinterest"
                                                        aria-hidden="true"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                    <div class="center">
                        <ul class="pagination style_1">
                            <?php 
                        echo paginate_links();
                        ?>
                            <li><a href="#"><i class="fa fa-angle-left" aria-hidden="true"></i></a></li>
                            <li class="active"><a href="#">1</a></li>
                            <li><a href="blog_page3.html">2</a></li>
                            <li><a href=""><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-left">
                <div class="side_bar">
                    <div class="side_bar_blog">
                        <h4>SEARCH</h4>
                        <div class="side_bar_search">
                            <div class="input-group stylish-input-group">
                                <input class="form-control" placeholder="" type="text">
                                <span class="input-group-addon">
                                    <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="side_bar_blog">
                        <h4>RECENT POST</h4>
                        <div class="recent_post">
                            <ul>
                                <?php 
                                $args = array(
                                    'post_type' => ''
                                );
                                $q = new WP_Query($args);
                                if ( $q->have_posts() ) {
                                    while ( $q->have_posts() ) {
                                        $q->the_post(); 
                                ?>
                                <li>
                                    <p class="post_head left_text"><a
                                            href="<?php the_permalink(); ?>"><?php the_title()?></a></p>
                                    <p class="post_date left_text"><i class="fa fa-calendar"
                                            aria-hidden="true"></i><?php echo get_the_date(),' ', get_the_time();?>
                                    </p>
                                </li>
                                <?php
                                    } // end while
                                    wp_reset_postdata();
                                    } // end if
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="side_bar_blog">
                        <h4>CATEGORIES</h4>
                        <div class="categary">
                            <ul>
                                <li><a href="#"><i class="fa fa-caret-right"></i> Events</a></li>
                                <li><a href="#"><i class="fa fa-caret-right"></i> News</a></li>
                                <li><a href="#"><i class="fa fa-caret-right"></i> Business</a></li>
                                <li><a href="#"><i class="fa fa-caret-right"></i> Trending Price</a></li>
                                <li><a href="#"><i class="fa fa-caret-right"></i> Post</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="side_bar_blog">
                        <h4>TAG</h4>
                        <div class="tags">
                            <ul>
                                <li><a href="#">Bootstrap</a></li>
                                <li><a href="#">HTML5</a></li>
                                <li><a href="#">Wordpress</a></li>
                                <li><a href="#">Bootstrap</a></li>
                                <li><a href="#">HTML5</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="side_bar_blog">
                        <h4>CATEGORIES</h4>
                        <div class="categary">
                            <ul>
                                <li><a href="#"><i class="fa fa-caret-right"></i> June (12)</a></li>
                                <li><a href="#"><i class="fa fa-caret-right"></i> January (12)</a></li>
                                <li><a href="#"><i class="fa fa-caret-right"></i> March (12)</a></li>
                                <li><a href="#"><i class="fa fa-caret-right"></i> November (12)</a></li>
                                <li><a href="#"><i class="fa fa-caret-right"></i> December (12)</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section -->

<!-- section -->

<section class="layout_padding light_bg2 gream_color" style="margin-top: 35px">
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
        <?php 
            get_template_part('template-part/logos','ads');
        ?>
    </div>
</section>

<!-- end section -->
<?php get_footer(); ?>