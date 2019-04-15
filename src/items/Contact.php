<?php

namespace Drupal\constant_contact_block\items;

/**
 * Defines a Constant Contact.
 */
class Contact implements \JsonSerializable {
  private $status;
  private $first_name;
  private $last_name;
  private $email_addresses = [];
  private $company_name;
  private $lists = [];

  /**
   * Creates a new contact.
   *
   * @param string $firstName
   *   The first name of the contact.
   * @param string $lastName
   *   The last name of the contact.
   * @param string $companyName
   *   The company name of the contact.
   * @param string $status
   *   The status of the contact.
   * @param array $emailAddresses
   *   The email addresses of the contact.
   * @param array $lists
   *   The lists the constant contact will belong to.
   */
  public function __construct($firstName,
  $lastName,
  $companyName,
  $status,
                              array $emailAddresses,
  array $lists) {
    $this->status = $status;
    $this->first_name = $firstName;
    $this->last_name = $lastName;
    $this->email_addresses = $emailAddresses;
    $this->lists = $lists;
    $this->company_name = $companyName;
  }

  /**
   * Gets the contacts status.
   *
   * @return string
   *   Returns the contact status.
   */
  public function getStatus() {
    return $this->status;
  }

  /**
   * Sets the contact status.
   *
   * @param string $status
   *   The status of the contact (ACTIVE, UNCONFIRMED, OPTOUT, REMOVED
   *   NON_SUBSCRIBER, VISITOR, VISITOR).
   */
  public function setStatus($status) {
    $this->status = $status;
  }

  /**
   * Gets the first name of the contact.
   *
   * @return string
   *   The first name of the contact.
   */
  public function getFirstName() {
    return $this->first_name;
  }

  /**
   * Sets the first name of the contact.
   *
   * @param string $firstName
   *   The first name of the contact.
   */
  public function setFirstName($firstName) {
    $this->first_name = $firstName;
  }

  /**
   * Gets the last name of the contact.
   *
   * @return string
   *   The last name of the contact.
   */
  public function getLastName() {
    return $this->last_name;
  }

  /**
   * Sets the last name of the contact.
   *
   * @param string $lastName
   *   The last name of the contact.
   */
  public function setLastName($lastName) {
    $this->last_name = $lastName;
  }

  /**
   * Gets the email addresses of the contact.
   *
   * @return array
   *   The email addresses of the contact.
   */
  public function getEmailAddresses() {
    return $this->email_addresses;
  }

  /**
   * Sets the email addresses of the contact.
   *
   * @param array $emailAddresses
   *   The emails addresses.
   */
  public function setEmailAddresses(array $emailAddresses) {
    $this->email_addresses = $emailAddresses;
  }

  /**
   * Gets the company name of the contact.
   *
   * @return string
   *   The company name of the contact.
   */
  public function getCompanyName() {
    return $this->company_name;
  }

  /**
   * Sets the company name of the contact.
   *
   * @param string $companyName
   *   The company name.
   */
  public function setCompanyName($companyName) {
    $this->company_name = $companyName;
  }

  /**
   * Gets the constant contact lists this contact belongs to.
   *
   * @return array
   *   The list of constant contact lists.
   */
  public function getLists() {
    return $this->lists;
  }

  /**
   * Sets the list of constant contact lists this contact will belong to.
   *
   * @param array $lists
   *   The constant contact lists this contact belongs to.
   */
  public function setLists(array $lists) {
    $this->lists = $lists;
  }

  /**
   * Json Serialize.
   *
   * @return array
   *   Json Serialize.
   */
  public function jsonSerialize() {
    $vars = get_object_vars($this);

    return $vars;
  }

}
