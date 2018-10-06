<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 9/30/18
 * Time: 7:01 PM
 */

namespace Drupal\yuki\Commands;


use Drupal\file\FileStorageInterface;

class FileCommands
{

  /**
   * @var $fileStorage FileStorageInterface
   */
  private $fileStorage;

  /**
   * @param $path
   *  The Path
   *
   * @command yuki:files
   * @aliases yufi
   *
   */
  public function files($path, $regex)
  {
    $path = realpath($path);

    //Ver si conviene
    $objects = file_scan_directory($path, $regex);

    foreach($objects as $name => $object)
    {
      $this->addFile($object);
    }

  }

  public function addFile($fileObject) {
    $values = $this->mapFileInfo($fileObject);

    $file = $this->fileStorage->create($values);
    $file->save();
  }

  /**
   * @return array
   */
  public function mapFileInfo($fileObject) {
    return [
      'filename'  => $fileObject->filename,
      'uri'       =>  'file://'.$fileObject->uri,
      'status'    => true
    ];
  }


  /**
   * @param FileStorageInterface $fileStorage
   */
  public function setFileStorage(FileStorageInterface $fileStorage) {

    $this->fileStorage = $fileStorage;
  }

  /**
   *
   * @return FileStorageInterface
   */
  public function getFileStorage() {

    return $this->fileStorage;
  }


}