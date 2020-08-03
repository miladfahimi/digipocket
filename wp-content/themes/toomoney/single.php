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
                                    echo get_the_ID();}  ?></p>
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
                                    <li><span>
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
                            <?php 
                            $relatedResources = get_field('related_resources');
                            print_r($relatedResources);
                                  }  ?>
                        </div>
                        <div class=" full testimonial_simple_say margin_bottom_30_all" style="margin-top:0;">
                            <div class="qoute_testimonial"><i class="fa fa-quote-left" aria-hidden="true"></i></div>
                            <p class="margin_bottom_0"><i>Adipisicing elit lorem ipsum dolor sit
                                    amet, consectetur, sed
                                    do eiusmod tempor incididunt ut labore et dolore magna
                                    aliqua.</i></p>
                            <p class="large_2 theme_color">John Barber</p>
                        </div>

                        <div class="view_commant">
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                                    <div class="full">
                                        <div class="img-responsive img-circle" style="max-width:100px">
                                            <?php echo get_avatar(get_userdatabylogin('user1'),20)?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
                                    <div class="full command_cont" style="background: #e9d16f;">
                                        <p class="comm_head">Christian Perez <span>Sep 27,2017</span><a class="rply"
                                                href="#">Reply</a></p>
                                        <p>magine you are 10 years into the future but this time it’s different.
                                            Why?
                                            Because starting today you actually begin making changes in your life.
                                            Specific intentional changes are not easy. They are intentional
                                            because these changes are changes that you are choosing and they are the
                                            changes that will cause you to live the life you want to live and dream.
                                        </p>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                                            <div class="full">
                                                <div class="img-responsive img-circle">
                                                    <?php echo get_avatar(get_userdatabylogin('mldfhm'),20)?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
                                            <div class="full command_cont margin_bottom_0">
                                                <p class="comm_head">Christian Perez <span>Sep 27,2017</span><a
                                                        class="rply" href="#">Reply</a></p>
                                                <p>magine you are 10 years into the future but this time it’s
                                                    different.
                                                    Why? Because starting today you actually begin making changes in
                                                    your life. Specific intentional changes are not easy. They are
                                                    intentional
                                                    because these changes are changes.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                                    <div class="full">
                                        <img class="img-responsive img-circle" style="max-width:100px"
                                            src="<?php echo get_theme_file_uri('images/150x150.png')?>" alt="#">
                                    </div>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
                                    <div class="full command_cont" style="background: #e9d16f;">
                                        <p class="comm_head">Christian Perez <span>Sep 27,2017</span><a class="rply"
                                                href="#">Reply</a></p>
                                        <p>magine you are 10 years into the future but this time it’s different.
                                            Why?
                                            Because starting today you actually begin making changes in your life.
                                            Specific intentional changes are not easy. They are intentional
                                            because these changes are changes that you are choosing and they are the
                                            changes that will cause you to live the life you want to live and dream.
                                        </p>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                                            <div class="full">
                                                <img class="img-responsive img-circle" style="max-width:100px"
                                                    src="<?php echo get_theme_file_uri('images/150x150.png')?>" alt="#">
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
                                            <div class="full command_cont margin_bottom_0">
                                                <p class="comm_head">Christian Perez <span>Sep 27,2017</span><a
                                                        class="rply" href="#">Reply</a></p>
                                                <p>magine you are 10 years into the future but this time it’s
                                                    different.
                                                    Why? Because starting today you actually begin making changes in
                                                    your life. Specific intentional changes are not easy. They are
                                                    intentional
                                                    because these changes are changes.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                                            <div class="full">
                                                <img class="img-responsive img-circle" style="max-width:100px"
                                                    src="<?php echo get_theme_file_uri('images/150x150.png')?>" alt="#">
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
                                            <div class="full command_cont margin_bottom_0">
                                                <p class="comm_head">Christian Perez <span>Sep 27,2017</span><a
                                                        class="rply" href="#">Reply</a></p>
                                                <p>magine you are 10 years into the future but this time it’s
                                                    different.
                                                    Why? Because starting today you actually begin making changes in
                                                    your life. Specific intentional changes are not easy. They are
                                                    intentional
                                                    because these changes are changes.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="post_commt_form">
                            <h4>POST YOUR COMMENT</h4>
                            <div class="form_section">
                                <form class="form_contant" action="index.html">
                                    <fieldset>
                                        <div class="field col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <input class="field_custom" placeholder="Email" type="email">
                                        </div>
                                        <div class="field col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <input class="field_custom" placeholder="Phone" type="text">
                                        </div>
                                        <div class="field col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <input class="field_custom" placeholder="Password" type="password">
                                        </div>
                                        <div class="field col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <textarea class="field_custom" placeholder="Comment"></textarea>
                                        </div>
                                        <div class="center"><a class="btn main_btn" href="#">SUBMIT NOW</a></div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php ?>
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