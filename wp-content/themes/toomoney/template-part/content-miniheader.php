<?php 

while(have_posts()) {
    the_post(); 
?>
<section id="inner_page_infor" class="innerpage_banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="full">
                    <div class="inner_page_info">
                        <h3 style="text-transform:capitalize;"><?php echo get_post_type(); ?><h3>
                                <ul>
                                    <li><a href="<?php echo get_post_type_archive_link(print_r(get_post_type()));?>"
                                            style="text-transform:capitalize;"><?php echo get_post_type(); ?></a></li>
                                    <li><i class="fa fa-angle-right"></i></li>
                                    <li><a href="#"><?php echo the_title(); ?> </a></li>
                                </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php } ?>