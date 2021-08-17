
$(document).ready(function () {

});	

function doValidate(frm) {
  var emailValue = frm.email.value;
  if (emailValue == null || emailValue.trim() == "" ||
	  emailValue.indexOf("@") == -1 || emailValue.indexOf(".") == -1) {
  	alert("Email looks invalid");
        return false;
  } 
  addEmail(emailValue);
  return false;
}



	function addEmail(email) {
			$('#btnSubmit').text("Please wait...");
			$('#btnSubmit').prop("disabled", true);
			var postData = "email=" + email;
			$.ajax({
			 type: "POST",
			 url: WEBROOT + "ajax/add-email.php",
			 data: postData,
			 error: function (xhr, status, error) {
				 $('#btnSubmit').text("Submit");
				 $('#btnSubmit').prop("disabled", false);

			 	alert(xhr +"," + status +"," + error);

			 },
			 success: function(data){
			 	 $('#btnSubmit').text("Submit");
				 $('#btnSubmit').prop("disabled", false);
			
				 if (data != "") {
					alert(data);
				 }
				 else {
				    document.frmEmail.email.value = "";
				    $('#rowMsg').show();
				    setTimeout(function() { $('#rowMsg').hide();}, 3000);
				 }

				
			} // function(data)
		});

	}


