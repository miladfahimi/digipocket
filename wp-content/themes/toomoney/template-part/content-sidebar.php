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
            <h4>درباره مرجع</h4>
            <p class="right_text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. sed do
                eiusmod
                tempor.</p>
            <p class="right_text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                eiusmod
                tempor incididunt ut labore et dolore magna aliqua.</p>
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
                        <p class="post_head right_text"><a href="<?php the_permalink(); ?>"><?php the_title()?></a></p>
                        <p class="post_date right_text"><i class="fa fa-calendar"
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
            <h4>طبقه بندی</h4>
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
            <h4>برچسب</h4>
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
            <h4>آرشیو</h4>
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