<?php

namespace Drupal\custom_module;

/**
 * Mail Service.
 */
class HelloServices {

  /**
   * {@inheritdoc}
   */
  public function sayHello($module, $keys, $to, $params, $langcode, $from, $send) {
    $mailManager = \Drupal::service('plugin.manager.mail');

    $result2 = $mailManager->mail($module, $keys, $to, $langcode, $params, $from, $send);
    if ($result2['result'] == TRUE) {
      drupal_set_message(t('Your message has been sent.'));
    }
    else {
      drupal_set_message(t('There was a problem sending your message and it was not sent.'), 'error');
    }

  }

}
