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
use Drupal\yuki\File\Saver\SaverChainInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class FileCommands
{


  private $saverChain;

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

  public function setSaverChain(SaverChainInterface $saverChain) {
    $this->saverChain = $saverChain;
  }

}