<?php

namespace Drupal\constant_contact_block\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Reason entities.
 *
 * @ingroup constant_contact_block
 */
interface ReasonInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Reason name.
   *
   * @return string
   *   Name of the Reason.
   */
  public function getName();

  /**
   * Sets the Reason name.
   *
   * @param string $name
   *   The Reason name.
   *
   * @return \Drupal\constant_contact_block\Entity\ReasonInterface
   *   The called Reason entity.
   */
  public function setName($name);

  /**
   * Gets the Reason creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Reason.
   */
  public function getCreatedTime();

  /**
   * Sets the Reason creation timestamp.
   *
   * @param int $timestamp
   *   The Reason creation timestamp.
   *
   * @return \Drupal\constant_contact_block\Entity\ReasonInterface
   *   The called Reason entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Reason published status indicator.
   *
   * Unpublished Reason are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Reason is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Reason.
   *
   * @param bool $published
   *   TRUE to set this Reason to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\constant_contact_block\Entity\ReasonInterface
   *   The called Reason entity.
   */
  public function setPublished($published);

}
