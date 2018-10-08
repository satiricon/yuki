<?php

namespace Drupal\yuki\Commands\EventSubscriber;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\yuki\Commands\Events\NewFileEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NewFileSubscriber implements EventSubscriberInterface
{

  /**
   * @var EntityStorageInterface
   */
  private $mediaStorage;
  /**
   * Returns an array of event names this subscriber wants to listen to.
   *
   * The array keys are event names and the value can be:
   *
   *  * The method name to call (priority defaults to 0)
   *  * An array composed of the method name to call and the priority
   *  * An array of arrays composed of the method names to call and respective
   *    priorities, or 0 if unset
   *
   * For instance:
   *
   *  * array('eventName' => 'methodName')
   *  * array('eventName' => array('methodName', $priority))
   *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
   *
   * @return array The event names to listen to
   */
  public static function getSubscribedEvents()
  {
    return [
      // Static class constant => method on this class.
      NewFileEvent::EVENT_NAME => 'onNewFile',
    ];
  }


  public function onNewFile(NewFileEvent $event)
  {
    $file = $event->getFile();

    $media = $this->mediaStorage->create(['bundle' => 'song']);
    $mediaSource = $media->getSource();
    $mediaConfiguration = $mediaSource->getConfiguration();

    $media->set($mediaConfiguration['source_field'], $file);



    $media->save();

  }

  public function setMediaStorage(EntityStorageInterface $mediaStorage)
  {
    $this->mediaStorage = $mediaStorage;
  }
}