var sl = false;
document.getElementById("slideout").onclick = function() {
	sl = !sl;
	if (sl == true) {
		document.querySelectorAll(".nav")[0].style.left = "0px";
		document.querySelectorAll(".nav")[0].style.width = "100vw";
		document.querySelectorAll(".main")[0].style.width = "100vw";
		document.querySelectorAll(".main")[0].style.left = "0px";
		document.querySelectorAll(".side_bar")[0].style.left = "-250px";
	}
	else {
		document.querySelectorAll(".nav")[0].style.left = "250px";
		document.querySelectorAll(".nav")[0].style.width = "calc(100vw - 250px)";
		document.querySelectorAll(".main")[0].style.width = "calc(100vw - 250px)";
		document.querySelectorAll(".main")[0].style.left = "250px";
		document.querySelectorAll(".side_bar")[0].style.left = "0px";
	}
}