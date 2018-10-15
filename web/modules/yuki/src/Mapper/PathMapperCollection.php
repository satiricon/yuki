<?php

namespace Drupal\yuki\Mapper;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\yuki\Entity\PathInfoMapper;

class PathMapperCollection {

  protected $mappers;

  protected $mapperStorage;

  public function map($attribute_name, $path){

    foreach($this->mappers as $mapper){

      if($value = $mapper->map($attribute_name, $path)){


        return $value;
      }
    }

    return false;
  }


  public function addPathInfoMapper(PathInfoMapper $mapper)
  {
    $this->mappers[] = $mapper;
  }

  public function getMappers(){

    return $this->mappers;
  }

  public function setMapperStorage(ConfigEntityStorageInterface $mapperStorage)
  {
    $this->mapperStorage = $mapperStorage;
    $query = $mapperStorage->getQuery();

    $ids = $query->execute();

    $this->mappers = $mapperStorage->loadMultiple($ids);

  }


}