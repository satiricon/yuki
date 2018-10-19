<?php

namespace Drupal\yuki\Plugin\Mapper;

use Drupal\yuki\Entity\Mapper;

/**
 * Named Regex Mapper
 *
 * @Mapper(
 *   id = "mapper_regex",
 *   label = @Translation("Regex Mapper")
 * )
 */
class PathInfoMapper extends MapperBase {

  public function map($attribute_name, $data) {

    /** @var Mapper $mapper */
    $mapper = $this->configuration['config'];
    $regex = $mapper->getData();
    $matches = array();
    preg_match($regex, $data,$matches);

    dump($matches);

    return $matches[$attribute_name];
  }


}