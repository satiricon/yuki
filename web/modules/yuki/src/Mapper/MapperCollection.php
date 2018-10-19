<?php

namespace Drupal\yuki\Mapper;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\media\MediaInterface;
use Drupal\media\MediaSourceInterface;
use Drupal\yuki\Entity\Mapper;
use Drupal\yuki\Entity\MapperInterface;

class MapperCollection {

  /** @var array */
  protected $mappers;

  /** @var ConfigEntityStorageInterface */
  protected $mapperStorage;

  /** @var PluginManagerInterface */
  protected $mapperManager;

  public function map($attribute_name, MediaSourceInterface $mediaSource){

    /** @var Mapper $mapper */
    foreach($this->mappers as $mapper){

      /** @var \Drupal\yuki\Plugin\Mapper\MapperInterface $plugin */
      $plugin = $this->mapperManager->createInstance($mapper->getPluginId(), ['config' => $mapper]);

      if($value = $plugin->map($attribute_name, $mediaSource)){

        return $value;
      }
    }

    return false;
  }

  public function addMapper(MapperInterface $mapper)
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

  /**
   * @return mixed
   */
  public function getMapperStorage()
  {
    return $this->mapperStorage;
  }

  public function setMapperManager(PluginManagerInterface $mapperManager){

    $this->mapperManager = $mapperManager;
  }


}