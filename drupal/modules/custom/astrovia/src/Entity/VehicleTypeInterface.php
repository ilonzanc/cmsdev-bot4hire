<?php

namespace Drupal\astrovia\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Vehicle Type entities.
 *
 * @ingroup astrovia
 */
interface VehicleTypeInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Vehicle Type name.
   *
   * @return string
   *   Name of the Vehicle Type.
   */
  public function getName();

  /**
   * Sets the Vehicle Type name.
   *
   * @param string $name
   *   The Vehicle Type name.
   *
   * @return \Drupal\astrovia\Entity\VehicleTypeInterface
   *   The called Vehicle Type entity.
   */
  public function setName($name);

  /**
   * Gets the Vehicle Type creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Vehicle Type.
   */
  public function getCreatedTime();

  /**
   * Sets the Vehicle Type creation timestamp.
   *
   * @param int $timestamp
   *   The Vehicle Type creation timestamp.
   *
   * @return \Drupal\astrovia\Entity\VehicleTypeInterface
   *   The called Vehicle Type entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Vehicle Type published status indicator.
   *
   * Unpublished Vehicle Type are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Vehicle Type is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Vehicle Type.
   *
   * @param bool $published
   *   TRUE to set this Vehicle Type to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\astrovia\Entity\VehicleTypeInterface
   *   The called Vehicle Type entity.
   */
  public function setPublished($published);

}
