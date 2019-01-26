<?php

namespace Drupal\constant_contact_block\services;


interface ConstantContactDataInterface {
  public function addContactList($values);

  /**
   * Gets locally stored Constant Contact Lists
   */
  public function getContactLists();

  /**
   * Deletes a Constant Contact List locally.
   *
   * @param integer $listId
   *Constant Contact List Id
   */
  public function deleteList($listId);
  public function deleteTable($table);
}