<?php

/**
 * @file
 * Contains rental.page.inc.
 *
 * Page callback for Rental entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Rental templates.
 *
 * Default template: rental.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_rental(array &$variables) {
  // Fetch Rental Entity Object.
  $rental = $variables['elements']['#rental'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
