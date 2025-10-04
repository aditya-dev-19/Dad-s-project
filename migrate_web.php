<?php
/**
 * Web-based migration script to assign company_id to existing surveys
 * Access via browser: http://localhost/vap/migrate_web.php
 */

// Increase execution time
set_time_limit(300);
ini_set('max_execution_time', 300);

// Prevent running twice accidentally
session_start();
if (isset($_SESSION['migration_completed']) && !isset($_GET['force'])) {
    die('<h1>Migration Already Completed!</h1><p>This script has already been run. <a href="?force=1">Click here to run again</a> or <a href="surveys.php">Go to Surveys</a></p>');
}

require_once('controllers/Controller.php');

// Database configuration
$config = parse_ini_file('config/Database.ini');
$pdo = new PDO($config['dsn']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo '<html><head><title>Survey Migration</title><style>
body { font-family: Arial, sans-serif; max-width: 1200px; margin: 50px auto; padding: 20px; }
h1 { color: #333; }
.success { color: green; }
.error { color: red; }
.info { color: blue; }
table { width: 100%; border-collapse: collapse; margin: 20px 0; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
th { background-color: #f2f2f2; position: sticky; top: 0; }
.summary { background: #f0f0f0; padding: 20px; margin: 20px 0; border-left: 4px solid #333; }
.btn { display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px; margin: 5px; }
</style></head><body>';

echo '<h1>Survey Migration to Company Structure</h1>';
flush();
ob_flush();

// Company name patterns
$companyPatterns = array(
    1 => array('TCS', 'Tata'),
    4 => array('AGILUS', 'Agilus'),
    5 => array('1 Finance', '1Finance', '1 Finance'),
    6 => array('GODREJ', 'Godrej'),
    7 => array('10.Or', '10.OR', 'APR'),
    8 => array('Real Estate', 'REAL ESTATE', 'Basics of Real Estate'),
    11 => array('CredR', 'CREDR', 'Credr'),
    12 => array('DOSTI', 'Dosti'),
    13 => array('Edge Academy', 'EDGE ACADEMY'),
    14 => array('FINO', 'Fino'),
    15 => array('FINOLEX', 'Finolex'),
    16 => array('FINSHELL', 'Finshell'),
    17 => array('GRO-X', 'UGRO', 'U Gro', 'Ugro', 'Urgo'),
    18 => array('HK Jewels', 'HK'),
    19 => array('I-Pru', 'IPRU', 'I_Pru', 'Ipru'),
    20 => array('Indian Express'),
    21 => array('NHT'),
    22 => array('NUTRITE', 'Nutrite'),
    23 => array('OPPO', 'Oppo'),
    24 => array('GURUKRUPA', 'Gurukrupa'),
    25 => array('HIRANANDANI', 'Hiranandani'),
    26 => array('KANAKIA', 'Kanakia'),
    27 => array('KARE', 'Kare'),
    28 => array('PENINSULA', 'Peninsula'),
    29 => array('PHARANDE', 'Pharande'),
    30 => array('PIRAMAL', 'Piramal'),
    31 => array('RAYMOND', 'Raymond'),
    32 => array('RUNWAL', 'Runwal'),
    33 => array('The Week', 'WEEK'),
    34 => array('TOPPR', 'Toppr'),
    35 => array('QA'),
    36 => array('SRL'),
    37 => array('SAVEX', 'Savex'),
    38 => array('SERVIFY', 'Servify'),
    39 => array('SPENTA', 'Spenta'),
    9 => array('CLIENT', 'Client'),
    10 => array('CORPORATE', 'Corporate'),
);

// Get company names for display
$companyNames = array();
$stmt = $pdo->query("SELECT company_id, company_name FROM company ORDER BY company_id");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $companyNames[$row['company_id']] = $row['company_name'];
}

// Get all surveys without company_id
$stmt = $pdo->query("SELECT survey_id, survey_name FROM survey WHERE company_id IS NULL ORDER BY survey_id");
$surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalSurveys = count($surveys);
echo '<p class="info">Found ' . $totalSurveys . ' surveys to process...</p>';
flush();
ob_flush();

echo '<table>';
echo '<tr><th>Survey ID</th><th>Survey Name</th><th>Assigned Company</th><th>Status</th></tr>';
flush();
ob_flush();

$updateCount = 0;
$notFoundCount = 0;
$processed = 0;

foreach ($surveys as $survey) {
    $surveyName = $survey['survey_name'];
    $surveyId = $survey['survey_id'];
    $companyFound = false;
    
    echo '<tr>';
    echo '<td>' . htmlspecialchars($surveyId) . '</td>';
    echo '<td>' . htmlspecialchars($surveyName) . '</td>';
    
    // Try to match survey name with company patterns
    foreach ($companyPatterns as $companyId => $patterns) {
        foreach ($patterns as $pattern) {
            if (stripos($surveyName, $pattern) !== false) {
                // Update survey with company_id
                $updateStmt = $pdo->prepare("UPDATE survey SET company_id = ? WHERE survey_id = ?");
                $updateStmt->execute(array($companyId, $surveyId));
                
                $companyName = isset($companyNames[$companyId]) ? $companyNames[$companyId] : "ID: $companyId";
                echo '<td>' . htmlspecialchars($companyName) . '</td>';
                echo '<td class="success">✓ Updated</td>';
                $updateCount++;
                $companyFound = true;
                break 2;
            }
        }
    }
    
    if (!$companyFound) {
        echo '<td>-</td>';
        echo '<td class="error">✗ No match</td>';
        $notFoundCount++;
    }
    
    echo '</tr>';
    
    $processed++;
    
    // Flush output every 50 records to show progress
    if ($processed % 50 == 0) {
        echo '<tr><td colspan="4" style="background: #ffffcc; text-align: center;">Processed ' . $processed . ' of ' . $totalSurveys . '...</td></tr>';
        flush();
        ob_flush();
    }
}

echo '</table>';

echo '<div class="summary">';
echo '<h2>Migration Summary</h2>';
echo '<p><strong>Total surveys processed:</strong> ' . $totalSurveys . '</p>';
echo '<p><strong>Surveys updated:</strong> <span class="success">' . $updateCount . '</span></p>';
echo '<p><strong>Surveys not matched:</strong> <span class="error">' . $notFoundCount . '</span></p>';
echo '<p class="info">Unmatched surveys can be assigned manually through the survey edit page.</p>';

// Show breakdown by company
echo '<h3>Surveys by Company</h3>';
$stmt = $pdo->query("SELECT c.company_name, COUNT(s.survey_id) as count 
                     FROM company c 
                     LEFT JOIN survey s ON c.company_id = s.company_id 
                     GROUP BY c.company_id, c.company_name 
                     HAVING count > 0
                     ORDER BY count DESC");
echo '<table style="width: 50%;">';
echo '<tr><th>Company</th><th>Survey Count</th></tr>';
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<tr><td>' . htmlspecialchars($row['company_name']) . '</td><td>' . $row['count'] . '</td></tr>';
}
echo '</table>';

echo '</div>';

echo '<p><a href="surveys.php" class="btn">Go to Surveys Page</a>';
echo '<a href="?force=1" class="btn" style="background: #ff9800;">Run Migration Again</a></p>';

echo '</body></html>';

// Mark migration as completed
$_SESSION['migration_completed'] = true;

?>