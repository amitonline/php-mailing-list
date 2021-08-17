<?php
/**
 * 
 */
	session_start();
	error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE ^ E_DEPRECATED);
	
	require_once("includes/globals.php");

	$pageTitle = "Mailing List - Home";
	$pageName = "home";


?>
<?php require_once($g_docRoot . "components/header.php"); ?>


  <div class="container mt-3">	
	<div class="jumbotron">
	  <h1 class="display-4">Mailing List!</h1>
	  <p class="lead">We are getting ready... Please drop your email id below to be notified when we go LIVE.</p>
	  <hr class="my-4">
	  <form name=frmEmail id=frmEmail method=post onsubmit="return doValidate(this);">
	  <div class="row">
		<div class="col-md-5 offset-md-2 text-right mt-3">
			<input class="form-control" maxlength=75 name=email id=email type="email"
				placeholder="Enter your email id">		
		</div>
		
		<div class="col-md-2 text-center mt-3">
			<button type=submit class="btn btn-lg btn-secondary" id="btnSubmit">Submit</button>
		</div>
	  </div> <!--row-->
	  <div class="row mt-2" id="rowMsg" style="display:none;">
		<div class="alert alert-success col-md-12 text-center">
			Thank you. Your email id has been added to our mailing list.
		</div>
	  </div> <!--row-->
	 </form>
	</div>


 </div> <!--container-->
 
<?php require_once($g_docRoot . "components/footer.php"); ?>
<?php require_once($g_docRoot . "components/scripts.php"); ?>
<script>
	var WEBROOT = "<?php echo($g_webRoot); ?>";
	
</script>
	<script src="<?php echo($g_webRoot);?>includes/home.js"></script>

</body>
</html>
