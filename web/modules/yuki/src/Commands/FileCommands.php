<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 9/30/18
 * Time: 7:01 PM
 */

namespace Drupal\yuki\Commands;

use Drupal\file\Entity\File;
use Drupal\file\FileStorageInterface;
use Drupal\yuki\File\Saver\SaverChainInterface;

class FileCommands
{

  /**
   * @var FileStorageInterface
   */
  private $fileStorage;

  private $saverChain;

  /**
   *
   * @command yuki:files:delete
   * @aliases yufid
   *
   */
  public function deleteFiles()
  {
    $query = $this->fileStorage->getQuery();

    $ids = $query->execute();

    foreach($ids as $id){
      /* @var $media File */
      $file = $this->fileStorage->load($id);
      $file->delete();
    }
  }

  /**
   * @param $path
   *  The Path
   *
   * @command yuki:files
   * @aliases yufi
   *
   */
  public function files(string $path, string $regex)
  {

    $path = realpath($path);

    $objects = file_scan_directory($path, $regex);

    foreach($objects as $name => $object)
    {
      $file = $this->saverChain->save($object);
    }
  }
  /**
   * @command yuki:files:update
   * @aliases yufiu
   *
   */
  public function filesUpdate(){
    $query = $this->fileStorage->getQuery();

    $ids = $query->execute();

    foreach($ids as $id){
      /* @var $media File */
      $file = $this->fileStorage->load($id);
      $file->save();
    }
  }

  public function setSaverChain(SaverChainInterface $saverChain)
  {
    $this->saverChain = $saverChain;
  }


  public function setFileStorage(FileStorageInterface $fileStorage)
  {

    $this->fileStorage = $fileStorage;
  }

}
