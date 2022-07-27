<?php

class imageController extends View
{
  var $model;
  function __construct ()
  {
    include './system/controller/partial/__construct.php';
  }
}
class generate extends imageController
{
  function __construct()
  {
    parent::__construct();

    include './system/class/createImage.php';

    //post attribute
    @$source = $_POST['source'];
    @$part_x = $_POST['part_x'];
    @$part_y = $_POST['part_y'];
    @$part_w = $_POST['part_w'];
    @$part_h = $_POST['part_h'];
    @$degrees = $_POST['degrees'];
    @$role = $_POST['role'];
    @$directpost = $_POST['directpost'];

    //create object
    $obj = new createImage();
    $obj -> Create($source, $part_x, $part_y, $part_w, $part_h, $degrees, $role, $directpost);
  }
}
class genbackground extends imageController
{
  function __construct()
  {
    parent::__construct();

    include './system/class/createImage.php';

    //post attribute
    @$source = $_POST['source'];
    @$part_x = $_POST['part_x'];
    @$part_y = $_POST['part_y'];
    @$part_w = $_POST['part_w'];
    @$part_h = $_POST['part_h'];
    @$degrees = $_POST['degrees'];
    @$role = $_POST['role'];
    @$directpost = 2;

    //create object
    $obj = new createImage();
    $obj -> Create($source, $part_x, $part_y, $part_w, $part_h, $degrees, $role, $directpost);
  }
}
class facebookpost extends imageController
{
  function __construct()
  {
    parent::__construct();

    if(isset($_GET['photo']))
    {
      $source = $_GET['photo'];
      include './system/class/facebookPublish.php';
    }
    else
    {
      $url = $this->config['web']['path'];
      header("Location: index.php");
    }
  }
}
