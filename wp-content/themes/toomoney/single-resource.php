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
                            <img class="img-responsive" src="<?php echo get_the_post_thumbnail_url();?>" alt="#">
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
                            <?php
                        
                            $homePageNews = new WP_Query(array(
                                'posts_per_page' => -1,
                                'post_type' => 'news',
                                'orderby' => 'title',
                                'order' => 'ASC',
                                'meta_query' => array(                  //      just for example :
                                        array(                              //      just for example :
                                            'key' => 'related_resources',   //      getting back if contains of related_resources
                                            'compare' => 'LIKE',            //
                                            'value' => '"'.get_the_ID().'"' //
                                        ))));                               //
                            if($homePageNews){
                            while($homePageNews->have_posts()){
                                $homePageNews->the_post();
                            ?>
                            <ul>
                                <li><a href="<?php the_permalink();?>"><?php the_title(); ?></a></li>
                                <p><?php the_excerpt();?></p>
                            </ul>
                            <?php }}?>
                        </div>
                        <div class="full testimonial_simple_say margin_bottom_30_all" style="margin-top:0;">
                            <div class="qoute_testimonial"><i class="fa fa-quote-left" aria-hidden="true"></i></div>
                            <p class="margin_bottom_0"><i>Adipisicing elit lorem ipsum dolor sit amet, consectetur, sed
                                    do eiusmod tempor incididunt ut labore et dolore magna aliqua.</i></p>
                            <p class="large_2 theme_color">John Barber</p>
                        </div>

                        <div class="view_commant">
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                                    <div class="full">
                                        <img class="img-responsive img-circle" style="max-width:100px"
                                            src="images/150x150.png" alt="#">
                                    </div>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
                                    <div class="full command_cont" style="background: #e9d16f;">
                                        <p class="comm_head">Christian Perez <span>Sep 27,2017</span><a class="rply"
                                                href="#">Reply</a></p>
                                        <p>magine you are 10 years into the future but this time it’s different. Why?
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
                                                    src="images/150x150.png" alt="#">
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
                                            <div class="full command_cont margin_bottom_0">
                                                <p class="comm_head">Christian Perez <span>Sep 27,2017</span><a
                                                        class="rply" href="#">Reply</a></p>
                                                <p>magine you are 10 years into the future but this time it’s different.
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
                                            src="images/150x150.png" alt="#">
                                    </div>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
                                    <div class="full command_cont" style="background: #e9d16f;">
                                        <p class="comm_head">Christian Perez <span>Sep 27,2017</span><a class="rply"
                                                href="#">Reply</a></p>
                                        <p>magine you are 10 years into the future but this time it’s different. Why?
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
                                                    src="images/150x150.png" alt="#">
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
                                            <div class="full command_cont margin_bottom_0">
                                                <p class="comm_head">Christian Perez <span>Sep 27,2017</span><a
                                                        class="rply" href="#">Reply</a></p>
                                                <p>magine you are 10 years into the future but this time it’s different.
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
                                                    src="images/150x150.png" alt="#">
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
                                            <div class="full command_cont margin_bottom_0">
                                                <p class="comm_head">Christian Perez <span>Sep 27,2017</span><a
                                                        class="rply" href="#">Reply</a></p>
                                                <p>magine you are 10 years into the future but this time it’s different.
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
                    <?php } ?>
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
                        <h4>ABOUT AUTHOR</h4>
                        <p class="left_text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. sed do eiusmod
                            tempor.</p>
                        <p class="left_text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua.</p>
                    </div>
                    <div class="side_bar_blog">
                        <h4>RECENT POST</h4>
                        <div class="recent_post">
                            <ul>
                                <li>
                                    <p class="post_head left_text"><a href="#">How To Look Up</a></p>
                                    <p class="post_date left_text"><i class="fa fa-calendar" aria-hidden="true"></i> Aug
                                        20, 2017</p>
                                </li>
                                <li>
                                    <p class="post_head left_text"><a href="#">Compatible Inkjet Cartridge</a></p>
                                    <p class="post_date left_text"><i class="fa fa-calendar" aria-hidden="true"></i> Aug
                                        20, 2017</p>
                                </li>
                                <li>
                                    <p class="post_head left_text"><a href="#">Treat That Oral Thrush Now</a></p>
                                    <p class="post_date left_text"><i class="fa fa-calendar" aria-hidden="true"></i> Aug
                                        20, 2017</p>
                                </li>
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
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="full">
                    <ul class="brand-list">
                        <li><img src="images/brand1.png" alt="#"></li>
                        <li><img src="images/brand2.png" alt="#"></li>
                        <li><img src="images/brand3.png" alt="#"></li>
                        <li><img src="images/brand4.png" alt="#"></li>
                        <li><img src="images/brand5.png" alt="#"></li>
                        <li><img src="images/brand6.png" alt="#"></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- end section -->
<?php get_footer(); ?>