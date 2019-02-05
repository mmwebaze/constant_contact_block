<?php

/**
 * @file
 * Contains reason.page.inc.
 *
 * Page callback for Reason entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Reason templates.
 *
 * Default template: reason.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_reason(array &$variables) {
  // Fetch Reason Entity Object.
  $reason = $variables['elements']['#reason'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
