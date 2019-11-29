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

      if (empty($title)) {
        $title = 'Unknown';
      }

      $node = array_shift($this->nodeStorage->loadByProperties(
        ['title' => $title]));

      if (!$node) {
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

      $this->addArtist($song, $node);
    }

  }

  private function addArtist($song, $album)
  {
    $artistName = $song->get('field_artist')->value;

    $artistNode = array_shift($this->nodeStorage->loadByProperties(
      ['title' => $artistName]));

    if (!$artistNode) {
      $values = [
        'type' => 'artist',
        'title' => $artistName
      ];
      $artistNode = $this->nodeStorage->create($values);
    }
    $artistNode->save();

    $artists = $album->get('field_artist')->referencedEntities();

    $exists = array_filter($artists, function ($artist) use ($artistNode) {
      if ($artist->id() === $artistNode->id()) {
        return true;
      }
      return false;
    });

    if (!$exists) {
      $artists[] = $artistNode;
      $album->set('field_artist', $artists);
      $album->save();

      $artistAlbums = $artistNode->get('field_albums')->referencedEntities();
      $artistAlbums[] = $album;
      $artistNode->set('field_albums', $artistAlbums);
      $artistNode->save();
    }

  }


  public function setFileStorage(FileStorageInterface $fileStorage)
  {

    $this->fileStorage = $fileStorage;
  }

  public function setMediaStorage(SqlEntityStorageInterface $mediaStorage)
  {

    $this->mediaStorage = $mediaStorage;
  }

  public function setNodeStorage(SqlEntityStorageInterface $nodeStorage)
  {

    $this->nodeStorage = $nodeStorage;
  }


}
