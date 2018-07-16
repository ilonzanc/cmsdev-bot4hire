<?php

namespace Drupal\astrovia\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Profile entities.
 *
 * @ingroup astrovia
 */
interface ProfileInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Profile name.
   *
   * @return string
   *   Name of the Profile.
   */
  public function getName();

  /**
   * Sets the Profile name.
   *
   * @param string $name
   *   The Profile name.
   *
   * @return \Drupal\astrovia\Entity\ProfileInterface
   *   The called Profile entity.
   */
  public function setName($name);

  /**
   * Gets the Profile creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Profile.
   */
  public function getCreatedTime();

  /**
   * Sets the Profile creation timestamp.
   *
   * @param int $timestamp
   *   The Profile creation timestamp.
   *
   * @return \Drupal\astrovia\Entity\ProfileInterface
   *   The called Profile entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Profile published status indicator.
   *
   * Unpublished Profile are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Profile is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Profile.
   *
   * @param bool $published
   *   TRUE to set this Profile to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\astrovia\Entity\ProfileInterface
   *   The called Profile entity.
   */
  public function setPublished($published);

}
