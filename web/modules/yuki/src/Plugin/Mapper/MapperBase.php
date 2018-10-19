<?php

namespace Drupal\yuki\Plugin\Mapper;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\yuki\Entity\MapperInterface;
use Drupal\yuki\Plugin\Mapper\MapperInterface as PluginMapperInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class MapperBase extends PluginBase implements PluginMapperInterface, ContainerFactoryPluginInterface {

  public function __construct(array $configuration, $plugin_id, $plugin_definition)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    if (!isset($configuration['config'])) {
      throw new PluginException('Missing Importer configuration.');
    }

    if (!$configuration['config'] instanceof MapperInterface) {
      throw new PluginException('Wrong Importer configuration.');
    }
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

}