<?php

# Define page access level:
session_start();
$page_access = 2;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

# include_once graph:
include_once('../inc/phplot/phplot.php');

# Get all invoices:
$get_payments = mysql_query("SELECT method_id, SUM(amount) AS amount_totals FROM payments GROUP BY method_id LIMIT 3");

while($show_payment = mysql_fetch_array($get_payments)) {
$get_payment_methods = mysql_query("SELECT * FROM payment_methods WHERE method_id = " . $show_payment['method_id'] . "");
$show_payment_method = mysql_fetch_array($get_payment_methods);
$graph_data[] = array($show_payment_method['name'], $show_payment['amount_totals']);
}

$plot = new PHPlot(300, 140);
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetDataValues($graph_data);
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');
$plot->SetTitle('Top Payment Methods');
$plot->DrawGraph();

?>