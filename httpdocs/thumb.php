<?
	$width = $_GET['width'];
	$height = $_GET['height'];
	$imagepath = "/".str_replace("|", "/", $_GET['path']);
	$i = new Imagick($_SERVER['DOCUMENT_ROOT']."/images".$imagepath);
	$i->setImageCompression(Imagick::COMPRESSION_JPEG);
	$i->setImageCompressionQuality(80);
	
	$clone = clone $i;
	$clone->thumbnailImage(0, $height);
	
	$i->resizeimage($clone->getimagewidth(), $clone->getimageheight(), IMagick::FILTER_GAUSSIAN, 0.125);
	
	$i->cropimage($width, $height, (($i->getImageWidth()/2) - ($width/2)), 0 );
	$i->setImageFormat("png");
	header('Content-type: image/jpeg');
	echo $i;