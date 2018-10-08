<?php

namespace Drupal\yuki\Commands\Events;

use Drupal\file\Entity\File;
use Symfony\Component\EventDispatcher\Event;

class NewFileEvent extends Event
{

  const EVENT_NAME = 'yuki_command_new_file';

  private $file;


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