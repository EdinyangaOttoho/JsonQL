$(document).ready(function() {
	$("#tab").DataTable();
});
$('.ui.accordion')
  .accordion()
;
document.getElementById("close").onclick = function() {
	$('.ui.modal')
	  .modal('hide')
	;
}