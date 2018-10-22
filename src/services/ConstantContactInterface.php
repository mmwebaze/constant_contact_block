<?php

namespace Drupal\constant_contact_block\services;


interface ConstantContactInterface
{
    /**
     * Adds a contact to a Constant Contact list
     * @param $contact
     * @return string json formatted string output
     */
    public function addContact($contact);

    /**
     * Gets Constant Contact lists
     *
     * @return string json formatted string output
     */
    public function getContactLists();

    /**
     * Gets Constant Contact contacts
     *
     * @return string json formatted string output
     */
    public function getContacts();
    /**
     * Adds a new list to Constant Contact
     *
     * @param $name
     * Constant Contact name
     *
     * @return string json formatted string
     */
    public function addContactList($name, $status);

    /**
     * Removes Constant Contact list by id
     *
     * @param $listId
     * Constant Contact list id
     * @return string json formatted string
     */
    public function deleteContactList($listId);

  /**
   * Updates a contact to a Constant Contact list
   * @param $contact
   *
   * @return string json formatted string output
   */
    public function updateContant($contact, $lists);

  /**
   * Checks for the existence of a contact on Constant Contact by email
   * @param $email
   *
   * @return boolean
   */
    public function checkContactExistsByEmail($email);
}