<?php

namespace Drupal\constant_contact_block\services;

/**
 *
 */
interface AuthenticationServiceInterface {

  /**
   *
   */
  public function getAuthorizationUrl();

  /**
   *
   */
  public function getAccessToken($code);

}
