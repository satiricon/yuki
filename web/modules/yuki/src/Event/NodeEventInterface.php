<?php


namespace Drupal\yuki\Event;


use Drupal\node\Entity\Node;

interface NodeEventInterface extends EventInterface
{

  public function getNode();

  public function setNode(Node $node);

}
