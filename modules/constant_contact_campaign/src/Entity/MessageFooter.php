<?php

namespace Drupal\constant_contact_campaign\Entity;


class MessageFooter implements \JsonSerializable {

  protected $organization_name;
  protected $address_line_1;
  protected $address_line_2;
  protected $address_line_3;
  protected $city;
  protected $state;
  protected $international_state;
  protected $postal_code;
  protected $country;
  protected $include_forward_email;
  protected $forward_email_link_text;
  protected $include_subscribe_link;
  protected $subscribe_link_text;

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