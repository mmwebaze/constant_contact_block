<?php

namespace Drupal\constant_contact_block\items;


abstract class BaseItem implements \JsonSerializable{
  //protected $id;
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