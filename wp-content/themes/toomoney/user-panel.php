<?php /* Template Name: User Panel */ 
get_header();
?>
<!-- section -->
<section class="layout_padding">
    <div class="bhoechie-tab-container">
        <div class="bhoechie-tab-menu">
            <a href="#" class="list-group-item text-center">
                <h5>ارسال مقاله</h5>
                <h4 class="glyphicon glyphicon-road"></h4>
            </a>
            <a href="#" class="list-group-item text-center">
                <h5> آگهی های من</h5>
                <h4 class="glyphicon glyphicon-home"></h4>
            </a>
            <a href="#" class="list-group-item text-center">
                <h5> ارسال آگهی</h5>
                <h4 class="glyphicon glyphicon-credit-card"></h4>
            </a>
            <a href="#" class="list-group-item text-center">
                <h5> مقالات من</h5>
                <h4 class="glyphicon glyphicon-cutlery"></h4>
            </a>
            <a href="#" class="list-group-item text-center">
                <h5> ارسال مقاله</h5>
                <h4 class="glyphicon glyphicon-credit-card"></h4>
            </a>
        </div>
        <div class="bhoechie-tab">
            <!-- NEW POST SECTION -->
            <div class="bhoechie-tab-content">
                <center>
                </center>
            </div>

            <!-- MY ADS SECTION -->
            <div class="bhoechie-tab-content">
                <center>
                    <div class="full">
                        <table class="table profile-table" style="color:black !important;">
                            <thead>
                                <tr>
                                    <th scope="col">تاریخ</th>
                                    <th scope="col">خرید/فروش</th>
                                    <th scope="col">مقدار</th>
                                    <th scope="col">واحد</th>
                                    <th scope="col">شماره</th>
                                    <th scope="col"><button class="btnc">آگهی جدید</button></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                        $args = array(
                            'post_type' => 'ads',
                            'posts_per_page' => -1,
                            'author' => get_current_user_id()
                        );
                        $q = new WP_Query($args);
                        if ( $q->have_posts() ) {
                            while ( $q->have_posts() ) {
                                $q->the_post(); 
                        ?>

                                <tr data-id="<?php the_ID();?>">
                                    <td><input style="border:none;" readonly type="text"
                                            value="<?php echo get_the_date('F j, Y G:i');?>">
                                    </td>
                                    <td><input name="buy_sale" class="ads_buysale" style="width:100%;border:none;"
                                            readonly type="text" value="<?php the_field('buy_sale'); ?>"></td>
                                    <td><input name="amount" class="ads_amount" style="width:100%;border:none;" readonly
                                            type="text" value="<?php the_field('amount'); ?>">
                                    </td>
                                    <td><input name="index" class="ads_index" style="width:100%;border:none;" readonly
                                            type="text" value="<?php the_field('index'); ?>">
                                    </td>
                                    <th scope="row"><input style="width:100%;border:none;margin-top:10px;" readonly
                                            type="text" value="<?php the_ID(); ?>"></th>
                                    <td style="text-align:center"><a style="cursor:pointer" class="adsEdit">اصلاح</a> |
                                        <a style="cursor:pointer" class="adsDelete">حذف</a>
                                        <span><button style="display:none;"
                                                class="adsSave btnc btnc--green">ذخیره</button></span>
                                        <span><button style="display:none;"
                                                class="adsCancel btnc btnc--red">لغو</button></span>
                                    </td>
                                </tr>
                                <?php
                        }}
                        ?>

                            </tbody>
                        </table>
                    </div>
                    <?php 
                        echo paginate_links();
                        ?>
                </center>
            </div>

            <!-- NEW ADS SECTION -->
            <div class="bhoechie-tab-content">
                <center>
                    <form class="ans_new_form" style="width:50%;text-align:right;">
                        <div class="form-group">
                            <label for="index">واحد</label>
                            <select id="index" placeholder="واحد" class="custom-select ads_new_index form-control"
                                id="inputGroupSelect01" require>
                                <option selected value="SEK">SEK</option>
                                <option value="NOK">NOK</option>
                                <option value="DAK">DAK</option>
                                <option value="EUR">EUR</option>
                                <option value="USD">USD</option>
                            </select>
                            <small id="emailHelp" class="form-text text-muted">واحد های قابل معاوضه
                                یورو، کرون سوئد، کرون نروژ، و کرون دانمارک می باشد.</small>
                        </div>
                        <div class="form-group">
                            <label for="amount">مقدار</label>
                            <input class="ads_new_amount form-control" id="amount" placeholder="مقدار" require>
                        </div>
                        <div class="form-group">
                            <label for="buy-sale">خرید / فروش</label>
                            <select id="buy-sale" placeholder="خرید / فروش"
                                class="custom-select ads_new_buysale form-control" id="inputGroupSelect01" require>
                                <option selected value="خرید">خرید</option>
                                <option value="فروش">فروش</option>
                            </select>
                        </div>
                        <button class="btnc btnc--red">لغو</button>
                        <a role="button" class="ansNew btnc btnc--blue">ذخیره</a>
                    </form>
                </center>
            </div>

            <!-- MY POSTS SECTION -->
            <div class="bhoechie-tab-content">
                <center>
                    <div class="full">
                        <?php 
                        $args = array(
                            'post_type' => '',
                            'posts_per_page' => -1,
                            'author' => get_current_user_id()
                        );
                        $q = new WP_Query($args);
                        if ( $q->have_posts() ) {
                            ?>
                        <table class="table profile-table" style="color:black !important;">
                            <thead>
                                <tr>
                                    <th scope="col">تاریخ</th>
                                    <th scope="col">عنوان</th>
                                    <th scope="col"><button class="btnc">مقاله جدید</button></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                            while ( $q->have_posts() ) {
                                $q->the_post(); 
                        ?>

                                <tr data-id="<?php the_ID();?>">
                                    <td><input style="border:none;" readonly type="text" value="<?php the_date(); ?>">
                                    </td>
                                    <td><input name="title" class="post_title" style="width:100%;border:none;" readonly
                                            type="text" value="<?php the_title(); ?>">
                                    </td>
                                    <td style="text-align:center"><a style="cursor:pointer" class="postEdit">اصلاح</a> |
                                        <a style="cursor:pointer" class="postDelete">حذف</a>
                                        <span><button style="display:none;"
                                                class="postSave btnc btnc--green">ذخیره</button></span>
                                        <span><button style="display:none;"
                                                class="postCancel btnc btnc--red">لغو</button></span>
                                    </td>
                                </tr>
                                <?php
                        }}else{
                        ?>
                                <p>هیچ مطلبی با نام شما در وبسایت نشر نشده است!</p>
                                <?php
                        }
                        ?>
                            </tbody>
                        </table>
                    </div>
                    <?php 
                        echo paginate_links();
                        ?>
                </center>
            </div>

            <!-- NEW POST SECTION -->
            <div class="bhoechie-tab-content">
                <center>
                    <p>دسترسی به این قسمت برای شما محدود شده است، لطفا با ادمین تماس بگیرید!</p>
                    <!-- <form class="post_new_form" style="display:nonjhe;margin-top:10%;width:50%;text-align:right;">
                        <div class="form-group">
                            <label for="new-post-title">عنوان</label>
                            <input class="post_new_title form-control" id="new-post-title" placeholder="مقاله"></input>
                        </div>
                        <div class="form-group">
                            <label for="new-post-content">مقاله</label>
                            <textarea class="post_new_content form-control" id="new-post-content"
                                placeholder="مقاله"></textarea>
                        </div>
                        <button class="btnc btnc--red">لغو</button>
                        <a role="button" class="postNew btnc btnc--blue">ذخیره</a>
                    </form> -->
                </center>
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
        <?php
            get_template_part('template-part/logos');
        ?>
    </div>
</section>

<!-- end section -->
<?php get_footer(); ?>