<?php
//*********************************************************
//********        Class Image          ********************
//********                             ********************
//******** Author : Matthieu LACOMBLEZ ********************
//******** Last modified : 13 Nov 2016 ********************
//*********************************************************

class Image {
  //-------- Attributes ---------------------------------
  var $im; // image resource
  //-----------------------------------------------------

  //-------- Constructor --------------------------------
	function Image() {
		// initialize the image resource to null
		$this->im = NULL;
	}
	//-----------------------------------------------------

	//-------- Destructor ---------------------------------
	function destroy() {
		return ImageDestroy($this->im);
	}
	//-----------------------------------------------------

	//-------- Create a blank image -----------------------
	function create($width = 250, $height = 40) {
		$im = @ImageCreateTrueColor($width, $height);
		if ($im == "")
			return false;
		else {
			$this->im = $im;
			return true;
		}
	}
	//-----------------------------------------------------

	//-------- Open image ---------------------------------
  function open($filename) {
    $ext = strtoupper(pathinfo($filename, PATHINFO_EXTENSION));
    switch ($ext) {
      case "JPG":
        $this->im = @ImageCreateFromJpeg($filename);
        break;
      case "JPEG":
        $this->im = @ImageCreateFromJpeg($filename);
        break;
      case "PNG":
        $this->im = @ImageCreateFromPng($filename);
        break;
      case "GIF":
        $this->im = @ImageCreateFromGif($filename);
        break;
    }
    return ($this->im != "");
  }
  //-----------------------------------------------------

	//-------- Write a string into the image --------------
	function writeString($str) {
		// white background
		$white = ImageColorAllocate($this->im, 255, 255, 255);
		// black drawing
		$black = ImageColorAllocate($this->im, 0, 0, 0);
		$font = 5;
		// string's width
		$width = ImageFontWidth($font) * strlen($str);
		// string's height
		$height = ImageFontHeight($font);
		// centered string coordinates
		$x = floor((ImageSX($this->im) - $width) / 2);
		$y = floor((ImageSY($this->im) - $height) / 2);
		// draw the string
		return ImageString($this->im, $font, $x, $y, $str, $black);
	}
	//-----------------------------------------------------

	//-------- Set interlace ------------------------------
	function setInterlace($bool) {
		return ImageInterlace($this->im, $bool);
	}
	//-----------------------------------------------------

	//-------- Save image ---------------------------------
	function saveAs($filename) {
    if (!is_null($this->im)) {
      $ext = strtoupper(pathinfo($filename, PATHINFO_EXTENSION));
      switch ($ext) {
				case "JPG":
					$ok = ImageJpeg($this->im, $filename);
					break;
				case "JPEG":
					$ok = ImageJpeg($this->im, $filename);
					break;
				case "PNG":
					$ok = ImagePng($this->im, $filename);
					break;
				case "GIF":
					$ok = ImageGif($this->im, $filename);
					break;
			}
			return $ok;
		}
		else
			return false;
  }
  //-----------------------------------------------------

	//-------- Resample image -----------------------------
	function resample($width, $height = 0) {
		$x = ImageSX($this->im);
		$y = ImageSY($this->im);
		if ($width == 0)
			$width = round($x * $height / $y);
		if ($height == 0)
			$height = round($y * $width / $x);
		$im = @ImageCreateTrueColor($width, $height);
		if ($im == "")
			return false;
		else {
			$ok = ImageCopyResampled($im, $this->im, 0, 0, 0, 0, $width, $height, $x, $y);
			ImageDestroy($this->im);
			$this->im = $im;
			return $ok;
		}
	}
	//-----------------------------------------------------

	//-------- Save as custom -----------------------------
  function saveAsCustom($filename, $width, $height = 0) {
		$this->resample($width, $height);
		return $this->saveAs($filename);
	}
	//-----------------------------------------------------
  
  //-------- Save as logo (max 350x200) -----------------
	function saveAsLogo($filename) {
		$x = ImageSX($this->im);
		$y = ImageSY($this->im);
		if ($x > 350)
			$this->resample(350);
		if ($y > 200)
			$this->resample(0, 200);
		return $this->saveAs($filename);
	}
	//-----------------------------------------------------

	//-------- Save as thumbnail (max 150x50) -------------
	function saveAsThumbnail($filename) {
		$x = ImageSX($this->im);
		$y = ImageSY($this->im);
		if ($y > 50)
			$this->resample(0, 50);
		elseif ($x > 200)
			$this->resample(200);
		return $this->saveAs($filename);
	}
	//-----------------------------------------------------

	//-------- Save as cover (max width 150) --------------
	function saveAsCover($filename) {
		$x = ImageSX($this->im);
		if ($x != 150)
			$this->resample(150);
		return $this->saveAs($filename);
	}
	//-----------------------------------------------------
}
?>
