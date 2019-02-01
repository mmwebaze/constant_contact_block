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
   * Gets Constant Contact List by id
   *
   * @param $listId
   *
   * @return string json formatted string output
   */
    public function getContactList($listId);

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
   *
   * @param $contact
   * A Constant Contact
   *
   * @param array $lists
   * Array of Constant Contact Lists a contact wants to belong to.
   *
   * @param boolean $isUpdateable
   * Boolean value that determines whether a user is updating their communications
   * settings. If true, then user is updating their settings otherwise user is
   * registering to add their email to a list
   *
   * @return string json formatted string output
   */
    public function updateContant($contact, array $lists, $isUpdateable = FALSE);

  /**
   * Checks for the existence of a contact on Constant Contact by email
   * @param string $email
   *
   * @return boolean
   */
    public function checkContactExistsByEmail($email);

  /**
   * Gets Constant Contact Lists a user is part of.
   *
   * @param $contactId
   * Contact ID
   *
   * @return string json formatted output.
   */
    public function getContactById($contactId);

  /**
   * @param $contactId
   * Contact ID that will be deleted.
   *
   * @return string json formatted output.
   */
    public function deleteContact($contactId);
}