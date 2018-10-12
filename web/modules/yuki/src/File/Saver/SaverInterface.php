<?php

namespace Drupal\yuki\File\Saver;

use Drupal\file\FileStorageInterface;

interface SaverInterface {

  public function save();

  public function setFileInfo($fileObject);

  /**
   * @return boolean
   */
  public function fileExists();

  /**
   * @param FileStorageInterface $fileStorage
   */
  public function setFileStorage(FileStorageInterface $fileStorage);

  /**
   *
   * @return FileStorageInterface
   */
  public function getFileStorage();

}