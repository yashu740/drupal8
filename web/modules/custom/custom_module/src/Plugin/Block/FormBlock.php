<?php

namespace Drupal\custom_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'FormBlock' block.
 *
 * @Block(
 *   id = "custom_module_example_block",
 *   admin_label = @Translation("Example block"),
 *   category = @Translation("Custom example block")
 * )
 */
class FormBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $form = \Drupal::formBuilder()->getForm('Drupal\custom_module\Form\CustomForm');

    return $form;
  }

}
