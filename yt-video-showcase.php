<?php
/*
Plugin Name: YouTube Video Showcase
Description: A plugin to showcase YouTube videos.
Version: 1.0
Author: Alex Ruco
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin paths
define('YT_VIDEO_SHOWCASE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('YT_VIDEO_SHOWCASE_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include admin settings
require_once YT_VIDEO_SHOWCASE_PLUGIN_DIR . 'admin/admin-settings.php';

// Enqueue scripts and styles
function yt_video_showcase_enqueue_assets() {
    wp_enqueue_style('yt-video-showcase-styles', YT_VIDEO_SHOWCASE_PLUGIN_URL . 'public/css/styles.css');
    wp_enqueue_script('yt-video-showcase-scripts', YT_VIDEO_SHOWCASE_PLUGIN_URL . 'public/js/scripts.js', [], false, true);
    wp_localize_script('yt-video-showcase-scripts', 'ytVideoShowcase', [
        'apiKey' => get_option('yt_video_showcase_api_key'),
        'videoIds' => explode(',', get_option('yt_video_showcase_video_ids')),
        'featuredVideoId' => get_option('yt_video_showcase_featured_video_id'),
        'ctaText' => get_option('yt_video_showcase_cta_text'),
        'ctaUrl' => get_option('yt_video_showcase_cta_url')
    ]);
}
add_action('wp_enqueue_scripts', 'yt_video_showcase_enqueue_assets');

// Shortcode to display video showcase
function yt_video_showcase_shortcode() {
    ob_start();
    include YT_VIDEO_SHOWCASE_PLUGIN_DIR . 'public/partials/video-showcase.php';
    return ob_get_clean();
}
add_shortcode('yt_video_showcase', 'yt_video_showcase_shortcode');

// Add link to settings page in plugin list
function yt_video_showcase_plugin_action_links($links) {
    $settings_link = '<a href="options-general.php?page=yt_video_showcase">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'yt_video_showcase_plugin_action_links');

// Add custom admin menu
function yt_video_showcase_admin_menu() {
    add_menu_page(
        'YouTube Video Showcase',      // Page title
        'YT Video Showcase',           // Menu title
        'manage_options',              // Capability
        'yt_video_showcase',           // Menu slug
        'yt_video_showcase_options_page', // Callback function
        'dashicons-video-alt3',        // Icon URL
        20                             // Position
    );
}
add_action('admin_menu', 'yt_video_showcase_admin_menu');

?>