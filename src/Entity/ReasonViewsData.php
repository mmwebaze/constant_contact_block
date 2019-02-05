<?php

namespace Drupal\constant_contact_block\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Reason entities.
 */
class ReasonViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
