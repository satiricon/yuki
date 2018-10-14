<?php

namespace Drupal\yuki\Commands;

use Drupal\Core\Entity\Sql\SqlEntityStorageInterface;
use Drupal\file\FileStorageInterface;

class MediaCommands
{


  /** @var FileStorageInterface */
  private $fileStorage;

  /** @var  */
  private $mediaStorage;

  /**
   * @command yuki:media:albums
   * @aliases yual
   *
   */
  public function createAlbums()
  {
    $this->fileStorage;

  }


  public function setFileStorage(FileStorageInterface $fileStorage) {

    $this->fileStorage = $fileStorage;
  }

  public function setMediaStorage(SqlEntityStorageInterface $mediaStorage) {

    $this->mediaStorage = $mediaStorage;
  }

}