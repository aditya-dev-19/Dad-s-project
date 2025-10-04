<?php

require_once('controllers/Controller.php');

/**
 * Display a list of companies and allow managing them
 */
$controller = new CompaniesController();
$controller->display();

?>