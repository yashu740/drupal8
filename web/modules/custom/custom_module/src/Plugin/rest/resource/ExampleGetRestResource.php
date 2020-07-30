<?php

namespace Drupal\custom_module\Plugin\rest\resource;

use Drupal\node\Entity\Node;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "example_get_rest_resource",
 *   label = @Translation("Example get rest resource"),
 *   uri_paths = {
 *     "canonical" = "/example-rest"
 *   }
 * )
 */
class ExampleGetRestResource extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Constructs a Drupal\rest\Plugin\ResourceBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A current user instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxyInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);

    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('example_rest'),
      $container->get('current_user')
    );
  }

  /**
   * Responds to GET requests.
   *
   * Returns a list of bundles for specified entity.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get() {

    $nids = \Drupal::entityQuery('node')
      ->condition('status', 1)
      ->condition('type', 'example_rest_api')
      ->execute();
    $nodes = Node::loadMultiple($nids);

    foreach ($nodes as $node) {

      $json_array['data'][] = [
        'type' => $node->get('type')->target_id,
        'id' => $node->get('nid')->value,
        'attributes' => [
          'title' => $node->get('title')->value,
          'body' => $node->get('body')->value,
          'boolean' => $node->get('field_boolean')->value,
          'pdf' => $node->field_pdf->entity->getFileUri(),
          'image' => $node->field_images->entity->getFileUri(),
          'number' => $node->get('field_contact')->value,
          'selection' => $node->get('field_select')->value,
          'Description' => $node->get('field_text')->value,
          'url' => $node->get('field_url')->uri,

        ],
      ];
    }

    $response = new ResourceResponse($json_array);
    $response->addCacheableDependency($json_array);
    return $response;
  }

}
