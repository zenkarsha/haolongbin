 <?php

class uploadController extends View
{
  var $model;
  function __construct ()
  {
    include './system/controller/partial/__construct.php';
  }
}
class uploader extends uploadController
{
  function __construct()
  {
    parent::__construct();
    header('Content-Type: text/javascript');

    $allowed = array('png', 'jpg', 'jpeg', 'gif');
    if(isset($_FILES['Filedata']) && $_FILES['Filedata']['error'] == 0){

      $randomString = generateRandomString();
      $extension = strtolower(pathinfo($_FILES['Filedata']['name'], PATHINFO_EXTENSION));
      $filename = date('YmdHis').'_'.base64_encode($_FILES['Filedata']['name']).'_'.$randomString.'.'.$extension;


      if(!in_array(strtolower($extension), $allowed)){
        echo '{"status":"error"}';
        exit;
      }

      if($_FILES['Filedata']['tmp_name']){

        $upload_src=$_FILES['Filedata']['tmp_name'];
        if($extension=='jpeg' || $extension=='jpg')
          $upload_img=imagecreatefromjpeg($upload_src);
        elseif($extension=='png')
          $upload_img=imagecreatefrompng($upload_src);
        elseif($extension=='gif')
          $upload_img=imagecreatefromgif($upload_src);

        $upload_img_w=imagesx($upload_img);
        $upload_img_h=imagesy($upload_img);

        if($upload_img_w > 800 || $upload_img_h > 800)
        {
          if($upload_img_h > $upload_img_w)
          {
            $scale = 800/$upload_img_h;
            $new_h = 800;
            $new_w = $upload_img_w*$scale;
          }
          elseif($upload_img_h < $upload_img_w)
          {
            $scale = 800/$upload_img_w;
            $new_w = 800;
            $new_h = $upload_img_h*$scale;
          }
          else
          {
            $new_w = 800;
            $new_h = 800;
          }

          $image = imagecreatetruecolor($new_w, $new_h);
          imagecopyresampled($image, $upload_img, 0, 0, 0, 0, $new_w, $new_h, $upload_img_w, $upload_img_h);

          $save = 'upload/'.$filename;
          if($extension=='jpeg' || $extension=='jpg')
            imagejpeg($image,$save,75);
          elseif($extension=='png')
            imagepng($image,$save,9,null);
          elseif($extension=='gif')
            imagegif($image,$save);

          @imagedestroy($image);
          @imagedestroy($upload_img);
          unlink($_FILES['Filedata']['tmp_name']);

          print(json_encode(array('filename'=>$filename)));
          exit;
        }
        else
        {
          if(move_uploaded_file($_FILES['Filedata']['tmp_name'], 'upload/'.$filename
            )){
            print(json_encode(array('filename'=>$filename)));
            exit;
          }
          @imagedestroy($upload_img);
        }
      }
    }
    //echo '{"status":"error2"}';
    exit;
  }
}
class urloader extends uploadController
{
  function __construct()
  {
    parent::__construct();

    //post attribute
    @$url = $_POST['url'];
    //$extension = strtolower(end(explode('.', $url)));
    $clear_url = strtok($url, '?');
    $extension = pathinfo($clear_url, PATHINFO_EXTENSION);

    $allowed = array('png', 'jpg', 'jpeg', 'gif');
    if(in_array($extension, $allowed))
    {
      $filename = date('YmdHis').'_'.base64_encode($url).'.'.$extension;
      $fileurl = 'upload/'.$filename;
      if(copy($url, $fileurl))
      {
        if($extension=='jpeg' || $extension=='jpg')
          $upload_img=imagecreatefromjpeg($fileurl);
        elseif($extension=='png')
          $upload_img=imagecreatefrompng($fileurl);
        elseif($extension=='gif')
          $upload_img=imagecreatefromgif($fileurl);

        $upload_img_w=imagesx($upload_img);
        $upload_img_h=imagesy($upload_img);

        if($upload_img_w > 1024 || $upload_img_h > 1024)
        {
          if($upload_img_h > $upload_img_w)
          {
            $scale = 1024/$upload_img_h;
            $new_h = 1024;
            $new_w = $upload_img_w*$scale;
          }
          elseif($upload_img_h < $upload_img_w)
          {
            $scale = 1024/$upload_img_w;
            $new_w = 1024;
            $new_h = $upload_img_h*$scale;
          }
          else
          {
            $new_w = 1024;
            $new_h = 1024;
          }

          $image = imagecreatetruecolor($new_w, $new_h);
          imagecopyresampled($image, $upload_img, 0, 0, 0, 0, $new_w, $new_h, $upload_img_w, $upload_img_h);

          if($extension=='jpeg' || $extension=='jpg')
            imagejpeg($image,$fileurl,75);
          elseif($extension=='png')
            imagepng($image,$fileurl,9,null);
          elseif($extension=='gif')
            imagegif($image,$fileurl);

          @imagedestroy($image);
          @imagedestroy($upload_img);
        }
        echo $filename;
      }
      else {
        echo 'error';
      }
    }
    else
    {
      echo 'error';
    }

  }
}
