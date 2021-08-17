	
	<div class="footer">
		<div class="col-sm-8">
		</div>

		<div class="col-sm-4 text-right">
		</div>
	</div>

<?php

	// store current url in session and output it to javascript also

	$_SESSION["current_url"] = $_SERVER['REQUEST_URI'];

	echo("<script>\n var current_url='" . $_SESSION["current_url"] . "';\n</script>\n");
?>


