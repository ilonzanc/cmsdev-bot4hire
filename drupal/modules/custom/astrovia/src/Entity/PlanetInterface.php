<?php

namespace Drupal\astrovia\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Planet entities.
 *
 * @ingroup astrovia
 */
interface PlanetInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Planet name.
   *
   * @return string
   *   Name of the Planet.
   */
  public function getName();

  /**
   * Sets the Planet name.
   *
   * @param string $name
   *   The Planet name.
   *
   * @return \Drupal\astrovia\Entity\PlanetInterface
   *   The called Planet entity.
   */
  public function setName($name);

  /**
   * Gets the Planet creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Planet.
   */
  public function getCreatedTime();

  /**
   * Sets the Planet creation timestamp.
   *
   * @param int $timestamp
   *   The Planet creation timestamp.
   *
   * @return \Drupal\astrovia\Entity\PlanetInterface
   *   The called Planet entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Planet published status indicator.
   *
   * Unpublished Planet are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Planet is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Planet.
   *
   * @param bool $published
   *   TRUE to set this Planet to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\astrovia\Entity\PlanetInterface
   *   The called Planet entity.
   */
  public function setPublished($published);

}
