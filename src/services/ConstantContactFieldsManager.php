<?php

namespace Drupal\constant_contact_block\services;

use Symfony\Component\Yaml\Yaml;

/**
 * Implements the ConstantContactFieldsInterface.
 */
class ConstantContactFieldsManager implements ConstantContactFieldsInterface {

  /**
   * {@inheritdoc}
   */
  public function loadFields() {
    $module_path = drupal_get_path('module', 'constant_contact_block');
    $file_contents = file_get_contents($module_path . '/constant_contact_block.fields.yml');
    $fields = Yaml::parse($file_contents);
    $fields = $fields['fields'];
    return $fields;
  }

}
