<?php
/**
 * Migration script to add timestamp columns
 * Run this once via browser: http://localhost/your-app/run_timestamp_migration.php
 */

require_once('controllers/Controller.php');

$config = parse_ini_file('config/Database.ini');
$pdo = new PDO($config['dsn']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo '<html><head><title>Timestamp Migration</title><style>
body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
.success { color: green; font-weight: bold; }
.error { color: red; font-weight: bold; }
.info { background: #e3f2fd; padding: 15px; margin: 10px 0; border-left: 4px solid #2196F3; }
</style></head><body>';

echo '<h1>Timestamp Migration</h1>';

try {
    // Check if column already exists
    $stmt = $pdo->query("PRAGMA table_info(survey)");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $hasCreatedAt = false;
    
    foreach ($columns as $column) {
        if ($column['name'] === 'created_at') {
            $hasCreatedAt = true;
            break;
        }
    }
    
    if ($hasCreatedAt) {
        echo '<div class="info">✓ Column <code>created_at</code> already exists in survey table.</div>';
    } else {
        echo '<p>Adding <code>created_at</code> column to survey table...</p>';
        
        // Add created_at column
        $pdo->exec("ALTER TABLE survey ADD COLUMN created_at TEXT");
        echo '<div class="success">✓ Column added successfully!</div>';
        
        // Set default value for existing surveys
        $pdo->exec("UPDATE survey SET created_at = datetime('now') WHERE created_at IS NULL");
        echo '<div class="success">✓ Set timestamps for existing surveys!</div>';
    }
    
    // Verify survey_response has time_taken
    $stmt = $pdo->query("PRAGMA table_info(survey_response)");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $hasTimeTaken = false;
    
    foreach ($columns as $column) {
        if ($column['name'] === 'time_taken') {
            $hasTimeTaken = true;
            break;
        }
    }
    
    if ($hasTimeTaken) {
        echo '<div class="info">✓ Column <code>time_taken</code> already exists in survey_response table.</div>';
    } else {
        echo '<div class="error">⚠ Warning: survey_response table is missing time_taken column!</div>';
    }
    
    // Show sample data
    echo '<h2>Sample Survey Data</h2>';
    $stmt = $pdo->query("SELECT survey_id, survey_name, created_at FROM survey LIMIT 5");
    echo '<table border="1" cellpadding="8" style="border-collapse: collapse;">';
    echo '<tr><th>Survey ID</th><th>Survey Name</th><th>Created At</th></tr>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['survey_id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['survey_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    
    echo '<div class="success"><h2>✓ Migration Completed Successfully!</h2></div>';
    echo '<p><a href="surveys.php" style="display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">Go to Surveys</a></p>';
    
} catch (Exception $e) {
    echo '<div class="error">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

echo '</body></html>';
?>