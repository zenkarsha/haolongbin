<?php

session_start();
require_once('./system/extension/facebook-php-sdk-v4-4.0-dev/autoload.php');
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;

FacebookSession::setDefaultApplication(
  $this->config['facebook']['app-id'],
  $this->config['facebook']['app-secret']
);

// login helper with redirect_uri
$helper_url = $this->config['web']['path'].'facebookpost/?photo='.$source;
$helper = new FacebookRedirectLoginHelper($helper_url);

$photo = "./temp/".$source.".png";
if(file_exists($photo)) {

  try {
    $session = $helper->getSessionFromRedirect();
  } catch( FacebookRequestException $ex ) {
    $url = $this->config['web']['path'];
    header("Location: $url");
  } catch( Exception $ex ) {
    $url = $this->config['web']['path'];
    header("Location: $url");
  }

  // see if we have a session
  if (isset($session)) {

    $request = new FacebookRequest( $session, 'GET', '/me?fields=albums.fields(id,name)' );
    $response = $request->execute();

    // get response
    $graphObject = $response->getGraphObject()->asArray();
    $userid = $graphObject[id];

    //get timeline photos album id
    $albums = $graphObject[albums]->data;
    for($i=0;$i<count($albums);$i++) {
      if($albums[$i]->name=='Timeline Photos') {
        $timelinealbumid=$albums[$i]->id;
        break;
      }
    }

    //publish actions
    $publish = (new FacebookRequest( $session, 'POST', '/'.$timelinealbumid.'/photos', array(
      'source' => '@' . $photo
    )))->execute();

    //redirect to users facebook
    $header_url="https://www.facebook.com/".$userid;
    header("Location: $header_url");
  }
  else
  {
    $login_url = $helper->getLoginUrl( array( 'scope' => 'publish_actions') );
    header("Location: $login_url");
  }
}
else
{
  $url = $this->config['web']['path'];
  header("Location: $url");
}
?>
