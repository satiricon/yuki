<?php

namespace Drupal\yuki\Plugin\Mapper;


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
    $matches = array();
    preg_match($this->getRegexp(), $data,$matches);

    return $matches[$attribute_name];
  }


}