<?php

namespace Drupal\yuki\Event;

use Drupal\file\Entity\File;
use Symfony\Component\EventDispatcher\Event;

class NewFileEvent extends Event
{

  const EVENT_NAME = 'yuki.new_file';

  private $file;

  private $bundle;


  public function setBundle($bundle)
  {

    $this->bundle = $bundle;
  }

  public function getBundle(){

    return $this->bundle;
  }

  /**
   * @param File $file
   */
  public function setFile(File $file)
  {

    $this->file = $file;
  }

  /**
   * @return File
   */
  public function getFile()
  {
    return $this->file;
  }

}