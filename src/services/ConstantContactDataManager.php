<?php

namespace Drupal\constant_contact_block\services;

use Drupal\Core\Database\Driver\mysql\Connection;


class ConstantContactDataManager implements ConstantContactDataInterface{
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
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  public function addContactList($values){
    if(isset($values)){
      $values = json_decode($values);
      $value = [
        'id' => $values->id,
        'name' => $values->name,
        'list_id' => '',
        'modified_date' => $values->modified_date,
        'status' => $values->status,
        'contact_count' => $values->contact_count,
        'created_date' => $values->created_date
      ];
      $this->connection->insert('constant_contact_lists')
        ->fields([
          'id','name','list_id','modified_date','status', 'contact_count',
          'created_date'
        ])->values($value)->execute();
    }
  }
  public function getContactLists(){
    $query = $this->connection->select('constant_contact_lists', 'ccl')
      ->fields('ccl');
    $pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(50);
    $results = $pager->execute()->fetchAll();
    return $results;
  }
  public function deleteList($listId){
    $query = $this->connection->delete('constant_contact_lists')
      ->condition('id', $listId);
    $query->execute();
  }
  public function deleteTable($table){
    $this->connection->truncate($table)->execute();
  }
}