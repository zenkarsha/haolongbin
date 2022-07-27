<?php

class Controller
{
  var $view;
  function Controller()
  {
    //INCLUDE CONFIG
    include_once './system/config/systemConfig.php';
    $this->config = $config;
    $this->parent = $config['site']['parent'];

    //INCLUDE FUNCTION
    include_once './system/function/systemFunction.php';
    include_once './system/function/customFunction.php';

    //DEAL WITH URL
    $this->url = explodeUrl($this->config['site']['path']);

    //USERAGENT
    $this->useragent = mobileDetect();

    //CONTROLOR
    include_once './system/controller/defaultController.php';
    switch($this->url[1])
    {
      // default
      case 'index':
        $this->view = new index();
        break;

      case 'og':
        $this->view = new og();
        break;

      // application actions
      case 'uploader':
        include_once './system/controller/uploadController.php';
        $this->view = new uploader();
        break;

      case 'generate':
        include_once './system/controller/imageController.php';
        $this->view = new generate();
        break;

      case 'genbackground':
        include_once './system/controller/imageController.php';
        $this->view = new genbackground();
        break;

      default:
        $this->view = new index();
        break;
    }
  }
}
