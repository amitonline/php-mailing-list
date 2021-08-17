
$(document).ready(function () {

		

})

function doValidate(frm) {
 if (document.frmEmail.xemailid.value != null && document.frmEmail.xemailid.value != "")
	document.frmEmail.p.value = 1;
 return true;	
}
function doPaging(p) {
	document.frmEmail.p.value = p;
	document.frmEmail.submit();
}

