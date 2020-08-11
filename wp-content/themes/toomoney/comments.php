<?php
    wp_list_comments('type=comment&callback=format_comment'); 
    function format_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment; ?>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
        <div class="full">
            <div class="img-responsive img-circle" style="max-width:100px">
                <?php echo get_avatar(get_user_by("user_email", get_comment_author_email($comment_ID)),100)?>
            </div>
        </div>
    </div>
    <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
        <?php if(get_comment_author($comment_ID)==get_the_author()) { 
            ?>
        <div class="full command_cont" style="background: #e9d16f;">
            <p class="comm_head"><?php comment_author($comment_ID);?>
                <span><?php comment_date($comment_ID)?></span>
                <?php if ($comment->comment_approved == '0') : ?>
                <em>
                    <php _e('Your comment is awaiting moderation.') ?>
                </em><br />
                <?php endif; ?>
                <div class="rply">
                    <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                </div>
            </p>
            <p> <?php comment_text(); ?>
            </p>
        </div>
        <?php }else{ ?>
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
                <div class="full command_cont margin_bottom_0">
                    <p class="comm_head"><?php comment_author($comment_ID)?>
                        <span><?php comment_date($comment_ID)?></span><?php if ($comment->comment_approved == '0') : ?>
                        <em>
                            <php _e('Your comment is awaiting moderation.') ?>
                        </em><br />
                        <?php endif; ?>
                    </p>
                    <p><?php comment_text(); ?>
                    </p>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<?php } ?>
<div class="post_commt_form">
    <h4>POST YOUR COMMENT</h4>
    <div class="form_section">
        <form class="form_contant" action="index.html">
            <fieldset>
                <div class="field col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <textarea class="field_custom" placeholder="Comment"></textarea>
                </div>
                <div class="center"><button class="btn btn__green u-margin-t-small" href="#">SUBMIT NOW</button></div>
            </fieldset>
        </form>
    </div>
</div>