<?php

namespace Drupal\yuki\File\Saver;

interface SaverChainInterface {

  public function save($object);

  public function addSaver(SaverInterface $saver);

  public function getSavers();


}