<?php

namespace Drupal\astrovia\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Planet entities.
 */
class PlanetViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
