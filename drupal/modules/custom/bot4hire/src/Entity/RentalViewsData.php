<?php

namespace Drupal\bot4hire\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Rental entities.
 */
class RentalViewsData extends EntityViewsData {

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
