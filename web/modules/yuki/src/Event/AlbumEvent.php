<?php

namespace Drupal\yuki\Event;

use Drupal\hook_event_dispatcher\Event\EventInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\EventDispatcher\Event;

class AlbumEvent extends Event implements EventInterface
{

  const EVENT_ALBUM_NEW = 'yuki.album.new';
  const EVENT_ALBUM_MOD = 'yuki.album.mod';
  const EVENT_ALBUM_DEL = 'yuki.album.del';

  /**
   * @var Node $album
   */
  private $album;

  public function __construct(Node $album)
  {
    $this->setAlbum($album);
  }

  /**
   * @param Node $album
   * @return AlbumEvent $this
   */
  public function setAlbum(Node $album)
  {
    $this->checkNodeisAlbum($album);
    $this->album = $album;

    return $this;
  }

  /**
   * @return Node
   */
  public function getAlbum()
  {

    return $this->album;
  }

  private function checkNodeisAlbum(Node $album)
  {
    if ($album->getType() !== 'album') {
      throw new \InvalidArgumentException("Album Event expects a Node of album type. " .
        $album->getType() . "given.");
    }
  }

  public function getDispatcherType()
  {
     if($this->album->isNew()) {
       return self::EVENT_ALBUM_NEW;
     }
     return self::EVENT_ALBUM_MOD;
  }


}
