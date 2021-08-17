<?php
	error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE ^ E_DEPRECATED);
	session_start();

	$pageName = "dashboard";
	$pageTitle = "Admin Dashboard";
	
	require_once("../includes/globals.php");

	if ($_SESSION["admin_id"] != "1") {
		header("Location: index.php");
		exit();
	}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <title><?php echo($pageTitle); ?></title>

<?php require_once($g_docRoot . "components/admin-header.php"); ?>
<?php require_once($g_docRoot . "components/admin-styles.php"); ?>
</head>



<body>
   <div class="container mt-3">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        <h3 class="panel-title">ADMINISTRATION DASHBOARD</h3>
                    </div>
		    <div class="panel-body ">
			<div class="row">
			   <div class="col-md-6 offset-md-3 text-center">
				   <button type=button onclick="window.location='logout.php';"
					class="btn btn-sm btn-secondary mr-2">Logout</button>
				   <button type=button onclick="window.location='emails.php';" 
					class="btn btn-sm btn-secondary">Email Ids</button>
			   </div>
			</div>
                    </div>
                </div>

				<div class="clearfix"></div><br>
				
            </div> <!--col-md-6-->
        </div> <!--row-->
    </div> <!--container-->
	<?php require_once($g_docRoot . "components/admin-scripts.php"); ?>


</body>

</html>
