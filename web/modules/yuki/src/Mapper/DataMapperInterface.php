<?php

namespace Drupal\yuki\Mapper;

interface DataMapperInterface {

  public function map($attribute_name, $data);

}