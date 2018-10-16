<?php

namespace Drupal\yuki\Commands;

use Drupal\Core\Entity\Sql\SqlEntityStorageInterface;
use Drupal\file\FileStorageInterface;

class AlbumsCommands
{


  /** @var FileStorageInterface */
  private $fileStorage;

  /** @var SqlEntityStorageInterface */
  private $mediaStorage;

  /** @var SqlEntityStorageInterface */
  private $nodeStorage;

  /**
   * @command yuki:media:albums
   * @aliases yual
   *
   */
  public function createAlbums()
  {

    $query = $this->mediaStorage->getQuery();

    $ids = $query->condition('bundle', 'song')
      ->execute();

    //dump(\Drupal::entityManager()->getFieldDefinitions('media', 'song'));

    $songs = $this->mediaStorage->loadMultiple($ids);

    foreach ($songs as $song) {

      $title = $song->get('field_album')->value;

      if(empty($title)){
        $title = 'Unknown';
      }

      $node = array_shift($this->nodeStorage->loadByProperties(
        ['title' => $title]));

      if(!$node) {
        $values = [
          'type' => 'album',
          'title' => $title
        ];
        /** @var NodeInterface $node */
        $node = $this->nodeStorage->create($values);

      }

      $values = $node->get('field_song')->getValue();
      $values[] = $song;
      $node->set('field_song', $values);

      $node->save();
    }

  }


  public function setFileStorage(FileStorageInterface $fileStorage) {

    $this->fileStorage = $fileStorage;
  }

  public function setMediaStorage(SqlEntityStorageInterface $mediaStorage) {

    $this->mediaStorage = $mediaStorage;
  }

  public function setNodeStorage(SqlEntityStorageInterface $nodeStorage) {

    $this->nodeStorage = $nodeStorage;
  }


}