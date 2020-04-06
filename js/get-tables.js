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