<?php

namespace Drupal\yuki\File\Saver;

use Drupal\yuki\Event\NewFileEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class SongSaver extends FileSaver {

  /** @var $eventDispatcher EventDispatcherInterface */
  private $eventDispatcher;

  public function save()
  {
    $file = parent::save();
    if($file){
      $event = new NewFileEvent();

      $event->setFile($file);
      $event->setBundle('song');

      $this->getEventDispatcher()->dispatch(NewFileEvent::EVENT_NAME, $event);

      return $file;
    }
    return false;
  }

  /**
   * @param EventDispatcherInterface $eventDispatcher
   */
  public function setEventDispatcher(EventDispatcherInterface $eventDispatcher) {

    $this->eventDispatcher = $eventDispatcher;
  }

  public function getEventDispatcher()
  {

    return $this->eventDispatcher;
  }


}