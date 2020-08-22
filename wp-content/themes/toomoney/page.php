<?php /* Template Name: Blog */ 
get_header();
    //USE TEMPLATE PART FOR REFACTOR HTML CODE LIKE HERE, THE CODE MOVED TO THE RELATED PATH AS SEND TO METHOD BY ARGUMENTS, Milad Fahimi
    get_template_part('template-part/content','post');
?>

<!-- section -->

<?php get_template_part( 'template-part/content', 'index' ); ?>

<!-- section -->
<section class="layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 pull-left">
                <div class="full">
                    <?php 
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        //$paged = (get_query_var('page')) ? get_query_var('page') : 1; IN INDEX PAGE
                        
                        $args = array(
                                    'post_type'         =>'',
                                    'paged'             => $paged,
                                );
                        $q = new Wp_Query($args);
                        if($q -> have_posts()){
                            while ( $q->have_posts() ) {
                                $q->the_post(); 
                                $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $q->ID ),'my_dummy_size' ); //     Just as an example
                        ?>
                    <div class="blog_section">
                        <?php if($thumbnail[0]){?>
                        <div class="blog_feature_img">
                            <img class="img-responsive" src="<?php echo $thumbnail[0];?>" alt="#">
                        </div>
                        <?php } ?>
                        <div class="blog_feature_cantant light_silver_2">
                            <p class="blog_head"><?php the_title(); ?></p>
                            <div class="post_info">
                                <ul>
                                    <li><i class="fa fa-user" aria-hidden="true"></i> <?php the_author();?></li>
                                    <li><i class="fa fa-comment" aria-hidden="true"></i> <?php echo comments_number();?>
                                    </li>
                                    <li><?php the_category();?></li>
                                    <li><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo get_the_date();?>
                                    </li>
                                </ul>
                            </div>
                            <p><?php the_excerpt(); ?>
                            </p>
                            <div class="bottom_info">
                                <div class="pull-left"><a class="read_more" href="<?php the_permalink(); ?>">ادامه
                                        مطلب ...
                                    </a></div>
                                <div class="pull-right">
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
                                            <li class="read_more">اشتراک گذاری: </li>
                                        </ul>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                        wp_reset_postdata();
                        }
                    ?>
                    <div class="center">
                        <ul class="pagination style_1">
                            <?php 
                        echo paginate_links(array(  
                            'total'     => $q->max_num_pages, 
                            'base'      => add_query_arg('paged','%#%'),
                            'format'    => '?paged=%#%',
                            'current'   => max(1, get_query_var('paged'))
                        ));
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
                        <h4>جستجو</h4>
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
                        <h4>آخرین مطالب</h4>
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
                        <h4>طبقه بندی ها</h4>
                        <div class="categary">
                            <ul>
                                <li><a href="#"> بلاگ </a><i class="fa fa-caret-left"></i></li>
                                <li><a href="#"> اخبار اقتصادی </a><i class="fa fa-caret-left"></i></li>
                                <li><a href="#"> آموزش </a><i class="fa fa-caret-left"></i></li>
                                <li><a href="#"> بوزس بین الملل </a><i class="fa fa-caret-left"></i></li>
                                <li><a href="#"> فورکس </a><i class="fa fa-caret-left"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="side_bar_blog">
                        <h4> برچست ها</h4>
                        <div class="tags">
                            <ul>
                                <li><a href="#">بلاگ</a></li>
                                <li><a href="#">اخبار اقتصادی</a></li>
                                <li><a href="#">آموزش</a></li>
                                <li><a href="#">بوزس بین الملل</a></li>
                                <li><a href="#">فورکس</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="side_bar_blog">
                        <h4>آرشیو</h4>
                        <div class="categary">
                            <ul>
                                <li style="text-align:left"><a href="#"> فروردین (12) </a><i
                                        class="fa fa-caret-left"></i></li>
                                <li style="text-align:left"><a href="#"> اردیبهشت (12) </a><i
                                        class="fa fa-caret-left"></i></li>
                                <li style="text-align:left"><a href="#"> خرداد (12) </a><i class="fa fa-caret-left"></i>
                                </li>
                                <li style="text-align:left"><a href="#"> تیر (12) </a><i class="fa fa-caret-left"></i>
                                </li>
                                <li style="text-align:left"><a href="#"> مرداد (12) </a><i class="fa fa-caret-left"></i>
                                </li>
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
<?php get_footer(); ?>