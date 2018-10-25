<?php
namespace Drupal\constant_contact_campaign\Entity;

class Campaign implements \JsonSerializable {

  protected $name;
  protected $subject;
  protected $from_name;
  protected $from_email;
  protected $reply_to_email;
  protected $is_permission_reminder_enabled;
  protected $permission_reminder_text;
  protected $is_view_as_webpage_enabled;
  protected $view_as_web_page_text;
  protected $view_as_web_page_link_text;
  protected $greeting_salutations;
  protected $greeting_name;
  protected $greeting_string;
  protected $email_content;
  protected $text_content;
  protected $email_content_format;
  protected $style_sheet;
  protected $message_footer;

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