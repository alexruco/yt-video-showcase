<?php
// admin/admin-settings.php

function yt_video_showcase_register_settings() {
    add_option('yt_video_showcase_api_key', '');
    add_option('yt_video_showcase_video_ids', '');
    add_option('yt_video_showcase_featured_video_id', '');
    add_option('yt_video_showcase_cta_text', 'Click Here'); // Default CTA text
    add_option('yt_video_showcase_cta_url', '#'); // Default CTA URL
    add_option('yt_video_showcase_custom_image_thumbnail_1', ''); // Custom image for thumbnail 1
    add_option('yt_video_showcase_custom_image_thumbnail_2', ''); // Custom image for thumbnail 2
    add_option('yt_video_showcase_custom_image_thumbnail_3', ''); // Custom image for thumbnail 3
    add_option('yt_video_showcase_custom_image_featured_video', ''); // Custom image for featured video

    register_setting('yt_video_showcase_options_group', 'yt_video_showcase_api_key');
    register_setting('yt_video_showcase_options_group', 'yt_video_showcase_video_ids');
    register_setting('yt_video_showcase_options_group', 'yt_video_showcase_featured_video_id');
    register_setting('yt_video_showcase_options_group', 'yt_video_showcase_cta_text');
    register_setting('yt_video_showcase_options_group', 'yt_video_showcase_cta_url');
    register_setting('yt_video_showcase_options_group', 'yt_video_showcase_custom_image_thumbnail_1');
    register_setting('yt_video_showcase_options_group', 'yt_video_showcase_custom_image_thumbnail_2');
    register_setting('yt_video_showcase_options_group', 'yt_video_showcase_custom_image_thumbnail_3');
    register_setting('yt_video_showcase_options_group', 'yt_video_showcase_custom_image_featured_video');
}
add_action('admin_init', 'yt_video_showcase_register_settings');

function yt_video_showcase_register_options_page() {
    add_options_page('YouTube Video Showcase', 'YouTube Video Showcase', 'manage_options', 'yt_video_showcase', 'yt_video_showcase_options_page');
}
add_action('admin_menu', 'yt_video_showcase_register_options_page');

function yt_video_showcase_enqueue_media_uploader() {
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'yt_video_showcase_enqueue_media_uploader');

function yt_video_showcase_options_page() {
?>
    <div>
        <h2>YouTube Video Showcase Settings</h2>
        <div>Shortcode: [yt_video_showcase]</div>
        <form method="post" action="options.php">
            <?php settings_fields('yt_video_showcase_options_group'); ?>
            <table>
                <tr valign="top">
                    <th scope="row"><label for="yt_video_showcase_api_key">YouTube API Key</label></th>
                    <td><input type="text" id="yt_video_showcase_api_key" name="yt_video_showcase_api_key" value="<?php echo get_option('yt_video_showcase_api_key'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="yt_video_showcase_video_ids">Video IDs (comma-separated)</label></th>
                    <td><input type="text" id="yt_video_showcase_video_ids" name="yt_video_showcase_video_ids" value="<?php echo get_option('yt_video_showcase_video_ids'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="yt_video_showcase_featured_video_id">Featured Video ID</label></th>
                    <td><input type="text" id="yt_video_showcase_featured_video_id" name="yt_video_showcase_featured_video_id" value="<?php echo get_option('yt_video_showcase_featured_video_id'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="yt_video_showcase_cta_text">CTA Text</label></th>
                    <td><input type="text" id="yt_video_showcase_cta_text" name="yt_video_showcase_cta_text" value="<?php echo get_option('yt_video_showcase_cta_text'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="yt_video_showcase_cta_url">CTA URL</label></th>
                    <td><input type="text" id="yt_video_showcase_cta_url" name="yt_video_showcase_cta_url" value="<?php echo get_option('yt_video_showcase_cta_url'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="yt_video_showcase_custom_image_thumbnail_1">Custom Image for Thumbnail 1</label></th>
                    <td>
                        <input type="text" id="yt_video_showcase_custom_image_thumbnail_1" name="yt_video_showcase_custom_image_thumbnail_1" value="<?php echo get_option('yt_video_showcase_custom_image_thumbnail_1'); ?>" />
                        <button type="button" class="upload_image_button button" data-target="#yt_video_showcase_custom_image_thumbnail_1">Select Image</button>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="yt_video_showcase_custom_image_thumbnail_2">Custom Image for Thumbnail 2</label></th>
                    <td>
                        <input type="text" id="yt_video_showcase_custom_image_thumbnail_2" name="yt_video_showcase_custom_image_thumbnail_2" value="<?php echo get_option('yt_video_showcase_custom_image_thumbnail_2'); ?>" />
                        <button type="button" class="upload_image_button button" data-target="#yt_video_showcase_custom_image_thumbnail_2">Select Image</button>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="yt_video_showcase_custom_image_thumbnail_3">Custom Image for Thumbnail 3</label></th>
                    <td>
                        <input type="text" id="yt_video_showcase_custom_image_thumbnail_3" name="yt_video_showcase_custom_image_thumbnail_3" value="<?php echo get_option('yt_video_showcase_custom_image_thumbnail_3'); ?>" />
                        <button type="button" class="upload_image_button button" data-target="#yt_video_showcase_custom_image_thumbnail_3">Select Image</button>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="yt_video_showcase_custom_image_featured_video">Custom Image for Featured Video</label></th>
                    <td>
                        <input type="text" id="yt_video_showcase_custom_image_featured_video" name="yt_video_showcase_custom_image_featured_video" value="<?php echo get_option('yt_video_showcase_custom_image_featured_video'); ?>" />
                        <button type="button" class="upload_image_button button" data-target="#yt_video_showcase_custom_image_featured_video">Select Image</button>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <script>
    jQuery(document).ready(function($){
        var custom_uploader;
        jQuery('.upload_image_button').click(function(e) {
            e.preventDefault();
            var button = jQuery(this);
            var target = button.data('target');
            if (custom_uploader) {
                custom_uploader.open();
                return;
            }
            custom_uploader = wp.media({
                title: 'Select Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            });
            custom_uploader.on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                jQuery(target).val(attachment.url);
            });
            custom_uploader.open();
        });
    });
    </script>
<?php
}
?>
