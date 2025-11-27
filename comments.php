<?php
/**
 * Comments template
 *
 * @package giaimatuky
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            if ($comment_count > 0) {
                printf(
                    esc_html(_n('%s bình luận', '%s bình luận', $comment_count, 'giaimatuky')),
                    number_format_i18n($comment_count)
                );
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'      => 'ol',
                'short_ping' => true,
            ));
            ?>
        </ol>

        <?php
        the_comments_pagination(array(
            'prev_text' => __('&laquo; Trước', 'giaimatuky'),
            'next_text' => __('Sau &raquo;', 'giaimatuky'),
        ));
        ?>
    <?php endif; ?>

    <?php
    comment_form(array(
        'title_reply'          => __('Để lại bình luận', 'giaimatuky'),
        'title_reply_to'       => __('Trả lời %s', 'giaimatuky'),
        'cancel_reply_link'    => __('Hủy trả lời', 'giaimatuky'),
        'label_submit'         => __('Gửi bình luận', 'giaimatuky'),
    ));
    ?>
</div>

