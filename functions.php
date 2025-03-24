<?php

if (!defined('ABSPATH')) {
    exit;
}

function my_child_theme_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'my_child_theme_enqueue_styles');
add_action('admin_post_export_feedback_csv', 'export_feedback_csv');

// Handle feedback submission (AJAX)
add_action('wp_ajax_handle_feedback_submission', 'handle_feedback_submission');
add_action('wp_ajax_nopriv_handle_feedback_submission', 'handle_feedback_submission');

function handle_feedback_submission() {
    error_log(print_r($_POST, true));
    
    global $wpdb;
    header('Content-Type: application/json');

    // Verify nonce for security
    if ($_SERVER["REQUEST_METHOD"] == "POST" && check_ajax_referer('ajax_nonce', 'security', false)) {
        $emojiMap = ["😀" => 5, "😊" => 4, "😐" => 3, "😕" => 2, "😨" => 1];

        $q1 = isset($_POST["q1"]) ? ($emojiMap[$_POST["q1"]] ?? null) : null;
        $q2 = isset($_POST["q2"]) ? ($emojiMap[$_POST["q2"]] ?? null) : null;
        $q3 = isset($_POST["q3"]) ? ($emojiMap[$_POST["q3"]] ?? null) : null;
        $q4 = isset($_POST["q4"]) ? ($emojiMap[$_POST["q4"]] ?? null) : null;
        $q5 = isset($_POST["q5"]) ? ($emojiMap[$_POST["q5"]] ?? null) : null;
        $comments = isset($_POST["comments"]) ? trim($_POST["comments"]) : null;

        if ($q1 === null || $q2 === null || $q3 === null || $q4 === null || $q5 === null) {
            echo json_encode(["status" => "error", "message" => "Please fill up all questions."]);
            wp_die();
        }

        $table_name = $wpdb->prefix . 'feedback_survey';
        $result = $wpdb->insert($table_name, [
            'q1' => $q1, 'q2' => $q2, 'q3' => $q3, 'q4' => $q4, 'q5' => $q5, 'comments' => $comments
        ], ['%d', '%d', '%d', '%d', '%d', '%s']);

        if ($result === false) {
            echo json_encode(["status" => "error", "message" => "Database error: " . $wpdb->last_error]);
        } else {
            echo json_encode(["status" => "success", "message" => "Thank you for your feedback!"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid request."]);
    }
    wp_die();
}

function enqueue_feedback_script() {
    wp_enqueue_script('jquery');
    wp_localize_script('jquery', 'ajax_object', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ajax_nonce')
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_feedback_script');

// CSV Export Function
function export_feedback_csv() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have permission to access this page.'));
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'feedback_survey';
    $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC", ARRAY_A);

    if (empty($results)) {
        wp_die(__('No data available for export.'));
    }

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename=Feedback_Report.csv');

    $output = fopen('php://output', 'w');
    fputs($output, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel

    // CSV Header
    fputcsv($output, ['Respondents Answers', '', '', '', '', '','', 'Overall Avg']);
    fputcsv($output, ['5 = Very Satisfied', '', '', '', '', '','', '4 = Lowest']);
    fputcsv($output, ['4 = Satisfied', '', '', '', '', '', '','1 = Highest']);
    fputcsv($output, ['3 = Neutral']);
    fputcsv($output, ['2 = Unsatisfied']);
    fputcsv($output, ['1 = Very Unsatisfied']);
    fputcsv($output, []); // Empty row for spacing

    // CSV Headers
    fputcsv($output, ['Date Submitted', 'Q1', 'Q2', 'Q3', 'Q4', 'Q5', 'Comments', 'Overall Avg']);

    foreach ($results as $row) {
        $date = date('Y-m-d', strtotime($row['created_at']));
        $q1 = (int) $row['q1'];
        $q2 = (int) $row['q2'];
        $q3 = (int) $row['q3'];
        $q4 = (int) $row['q4'];
        $q5 = (int) $row['q5'];
        $overall_avg = round(($q1 + $q2 + $q3 + $q4 + $q5) / 5, 2);

        fputcsv($output, [$date, $q1, $q2, $q3, $q4, $q5, $row['comments'], $overall_avg]);
    }

    fclose($output);
    exit;
}

function load_overlay_template() {
    include plugin_dir_path(__FILE__) . 'overlay.php';
}

?>
