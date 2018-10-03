<?php

namespace Drupal\yuki\Plugin\media\Metadata;

interface ResolverInterface {

  public function get($data);

  public function setDataSource($dataSource);

}