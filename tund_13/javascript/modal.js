let modal;
let modalImg;
let captionText;
let closeBtn;
let photoDir = "../vp_pic_uploads/";

window.onload = function(){
	modal = document.getElementById("myModal");
	modalImg = document.getElementById("modalImg");
	captionText = document.getElementById("caption");
	closeBtn = document.getElementsByClassName("close")[0];
	let allThumbs = document.getElementById("gallery").getElementsByTagName("img");
	let thumbCount = allThumbs.length;
	for(let i = 0;i < thumbCount; i ++){
		allThumbs[i].addEventListener("click", openModal);
	}
	closeBtn.addEventListener("click", closeModal);
	modalImg.addEventListener("click", closeModal);
}

function openModal(e){
	console.log(e);
	modalImg.src = photoDir + e.target.dataset.fn;
	captionText.innerHTML = "<p>" + e.target.alt + "</p>";
	modal.style.display = "block";
}

function closeModal(){
	modal.style.display = "none";
}