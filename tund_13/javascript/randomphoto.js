window.onload = function(){
	//document.getElementById("pic").innerHTML = "<p>Siia tuleb pilt</p> \n";
	setRandomPic();
	document.getElementById("pic").addEventListener("click", setRandomPic);
}

function setRandomPic(){
	//siit alab AJAX
	let req = new XMLHttpRequest();
	req.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//siia kõik see mida javascript teeb serverilt saadud vastusega
			document.getElementById("pic").innerHTML = this.responseText;
		}
	};
	//teeme päringu, määrame aadressi ja parameetrid
	req.open("GET", "randompic.php", true);
	req.send();
	//AJAX lõppes
}