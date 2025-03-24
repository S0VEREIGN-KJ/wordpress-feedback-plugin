<?php

function export_feedback_csv() {
    if (!isset($_GET['action']) || $_GET['action'] !== 'export_feedback_csv') {
        return; 
    }

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have permission to access this page.'));
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'feedback_survey';
    
    $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

    if (empty($results)) {
        wp_die(__('No data available for export.'));
    }
    
    // Prevent output before sending headers
    ob_clean();
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename=feedback_survey_report.csv');
    
    $output = fopen('php://output', 'w');
    
    fputcsv($output, array_keys($results[0]));
    
    foreach ($results as $row) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit;
}


add_action('admin_post_export_feedback_csv', 'export_feedback_csv');

function add_csv_export_button() {
    echo'<div class="wrap">
        <h2>Feedback Survey Report</h2>
            <form method="post" action="' . admin_url('admin-post.php') . '">
                <input type="hidden" name="action" value="export_feedback_csv">
                <button type="submit" class="button button-primary">Download CSV Report</button>
            </form>
        </div>';
}

add_action('admin_menu', function() {
    add_submenu_page(
        'data-monitor',      // parent slug (attach to your existing page)
        'Export Feedback',   // page title
        'Export CSV',        // menu title
        'manage_options',    // capability
        'export-feedback',   // menu slug
        'add_csv_export_button' // callback function
    );
});
?>