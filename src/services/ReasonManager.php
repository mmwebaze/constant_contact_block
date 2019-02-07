<?php

namespace Drupal\constant_contact_block\services;


use Drupal\constant_contact_block\Entity\Reason;
use Drupal\Core\Entity\EntityTypeManager;


class ReasonManager implements ReasonServiceInterface {
    protected $storageReason;

    public function __construct(EntityTypeManager $entityTypeManager){
        $this->storageReason = $entityTypeManager->getStorage('reason');
    }
  /**
   * @inheritdoc
   */
  public function createReason(array $options){
    $reason = Reason::create($options);

    $reason->save();
  }
  /**
   * @inheritdoc
   */
  public function getReasons(){
      $reasons = [];
      $enabledReasonIds = $this->storageReason->getQuery()->condition('status', 1)
          ->sort('weight', 'ASC')
          ->execute();
      $reasonEntities = $this->storageReason->loadMultiple($enabledReasonIds);

      foreach ($reasonEntities as $reasonEntity){
          //die($reasonEntity->uuid());
          $reasons[$reasonEntity->id()] = $reasonEntity->getName();
      }

      return $reasons;
  }
  /**
   * @inheritdoc
   */
  public function updateNumberLeft($reasonId){
      $selectedReasons = $this->storageReason->loadMultiple([$reasonId]);
      $selectedReason = $selectedReasons[$reasonId];
      $newNumberLeft = $selectedReason->get('number_left')->value + 1;
      $selectedReason->set('number_left', $newNumberLeft);
      $selectedReason->save();
      //print_r($newNumberLeft);die();
  }
}