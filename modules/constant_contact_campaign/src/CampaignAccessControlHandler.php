<?php

namespace Drupal\constant_contact_campaign;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Campaign entity.
 *
 * @see \Drupal\constant_contact_campaign\Entity\Campaign.
 */
class CampaignAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\constant_contact_campaign\Entity\CampaignInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished campaign entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published campaign entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit campaign entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete campaign entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add campaign entities');
  }

}
