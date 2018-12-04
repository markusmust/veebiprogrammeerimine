<?php

	class Photoupload
	{
		
		private $myTempImage;
		private $imageFileType;
		private $tempName;
		private $myImage;
		
		public function __construct($file, $type){
			$this->tempName = $file;
			$this->imageFileType = $type;
			$this->imageFromFile();
			
		}
			
			
		public function __destruct(){
			imagedestroy($this->myTempImage);
			imagedestroy($this->myImage);
		}
		
		public function readExif(){
	  if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
		@$exif = exif_read_data($this->tempName, "ANY_TAG", 0, true);
		$this->photoDate = $exif["DateTimeOriginal"];
	  }
    }
		
		/* private function changePicSize($image, $ow, $oh, $w,  $h){
			$newImage = imagecreatetruecolor($w, $h);
			//säilitan osade piltitde läbipaistvuse
			imagesavealpha($newImage, true);
			$transColor = imagecolorallocatealpha($newImage, 0,0,0, 127);
			imagefill($newImage, 0, 0, $transColor);
			
		} */
		
		private function imageFromFile(){
			//loome vastavalt failitüübile pildiobjekti
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
				$this->myTempImage = imagecreatefromjpeg($this->tempName);
			}
			if($this->imageFileType == "png"){
				$this->myTempImage = imagecreatefrompng($this->tempName);
			}
			if($this->imageFileType == "gif"){
				$this->myTempImage = imagecreatefromgif($this->tempName);
			}
		}
		
		public function changePhotoSize($width, $height){
			$imageWidth = imagesx($this->myTempImage);
			$imageHeight = imagesy($this->myTempImage);
			//arvutan suuruse suhtarvu
			if($imageWidth > $imageHeight){
				$sizeRatio = $imageWidth / $width;
			} else {
				$sizeratio = $imageHeight / $height;
			}
			
			$newWidth = round($imageWidth / $sizeRatio);
			$newHeight = round($imageHeight / $sizeRatio);
			
			$this->myImage = $this->resizeImage($this->myTempImage,$imageWidth, $imageHeight, $newWidth, $newHeight );
		}
		
		private function resizeimage($image, $ow, $oh, $w, $h){
		  $newImage = imagecreatetruecolor($w, $h);
		  imagecopyresampled($newImage, $image, 0, 0, 0, 0, $w, $h, $ow, $oh);
		  return $newImage;
	    }
		
		public function addWaterMark(){
			//lisan vesimärgi
			$waterMark = imagecreatefrompng("../vp_picfiles/vp_logo_w100_overlay.png");
			$waterMarkWidth = imagesx($waterMark);
			$waterMarkHeight = imagesy($waterMark);
			$waterMarkPosX = imagesx($this->myImage) - $waterMarkWidth - 10;
			$waterMarkPosY = imagesy($this->myImage) - $waterMarkHeight - 10;
			
			imagecopy($this->myImage, $waterMark, $waterMarkPosX, $waterMarkPosY, 0, 0, $waterMarkWidth, $waterMarkHeight);
		}
		
		public function addText(){
				//lisame teksti
				$textToImage = "Veebiprogrammeermine";
				$textColor = imagecolorallocatealpha($this->myImage, 255,255,255, 60);
				imagettftext($this->myImage, 20, 0, 10, 30, $textColor, "../vp_picfiles/ARIALBD.TTF", $textToImage);
			}

			public function createThumbnail($directory, $size){
			$imageWidth = imagesx($this->myTempImage);
			$imageHeight = imagesy($this->myTempImage);
			if($imageWidth > $imageHeight){
				$cutSize = $imageHeight;
				$cutX = round(($imageWidth - $cutSize) / 2);
				$cutY = 0;
			} else {
				$cutsize = $imageWidth;
				$cutX = 0;
				$cutY = round(($imageheight	- $cutSize) / 2);	
			}
			$myThumbnail = imagecreatetruecolor($size, $size);
			
			imagecopyresampled($myThumbnail, $this->myTempImage, 0, 0, $cutX, $cutY, $size, $size, $cutSize, $cutSize);
			
			$target_file = $directory .$this->fileName;
			
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
				imagejpeg($myThumbnail, $target_file, 95);				
			}
			if($this->imageFileType == "png"){
				imagepng($myThumbnail, $target_file, 6);				
			}
			
			if($this->imageFileType == "gif"){
				imagegif($myThumbnail, $target_file);					
			}			
		}
			
			public function saveFile($target_file){
				$notice = null;
				//lähtudes failitüübist kirjutan failitüübile
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
				if(imagejpeg($this->myImage, $target_file, 95)){
					$notice = 1;
			} else {
					$notice = 0;
				}
			}
			if($this->imageFileType == "png"){
				if(imagepng($this->myImage, $target_file, 6)){
					$notice = 1;
			} else {
				$notice = 0;
				}
			}
			
			if($this->imageFileType == "gif"){
				if(imagegif($this->myImage, $target_file)){
					$notice = 1;
			} else {
					$notice = 0;
					}
			}
		return $notice;
		}
		
	}//class lõppeb




?>