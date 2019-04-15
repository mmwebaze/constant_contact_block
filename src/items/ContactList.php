<?php

namespace Drupal\constant_contact_block\items;

/**
 * Defines a constant contact list.
 */
class ContactList implements \JsonSerializable {
  private $name;
  private $status;

  /**
   * Creates a new Contact List.
   *
   * @param string $name
   *   The name of the list.
   * @param string $status
   *   The status of the list.
   */
  public function __construct($name, $status) {
    $this->name = $name;
    $this->status = $status;
  }

  /**
   * Gets the name of the list.
   *
   * @return string
   *   The list name.
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Gets the status of the list.
   *
   * @return string
   *   The list status
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
