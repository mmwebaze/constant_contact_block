<?php

namespace Drupal\constant_contact_block\items;

/**
 * Defines a constant contact email address.
 */
class EmailAddress implements \JsonSerializable {
  /**
   * The email address.
   *
   * @var string
   */
  private $email_address;

  /**
   * EmailAddress constructor.
   *
   * @param string $emailAddress
   *   The email address.
   */
  public function __construct($emailAddress) {
    $this->email_address = $emailAddress;
  }

  /**
   * Gets the email address.
   *
   * @return string
   *   The email address.
   */
  public function getEmailAddress() {
    return $this->email_address;
  }

  /**
   * Sets the email address.
   *
   * @param string $emailAddress
   *   The email address to be set.
   */
  public function setEmailAddress($emailAddress) {
    $this->email_address = $emailAddress;
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
