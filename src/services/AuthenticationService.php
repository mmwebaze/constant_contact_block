<?php

namespace Drupal\constant_contact_block\services;

use Drupal\constant_contact_block\authentication\ConstantContactAuth2;
use Drupal\Core\Config\ConfigFactory;

/**
 * Implements AuthenticationServiceInterface.
 */
class AuthenticationService implements AuthenticationServiceInterface {
  protected $redirectUri;
  protected $clientId;
  protected $clientSecret;
  protected $grantType = 'authorization_code';
  protected $code = 'code';
  protected $authRequestUrl;

  /**
   * The configuration item.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * Creates an authentication service.
   *
   * @param \Drupal\Core\Config\ConfigFactory $configFactory
   *   The configuration object.
   */
  public function __construct(ConfigFactory $configFactory) {
    $this->configFactory = $configFactory->getEditable('constant_contact_block.constantcontantconfig');
    $this->authRequestUrl = $this->configFactory->get('auth_request_url');
    $this->clientSecret = $this->configFactory->get('client_secret');
    $this->clientId = $this->configFactory->get('api_key');
    $this->redirectUri = $this->configFactory->get('redirect_uri');
    $this->auth = new ConstantContactAuth2();
  }

  /**
   * {@inheritdoc}
   */
  public function getAuthorizationUrl() {

    if ((isset($this->clientId) && isset($this->authRequestUrl) && isset($this->clientSecret)
      && isset($this->redirectUri))) {
      $oauthSignup = 'true';
      $authUrl = $this->authRequestUrl . '?response_type=code&client_id=' . $this->clientId . '&oauthSignup=' . $oauthSignup . '&redirect_uri=' . $this->redirectUri;

      return $authUrl;
    }
    else {

      return FALSE;
    }
  }

  /**
   * {@inheritdoc}
   */
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
