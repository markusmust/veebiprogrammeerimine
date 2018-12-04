//window.alert("Jumal küll, see töötab!");
//console.log("Tõesti töötab!");

window.onload = function(){
  	document.getElementById("submitPic").disabled = true;
	document.getElementById("fileToUpload").addEventListener("change", checkSize);
}

function checkSize(){
	let fileToUpload = document.getElementById("fileToUpload").files[0];
	if(fileToUpload.size <= 2500000){
		document.getElementById("submitPic").disabled = false;
		document.getElementById("infoPlace").innerHTML = "Fail on sobiva suurusega!";
	} else {
		document.getElementById("infoPlace").innerHTML = "Kahjuks valisite liiga suure faili!";
	}
}