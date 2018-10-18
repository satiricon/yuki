<?php

namespace Drupal\yuki\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Url;

interface MapperInterface extends ConfigEntityInterface{

  /**
   * @return string
   */
  public function getPluginId();

  /**
   * @return mixed
   */
  public function getData();

  /**
   * @return mixed
   */
  public function map($attribute_name, $data);


}