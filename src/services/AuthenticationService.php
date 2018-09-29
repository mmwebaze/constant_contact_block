<?php

namespace Drupal\constant_contact_block\services;


use Drupal\constant_contact_block\authentication\ConstantContactAuth2;
use Drupal\Core\Config\ConfigFactoryInterface;

class AuthenticationService implements AuthenticationServiceInterface
{
  protected $redirectUri;
  protected $clientId;
  protected $clientSecret;
  protected $grantType = 'authorization_code';
  protected $code = 'code';
  protected $auth;
  protected $authRequestUrl;
  //protected $authRequestUrl = 'https://oauth2.constantcontact.com/oauth2/oauth/siteowner/authorize';

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->configFactory = $configFactory->getEditable('constant_contact_block.constantcontantconfig');
    $this->authRequestUrl = $this->configFactory->get('auth_request_url');
    $this->clientSecret = $this->configFactory->get('client_secret');
    $this->clientId = $this->configFactory->get('api_key');
    $this->redirectUri = $this->configFactory->get('redirect_uri');
    $this->auth = new ConstantContactAuth2();
  }
 /* private function getHeaders() {
      return array(
        //'Content-Type' => 'application/json',
        //'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . '4f2f5ecd-0156-412e-bffc-cf95b4ce7958'
      );
  }*/

  /**
   * Get the URL at which the user can authenticate and authorize the requesting application
   *
   * @return string
   */
  public function getAuthorizationUrl() {

    $oauthSignup = 'true';
    $authUrl = $this->authRequestUrl.'?response_type=code&client_id='.$this->clientId.'&oauthSignup='.$oauthSignup.'&redirect_uri='.$this->redirectUri;
    return $authUrl;
  }
  public function getAccessToken($code) {

    $params = [
      'client_id' => $this->clientId,
      'grant_type' => $this->grantType,
      'redirect_uri' => $this->redirectUri,
      'client_secret' => $this->clientSecret,
    ];
    return $this->auth->getAccessToken($code, $params);
  }
}