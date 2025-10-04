<?php
/**
 * Simple fast migration using bulk SQL updates
 * Access via browser: http://localhost/vap/migrate_simple.php
 */

set_time_limit(300);

require_once('controllers/Controller.php');

$config = parse_ini_file('config/Database.ini');
$pdo = new PDO($config['dsn']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo '<html><head><title>Simple Migration</title><style>
body { font-family: Arial, sans-serif; max-width: 900px; margin: 50px auto; padding: 20px; }
.success { color: green; font-weight: bold; }
.info { background: #e3f2fd; padding: 15px; margin: 10px 0; border-left: 4px solid #2196F3; }
.btn { display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px; margin: 10px 5px 0 0; }
</style></head><body>';

echo '<h1>Fast Migration Using SQL</h1>';

try {
    // Count surveys before
    $stmt = $pdo->query("SELECT COUNT(*) FROM survey WHERE company_id IS NULL");
    $beforeCount = $stmt->fetchColumn();
    
    echo '<div class="info">Surveys without company: <strong>' . $beforeCount . '</strong></div>';
    
    // Bulk update queries - much faster than row-by-row
    $updates = array(
        1 => "UPDATE survey SET company_id = 1 WHERE company_id IS NULL AND (survey_name LIKE '%TCS%' OR survey_name LIKE '%Tata%')",
        4 => "UPDATE survey SET company_id = 4 WHERE company_id IS NULL AND survey_name LIKE '%Agilus%'",
        5 => "UPDATE survey SET company_id = 5 WHERE company_id IS NULL AND survey_name LIKE '%Finance%'",
        6 => "UPDATE survey SET company_id = 6 WHERE company_id IS NULL AND survey_name LIKE '%Godrej%'",
        7 => "UPDATE survey SET company_id = 7 WHERE company_id IS NULL AND (survey_name LIKE '%10.Or%' OR survey_name LIKE '%APR%')",
        8 => "UPDATE survey SET company_id = 8 WHERE company_id IS NULL AND survey_name LIKE '%Real Estate%'",
        11 => "UPDATE survey SET company_id = 11 WHERE company_id IS NULL AND (survey_name LIKE '%CredR%' OR survey_name LIKE '%Credr%')",
        12 => "UPDATE survey SET company_id = 12 WHERE company_id IS NULL AND survey_name LIKE '%Dosti%'",
        13 => "UPDATE survey SET company_id = 13 WHERE company_id IS NULL AND survey_name LIKE '%Edge%'",
        14 => "UPDATE survey SET company_id = 14 WHERE company_id IS NULL AND survey_name LIKE '%Fino%'",
        15 => "UPDATE survey SET company_id = 15 WHERE company_id IS NULL AND survey_name LIKE '%Finolex%'",
        16 => "UPDATE survey SET company_id = 16 WHERE company_id IS NULL AND survey_name LIKE '%Finshell%'",
        17 => "UPDATE survey SET company_id = 17 WHERE company_id IS NULL AND (survey_name LIKE '%Gro%' OR survey_name LIKE '%Ugro%')",
        18 => "UPDATE survey SET company_id = 18 WHERE company_id IS NULL AND survey_name LIKE '%HK%'",
        19 => "UPDATE survey SET company_id = 19 WHERE company_id IS NULL AND (survey_name LIKE '%Pru%' OR survey_name LIKE '%IPRU%')",
        20 => "UPDATE survey SET company_id = 20 WHERE company_id IS NULL AND survey_name LIKE '%Indian Express%'",
        21 => "UPDATE survey SET company_id = 21 WHERE company_id IS NULL AND survey_name LIKE '%NHT%'",
        22 => "UPDATE survey SET company_id = 22 WHERE company_id IS NULL AND survey_name LIKE '%Nutrite%'",
        23 => "UPDATE survey SET company_id = 23 WHERE company_id IS NULL AND survey_name LIKE '%Oppo%'",
        24 => "UPDATE survey SET company_id = 24 WHERE company_id IS NULL AND survey_name LIKE '%Gurukrupa%'",
        25 => "UPDATE survey SET company_id = 25 WHERE company_id IS NULL AND survey_name LIKE '%Hiranandani%'",
        26 => "UPDATE survey SET company_id = 26 WHERE company_id IS NULL AND survey_name LIKE '%Kanakia%'",
        27 => "UPDATE survey SET company_id = 27 WHERE company_id IS NULL AND survey_name LIKE '%Kare%'",
        28 => "UPDATE survey SET company_id = 28 WHERE company_id IS NULL AND survey_name LIKE '%Peninsula%'",
        29 => "UPDATE survey SET company_id = 29 WHERE company_id IS NULL AND survey_name LIKE '%Pharande%'",
        30 => "UPDATE survey SET company_id = 30 WHERE company_id IS NULL AND survey_name LIKE '%Piramal%'",
        31 => "UPDATE survey SET company_id = 31 WHERE company_id IS NULL AND survey_name LIKE '%Raymond%'",
        32 => "UPDATE survey SET company_id = 32 WHERE company_id IS NULL AND survey_name LIKE '%Runwal%'",
        33 => "UPDATE survey SET company_id = 33 WHERE company_id IS NULL AND survey_name LIKE '%Week%'",
        34 => "UPDATE survey SET company_id = 34 WHERE company_id IS NULL AND survey_name LIKE '%Toppr%'",
        35 => "UPDATE survey SET company_id = 35 WHERE company_id IS NULL AND survey_name LIKE '%QA%'",
        36 => "UPDATE survey SET company_id = 36 WHERE company_id IS NULL AND survey_name LIKE '%SRL%'",
        37 => "UPDATE survey SET company_id = 37 WHERE company_id IS NULL AND survey_name LIKE '%Savex%'",
        38 => "UPDATE survey SET company_id = 38 WHERE company_id IS NULL AND survey_name LIKE '%Servify%'",
        39 => "UPDATE survey SET company_id = 39 WHERE company_id IS NULL AND survey_name LIKE '%Spenta%'",
        9 => "UPDATE survey SET company_id = 9 WHERE company_id IS NULL AND survey_name LIKE '%Client%'",
        10 => "UPDATE survey SET company_id = 10 WHERE company_id IS NULL AND survey_name LIKE '%Corporate%'",
    );
    
    echo '<h3>Running Updates...</h3>';
    $totalUpdated = 0;
    
    foreach ($updates as $companyId => $sql) {
        $result = $pdo->exec($sql);
        if ($result > 0) {
            // Get company name
            $stmt = $pdo->prepare("SELECT company_name FROM company WHERE company_id = ?");
            $stmt->execute(array($companyId));
            $companyName = $stmt->fetchColumn();
            
            echo '<div class="success">✓ ' . $companyName . ': ' . $result . ' surveys updated</div>';
            $totalUpdated += $result;
        }
    }
    
    // Count surveys after
    $stmt = $pdo->query("SELECT COUNT(*) FROM survey WHERE company_id IS NULL");
    $afterCount = $stmt->fetchColumn();
    
    echo '<div class="info">';
    echo '<h3>Migration Complete!</h3>';
    echo '<p><strong>Total surveys updated:</strong> ' . $totalUpdated . '</p>';
    echo '<p><strong>Surveys still unassigned:</strong> ' . $afterCount . '</p>';
    echo '</div>';
    
    // Show breakdown
    echo '<h3>Surveys by Company</h3>';
    $stmt = $pdo->query("SELECT c.company_name, COUNT(s.survey_id) as count 
                         FROM company c 
                         LEFT JOIN survey s ON c.company_id = s.company_id 
                         WHERE s.survey_id IS NOT NULL
                         GROUP BY c.company_id, c.company_name 
                         ORDER BY count DESC");
    
    echo '<table border="1" cellpadding="8" style="border-collapse: collapse; width: 50%;">';
    echo '<tr><th>Company</th><th>Survey Count</th></tr>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr><td>' . htmlspecialchars($row['company_name']) . '</td><td>' . $row['count'] . '</td></tr>';
    }
    echo '</table>';
    
} catch (Exception $e) {
    echo '<div style="color: red; background: #ffebee; padding: 15px; border-left: 4px solid red;">';
    echo '<strong>Error:</strong> ' . htmlspecialchars($e->getMessage());
    echo '</div>';
}

echo '<p><a href="surveys.php" class="btn">Go to Surveys Page</a></p>';
echo '</body></html>';

?>