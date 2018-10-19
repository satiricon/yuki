<?php

namespace Drupal\yuki\Plugin\Mapper;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\media\MediaSourceInterface;

interface MapperInterface extends PluginInspectionInterface {

  public function map($attribute_name, MediaSourceInterface $mediaSource);

}