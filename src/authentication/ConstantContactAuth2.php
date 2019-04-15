<?php

namespace Drupal\constant_contact_block\authentication;

use GuzzleHttp\Exception\RequestException;

/**
 * The Constant Contact Auth class.
 */
class ConstantContactAuth2 {
  protected $params;

  /**
   * ConstantContactAuth2 constructor.
   *
   * @param array $params
   *   The configuration parameters.
   */
  public function __construct(array $params = []) {
    $this->params = $params;
  }

  /**
   * Gets the access code from Constant Contact.
   *
   * @param string $code
   *   The access code from constant contact.
   * @param mixed $params
   *   The access parameters available in module config object.
   *
   * @return string
   *   The access token
   */
  public function getAccessToken($code, $params) {
    $httpClient = \Drupal::service('http_client');
    $body = new \stdClass();
    $body->grant_type = 'authorization_code';
    $body->client_id = $params['client_id'];
    $body->client_secret = $params['client_secret'];
    $body->code = $code;
    $body->redirect_uri = $params['redirect_uri'];

    $url = 'https://oauth2.constantcontact.com/oauth2/oauth/token?grant_type=authorization_code&client_id=' . $params['client_id'] . '&client_secret=' . $params['client_secret'] . '&code=' . $code . '&redirect_uri=' . $params['redirect_uri'];
    try {
      $response = $httpClient->request('POST', $url, [
        'headers' => [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
        ],
      ]);

      return (string) $response->getBody();
    }
    catch (RequestException $e) {
      \Drupal::service('messenger')->addMessage('Redirect URI mismatch', 'Constant Contact Block');
    }
  }

}
