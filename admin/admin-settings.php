// yt_video_showcase_settings.php

<?php
function yt_video_showcase_register_settings() {
    add_option('yt_video_showcase_api_key', '');
    add_option('yt_video_showcase_video_ids', '');
    add_option('yt_video_showcase_featured_video_id', '');
    add_option('yt_video_showcase_cta_text', 'Click Here'); // Default CTA text
    add_option('yt_video_showcase_cta_url', '#'); // Default CTA URL
    add_option('yt_video_showcase_custom_image_thumbnail_1', ''); // Custom image for thumbnail 1

    register_setting('yt_video_showcase_options_group', 'yt_video_showcase_api_key');
    register_setting('yt_video_showcase_options_group', 'yt_video_showcase_video_ids');
    register_setting('yt_video_showcase_options_group', 'yt_video_showcase_featured_video_id');
    register_setting('yt_video_showcase_options_group', 'yt_video_showcase_cta_text');
    register_setting('yt_video_showcase_options_group', 'yt_video_showcase_cta_url');
    register_setting('yt_video_showcase_options_group', 'yt_video_showcase_custom_image_thumbnail_1');
}
add_action('admin_init', 'yt_video_showcase_register_settings');

function yt_video_showcase_register_options_page() {
    add_options_page('YouTube Video Showcase', 'YouTube Video Showcase', 'manage_options', 'yt_video_showcase', 'yt_video_showcase_options_page');
}
add_action('admin_menu', 'yt_video_showcase_register_options_page');

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
                    <td><input type="text" id="yt_video_showcase_custom_image_thumbnail_1" name="yt_video_showcase_custom_image_thumbnail_1" value="<?php echo get_option('yt_video_showcase_custom_image_thumbnail_1'); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}
?>
