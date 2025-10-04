<div id="header">
  <div id="logo">
    <div id="logo_text">
      <h1><a href="index.php"><span class="logo_colour">Voitekk Assessment Portal</span></a></h1>
      <h2></h2>
    </div>
  </div>
  <div id="menubar">
    <ul id="menu">
      <?php if (!empty($user) && $user instanceof Login): ?>
      <li<?php if (in_array(basename($_SERVER['SCRIPT_NAME']), array('index.php'))) echo ' class="selected"'; ?>>
        <a href="index.php">Home</a>
      </li>
      <li<?php if (in_array(basename($_SERVER['SCRIPT_NAME']), array('user_edit.php', 'users.php'))) echo ' class="selected"'; ?>>
        <a href="users.php">Users</a>
      </li>
      <li<?php if (in_array(basename($_SERVER['SCRIPT_NAME']), array('survey_edit.php', 'surveys.php'))) echo ' class="selected"'; ?>>
        <a href="surveys.php">Assessments</a>
      </li>
      <li>
        <a href="logout.php">Logout</a>
      </li>
      <?php else: ?>
      <li>
        <a href="login.php">Login</a>
      </li>
      <li>
        <a href="companies.php">Manage Companies</a>
      </li>
      <?php endif; ?>
    </ul>
  </div>
</div>
