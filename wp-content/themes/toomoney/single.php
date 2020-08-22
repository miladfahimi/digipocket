<?php get_header(); ?>
<!-- market value slider end -->
<?php
    get_template_part('template-part/content','miniheader');
?>

<!-- section -->
<?php get_template_part( 'template-part/content', 'index' ); ?>

<?php
 while(have_posts()) {
    the_post(); 
?>
<!-- end section -->
<!-- section -->
<section class="layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 pull-right">
                <div class="full">
                    <div class="blog_section margin_bottom_0">
                        <div class="blog_feature_img">
                            <img class="img-responsive"
                                src="<?php echo get_the_post_thumbnail_url('','my_dummy_size');?>" alt="#">
                        </div>
                        <div class="blog_feature_cantant">
                            <p class="blog_head"><?php the_title(); ?></p>
                            <div class="post_info">
                                <?php
                                $likeCount = new WP_Query(
                                    array(
                                        'post_type' => 'like',
                                        'meta_query'=> array(
                                            array(
                                                'key'       =>  'like_id',
                                                'compare'   =>  '=',
                                                'value'     =>  get_the_id()
                                            )
                                        )
                                        
                                    )
                                            );
                                $isLiked = new WP_Query(
                                    array(
                                        'author'    => get_current_user_id(),
                                        'post_type' => 'like',
                                        'meta_query'=> array(
                                            array(
                                                'key'       =>  'like_id',
                                                'compare'   =>  '=',
                                                'value'     =>  get_the_id()
                                            )
                                        )
                                    )
                                            );
                                    
                                ?>
                                <p class="the_like_id the_untouchbles"><?php while($isLiked->have_posts()){
                                    $isLiked->the_post();
                                    echo get_the_ID();
                                    wp_reset_query();
                                }  ?></p>
                                <p class="new_like_item the_untouchbles"><?php the_ID();?></p>

                                <ul>
                                    <li><i class="fa fa-user" aria-hidden="true"></i> <?php the_author();?></li>
                                    <li><i class="fa fa-comment" aria-hidden="true"></i>
                                        <?php echo count(get_comments());
                                        wp_reset_query();
                                        ?>
                                    </li>
                                    <li><?php the_category();?></li>
                                    <li><i class="fa fa-calendar" aria-hidden="true"></i>
                                        <?php echo get_the_date();?>
                                    </li>
                                    <li class="like-button-cont"><span>
                                            <a href="#"
                                                class="like-button  <?php if($isLiked->found_posts){echo 'active';}?>">
                                                <svg width="20" height="20" viewBox="0 0 500 500"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M370.994,49.998c-61.509,0-112.296,45.894-119.994,105.306    c-7.698-59.412-58.485-105.306-119.994-105.306C64.176,49.998,10,104.174,10,171.004s80.283,135.528,116.45,166.574    C160.239,366.582,251,452.002,251,452.002s90.761-85.42,124.55-114.424C411.717,306.532,492,237.834,492,171.004    S437.824,49.998,370.994,49.998z" />
                                                </svg>
                                            </a>
                                        </span>
                                        <span class="likes_number"
                                            style='float:left;color:#aaa'><?php echo $likeCount->found_posts; ?></span>
                                    </li>
                                </ul>
                            </div>
                            <p><?php the_content();?>
                            </p>
                            <div class=" full testimonial_simple_say margin_bottom_30_all" style="margin-top:0;">
                                <div class="qoute_testimonial"><i class="fa fa-quote-left" aria-hidden="true"></i></div>
                                <p class="margin_bottom_0"><i>Adipisicing elit lorem ipsum dolor sit
                                        amet, consectetur, sed
                                        do eiusmod tempor incididunt ut labore et dolore magna
                                        aliqua.</i></p>
                                <p class="large_2 theme_color">John Barber</p>
                            </div>
                            <?php 

                            if (is_single ()) comments_template ();
                                  }  ?>
                        </div>

                    </div>
                </div>
            </div>
            <?php  get_template_part( 'template-part/content', 'sidebar' ); ?>
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
            get_template_part('template-part/logos');
        ?>
    </div>
</section>

<!-- end section -->
<?php get_footer(); ?>