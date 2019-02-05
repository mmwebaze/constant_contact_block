<?php

namespace Drupal\constant_contact_block;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Reason entity.
 *
 * @see \Drupal\constant_contact_block\Entity\Reason.
 */
class ReasonAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\constant_contact_block\Entity\ReasonInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished reason entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published reason entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit reason entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete reason entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add reason entities');
  }

}
