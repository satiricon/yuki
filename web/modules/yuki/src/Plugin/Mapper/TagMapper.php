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
class TagMapper extends MapperBase
{

  public function map($attribute_name, MediaSourceInterface $mediaSource)
  {
    /** @var Mapper $mapper */
    $mapper = $this->configuration['config'];

    $rows = explode(PHP_EOL, $mapper->getData());
    foreach ($rows as $row) {
      if (strpos($row, $attribute_name) === 0) {
        $rules = explode(' : ', $row);
        $value = $this->getTag($mediaSource, $rules[1]);

        if ((!empty($rules[2]) && $rules[2] !== "\r") && $value) {

          $rules[2] = trim($rules[2], "\r");

          $matches = [];
          preg_match($rules[2], $value, $matches);

          $value = $matches[$attribute_name];
        }
        if(!empty($value)) {
          return $value;
        }

      }
    }

    return null;
  }


  public function getTag(HasTagInterface $mediaSource, $tag)
  {
    return $mediaSource->getTag($tag);
  }
}
