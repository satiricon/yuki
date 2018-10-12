<?php

namespace Drupal\yuki\Service;

use Drupal\yuki\File\Saver\FileSaverChain;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SaverPass implements CompilerPassInterface {

  /**
   * You can modify the container here before it is dumped to PHP code.
   */
  public function process(ContainerBuilder $container)
  {

    if (!$container->has('yuki.saver_chain')) {
      return;
    }

    $definition = $container->findDefinition('yuki.saver_chain');

    $taggedServices = $container->findTaggedServiceIds('yuki.saver');

    foreach ($taggedServices as $id => $tags) {
      $definition->addMethodCall('addSaver', array(new Reference($id)));
    }
  }
}