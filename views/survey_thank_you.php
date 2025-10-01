<!doctype html>
<html>
<head>
  <title>Thank You</title>
  <?php include 'stylesheets.php'; ?>
  <?php include 'scripts.php'; ?>
</head>
<body>
  <div id="main">
    <?php $title = htmlspecialchars($survey->survey_name); ?>
    <?php $subtitle = 'Submitted!'; ?>
    <?php include 'public_header.php'; ?>
    <div id="site_content">
      <h1>Thank you for completing the assessment!</h1>
      <div id="content">
        <p>Thank you for taking the time to complete the assessment.</p>
      </div>
    </div>
    <?php include 'footer.php'; ?>
  </div>
</body>
</html>
