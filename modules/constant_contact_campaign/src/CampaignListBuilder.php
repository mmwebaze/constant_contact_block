<?php

namespace Drupal\constant_contact_campaign;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Campaign entities.
 *
 * @ingroup constant_contact_campaign
 */
class CampaignListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Campaign ID');
    $header['name'] = $this->t('Name');
    $header['message'] = $this->t('message');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\constant_contact_campaign\Entity\Campaign */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.campaign.edit_form',
      ['campaign' => $entity->id()]
    );
    $row['message'] = $entity->getMessage();
    return $row + parent::buildRow($entity);
  }

}
