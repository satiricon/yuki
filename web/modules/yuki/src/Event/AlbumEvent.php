<?php

namespace Drupal\yuki\Event;

use Drupal\hook_event_dispatcher\Event\EventInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\EventDispatcher\Event;

class AlbumEvent extends Event implements NodeEventInterface
{

  const EVENT_ALBUM_NEW = 'yuki.album.new';
  const EVENT_ALBUM_MOD = 'yuki.album.mod';
  const EVENT_ALBUM_DEL = 'yuki.album.del';

  /**
   * @var Node $album
   */
  private $album;

  public function __construct(Node $node)
  {
    $this->setNode($node);
  }

  /**
   * @param Node $node
   * @return AlbumEvent $this
   */
  public function setNode(Node $node)
  {
    $this->checkNodeisAlbum($node);
    $this->album = $node;

    return $this;
  }

  /**
   * @return Node
   */
  public function getNode()
  {

    return $this->album;
  }

  private function checkNodeisAlbum(Node $node)
  {
    if ($node->getType() !== 'album') {
      throw new \InvalidArgumentException("Album Event expects a Node of album type. " .
        $node->getType() . "given.");
    }
  }

  public function getDispatcherType()
  {
     if($this->node->isNew()) {
       return self::EVENT_ALBUM_NEW;
     }
     return self::EVENT_ALBUM_MOD;
  }


}
