<?php

namespace Drupal\yuki\Event;


use Drupal\hook_event_dispatcher\Event\EventInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\EventDispatcher\Event;

class AlbumEventFactory implements NodeEventFactoryInterface
{

  public function create(Node $node) : EventInterface
  {

    return new AlbumEvent($node);
  }
}
