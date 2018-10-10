<?php


namespace Drupal\File\Mapper;

class FileMapperCollection implements FileMapperInterface {

  protected $mappers;

  public function map($object)
  {
    $result = [];
    /** @var $mapper FileMapperInterface */
    foreach($this->mappers as $mapper){
      array_merge($result, $mapper->map($object));
    }

    return $result;
  }


  public function setMappers($mappers){
    $this->mappers = $mappers;
  }
}