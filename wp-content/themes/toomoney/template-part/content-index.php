<section class="padding_0 info_coins light_bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="full">
                    <div class="coin_formation">
                        <ul style="font-size:8px; line-height:8px;margin:0">
                            <?php
                            // The Query
                            $args = array(
                                            'post_type' => 'index',
                                            'posts_per_page'=>1,
                                            'order'=>'DESC',
                                            'orderby'=>'ID',
                                            );

                            $the_query = new WP_Query( $args );

                            // The Loop
                            if ( $the_query->have_posts() ) {
                                while ( $the_query->have_posts() ) {
                                    $the_query->the_post();
                            ?>
                            <li>
                                <span class="curr_name">نرخ لحظه ای کرون دانمارک</span>
                                <span>تومان </span><span class="curr_price"
                                    id="index-dkk"><?php the_field('sek_sale')?></span>
                                <p style="font-size:8px; line-height:8px;margin:0">
                                    <?php echo get_the_date('F j, Y G:i');?></p>
                            </li>
                            <li>
                                <span class="curr_name">نرخ لحظه ای کرون سوئد</span>
                                <span>تومان </span><span class="curr_price"
                                    id="index-sek"><?php the_field('sek_buy')?></span>
                                <p style="font-size:8px; line-height:8px;margin:0">
                                    <?php echo get_the_date('F j, Y G:i');?></p>
                            </li>
                            <li>
                                <span class="curr_name">نرخ لحظه ای کرون نروژ</span>
                                <span>تومان </span><span class="curr_price"
                                    id="index-nok"><?php the_field('usd_buy')?></span>
                                <p style="font-size:8px; line-height:8px;margin:0">
                                    <?php echo get_the_date('F j, Y G:i');?></p>
                            </li>
                            <li>
                                <span class="curr_name">نرخ لحظه ای دلار</span>
                                <span>تومان </span><span class="curr_price"
                                    id="index-us"><?php the_field('usd_sale')?></span>
                                <p style="font-size:8px; line-height:8px;margin:0">
                                    <?php echo get_the_date('F j, Y G:i');?></p>
                            </li>
                            <?php wp_reset_query(); }} ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>