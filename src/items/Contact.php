<?php

namespace Drupal\constant_contact_block\items;


class Contact implements \JsonSerializable {
  private $status;
  private $first_name;
  private $last_name;
  private $email_addresses = array();
  private $company_name;
  private $lists = array();

  public function __construct($first_name, $last_name, $company_name, $status,
                              array $email_addresses, array $lists) {
    $this->status = $status;
    $this->first_name = $first_name;
    $this->last_name = $last_name;
    $this->email_addresses = $email_addresses;
    $this->lists = $lists;
    $this->company_name = $company_name;
  }

  /**
   * @return string
   */
  public function getStatus() {
    return $this->status;
  }

  /**
   * @param string $status
   *
   * can take on any of the following values (ACTIVE, UNCONFIRMED, OPTOUT, REMOVED
   * NON_SUBSCRIBER, VISITOR, VISITOR)
   */
  public function setStatus($status) {
    $this->status = $status;
  }

  /**
   * @return string
   */
  public function getFirstName() {
    return $this->first_name;
  }

  /**
   * @param string $first_name
   */
  public function setFirstName($first_name) {
    $this->first_name = $first_name;
  }

  /**
   * @return string
   */
  public function getLastName() {
    return $this->last_name;
  }

  /**
   * @param string $last_name
   */
  public function setLastName($last_name) {
    $this->last_name = $last_name;
  }

  /**
   * @return array
   */
  public function getEmailAddresses() {
    return $this->email_addresses;
  }

  /**
   * @param array $email_addresses
   */
  public function setEmailAddresses($email_addresses) {
    $this->email_addresses = $email_addresses;
  }

  /**
   * @return string
   */
  public function getCompanyName() {
    return $this->company_name;
  }

  /**
   * @param string $company_name
   */
  public function setCompanyName($company_name) {
    $this->company_name = $company_name;
  }

  /**
   * @return array
   */
  public function getLists() {
    return $this->lists;
  }

  /**
   * @param array $lists
   */
  public function setLists($lists) {
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