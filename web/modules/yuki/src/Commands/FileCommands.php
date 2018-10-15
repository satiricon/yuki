<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 9/30/18
 * Time: 7:01 PM
 */

namespace Drupal\yuki\Commands;

use Drupal\yuki\File\Saver\SaverChainInterface;

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

    dump(get_class(\Drupal::entityTypeManager()->getStorage('mapper')));

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