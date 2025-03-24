<?php
/**
 * Plugin Name: Feedback Form
 * Description: provide feedback overlay form and an admin menu in the wp dashboard.
 * Author: Karl Jasper G. Del Rosario
 */

if (!defined('ABSPATH')) {
    exit; 
}

require_once plugin_dir_path(__FILE__) . 'admin-menu.php';

if (file_exists(plugin_dir_path(__FILE__) . 'functions.php')) {
    require_once plugin_dir_path(__FILE__) . 'functions.php';
}

function feedback_form_enqueue_styles() {
    wp_enqueue_style('feedback-form-plugin-style', plugin_dir_url(__FILE__) . 'style.css');
}
add_action('wp_enqueue_scripts', 'feedback_form_enqueue_styles');

function feedback_form_enqueue_admin_styles() {
    wp_enqueue_style('feedback-form-admin-style', plugin_dir_url(__FILE__) . 'style.css');
}
add_action('admin_enqueue_scripts', 'feedback_form_enqueue_admin_styles');

add_action('wp_footer', 'load_overlay_template');

// create able on plugin activation
function create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . "feedback_survey";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        q1 INT,
        q2 INT,
        q3 INT,
        q4 INT,
        q5 INT,
        comments TEXT,
        created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'create_table');