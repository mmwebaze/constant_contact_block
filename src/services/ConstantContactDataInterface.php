<?php

namespace Drupal\constant_contact_block\services;


interface ConstantContactDataInterface {

  /**
   * Adds Constant Contact Lists to database
   *
   * @param $values
   *
   */
  public function addContactList($values);

  /**
   * Gets locally stored Constant Contact Lists
   */
  public function getContactLists();

  /**
   * Gets Constant Contact List stored locally.
   *
   * @param string $listId
   *
   * @return mixed
   */
  public function getContactList($listId);

  /**
   * Deletes a Constant Contact List locally.
   *
   * @param integer $listId
   *Constant Contact List Id
   */
  public function deleteList($listId);

  /**
   * Deletes all locally stored lists from database
   *
   * @param string $table
   *
   */
  public function deleteTable($table);
}