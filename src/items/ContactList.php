<?php

namespace Drupal\constant_contact_block\items;

/**
 *
 */
class ContactList implements \JsonSerializable {
  private $name;
  private $status;

  /**
   *
   */
  public function __construct($name, $status) {
    $this->name = $name;
    $this->status = $status;
  }

  /**
   *
   */
  public function getName() {
    return $this->name;
  }

  /**
   *
   */
  public function getStatus() {
    return $this->status;
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
