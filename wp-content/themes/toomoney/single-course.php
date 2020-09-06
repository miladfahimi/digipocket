<?php get_header(); ?>
<!-- market value slider end -->
<?php
    get_template_part('template-part/content','miniheader');
?>

<!-- section -->
<?php get_template_part( 'template-part/content', 'index' ); ?>

<?php while(have_posts()) {
    the_post(); 
?>
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
                            <div class="part-table">
                                <?php
                            $homePagePart = new WP_Query(array(
                                'posts_per_page' => -1,
                                'post_type' => 'part',
                                'meta_key'       => 'part_number',
                                'orderby'        => 'meta_value_num',
                                'order'          => 'ASC',
                                'meta_query' => array(
                                        array(                              //      just for example :
                                            'key' => 'related_course',   //      getting back if contains of related_resources
                                            'compare' => 'LIKE',            //
                                            'value' => '"'.get_the_ID().'"' //
                                        ))));                               //
                            if($homePagePart){
                            while($homePagePart->have_posts()){
                                $homePagePart->the_post();
                            ?>
                                <div class="part-row">
                                    <div class="part-number">
                                        <?php the_field("part_number"); ?>
                                    </div>
                                    <a class="part-title" href="<?php the_permalink();?>">
                                        <?php the_title(); ?>
                                    </a>
                                </div>
                                <?php }}?>
                            </div>
                        </div>
                        <div class="full testimonial_simple_say margin_bottom_30_all" style="margin-top:0;">
                            <div class="qoute_testimonial"><i class="fa fa-quote-left" aria-hidden="true"></i></div>
                            <p class="margin_bottom_0"><i>قیمت چیزی است که شما پرداخت می کنید و ارزش چیزی است که به دست
                                    می آورید.</i></p>
                            <p class="m-text m-t-l theme_color">وارن بافت</p>
                        </div>
                    </div>
                    <?php 
                            if (is_single ()) comments_template ();
                        }  ?>
                </div>
            </div>
            <?php  get_template_part( 'template-part/content', 'sidebar' ); ?>

        </div>
    </div>
</section>
<!-- end section -->
<!-- section -->
<?php get_template_part('template-part/logos'); ?>
<!-- end section -->
<?php get_footer(); ?>