<?php

namespace Drupal\yuki\Plugin\Mapper;

use Drupal\Component\Plugin\PluginInspectionInterface;

interface MapperInterface extends PluginInspectionInterface {

  public function map($attribute_name, $data);

}