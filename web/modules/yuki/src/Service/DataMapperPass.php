<?php

namespace Drupal\yuki\Service;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DataMapperPass implements CompilerPassInterface
{


  public function process(ContainerBuilder $container)
  {

    if (!$container->has('yuki.mapper_chain')) {
      return;
    }

    $definition = $container->findDefinition('yuki.mapper_chain');

    /**
     * @var $storage \Drupal\Core\Config\Entity\ConfigEntityStorage
     */
    $storage = $container->get('yuki.mapper_storage');

    $query = $storage->getQuery();

    $ids = $query->execute();

    $mappers = $storage->loadMultiple($ids);

    foreach ($mappers as $mapper) {
      $definition->addMethodCall('addMapper', array($mapper));
    }
  }


}