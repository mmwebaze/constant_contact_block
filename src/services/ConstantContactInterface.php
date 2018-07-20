<?php

namespace Drupal\constant_contact_block\services;


interface ConstantContactInterface
{
    public function addContact($contact);
    public function getContactLists();
    public function getContacts();
    public function addContactList($name, $status);
    public function deleteContactList($listId);
}