$(document).ready(function() {
	$("#tab").DataTable();
});
console.log(document.getElementById("tab").innerHTML);

document.getElementById("close").onclick = function() {
	$('.ui.modal')
	  .modal('hide')
	;
}
