<?php

namespace Drupal\constant_contact_block\services;

/**
 * Provides an interface defining the constant contact form fields.
 */
interface ConstantContactFieldsInterface {

  /**
   * Loads constant contact fields from a yaml file.
   *
   * @return array
   *   Returns an array of constant contact fields
   */
  public function loadFields();

}
