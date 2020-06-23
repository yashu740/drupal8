<?php
namespace Drupal\custom_module\Controller;
 
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Database\Database;
 
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
  public function successpage() {
  
    $result = \Drupal::database()->select('register', 'n')
            ->fields('n', array('name', 'email', 'gender', 'phone','address','exercise' , 'privacy_policy'))
            ->execute()->fetchAllAssoc('name');
    // Create the row element.
    $rows = array();
    foreach ($result as $row => $content) {
      $rows[] = array(
        'data' => array($content->name, $content->email, $content->gender, $content->phone, $content->address, $content->exercise, $content->privacy_policy));
    }
    // Create the header.
    $header = array( 'name', 'email', 'gender', 'phone','address','exercise', 'privacy_policy');
    $output = array(
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows
    );
    return $output;
  }
  }
 
