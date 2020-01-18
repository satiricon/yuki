<?php

namespace Drupal\yuki\Event;

use Drupal\hook_event_dispatcher\Event\EventInterface;
use Drupal\node\Entity\Node;

interface NodeEventFactoryInterface  {

  public function create(Node $node): EventInterface;

}
