function _(elem) {
	return document.getElementById(elem);
}
var upd = document.querySelectorAll(".update");
var del = document.querySelectorAll(".delete");

for (let i = 0; i < upd.length;i++) {
	upd[i].onclick = function() {
		updateRow(this.parentNode.parentNode, this.value);
	}
}

for (let i = 0; i < del.length; i++) {
	del[i].onclick = function() {
		deleteRow(this.value);
	}
}

function deleteRow(x) {
	var index = x;
	var formdata = new FormData();
	formdata.append("index",index);
	var xhttp;
	if (XMLHttpRequest) {
		xhttp = new XMLHttpRequest();
	}
	else {
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			if (this.responseText != "error") {
				alert("Successfully deleted row(s)");
				location.reload(1);
			}
			else {
				alert("Unable to delete!");
			}
		}
	}
	xhttp.open("POST", "config.php", true);
	xhttp.send(formdata);
}
function updateRow(x, y) {
	var elems = x.querySelectorAll("td");
	var obj = [];
	var index = y;
	cnt = elems.length - 2;
	for (i = 0; i < cnt; i++) {
		obj.push(elems[i].querySelectorAll("input")[0].value);
	}
	var formdata = new FormData();
	formdata.append("values", obj);
	formdata.append("index", index);
	var xhttp;
	if (XMLHttpRequest) {
		xhttp = new XMLHttpRequest();
	}
	else {
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			if (this.responseText != "error") {
				alert("Successfully updated row value(s)!");
			}
			else {
				alert("An error occured!");
			}
		}
	}
	xhttp.open("POST", "config.php", true);
	xhttp.send(formdata);
}

var values_to_add = new Array();

_("insert_pop").onclick = function() {
	$('.ui.modal')
	  .modal('show')
	;
	values_to_add = [];
	var tab_elem = document.querySelectorAll(".tab_titles");
	var cnt = tab_elem.length;
	for (i = 0; i < cnt; i++) {
		_("title_tab").innerHTML += '<th>'+ tab_elem[i].innerHTML +'</th>';
		_("insert_tab").innerHTML += '<td><input class="input_fields"></td>';
	}
}
_("insert").onclick = function() {
	var elems = document.querySelectorAll(".input_fields");
	for (i = 0; i < elems.length; i++) {
		values_to_add.push(elems[i].value);
	}
	insertRow(values_to_add);
}
function insertRow(arr) {
	var formdata = new FormData();
	formdata.append("insert", arr);
	var xhttp;
	if (XMLHttpRequest) {
		xhttp = new XMLHttpRequest();
	}
	else {
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			if (this.responseText != "error") {
				location.reload(1);
			}
			else {
				alert("An error occured!");
			}
		}
	}
	xhttp.open("POST", "config.php", true);
	xhttp.send(formdata);
}
