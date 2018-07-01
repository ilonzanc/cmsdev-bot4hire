<?php

/**
 * @file
 * Contains vehicle_type.page.inc.
 *
 * Page callback for Vehicle Type entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Vehicle Type templates.
 *
 * Default template: vehicle_type.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_vehicle_type(array &$variables) {
  // Fetch VehicleType Entity Object.
  $vehicle_type = $variables['elements']['#vehicle_type'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
