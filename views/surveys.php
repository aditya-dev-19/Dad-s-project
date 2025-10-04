<!doctype html>
<html>
<head>
  <title>Assessments</title>
  <?php include 'stylesheets.php'; ?>
  <?php include 'scripts.php'; ?>
  <script type="text/javascript">
    $(function()
    {
        // Initialize accordion for companies
        $('#companies_accordion').accordion({
            collapsible: true,
            active: false,
            heightStyle: "content"
        });

        // Initialize buttons
        $('#add_survey_button').button();
        $('.edit_survey').button();
        $('.take_survey').button();
        $('.view_charts').button();
    });
  </script>
  <style>
    .company-section {
      margin-bottom: 20px;
    }
    .company-header {
      background-color: #f0f0f0;
      padding: 10px;
      cursor: pointer;
      border: 1px solid #ccc;
      font-weight: bold;
    }
    .company-header:hover {
      background-color: #e0e0e0;
    }
    .survey-count {
      color: #666;
      font-size: 0.9em;
      font-weight: normal;
    }
    .ui-accordion-content {
      padding: 0 !important;
    }
    .ui-accordion-content .grid {
      margin: 0;
    }
    .created-date {
      font-size: 0.85em;
      color: #666;
      font-style: italic;
    }
  </style>
</head>
<body>
  <div id="main">
    <?php include 'header.php'; ?>
    <div id="site_content">
      <?php if (isset($statusMessage)): ?>
        <p class="error"><?php echo htmlspecialchars($statusMessage); ?></p>
      <?php endif; ?>
      <h1>Assessments by Company</h1>
      <div id="content">
        
        <!-- Companies with Surveys -->
        <?php if (!empty($companies)): ?>
          <div id="companies_accordion">
            <?php foreach ($companies as $company): ?>
              <h3><?php echo htmlspecialchars($company->company_name); ?> 
                  <span class="survey-count">(<?php echo count($company->surveys); ?> assessments)</span>
              </h3>
              <div>
                <table class="grid">
                  <tr>
                    <th>Survey Name</th>
                    <th>Created</th>
                    <th>Edit</th>
                    <th>Take Survey</th>
                    <th>View Results</th>
                    <th>View Charts</th>
                  </tr>
                  <?php foreach ($company->surveys as $survey): ?>
                    <tr>
                      <td>
                        <?php echo htmlspecialchars($survey->survey_name); ?>
                      </td>
                      <td class="created-date">
                        <?php 
                        if (!empty($survey->created_at)) {
                          echo date('M j, Y', strtotime($survey->created_at));
                        } else {
                          echo 'N/A';
                        }
                        ?>
                      </td>
                      <td><a class="edit_survey" href="survey_edit.php?survey_id=<?php echo htmlspecialchars($survey->survey_id); ?>">Edit</a></td>
                      <td><a class="take_survey" href="survey_form.php?survey_id=<?php echo htmlspecialchars($survey->survey_id); ?>" target="_blank">Visit page</a></td>
                      <td><a class="take_survey" href="survey_results.php?survey_id=<?php echo htmlspecialchars($survey->survey_id); ?>">View Results</a></td>
                      <td><a class="view_charts" href="survey_charts.php?survey_id=<?php echo htmlspecialchars($survey->survey_id); ?>">View Charts</a></td>
                    </tr>
                  <?php endforeach; ?>
                </table>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p><em>No companies with assessments found</em></p>
        <?php endif; ?>

        <!-- Unassigned Surveys
        <?php if (!empty($unassignedSurveys)): ?>
          <div style="margin-top: 30px;">
            <h2>Unassigned Assessments</h2>
            <table class="grid">
              <tr>
                <th>Survey Name</th>
                <th>Created</th>
                <th>Edit</th>
                <th>Take Survey</th>
                <th>View Results</th>
                <th>View Charts</th>
              </tr>
              <?php foreach ($unassignedSurveys as $survey): ?>
                <tr>
                  <td><?php echo htmlspecialchars($survey->survey_name); ?></td>
                  <td class="created-date">
                    <?php 
                    if (!empty($survey->created_at)) {
                      echo date('M j, Y', strtotime($survey->created_at));
                    } else {
                      echo 'N/A';
                    }
                    ?>
                  </td>
                  <td><a class="edit_survey" href="survey_edit.php?survey_id=<?php echo htmlspecialchars($survey->survey_id); ?>">Edit</a></td>
                  <td><a class="take_survey" href="survey_form.php?survey_id=<?php echo htmlspecialchars($survey->survey_id); ?>" target="_blank">Visit page</a></td>
                  <td><a class="take_survey" href="survey_results.php?survey_id=<?php echo htmlspecialchars($survey->survey_id); ?>">View Results</a></td>
                  <td><a class="view_charts" href="survey_charts.php?survey_id=<?php echo htmlspecialchars($survey->survey_id); ?>">View Charts</a></td>
                </tr>
              <?php endforeach; ?>
            </table>
          </div>
        <?php endif; ?> -->

        <div style="margin-top: 20px;">
          <a id="add_survey_button" href="survey_edit.php">Add Survey</a>
        </div>
      </div>
    </div>
    <?php include 'footer.php'; ?>
  </div>
</body>
</html>