<?php

namespace Drupal\constant_contact_block\services;

/**
 * Defines the constant contact authentication service.
 */
interface AuthenticationServiceInterface {

  /**
   * Gets URL the user can authenticate and authorize the requesting app.
   *
   * @return string
   *   The url sting.
   */
  public function getAuthorizationUrl();

  /**
   * Gets the constant contact access token.
   *
   * @return string
   *   The access token.
   */
  public function getAccessToken($code);

}
