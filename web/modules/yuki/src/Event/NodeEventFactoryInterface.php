<?php

namespace Drupal\yuki\Event;

use Drupal\node\Entity\Node;

interface NodeEventFactoryInterface  {

  public function create(Node $node): NodeEventInterface;

}
