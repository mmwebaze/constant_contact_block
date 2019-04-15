<?php

namespace Drupal\constant_contact_block\items;

/**
 *
 */
class EmailAddress implements \JsonSerializable {
  private $email_address;

  /**
   *
   */
  public function __construct($email_address) {
    $this->email_address = $email_address;
  }

  /**
   * @return string
   */
  public function getEmailAddress() {
    return $this->email_address;
  }

  /**
   * @param string $email_address
   */
  public function setEmailAddress($email_address) {
    $this->email_address = $email_address;
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
