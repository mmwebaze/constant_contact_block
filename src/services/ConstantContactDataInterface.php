<?php

namespace Drupal\constant_contact_block\services;


interface ConstantContactDataInterface {
  public function addContactList($values);
  public function getContactLists();
}