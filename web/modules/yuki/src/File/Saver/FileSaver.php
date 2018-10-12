<?php

namespace Drupal\yuki\File\Saver;

use Drupal\file\FileStorageInterface;

abstract class FileSaver implements SaverInterface {

  /** @var $fileStorage FileStorageInterface */
  private $fileStorage;

  private $fileObject;

  private $extensions;

  public function save() {

    if(!$this->fileExists()){
      $values = $this->mapFileInfo();

      $file = $this->getFileStorage()->create($values);
      $file->save();

      return $file;
    }

    return false;
  }

  public function isExtensionRelevant(){
    $fileExtension = pathinfo($this->fileObject->filename, PATHINFO_EXTENSION);
    foreach($this->extensions as $extension){
      if($extension === $fileExtension){
        return true;
      }
    }
  }

  public function fileExists()
  {

    $file = $this->fileStorage->loadByProperties(
      ['uri' => 'media://'.$this->fileObject->uri]);

    if(empty($file)){
      return false;
    }

    return true;
  }


  /**
  * @return array
  */
  public function mapFileInfo() {
    return [
      'filename'  => $this->fileObject->filename,
      'uri'       =>  'media://'.$this->fileObject->uri,
      'status'    => true
    ];
  }

  public function setFileInfo($fileObject)
  {
    $this->fileObject = $fileObject;
  }

  public function getFileInfo(){

    return $this->fileObject;
  }

  /**
   * @param FileStorageInterface $fileStorage
   */
  public function setFileStorage(FileStorageInterface $fileStorage)
  {
    $this->fileStorage = $fileStorage;
  }

  /**
   *
   * @return FileStorageInterface
   */
  public function getFileStorage() {
    return $this->fileStorage;
  }

  public function setFileExtensions(array $extensions) {
    $this->extensions = $extensions;
  }

  public function getFileExtensions() {
    return $this->extensions;
  }


}