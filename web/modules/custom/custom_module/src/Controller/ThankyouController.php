<?php

namespace Drupal\custom_module\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the Example module.
 */
class ThankyouController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function successPage() {
    $result = \Drupal::database()->select('register', 'n')
      ->fields('n', ['name', 'email', 'gender', 'phone', 'address', 'exercise', 'privacy_policy',
      ]
      )
      ->execute()->fetchAllAssoc('name');
    // Create the row element.
    $rows = [];
    foreach ($result as $row => $content) {
      $rows[] = [
        'data' => [$content->name, $content->email, $content->gender,
          $content->phone, $content->address, $content->exercise, $content->privacy_policy,
        ],
      ];
    }
    // Create the header.
    $header = ['name', 'email', 'gender', 'phone', 'address', 'exercise', 'privacy_policy',
    ];
    $output = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];
    return $output;
  }

}
