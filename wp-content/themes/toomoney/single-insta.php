<?php get_header(); ?>

<?php
    get_template_part('template-part/content','miniheader');
?>

<!-- section -->
<?php get_template_part( 'template-part/content', 'index' ); ?>

<?php
    global $post;
    while(have_posts()) {
    the_post(); 
?>

<!-- section -->
<section class="layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12 pull-right">
                <div class="full">
                    <div class="blog_section margin_bottom_0">
                        <div class="blog_feature_img">
                            <img class="img-responsive" src="<?php echo get_the_post_thumbnail_url('','insta')?>"
                                alt="#">
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