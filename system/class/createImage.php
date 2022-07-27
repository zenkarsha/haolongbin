<?php
class createImage
{
  function Create($source, $part_x, $part_y, $part_w, $part_h, $degrees, $role, $directpost)
  {
    $source = './upload/'.$source;

    //get the input file extension and create a GD resource from it
    $ext = pathinfo($source, PATHINFO_EXTENSION);
    if($ext == "jpg" || $ext == "jpeg") $image = imagecreatefromjpeg($source);
    elseif($ext == "png") $image = imagecreatefrompng($source);
    elseif($ext == "gif") $image = imagecreatefromgif($source);

    //get the image size
    $size = getimagesize($source);
    $height = $size[1];
    $width = $size[0];

    //rotate transparent
    imagesavealpha($image , true);
    $transparent = imagecolorallocatealpha($image , 0, 0, 0, 127);

    //draw object
    $object_url = './images/object/'.$role.'.png';
    $object = imagecreatefrompng($object_url);

    //resize
    $new_obj = imagecreatetruecolor($part_w, $part_h);
    imagealphablending($new_obj, false);
    imagesavealpha($new_obj,true);
    $new_obj_transparent = imagecolorallocatealpha($new_obj , 0, 0, 0, 127);
    imagefilledrectangle($new_obj, 0, 0, $part_w, $part_h, $new_obj_transparent);
    imagecopyresampled($new_obj, $object, 0, 0, 0, 0,$part_w,$part_h,imagesx($object),imagesy($object));

    //scale
    $scale = $part_w/imagesx($new_obj);

    //rotate
    $object = imagerotate($new_obj, -1*$degrees,$transparent);

    //draw
    $new_w = imagesx($object);
    $new_h = imagesy($object);
    imagecopyresampled($image, $object, $part_x, $part_y, 0, 0,$new_w*$scale,$new_h*$scale,$new_w,$new_h);
    @imagedestroy($object);

    switch ($directpost) {
      case 1:
        header('Content-Type: image/png');
        $filename=time();
        $save = "./temp/".$filename.".png";
        imagepng($image,$save,9,null);
        $url="facebookpost/?photo=".$filename;
        header("Location: $url");
        break;

      case 2:
        header('Content-Type: image/png');
        $filename = date('YmdHis').'_'.md5($source).'.png';
        $save = "./upload/".$filename;
        imagepng($image,$save,9,null);
        echo $filename;
        break;

      default:
        header('Content-Type: image/png');
        header("Content-Transfer-Encoding: binary");
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=天龍上人.png');
        imagepng($image,null,9,null);
        @imagedestroy($image);
        break;
    }
  }
}
