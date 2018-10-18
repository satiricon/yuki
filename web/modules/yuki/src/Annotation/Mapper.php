<?php

namespace Drupal\yuki\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Importer item annotation object.
 *
 * @see \Drupal\yuki\Plugin\Mapper\MapperManager
 *
 * @Annotation
 */
class Mapper extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}