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
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 pull-right">
                <div class="full">
                    <div class="blog_section margin_bottom_0">
                        <div class="blog_feature_img">
                            <img class="img-responsive"
                                src="<?php echo get_the_post_thumbnail_url('','my_dummy_size')?>" alt="#">
                        </div>
                        <div class="blog_feature_cantant">
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
                            <p><?php the_content();?>
                            </p>
                            <ul>
                                <?php }
                                    $relatedCourse = get_field('related_course');
                                    if($relatedCourse){
                                    foreach($relatedCourse as $item){
                                ?>
                                <li>
                                    <a href="<?php echo get_the_permalink($item); ?>">
                                        بازگشت به دوره: <?php echo get_the_title($item); ?>
                                    </a>
                                </li>
                                <?php }
                            ?>
                            </ul>
                        </div>
                        <div class=" full testimonial_simple_say margin_bottom_30_all" style="margin-top:0;">
                            <div class="qoute_testimonial"><i class="fa fa-quote-left" aria-hidden="true"></i></div>
                            <p class="margin_bottom_0"><i>Adipisicing elit lorem ipsum dolor sit
                                    amet, consectetur, sed
                                    do eiusmod tempor incididunt ut labore et dolore magna
                                    aliqua.</i></p>
                            <p class="large_2 theme_color">John Barber</p>
                        </div>
                        <?php }                           
                        if (is_single ()) comments_template ();
                        ?>
                    </div>
                </div>
            </div>
            <?php  get_template_part( 'template-part/content', 'sidebar' ); ?>
        </div>
    </div>
</section>
<!-- end section -->

<!-- section -->

<?php
            get_template_part('template-part/logos');
        ?>

<!-- end section -->
<?php get_footer(); ?>