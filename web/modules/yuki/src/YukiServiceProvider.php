<?php

namespace Drupal\yuki;

use Drupal\yuki\Service\DataMapperPass;
use Drupal\yuki\Service\SaverPass;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderInterface;

final class YukiServiceProvider implements ServiceProviderInterface {

  /**
   * Registers services to the container.
   *
   * @param \Drupal\Core\DependencyInjection\ContainerBuilder $container
   *   The ContainerBuilder to register services to.
   */
  public function register(ContainerBuilder $container) {
    $container->addCompilerPass(new SaverPass());
  }

}