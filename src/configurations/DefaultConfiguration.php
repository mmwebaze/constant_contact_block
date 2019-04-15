<?php

namespace Drupal\constant_contact_block\configurations;

/**
 * Default configuration messages for unsubscribe reasons.
 */
class DefaultConfiguration {

  /**
   * Gets the default unsubscribe reasons.
   *
   * @return array
   *   An array of default unsubscribe reasons.
   */
  public static function getUnsubscribeReasons() {

    $reasons = [
      'I no longer want to receive these emails***',
      'I never signed up for this mailing list',
      'The emails are inappropriate',
      'The emails are spam and should be reported##',
    ];

    return $reasons;
  }

  /**
   * Gets the default unsubscribe title.
   *
   * @return string
   *   The default unsubscribe title.
   */
  public static function getUnsubscribeTitle() {
    $title = 'If you have a moment, please let us know why you unsubscribed:';
    return $title;
  }

  /**
   * Gets the default unsubscribe message.
   *
   * @return string
   *   The default unsubscribe message.
   */
  public static function getUnsubscribeMessage() {

    $message = "<h2>Unsubscribe Successful</h2>Are you really sure you wanted to leave? 
If so, we're sad to see you go. We work hard to curate the right content for you. 
<br/>We would love feedback on why you left to know how we can improve.<br/>
<b>Thanks,<br/>Administrator & Editor </b>";

    return $message;
  }

}
