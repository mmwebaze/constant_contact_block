<?php

namespace Drupal\constant_contact_block\services;

use Drupal\constant_contact_block\items\Contact;

/**
 * Provides an interface defining constant contact api functions.
 */
interface ConstantContactInterface {

  /**
   * Adds a contact to a Constant Contact list.
   *
   * @param \Drupal\constant_contact_block\items\Contact $contact
   *   The contact.
   *
   * @return string
   *   Response as JSON formatted string.
   */
  public function addContact(Contact $contact);

  /**
   * Gets Constant Contact lists.
   *
   * @return string
   *   Response as JSON formatted string.
   */
  public function getContactLists();

  /**
   * Gets Constant Contact List by id.
   *
   * @param string $listId
   *   The list id.
   *
   * @return string
   *   Response as JSON formatted string.
   */
  public function getContactList($listId);

  /**
   * Gets Constant Contact contacts.
   *
   * @return string
   *   Response as JSON formatted string.
   */
  public function getContacts();

  /**
   * Adds a new list to Constant Contact.
   *
   * @param string $name
   *   Constant Contact name.
   * @param string $status
   *   The status of the list.
   *
   * @return string
   *   Response as JSON formatted string.
   */
  public function addContactList($name, $status);

  /**
   * Removes Constant Contact list by id.
   *
   * @param int $listId
   *   Constant Contact list id.
   *
   * @return string
   *   Response as JSON formatted string.
   */
  public function deleteContactList($listId);

  /**
   * Updates a contact to a Constant Contact list.
   *
   * @param mixed $contact
   *   A Constant Contact.
   * @param array $lists
   *   Array of Constant Contact Lists a contact wants to belong to.
   * @param bool $isUpdateable
   *   Boolean that determines whether a user is updating their communications
   *   settings. If true, then user is updating their settings otherwise user is
   *   registering to add their email to a list.
   *
   * @return string
   *   Response as JSON formatted string.
   */
  public function updateContant($contact, array $lists, $isUpdateable = FALSE);

  /**
   * Checks for the existence of a contact on Constant Contact by email.
   *
   * @param string $email
   *   The email address being checked.
   *
   * @return bool
   *   The status of the update.
   */
  public function checkContactExistsByEmail($email);

  /**
   * Gets Constant Contact Lists a user is part of.
   *
   * @param int $contactId
   *   The Constant Contact ID.
   *
   * @return string
   *   Response as JSON formatted string.
   */
  public function getContactById($contactId);

  /**
   * Removes a contact from constant contact.
   *
   * @param int $contactId
   *   The Constant Contact ID that will be deleted.
   *
   * @return string
   *   Response as JSON formatted string.
   */
  public function deleteContact($contactId);

}
