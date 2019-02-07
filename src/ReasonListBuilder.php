<?php

namespace Drupal\constant_contact_block;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Reason entities.
 *
 * @ingroup constant_contact_block
 */
class ReasonListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Reason ID');
    $header['name'] = $this->t('Name');
    $header['number_left'] = $this->t('Number unsubscribed');
    $header['status'] = $this->t('Enabled Reasons');
    $header['weight'] = $this->t('Weight');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\constant_contact_block\Entity\Reason */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.reason.edit_form',
      ['reason' => $entity->id()]
    );
    $row['number_left'] = $entity->getNumberLeft();
    $row['status'] = $entity->isPublished();
    $row['weight'] = $entity->getWeight();
    return $row + parent::buildRow($entity);
  }

}
