<?php
	error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE ^ E_DEPRECATED);
	session_start();
	$pageName = "emails";
	$pageTitle = "Admin- Browse Email ids";

	require_once("../includes/globals.php");
	require_once($g_docRoot . "classes/emails.php");

	$emails = new Emails($g_docRoot, $g_connServer, $g_connUserid, $g_connPwd, $g_connDBName);

	if ($_SESSION["admin_id"] != "1") {
		header("Location: index.php");
		exit();
	}

	define("MAXROWSPERPAGE", 5);
	define("MAXPAGELINKS", 10);
	
	// get params
	$emailid = $_GET["xemailid"];

	$rowCount = $emails->getCount($emailid);
        	
	// do paging logic
	$nStartPage = $_GET["p"];
	if (!$nStartPage || $nStartPage == 0)
		$nStartPage = 1;
		
	$nPages = 0;
	$nPageCount = intval($rowCount) / intval(MAXROWSPERPAGE);
	$nPageCount = intval($nPageCount);
	if ($nPageCount * intval(MAXROWSPERPAGE) < $rowCount)
		$nPageCount++;
	$sPageLinks = "";
	if ($nPageCount > 1) {
		if ($nPageCount < MAXPAGELINKS) {
		  $maxLinks= $nPageCount;
		  $startPoint = 1;
	    } else {
		  $startPoint = ((int)($nStartPage / MAXPAGELINKS) * MAXPAGELINKS)+1;
		  if ($startPoint < 1)
		  	$startPoint = 1;
		  $maxLinks = ($startPoint + MAXPAGELINKS);
		  if ($maxLinks > $nPageCount) {
		  	$maxLinks = $nPageCount;
			$nextSetFrom = null;
		  } else {
			  $nextSetFrom = $maxLinks;
		  }
		
		}
		if ($nStartPage >= MAXPAGELINKS) {
			$sPageLinks .=  "<button type='button' class='btn btn-default'  onclick=\"doPaging(" . ($startPoint - MAXPAGELINKS) . ");\">" . "<< Prev " . MAXPAGELINKS . " pages</button>&nbsp;";

		}
		for($i = $startPoint; $i <= $maxLinks; $i++) {
			if ($i == $nStartPage)
				$sPageLinks = $sPageLinks . "<button type='button' class='btn btn-primary' onclick=\"doPaging(" . $i . ");\">" . $i . "</button>&nbsp;";
			else
				$sPageLinks = $sPageLinks . "<button type='button' class='btn btn-default'  onclick=\"doPaging(" . $i . ");\">" . $i . "</button>&nbsp;";
		}
		if ($nextSetFrom != null) {
			$sPageLinks .=  "<button type='button' class='btn btn-default'  onclick=\"doPaging(" . $nextSetFrom . ");\">" . "Next " . MAXPAGELINKS . " pages >></button>&nbsp;";
		}
	}

	$nStartRec = 0;
	if ($nStartPage == 0)
		$nStartRec = 0;
	else
		$nStartRec = (intval(MAXROWSPERPAGE) * ($nStartPage-1));

	$rows = $emails->getList($emailid, $nStartRec, MAXROWSPERPAGE, $sort);
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
                        <h3 class="panel-title">BROWSE EMAIL IDS</h3>
                    </div>
		    <div class="panel-body ">
			<div class="row">
			   <div class="col-md-6 offset-md-3 text-center">
				   <button type=button onclick="window.location='logout.php';"
					class="btn btn-sm btn-secondary mr-2">Logout</button>
				   <button type=button onclick="window.location='dashboard.php';" 
					class="btn btn-sm btn-secondary">Dashboard</button>
			   </div>
			</div>
                    </div> <!--panel-body-->
		</div> <!--panel-->
	    </div> <!--col-md-6-->
	</div>	<!--row-->

	 <div class="row mt-4">
	   <div class="col-md-6 offset-md-3 text-center">
			<form name=frmEmail id=frmEmail onsubmit="return doValidate(this);">
			 <div class="row">
				<input type=hidden name=p id=p value="<?php echo($_GET["p"]); ?>">

					<div class="col-md-6">
					  <input name=xemailid id=xemailid class="form-control" maxlength=75
						placeholder="Emailid " value="<?php echo($emailid);?>">
					</div>
					<div class="col-sm-3 text-left">
						<button class="btn btn-default">Submit</button>
					</div>

			 </div>
			</form>
                       <div class="panel panel-default mt-2">
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th class="col-sm-5">Email (<?php echo($rowCount);?>)</th>
                                    </tr>
                                </thead>
                                <tbody>
				<?php 
				   foreach($rows as $row) {
				 ?>
             			      <tr>
				     <td>
					    <div class="col-sm-5 text-left">
						<?php echo($row["email"]);?>
					     </div>
					 </td>
                                    </tr>
				<?php
					} 
				?>
				</tbody>
			  </table>

			   <div class="col-sm-12 text-right">
				<?php echo($sPageLinks); ?>
			   </div>
			</div> <!--panel-body-->
	    	   </div> <!--panel-->	
		</div> <!--row-->		
				
            </div> <!--col-md-6-->
        </div> <!--row-->
    </div> <!--container-->
	<?php require_once($g_docRoot . "components/admin-scripts.php"); ?>

	<script src="../includes/admin-emails.js"></script>
	

</body>

</html>
