<?php

namespace Drupal\constant_contact_block\services;

/**
 * Defines an interface that manages the local constant contact tables.
 */
interface ConstantContactDataInterface {

  /**
   * Adds Constant Contact Lists to database.
   *
   * @param mixed $constantList
   *   The $constantList.
   */
  public function addContactList($constantList);

  /**
   * Gets locally stored Constant Contact Lists.
   */
  public function getContactLists();

  /**
   * Gets Constant Contact List stored locally.
   *
   * @param string $listId
   *   The contact list id.
   *
   * @return mixed
   *   The contact lists.
   */
  public function getContactList($listId);

  /**
   * Deletes a Constant Contact List locally.
   *
   * @param int $listId
   *   Constant Contact List Id.
   */
  public function deleteList($listId);

  /**
   * Deletes all locally stored lists from database.
   *
   * @param string $table
   *   The constant contact list table to purge.
   */
  public function deleteTable($table);

}
