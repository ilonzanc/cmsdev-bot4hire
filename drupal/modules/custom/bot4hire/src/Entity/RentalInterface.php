<?php

namespace Drupal\bot4hire\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Rental entities.
 *
 * @ingroup bot4hire
 */
interface RentalInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Rental name.
   *
   * @return string
   *   Name of the Rental.
   */
  public function getName();

  /**
   * Sets the Rental name.
   *
   * @param string $name
   *   The Rental name.
   *
   * @return \Drupal\bot4hire\Entity\RentalInterface
   *   The called Rental entity.
   */
  public function setName($name);

  /**
   * Gets the Rental creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Rental.
   */
  public function getCreatedTime();

  /**
   * Sets the Rental creation timestamp.
   *
   * @param int $timestamp
   *   The Rental creation timestamp.
   *
   * @return \Drupal\bot4hire\Entity\RentalInterface
   *   The called Rental entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Rental published status indicator.
   *
   * Unpublished Rental are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Rental is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Rental.
   *
   * @param bool $published
   *   TRUE to set this Rental to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\bot4hire\Entity\RentalInterface
   *   The called Rental entity.
   */
  public function setPublished($published);

}
