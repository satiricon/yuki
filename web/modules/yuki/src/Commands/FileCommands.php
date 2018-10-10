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
use Drupal\yuki\Commands\Events\NewFileEvent;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class FileCommands
{

  /**
   * @var $fileStorage FileStorageInterface
   */
  private $fileStorage;

  /**
   * @var $eventDispatcher EventDispatcherInterface
   */
  private $eventDispatcher;

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
      $file = $this->addFile($object);

      $this->dispatchFileEvent($file);
    }

  }

  /**
   * @param File $file
   */
  public function dispatchFileEvent(File $file)
  {
    $event = new NewFileEvent();
    $event->setFile($file);

    //$this->eventDispatcher->dispatch(NewFileEvent::EVENT_NAME, $event);

  }


  public function addFile($fileObject) {

    $values = $this->mapFileInfo($fileObject);

    $file = $this->fileStorage->create($values);
    $file->save();

    return $file;
  }

  /**
   * @return array
   */
  public function mapFileInfo($fileObject) {
    return [
      'filename'  => $fileObject->filename,
      'uri'       =>  'media://'.$fileObject->uri,
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


  /**
   * @param EventDispatcherInterface $eventDispatcher
   */
  public function setEventDispatcher(EventDispatcherInterface $eventDispatcher) {

    $this->eventDispatcher = $eventDispatcher;
  }

}