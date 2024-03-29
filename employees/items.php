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
$get_total_items = mysql_query("SELECT * FROM items");
$total_records = mysql_num_rows($get_total_items);
$get_items = mysql_query("SELECT * FROM items ORDER BY item_id DESC LIMIT $start, " . $_SESSION['records_per_page'] . "");

# Start search:
if(isset($_GET['query'])) {
$query = $_GET['query'];
$get_items = mysql_query("SELECT * FROM items WHERE description LIKE '%$query%'");
$total_records = mysql_num_rows($get_items);
$next_page = $total_records;
};

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Items</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
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
    <form id="items" name="items" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="halftopcell"><h1><img src="../images/icons/items.png" alt="Items" width="16" height="16" /> Items:</h1>
          <table class="fulltable">
              <tr>
                <td class="justred">Found <?php echo $total_records ?> record(s).</td>
              </tr>
              <tr>
                <td><input name="query" type="text" class="entrytext" id="query" onclick="this.value=''" value="search query" /></td>
              </tr>
              
              <tr>
                <td><input name="create" type="button" class="button" id="create" onclick="openWindow('create_item.php')" value="CREATE" />
                  <input name="categories" type="button" class="button" id="categories" onclick="openWindow('manage_item_categories.php')" value="CATEGORIES" />
                  <input name="export" type="button" class="button" id="export" onclick="window.location='export_items.php'" value="EXPORT" /></td>
              </tr>
          </table></td>
          <td class="halftopcell"><img src="items_pgraph.php" alt="Top Sales Volume By Category" /></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td width="8%" class="tabletop">&nbsp;</td>
          <td class="tabletop">description:</td>
          <td width="18%" class="tabletop">category / item #:</td>
          <td width="10%" class="tabletop">cost / price:</td>
          <td width="8%" class="tabletop">markup:</td>
          <td width="8%" class="tabletop">profit:</td>
          <td width="6%" class="tabletop">active:</td>
        </tr>
        <?php while($show_item = mysql_fetch_array($get_items)) { ?>
        <?php $get_item_categories = mysql_query("SELECT * FROM item_categories WHERE category_id = " . $show_item['category_id'] . ""); ?>
        <?php $show_item_category = mysql_fetch_array($get_item_categories) ?>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="delete_item.php?item_id=<?php echo $show_item['item_id'] ?>" onClick="return confirm('Delete record #: <?php echo $show_item['item_id'] ?> (<?php echo $show_item['name'] ?>)?')"><img src="../images/icons/delete.png" alt="Delete Item" width="16" height="16" class="iconspacer" /></a></td>
          <td class="tablerowborder"><a href="javascript:openWindow('update_item.php?item_id=<?php echo $show_item['item_id'] ?>')"><?php echo $show_item['name'] ?></a><br />
            <span class="smalltext"><?php echo $show_item['description'] ?></span></td>
          <td class="tablerowborder"><?php echo $show_item_category['name'] ?><br />
            <span class="smalltext"><?php echo $show_item['item_id'] ?></span></td>
          <td class="tablerowborder"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_item['cost'], 2) ?><br />
            <span class="smalltext"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_item['price'], 2) ?></span></td>
          <td class="tablerowborder"><?php echo number_format($show_item['markup'], 2) ?>%</td>
          <td class="tablerowborder"><span class="justred"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_item['profit'], 2) ?></span></td>
          <td class="tablerowborder"><?php echo $show_item['active'] ?></td>
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
