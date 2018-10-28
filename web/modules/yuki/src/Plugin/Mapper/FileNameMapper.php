<?php

namespace Drupal\yuki\Plugin\Mapper;

use Drupal\media\MediaSourceInterface;
use Drupal\yuki\Entity\Mapper;

/**
 * Named Regex Mapper
 *
 * @Mapper(
 *   id = "mapper_regex",
 *   label = @Translation("Regex Mapper")
 * )
 */
class FileNameMapper extends MapperBase {

  public function map($attribute_name, MediaSourceInterface $mediaSource) {

    /** @var Mapper $mapper */
    $mapper = $this->configuration['config'];
    $regex = $mapper->getData();

    $matches = array();
    preg_match($regex, $this->getPath($mediaSource),$matches);

    return $matches[$attribute_name];
  }

  public function getPath(HasPathInterface $source){

    return pathinfo($source->getPath(), PATHINFO_FILENAME);
  }


}