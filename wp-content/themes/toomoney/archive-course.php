<?php /* Template Name: Courses */ 
get_header();
?>
<!-- market value slider end -->

<section id="inner_page_infor" class="innerpage_banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="full">
                    <div class="inner_page_info">
                        <h3>آموزش</h3>
                                <ul>
                                    <li><a href="<?php echo site_url() ?>">صفحه اصلی</a></li>
                                    <li><i class="fa fa-angle-left"></i></li>
                                    <li><a href="#">دوره های آموزشی ساده و پیشرفته بازار سهام</a></li>
                                </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- section -->
<?php wp_reset_query(); get_template_part( 'template-part/content', 'index' ); ?>
<!-- section -->
<section class="layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 pull-left">
                <div class="full">
                    <?php while(have_posts()) {
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
                                <div class="pull-left"><a class="read_more" href="<?php the_permalink(); ?>">شروع دوره
                                        <i class="fa fa-angle-left"></i></a></div>
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
                                            <li style="font-size:12px;padding-top:5px" class="shr">اشتراک گذاری: </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

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
            <?php  get_template_part( 'template-part/content', 'sidebar' ); ?>
        </div>
    </div>
</section>
<!-- end section -->



<!-- end section -->
<?php get_footer(); ?>