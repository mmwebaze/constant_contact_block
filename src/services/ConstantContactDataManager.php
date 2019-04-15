<?php

namespace Drupal\constant_contact_block\services;

use Drupal\Core\Database\Driver\mysql\Connection;

/**
 * Implements the ConstantContactDataInterface.
 *
 * @package Drupal\constant_contact_block\services
 */
class ConstantContactDataManager implements ConstantContactDataInterface {
  /**
   * Drupal\Core\Database\Driver\mysql\Connection definition.
   *
   * @var \Drupal\Core\Database\Driver\mysql\Connection
   */
  protected $connection;

  /**
   * ConstantContactDataManager constructor.
   *
   * @param \Drupal\Core\Database\Driver\mysql\Connection $connection
   *   The connection service.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public function addContactList($values) {
    if (isset($values)) {
      $value = [
        'id' => $values->id,
        'name' => $values->name,
        'list_id' => '',
        'modified_date' => $values->modified_date,
        'status' => $values->status,
        'contact_count' => $values->contact_count,
        'created_date' => $values->created_date,
      ];
      $this->connection->insert('constant_contact_lists')
        ->fields([
          'id', 'name', 'list_id', 'modified_date', 'status', 'contact_count',
          'created_date',
        ])->values($value)->execute();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getContactLists() {
    $query = $this->connection->select('constant_contact_lists', 'ccl')
      ->fields('ccl');
    $pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(50);
    $results = $pager->execute()->fetchAll();
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function deleteList($listId) {
    $query = $this->connection->delete('constant_contact_lists')
      ->condition('id', $listId);
    $query->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function deleteTable($table) {
    $this->connection->truncate($table)->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function getContactList($listId) {
    $results = $this->connection->select('constant_contact_lists', 'ccl')
      ->fields('ccl', ['name'])
      ->condition('id', $listId, '=')
      ->execute()
      ->fetchAll();

    return $results;
  }

}
