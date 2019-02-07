<?php

namespace Drupal\constant_contact_block\services;


interface ReasonServiceInterface {

  /**
   * Creates a Reason Entity
   *
   * @param array $options
   * An array of options used to create a Reason Entity
   *
   *
   */
  public function createReason(array $options);

  /**
   * Gets a List of Reason Entities
   *
   */
  public function getReasons();

  /**
   * Updates number of subscribers who left for a particular reson
   *
   * @param integer $reasonId
   *
   */
  public function updateNumberLeft($reasonId);
}