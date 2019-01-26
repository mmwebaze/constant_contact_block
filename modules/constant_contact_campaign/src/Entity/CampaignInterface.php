<?php

namespace Drupal\constant_contact_campaign\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Campaign entities.
 *
 * @ingroup constant_contact_campaign
 */
interface CampaignInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Campaign name.
   *
   * @return string
   *   Name of the Campaign.
   */
  public function getName();

  /**
   * Sets the Campaign name.
   *
   * @param string $name
   *   The Campaign name.
   *
   * @return \Drupal\constant_contact_campaign\Entity\CampaignInterface
   *   The called Campaign entity.
   */
  public function setName($name);

  /**
   * Gets the Campaign creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Campaign.
   */
  public function getCreatedTime();

  /**
   * Sets the Campaign creation timestamp.
   *
   * @param int $timestamp
   *   The Campaign creation timestamp.
   *
   * @return \Drupal\constant_contact_campaign\Entity\CampaignInterface
   *   The called Campaign entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Campaign published status indicator.
   *
   * Unpublished Campaign are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Campaign is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Campaign.
   *
   * @param bool $published
   *   TRUE to set this Campaign to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\constant_contact_campaign\Entity\CampaignInterface
   *   The called Campaign entity.
   */
  public function setPublished($published);

}
