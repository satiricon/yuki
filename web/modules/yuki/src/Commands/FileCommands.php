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
  public function files($path)
  {
    $path = realpath($path);

    //Ver si conviene
    //$objects = file_scan_directory($path, '/.+/');

    $objects = new \RecursiveIteratorIterator(
      new \RecursiveDirectoryIterator($path),
      \RecursiveIteratorIterator::SELF_FIRST);
    foreach($objects as $name => $object) {
      /** @var $object \SplFileInfo */
      if(!$object->isDir()) {
        $this->addFile($object);
      }

    }


  }

  /**
   * @param \SplFileInfo $fileInfo
   */
  public function addFile(\SplFileInfo $fileInfo) {
    $values = $this->mapFileInfo($fileInfo);

    $file = $this->fileStorage->create($values);
    $file->save();
  }

  /**
   * @param \SplFileInfo $fileInfo
   *
   * @return array
   */
  public function mapFileInfo(\SplFileInfo $fileInfo) {
    return [
      'filename'  => $fileInfo->getFilename(),
      'uri'       =>  'file:///'.$fileInfo->getRealPath(),
      'filesize'  =>  $fileInfo->getSize(),
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