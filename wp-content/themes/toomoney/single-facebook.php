<?php /* Template Name: Facebook */ 
 get_header(); ?>

<section id="inner_page_infor" class="innerpage_banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="full">
                    <div class="inner_page_info">
                        <h3 style="text-transform:capitalize;"> محتوای شبکه های اجتماعی<h3>
                                <ul>
                                    <li><a href="<?php echo get_post_type_archive_link(print_r(get_post_type()));?>"
                                            style="text-transform:capitalize;">اینستاگرام و فیسبوک</a></li>
                                    <li><i class="fa fa-angle-right"></i></li>
                                    <li><a href="#"></a></li>
                                </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- section -->
<?php get_template_part( 'template-part/content', 'index' ); ?>

<?php
    $instaPost = new WP_Query(
        array(
            'post_type' => 'facebook',
        ));
    while($instaPost->have_posts()) {
        $instaPost->the_post(); 
?>

<!-- section -->
<section class="layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12 pull-right">
                <div class="full">
                    <div class="blog_section margin_bottom_0">
                        <div class="blog_feature_img">
                            <img class="img-responsive" src="<?php echo get_the_post_thumbnail_url()?>" alt="#">
                        </div>
                        <div class="blog_feature_cantant">
                            <p class="blog_head"><?php the_title(); ?></p>
                            <p><?php the_content();?>
                            </p>
                        </div>
                        <?php }                           
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section -->
<?php get_footer(); ?>