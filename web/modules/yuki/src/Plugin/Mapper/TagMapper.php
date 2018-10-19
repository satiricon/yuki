<?php

namespace Drupal\yuki\Plugin\Mapper;

use Drupal\media\MediaSourceInterface;
use Drupal\yuki\Entity\Mapper;

/**
 * Named Regex Mapper
 *
 * @Mapper(
 *   id = "mapper_tag",
 *   label = @Translation("Tag Mapper")
 * )
 */
class TagMapper extends MapperBase {

  public function map($attribute_name, MediaSourceInterface $mediaSource)
  {
    /** @var Mapper $mapper */
    $mapper = $this->configuration['config'];
    $rows = explode('\n', $mapper->getData());

    foreach ($rows as $row){
      if(strpos($row, $attribute_name) === 0){
        $rules = explode(' : ', $row);


      }
    }

    return null;

  }
}