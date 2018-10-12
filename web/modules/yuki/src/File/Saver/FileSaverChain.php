<?php

namespace Drupal\yuki\File\Saver;

final class FileSaverChain implements SaverChainInterface {

  private $savers;

  public function save($object)
  {
    foreach($this->savers as $saver){
      $saver->setFileInfo($object);
      if($saver->isExtensionRelevant($object)){
        return $saver->save();
      }
    }
    return false;
  }

  public function addSaver(SaverInterface $saver)
  {
    $this->savers[] = $saver;
  }

  public function getSavers()
  {
    return $this->savers;
  }
}