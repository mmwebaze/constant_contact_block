<?php

/**
 * @file
 * Contains campaign.page.inc.
 *
 * Page callback for Campaign entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Campaign templates.
 *
 * Default template: campaign.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_campaign(array &$variables) {
  // Fetch Campaign Entity Object.
  $campaign = $variables['elements']['#campaign'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
