<?php

function custom_admin_menu_page() {
    add_menu_page(
        'Feedback',
        'Feedback',
        'manage_options',
        'data-monitor',
        'display_data_monitor',
        'dashicons-chart-line',
        6
    );
}
add_action('admin_menu', 'custom_admin_menu_page');

function display_data_monitor() {
    if (!current_user_can('manage_options')) {
        wp_die(__('Sorry, you are not allowed to access this page.'));
		
}

///wordpress database conn
global $wpdb;  
$table_name = $wpdb->prefix . 'feedback_survey';

//get response count
function getResponseCount($wpdb, $column, $table, $value = null) {
    if ($value !== null) {
    $query = $wpdb->prepare("SELECT COUNT(*) FROM $table WHERE $column = %d", $value);
    }else{
    $query = $wpdb->prepare("SELECT COUNT(*) FROM $table WHERE $column IS NOT NULL");
    }
    return $wpdb->get_var($query);
}

$emojiMap = [
    '1' => '😀Very Satisfied',
    '2' => '😊Satisfied',
    '3' => '😐Neutral',
    '4' => '😕Unsatisfied',
    '5' => '😨Very Unsatisfied',
];

$scoreMap = [
    '5' => 5,
    '4' => 4,
    '3' => 3,
    '2' => 2,
    '1' => 1,
];

$results = $wpdb->get_results("SELECT * FROM $table_name");
$totalRespondents = count($results);

$q1_total = $q2_total = $q3_total = $q4_total = $q5_total = 0;

// calculate averages
foreach ($results as $row) {
    $q1_total += $scoreMap[$row->q1] ?? 0;
    $q2_total += $scoreMap[$row->q2] ?? 0;
    $q3_total += $scoreMap[$row->q3] ?? 0;
    $q4_total += $scoreMap[$row->q4] ?? 0;
    $q5_total += $scoreMap[$row->q5] ?? 0;
}

// avoid division by zero
$q1_avg = $totalRespondents > 0 ? round($q1_total / $totalRespondents, 2) : 0;
$q2_avg = $totalRespondents > 0 ? round($q2_total / $totalRespondents, 2) : 0;
$q3_avg = $totalRespondents > 0 ? round($q3_total / $totalRespondents, 2) : 0;
$q4_avg = $totalRespondents > 0 ? round($q4_total / $totalRespondents, 2) : 0;
$q5_avg = $totalRespondents > 0 ? round($q5_total / $totalRespondents, 2) : 0;

$ratings = [1, 2, 3, 4, 5];
$q1_counts = [];
$q2_counts = [];
$q3_counts = [];
$q4_counts = [];
$q5_counts = [];

foreach ($ratings as $rating) {
    $q1_counts[$rating] = getResponseCount($wpdb, 'q1', $table_name, $rating);
    $q2_counts[$rating] = getResponseCount($wpdb, 'q2', $table_name, $rating);
    $q3_counts[$rating] = getResponseCount($wpdb, 'q3', $table_name, $rating);
    $q4_counts[$rating] = getResponseCount($wpdb, 'q4', $table_name, $rating);
    $q5_counts[$rating] = getResponseCount($wpdb, 'q5', $table_name, $rating);
}


///dashboard overview title
echo'<div class="wrap" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;background: #f4f4f4; border: 2px solid #ccc; padding: 20px; border-radius: 10px;">';
echo'<h1 style="color: #333; text-align: center; font-size: 24px; margin-bottom: 20px;">📊 Dashboard Overview</h1>';
echo'<div style="display: flex; flex-direction: column; gap: 10px; font-size: 16px;">';

// display total responses
echo'<div style="display: flex; align-items: center; background: #f1f1f1; padding: 12px; border-radius: 6px;">
        <span style="font-weight: bold; flex: 1;">🔹 Total Responses:</span> 
        <span style="color: #007bff; font-weight: bold;">' . $totalRespondents . '</span>
    </div>';

// display overall satisfaction score
$overall_score = round(($q1_avg + $q2_avg + $q3_avg + $q4_avg + $q5_avg) / 5, 2);
echo'<div style="display: flex; align-items: center; background: #e3fcef; padding: 12px; border-radius: 6px;">
        <span style="font-weight: bold; flex: 1;">🔹 Overall Satisfaction Score:</span> 
        <span style="color: #28a745; font-weight: bold;">⭐ ' . $overall_score . ' / 5</span>
    </div>';

// determine lowest and highest rated questions
$question_scores = [
    'Q1' => $q1_avg,
    'Q2' => $q2_avg,
    'Q3' => $q3_avg,
    'Q4' => $q4_avg,
    'Q5' => $q5_avg
];

//sort scores ascending
asort($question_scores);
$lowest_rated = array_key_first($question_scores);
$lowest_score = reset($question_scores);

// sort scores descending
arsort($question_scores); 
$highest_rated = array_key_first($question_scores);
$highest_score = reset($question_scores);

$question_labels = [
    'Q1' => 'Q1 (Loads quickly and responsive)',
    'Q2' => 'Q2 (Easy Navigation)',
    'Q3' => 'Q3 (Visual Appeal)',
    'Q4' => 'Q4 (Readable Content)',
    'Q5' => 'Q5 (Modern Design and Professional)'
];

// display lowest rated question
echo'<div style="display: flex; align-items: center; background: #ffe3e3; padding: 12px; border-radius: 6px;">
        <span style="font-weight: bold; flex: 1;">🔹 Lowest Rated Question:</span> 
        <span style="color: #dc3545; font-weight: bold;">' . $question_labels[$lowest_rated] . ' – ' . $lowest_score . ' / 5</span>
    </div>';

// Display highest rated question
echo'<div style="display: flex; align-items: center; background: #e7f1ff; padding: 12px; border-radius: 6px;">
        <span style="font-weight: bold; flex: 1;">🔹 Highest Rated Question:</span> 
        <span style="color: #007bff; font-weight: bold;">' . $question_labels[$highest_rated] . ' – ' . $highest_score . ' / 5</span>
    </div>';

echo '</div>'; // End flex container
echo '</div>'; // End wrapper


// Display analytics summary
echo '<div class="wrap"><h1>Feedback Analytics</h1>';

 // Generate Chart using Google Charts
echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';
echo <<<EOF
<script type="text/javascript">
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Question", "Average Score", { role: "style" }, { role: "tooltip", p: {html: true} }],
            ["Question 1", {$q1_avg}, getColor({$q1_avg}), 
                "Response Option &nbsp;| Response Count<br>1😀Very Satisfied |= {$q1_counts[1]}<br>2😊Satisfied &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; |= {$q1_counts[2]}<br>3😐Neutral &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; |= {$q1_counts[3]}<br>4😕Unsatisfied &nbsp;&nbsp;&nbsp; |= {$q1_counts[4]}<br>5😨Very Unsatisfied |= {$q1_counts[5]}"],
            ["Question 2", {$q2_avg}, getColor({$q2_avg}), 
                "Response Option &nbsp;| Response Count<br>1😀Very Satisfied |= {$q2_counts[1]}<br>2😊Satisfied &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; |= {$q2_counts[2]}<br>3😐Neutral &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; |= {$q2_counts[3]}<br>4😕Unsatisfied &nbsp;&nbsp;&nbsp; |= {$q2_counts[4]}<br>5😨Very Unsatisfied |= {$q2_counts[5]}"],
            ["Question 3", {$q3_avg}, getColor({$q3_avg}), 
                "Response Option &nbsp;| Response Count<br>1😀Very Satisfied |= {$q3_counts[1]}<br>2😊Satisfied &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; |= {$q3_counts[2]}<br>3😐Neutral &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; |= {$q3_counts[3]}<br>4😕Unsatisfied &nbsp;&nbsp;&nbsp; |= {$q3_counts[4]}<br>5😨Very Unsatisfied |= {$q3_counts[5]}"],
            ["Question 4", {$q4_avg}, getColor({$q4_avg}), 
                "Response Option &nbsp;| Response Count<br>1😀Very Satisfied |= {$q4_counts[1]}<br>2😊Satisfied &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; |= {$q4_counts[2]}<br>3😐Neutral &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; |= {$q4_counts[3]}<br>4😕Unsatisfied &nbsp;&nbsp;&nbsp; |= {$q4_counts[4]}<br>5😨Very Unsatisfied |= {$q4_counts[5]}"],
            ["Question 5", {$q5_avg}, getColor({$q5_avg}), 
                "Response Option &nbsp;| Response Count<br>1😀Very Satisfied |= {$q5_counts[1]}<br>2😊Satisfied &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; |= {$q5_counts[2]}<br>3😐Neutral &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; |= {$q5_counts[3]}<br>4😕Unsatisfied &nbsp;&nbsp;&nbsp; |= {$q5_counts[4]}<br>5😨Very Unsatisfied |= {$q5_counts[5]}"],
        ]);
        
        var options = {
            title: "",
            hAxis: { 
                title: "Questions"
            },
            vAxis: { 
                title: "Score (1-5)", 
                minValue: 1, 
                maxValue: 5,  
                ticks: [
                    { v: 1, f: "Very Unsatisfied (1)" },
                    { v: 2, f: "Unsatisfied (2)" },
                    { v: 3, f: "Neutral (3)" },
                    { v: 4, f: "Satisfied (4)" },
                    { v: 5, f: "Very Satisfied (5)" }
                    ]
            },
            legend: "none",
            tooltip: { isHtml: true } 
        };

        var chart = new google.visualization.ColumnChart(document.getElementById("chart_div"));
        chart.draw(data, options);
    }

    function getColor(score) {
        if (score >= 4.0) return "#4CAF50"; 
        if (score >= 3.0) return "#2196F3"; 
        if (score >= 2.0) return "#FFC107"; 
        if (score >= 1.5) return "#FF9800"; 
            return "#F44336"; 
    }
</script>
EOF;

echo '<div id="chart_div" style="width: 100%; height: 400px;"></div>';
echo '<br>';

//CSV EXPORT BUTTON 
echo'<form method="post" action="' . admin_url('admin-post.php') . '">
        <input type="hidden" name="action" value="export_feedback_csv">
        <button type="submit" class="button button-primary">📥 CSV Report</button>
    </form>';


// group by date
$data_by_date = [];
foreach ($results as $row) {
    $date = date('Y-m-d', strtotime($row->created_at)); 
    $data_by_date[$date][] = $row;
}

// sort dates in latest date first
krsort($data_by_date);

// collapsible table
echo'<table class="widefat fixed" style="margin-top: 50px; border-collapse: collapse; width:90%;"><thead>
<tr>
    <th>Date</th>
    <th style="text-align: right;"></th>
</tr>
</thead><tbody>';


foreach ($data_by_date as $date => $rows) {

    usort($rows, function($a, $b) {
        return strtotime($b->created_at) - strtotime($a->created_at);
    });
    
    echo'<tr class="date-row" data-target="group-' . esc_attr($date) . '" style="cursor: pointer;">
            <td style="border-bottom: 2px solid #ccc; padding: 10px;"><strong>' . esc_html($date) . '</strong></td>
            <td style="border-bottom: 2px solid #ccc; padding: 10px; text-align: right;">
                <span class="arrow" data-target="group-' . esc_attr($date) . '" style="font-size: 18px;"></span>
            </td>
        </tr>';

    echo'<tr class="data-group" id="group-' . esc_attr($date) . '" style="display: none;">
            <td colspan="2">
                <table class="widefat fixed" style="margin-top: 10px;">
                    <thead>
                        <tr>
                            <th>Q1</th>
                            <th>Q2</th>
                            <th>Q3</th>
                            <th>Q4</th>
                            <th>Q5</th>
                            <th>Comments</th>
                            <th>Overall Avg</th>
                        </tr>
                    </thead>
                <tbody>';

foreach ($rows as $row) {
$q1 = $emojiMap[$row->q1] ?? $row->q1;
$q2 = $emojiMap[$row->q2] ?? $row->q2;
$q3 = $emojiMap[$row->q3] ?? $row->q3;
$q4 = $emojiMap[$row->q4] ?? $row->q4;
$q5 = $emojiMap[$row->q5] ?? $row->q5;

$q1_score = $scoreMap[$row->q1] ?? 0;
$q2_score = $scoreMap[$row->q2] ?? 0;
$q3_score = $scoreMap[$row->q3] ?? 0;
$q4_score = $scoreMap[$row->q4] ?? 0;
$q5_score = $scoreMap[$row->q5] ?? 0;
$overall_avg = round(($q1_score + $q2_score + $q3_score + $q4_score + $q5_score) / 5, 2);

    echo"<tr>
            <td>" . esc_html($q1) . "</td>
            <td>" . esc_html($q2) . "</td>
            <td>" . esc_html($q3) . "</td>
            <td>" . esc_html($q4) . "</td>
            <td>" . esc_html($q5) . "</td>
            <td>" . esc_html($row->comments) . "</td>
            <td><strong>" . esc_html($overall_avg) . "</strong></td>
        </tr>";
}

echo '</tbody>
        </table>
        </td>
</tr>';
}

echo '</tbody></table>';


echo '<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".date-row").forEach(function(row) {
        row.addEventListener("click", function() {
            let targetId = this.getAttribute("data-target");
            let targetRow = document.getElementById(targetId);
            let arrow = this.querySelector(".arrow");

            if (targetRow.style.display === "none") {
                targetRow.style.display = "table-row";
                arrow.textContent = "";
            } else {
                targetRow.style.display = "none";
                arrow.textContent = "";
            }
        });
    });
});
</script>
';

echo '</div>';

}
