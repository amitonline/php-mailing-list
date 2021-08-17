	 <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	 <script src="http://code.jquery.com/jquery-migrate-1.1.0.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>

<script>
	var WEBROOT = "<?php echo($g_webRoot);?>";
	
	$(document).ready(function() {
		$('#btnCookieOk').click(function () {
			cookieOk();	
		});
		$('#btnCookieLM').click(function () {
			window.location = "<?php echo($g_webRoot);?>cookies";
		});
	
	});

	function cookieOk() {
		$.ajax({
			 type: "GET",
			 url: WEBROOT + "ajax/cookie-ok.php",
			 data: "",
			 error: function (xhr, status, error) {
				 	alert(xhr +"," + status +"," + error);

			 },
			 success: function(data){
			 		$('#divCookie').hide();
			} // function(data)
		});
	}
		</script>

