<?php

namespace Drupal\constant_contact_block\authentication;


use Drupal\Console\Bootstrap\Drupal;
use GuzzleHttp\Exception\RequestException;

class ConstantContactAuth2 {
  protected $redirectUri = 'http://ungo.lndo.site/constant_contact_block/getCode';
  protected $clientId = 'g2jnh338hrwqxtzkuhxzkrqt';
  protected $clientSecret = 't8tjrrCVhWAwgDYvguzSABdy';
  protected $grantType = 'authorization_code';
  protected $code = 'code';
  protected $authRequestUrl = 'https://oauth2.constantcontact.com/oauth2/oauth/siteowner/authorize';
  protected $params;

  /**
   * displays or removes "Dont have an account? Sign up free" in the top right
   * of the login screen
   *
   * @var bool
   */
  protected $oauthSignup;

  /**
   * ConstantContactAuth2 constructor.
   *
   * @param bool $oauthSignup
   */
  public function __construct($oauthSignup = true, $params = array()) {
    $this->oauthSignup = $oauthSignup;
    $this->params = $params;
  }

  /**
   * Get the URL at which the user can authenticate and authorize the requesting application
   *
   * @return string
   */
 /* public function getAuthorizationUrl() {
    $httpClient = \Drupal::service('http_client');

    $url = $this->authRequestUrl.'?response_type=code&client_id='.$this->clientId.'&oauthSignup='.$this->oauthSignup.'&redirect_uri='.$this->redirectUri;
   // drupal_set_message($url);
    try{
      $response = $httpClient->get($url);

      if ($response->getStatusCode() == 200){
        //drupal_set_message($response->getUrl());

        return $url;
      }

      return 'invalid url';
    }
    catch (RequestException $e) {
      //drupal_set_message($e->getMessage());
      return json_encode(['error' => $e->getMessage()]);
    }
  }*/

  /**
   * @param $code
   */
  public function getAccessToken($code, $params){
    $httpClient = \Drupal::service('http_client');
    $body = new \stdClass();
    $body->grant_type = 'authorization_code';
    $body->client_id = $params['client_id'];
    $body->client_secret = $params['client_secret'];
    $body->code = $code;
    $body->redirect_uri = $params['redirect_uri'];

    //print_r($params['client_secret']);die('433*');
    $url = 'https://oauth2.constantcontact.com/oauth2/oauth/token?grant_type=authorization_code&client_id='.$params['client_id'].'&client_secret='.$params['client_secret'].'&code='.$code.'&redirect_uri='.$params['redirect_uri'];
    try{
      /*$response = $httpClient->post('https://oauth2.constantcontact.com/oauth2/oauth/token?',
        [
          'body' => json_encode($body),
          'headers' => [
            'Content-Type' => 'application/json',
          ]
        ]);*/
      //print($url);
      //die();
      $response = $httpClient->request('POST', $url, [
        'headers' => [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
        ]
      ]);
      //$response->getBody()->getContents();
      return (string)$response->getBody();
    }
    catch (RequestException $e) {
      print($e->getMessage());
    }
  }
}