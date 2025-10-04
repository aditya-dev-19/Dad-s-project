<!doctype html>
<html>
<head>
  <title>Manage Companies</title>
  <?php include 'stylesheets.php'; ?>
  <?php include 'scripts.php'; ?>
  <script type="text/javascript">
    $(function()
    {
        $('#add_company_button').button();
        $('.delete_company').button();
        
        $('.delete_company').click(function(e) {
            if (!confirm('Are you sure you want to delete this company? All surveys will be unassigned from this company.')) {
                e.preventDefault();
            }
        });
    });
  </script>
  <style>
    .add-company-form {
      background: #f5f5f5;
      padding: 20px;
      margin: 20px 0;
      border-radius: 4px;
    }
    .add-company-form input[type="text"] {
      padding: 8px;
      width: 300px;
      margin-right: 10px;
    }
  </style>
</head>
<body>
  <div id="main">
    <?php include 'header.php'; ?>
    <div id="site_content">
      <div id="content">
        <?php if (isset($statusMessage)): ?>
          <p class="error"><?php echo htmlspecialchars($statusMessage); ?></p>
        <?php endif; ?>
        
        <h1>Manage Companies</h1>
        
        <div class="add-company-form">
          <h2>Add New Company</h2>
          <form action="companies.php" method="post">
            <input type="hidden" name="action" value="add_company" />
            <input type="text" name="company_name" placeholder="Company Name" required />
            <button id="add_company_button" type="submit">Add Company</button>
          </form>
        </div>

        <h2>Existing Companies</h2>
        <?php if (!empty($companies)): ?>
        <table class="grid">
            <tr>
            <th>Company ID</th>
            <th>Company Name</th>
            <th>Survey Count</th>
            <th>Actions</th>
            </tr>
            <?php foreach ($companies as $company): ?>
            <tr>
                <td><?php echo htmlspecialchars($company->company_id); ?></td>
                <td><?php echo htmlspecialchars($company->company_name); ?></td>
                <td><?php echo $company->survey_count; ?></td>
                <td>
                <form action="companies.php" method="post" style="display: inline;">
                    <input type="hidden" name="action" value="delete_company" />
                    <input type="hidden" name="company_id" value="<?php echo htmlspecialchars($company->company_id); ?>" />
                    <button class="delete_company" type="submit">Delete</button>
                </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
        <p><em>No companies found</em></p>
        <?php endif; ?>

        <div style="margin-top: 20px;">
          <a href="surveys.php" class="ui-button ui-widget ui-corner-all">Back to Surveys</a>
        </div>
      </div>
    </div>
    <?php include 'footer.php'; ?>
  </div>
</body>
</html>