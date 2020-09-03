<?php /* Template Name: Contact */ 
get_header();
?>
<div class="cont"
    style="background-image:linear-gradient(to right top,rgba(27, 51, 77, 0.6),rgba(27, 51, 77, 0.8)),url(<?php echo get_theme_file_uri('images/slider_img1.png') ?>)">
    <ul class="rows">
        <li class="toomoney-logo"> <img src="<?php echo get_theme_file_uri('images/logos/logo_2.png') ?>" alt="">
        </li>
        <li class="columns">
            <div class="cells-date">
                تاریخ: <?php echo date('F j, Y');?> ساعت: <?php echo date('G:i');?></div>
        </li>
        <li class="columns">
            <div class="cells-full">
                نرخ لحظه ای ارز و کرایپوکارنسی ها
            </div>
        </li>
        <li class="columns">
            <div class="cells"> <img src="<?php echo get_theme_file_uri('images/sweden.jpg') ?>"
                    alt=""><span>2630</span>
                <p>
                    تومان</p>

            </div>
            <div class="cells">
                <img src="<?php echo get_theme_file_uri('images/BTC.png') ?>" alt=""><span
                    style="font-size:17px">11397</span>
                <p>
                    دلار</p>
            </div>
        </li>
        <li class="columns">
            <div class="cells"> <img src="<?php echo get_theme_file_uri('images/norwegin.jpg') ?>"
                    alt=""><span>2450</span>
                <p>
                    تومان</p>
            </div>
            <div class="cells"> <img src="<?php echo get_theme_file_uri('images/Etherium.png') ?>" alt=""><span
                    style="font-size:17px">11397</span>
                <p>
                    دلار</p>
            </div>
        </li>
        <li class="columns">
            <div class="cells"> <img src="<?php echo get_theme_file_uri('images/denmark.jpg') ?>"
                    alt=""><span>3620</span>
                <p>
                    تومان</p>
            </div>
            <div class="cells"> <img src="<?php echo get_theme_file_uri('images/ripple.png') ?>" alt=""><span
                    style="font-size:17px">11397</span>
                <p>
                    دلار</p>
            </div>
        </li>
        <li class="columns">
            <div class="cells-full-sub">
                قیمت ها با تغییرات لحظه ای بازار ارز تغییر می کنند. </div>
        </li>
    </ul>
</div>
<?php
get_footer();
?>