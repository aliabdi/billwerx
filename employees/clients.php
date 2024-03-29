<?php

# Define page access level:
session_start();
$page_access = 2;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

# include_once security POST loop:
include_once("../global/make_safe.php");

# Get company data:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Setup pagination:
# 2009/08/10 RC 5 Corrected undefined variable:
if(isset($_GET['start'])) { $start = $_GET['start']; } else { $start = 0; };
$previous_page = ($start - $_SESSION['records_per_page']);
$next_page = ($start + $_SESSION['records_per_page']);

# Get invoice data:
$get_total_clients = mysql_query("SELECT * FROM clients");
$total_records = mysql_num_rows($get_total_clients);
$get_clients = mysql_query("SELECT * FROM clients ORDER BY client_id DESC LIMIT $start, " . $_SESSION['records_per_page'] . "");

# Start search:
if(isset($_GET['query'])) {
$query = $_GET['query'];
$get_clients = mysql_query("SELECT * FROM clients WHERE (first_name LIKE '%$query%') OR (last_name LIKE '%$query%') OR (company_name LIKE '%$query%') OR (client_id = '$query')");
$total_records = mysql_num_rows($get_clients);
$next_page = $total_records;
};

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Clients</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
<script type="text/javascript" src="../scripts/tooltip.js"></script>
</head>
<body>
<div id="wrap">
  <div id="header"><img src="../global/company_logo.php" alt="<?php echo $show_company['company_name'] ?> - powered by: Billwerx" /></div>
  <div id="logininfo">
    <?php include_once("login_info.php") ?>
  </div>
  <div id="navbar">
    <?php include_once("navbar.php") ?>
  </div>
  <div id="content">
    <form id="clients" name="clients" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="halftopcell"><h1><img src="../images/icons/clients.png" alt="Clients" width="16" height="16" /> Clients:</h1>
          <table class="fulltable">
              <tr>
                <td class="justred">Found <?php echo $total_records ?> record(s).</td>
              </tr>
              <tr>
                <td><input name="query" type="text" class="entrytext" id="query" onclick="this.value=''" value="search query" /></td>
              </tr>
              
              <tr>
                <td><input name="create" type="button" class="button" id="create" onclick="window.location='create_client.php'" value="CREATE" />
                  <input name="categories" type="button" class="button" id="categories" onclick="openWindow('manage_campaigns.php')" value="CAMPAIGNS" />
                  <input name="email" type="button" class="button" id="email" onclick="openWindow('mass_email_clients.php')" value="E-MAIL" />
                  <input name="export" type="button" class="button" id="export" onclick="window.location='export_clients.php'" value="EXPORT" /></td>
              </tr>
          </table></td>
          <td class="halftopcell"><img src="clients_pgraph.php" alt="Top Clients" /></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td width="8%" class="tabletop">&nbsp;</td>
          <td width="8%" class="tabletop">client #:</td>
          <td class="tabletop">full name:</td>
          <td width="24%" class="tabletop">billing address:</td>
          <td width="14%" class="tabletop">primary number:</td>
          <td width="20%" class="tabletop">campaign / spent:</td>
          <td width="6%" class="tabletop">active:</td>
        </tr>
        <?php while($show_client = mysql_fetch_array($get_clients)) { ?>
        <?php $get_campaigns = mysql_query("SELECT * FROM campaigns WHERE campaign_id = " . $show_client['campaign_id'] . ""); ?>
        <?php $show_campaign = mysql_fetch_array($get_campaigns) ?>
        <?php $get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_client['employee_id'] . ""); ?>
        <?php $show_employee = mysql_fetch_array($get_employees) ?>
        <?php $get_invoice_totals = mysql_query("SELECT SUM(total) AS total FROM invoices WHERE client_id = " . $show_client['client_id'] . ""); ?>
        <?php $show_invoice_totals = mysql_fetch_array($get_invoice_totals) ?>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="export_client_vcard.php?client_id=<?php echo $show_client['client_id'] ?>"><img src="../images/icons/vcard.png" alt="Export VCard" width="16" height="16" class="iconspacer" /></a> <a href="javascript:openWindow('show_map.php?client_id=<?php echo $show_client['client_id'] ?>')"><img src="../images/icons/map.png" alt="View Map" width="16" height="16" class="iconspacer" /></a></td>
          <td class="tablerowborder"><a href="invoices.php?client_id=<?php echo strtoupper($show_client['client_id']) ?>"><?php echo strtoupper($show_client['client_id']) ?></a></td>
          <td class="tablerowborder"><a href="update_client.php?client_id=<?php echo $show_client['client_id'] ?>" onmouseover="tooltip(event, '<?php echo $show_client['client_id'] ?>')" onmouseout="tooltip(event, '<?php echo $show_client['client_id'] ?>')"><?php echo strtoupper($show_client['last_name']) ?>, <?php echo $show_client['first_name'] ?></a><br />
            <span class="smalltext"><?php echo $show_client['company_name'] ?><br />
            <a href="mailto:<?php echo $show_client['email_address'] ?>"><?php echo $show_client['email_address'] ?></a></span>
            <div class="tooltip" id="<?php echo $show_client['client_id'] ?>">
              <table>
                <tr>
                  <td><span class="justred"><?php echo strtoupper($show_employee['last_name']) ?> <?php echo $show_employee['first_name'] ?></span><br>
                    <span class="smalltext"><?php echo $show_client['created'] ?></span></td>
                </tr>
              </table>
          </div></td>
          <td class="tablerowborder"><?php echo $show_client['billing_address'] ?><br />
            <span class="smalltext"><?php echo $show_client['billing_city'] ?> <?php echo $show_client['billing_province'] ?> <?php echo $show_client['billing_postal'] ?><br />
            <?php echo $show_client['billing_country'] ?></span></td>
          <td class="tablerowborder"><?php echo $show_client['primary_number'] ?></td>
          <td class="tablerowborder"><?php echo $show_campaign['name'] ?><br />
            <span class="justred"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice_totals['total'], 2) ?></span></td>
          <td class="tablerowborder"><?php echo $show_client['active'] ?></td>
        </tr>
        <?php } ?>
      </table>
      <table class="fulltable">
        <tr>
          <td class="pagination"><?php if ($start > 0) { ?>
            <a href="?start=<?php echo $previous_page ?>"><img src="../images/icons/previous.png" alt="Prevous Page" width="16" height="16" class="iconspacer" /></a>
            <?php } ?>
            <?php if ($next_page < $total_records) { ?>
            <a href="?start=<?php echo $next_page ?>"><img src="../images/icons/next.png" alt="Next Page" width="16" height="16" class="iconspacer" /></a>
            <?php } ?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
