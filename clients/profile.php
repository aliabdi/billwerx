<?php

# Define page access level:
session_start();

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Include security POST loop:
include("../global/make_safe.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get client data:
$client_id = $_SESSION['client_id'];
$get_client = mysql_query("SELECT * FROM clients WHERE client_id = '$client_id'");
$show_client = mysql_fetch_array($get_client);

$get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_client['employee_id'] . "");
$show_employee = mysql_fetch_array($get_employees);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['update'])) {

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$company_name = $_POST['company_name'];
$home_number = $_POST['home_number'];
$work_number = $_POST['work_number'];
$mobile_number = $_POST['mobile_number'];
$fax_number = $_POST['fax_number'];
$email_address = $_POST['email_address'];

$billing_email_address = $_POST['billing_email_address'];
$account_password = $_POST['account_password'];

$billing_address = $_POST['billing_address'];
$billing_city = $_POST['billing_city'];
$billing_province = $_POST['billing_province'];
$billing_postal = $_POST['billing_postal'];
$billing_country = $_POST['billing_country'];

$shipping_address = $_POST['shipping_address'];
$shipping_city = $_POST['shipping_city'];
$shipping_province = $_POST['shipping_province'];
$shipping_postal = $_POST['shipping_postal'];
$shipping_country = $_POST['shipping_country'];

$client_id = $_SESSION['client_id'];

# Assign values to a database table:
$doSQL = "UPDATE clients SET first_name = '$first_name', last_name = '$last_name', company_name = '$company_name', home_number = '$home_number', work_number = '$work_number', mobile_number = '$mobile_number', fax_number = '$fax_number', email_address = '$email_address', billing_email_address = '$billing_email_address', account_password = '$account_password', billing_address = '$billing_address', billing_city = '$billing_city', billing_province = '$billing_province', billing_postal = '$billing_postal', billing_country = '$billing_country', shipping_address = '$shipping_address', shipping_city = '$shipping_city', shipping_province = '$shipping_province', shipping_postal = '$shipping_postal', shipping_country = '$shipping_country' WHERE client_id = '$client_id'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Return to screen:
header("Location: profile.php");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Profile</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body>
<div id="wrap">
  <div id="header">
    <h1><?php echo $show_client['first_name'] ?> <?php echo $show_client['last_name'] ?></h1>
    <h3>Record created <?php echo $show_client['created'] ?>.</h3>
  </div>
  <div id="logininfo">
    <?php include_once("login_info.php") ?>
  </div>
  <div id="navbar">
    <?php include_once("navbar.php") ?>
  </div>
  <div id="content">
    <form id="update_client" name="update_client" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="halftopcell"><h2>Contact: </h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">first name:</td>
                <td><input name="first_name" type="text" class="entrytext" id="first_name" value="<?php echo $show_client['first_name'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">last name:</td>
                <td><input name="last_name" type="text" class="entrytext" id="last_name" value="<?php echo $show_client['last_name'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">company name:</td>
                <td><input name="company_name" type="text" class="entrytext" id="company_name" value="<?php echo $show_client['company_name'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">home number:</td>
                <td><input name="home_number" type="text" class="entrytext" id="home_number" onblur="formatNumber(this)" value="<?php echo $show_client['home_number'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">work number:</td>
                <td><input name="work_number" type="text" class="entrytext" id="work_number" onblur="formatNumber(this)" value="<?php echo $show_client['work_number'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">mobile number:</td>
                <td><input name="mobile_number" type="text" class="entrytext" id="mobile_number" onblur="formatNumber(this)" value="<?php echo $show_client['mobile_number'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">fax number:</td>
                <td><input name="fax_number" type="text" class="entrytext" id="fax_number" onblur="formatNumber(this)" value="<?php echo $show_client['fax_number'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">e-mail address:</td>
                <td><input name="email_address" type="text" class="entrytext" id="email_address" value="<?php echo $show_client['email_address'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">account password:</td>
                <td><input name="account_password" type="text" class="entrytext" id="account_password" value="<?php echo $show_client['account_password'] ?>" /></td>
              </tr>
            </table>
            <h2>Financials: </h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">billing e-mail address:<br />
                  <a href="javascript:copyEmail()">copy e-mail address</a><br /></td>
                <td><input name="billing_email_address" type="text" class="entrytext" id="billing_email_address" value="<?php echo $show_client['billing_email_address'] ?>" /></td>
              </tr>
          </table></td>
          <td class="halftopcell"><h2>Billing: </h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">billing address:<br />
                  <a href="javascript:copyShipping()">copy shipping information</a></td>
                <td><input name="billing_address" type="text" class="entrytext" id="billing_address" value="<?php echo $show_client['billing_address'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing city:</td>
                <td><input name="billing_city" type="text" class="entrytext" id="billing_city" value="<?php echo $show_client['billing_city'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing province:</td>
                <td><input name="billing_province" type="text" class="entrytext" id="billing_province" value="<?php echo $show_client['billing_province'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing postal:</td>
                <td><input name="billing_postal" type="text" class="entrytext" id="billing_postal" value="<?php echo $show_client['billing_postal'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing country:</td>
                <td><input name="billing_country" type="text" class="entrytext" id="billing_country" value="<?php echo $show_client['billing_country'] ?>" /></td>
              </tr>
            </table>
            <h2>Shipping: </h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">shipping address:<br />
                  <a href="javascript:copyBilling()">copy billing information</a></td>
                <td><input name="shipping_address" type="text" class="entrytext" id="shipping_address" value="<?php echo $show_client['shipping_address'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping city:</td>
                <td><input name="shipping_city" type="text" class="entrytext" id="shipping_city" value="<?php echo $show_client['shipping_city'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping province:</td>
                <td><input name="shipping_province" type="text" class="entrytext" id="shipping_province" value="<?php echo $show_client['shipping_province'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping postal:</td>
                <td><input name="shipping_postal" type="text" class="entrytext" id="shipping_postal" value="<?php echo $show_client['shipping_postal'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping country:</td>
                <td><input name="shipping_country" type="text" class="entrytext" id="shipping_country" value="<?php echo $show_client['shipping_country'] ?>" /></td>
              </tr>
            </table></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td><input name="update" type="submit" class="button" id="update" value="UPDATE" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
