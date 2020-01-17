<?php

namespace Drupal\yuki\Commands;

use Drupal\Core\Entity\Sql\SqlEntityStorageInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\file\FileStorageInterface;
use Psr\Log\LoggerInterface;

class AlbumsCommands
{


  /** @var FileStorageInterface */
  private $fileStorage;

  /** @var SqlEntityStorageInterface */
  private $mediaStorage;

  /** @var SqlEntityStorageInterface */
  private $nodeStorage;

  /** @var LoggerInterface */
  private $logger;

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

    $songs = $this->mediaStorage->loadMultiple($ids);

    foreach ($songs as $song) {

      $title = $song->get('field_album')->value;

      $this->logger->info("========Processing Song: " . $song->get('name')->value);

      if (empty($title)) {
        $title = 'Unknown';
      }

      $this->logger->info("Album Title: " . $title);

      $nodes = $this->nodeStorage->loadByProperties(
        ['title' => $title, 'type' => 'album']);
      $node = array_shift($nodes);

      if (!$node) {
        $this->logger->info("Album $title doesn't exists... Creating");
        $values = [
          'type' => 'album',
          'title' => $title
        ];
        /** @var NodeInterface $node */
        $node = $this->nodeStorage->create($values);

      }

      $node->set('title', $title);

      if (!$this->songAlreadyInAlbum($song)) {
        $this->logger->info("Song not in Album: " . $song->get('name')->value);
        $values = $node->get('field_song')->getValue();
        $values[] = $song;
        $node->set('field_song', $values);
      }

      $node->save();

      $this->addArtist($song, $node);

      $this->logger->info("========End Processing Song");
    }

  }

  private function addArtist($song, $album)
  {
    $artistName = $song->get('field_artist')->value;

    $this->logger->info("Artist: $artistName");

    if (empty($artistName)) {
      $song->save();
      $this->logger->warning("Artist Name is Empty. Song:" . $song->get('name')->value);
      $this->logger->warning("Album:" . $album->get('title')->value);
      $artistName = 'Unknown';
    }

    $nodes = $this->nodeStorage->loadByProperties(
      ['title' => $artistName, 'type' => 'artist']);
    $artistNode = array_shift($nodes);

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
      $this->logger->info("Artist $artistName is not in the album, adding.");

      $artists[] = $artistNode;
      $album->set('field_artist', $artists);
      $album->save();

      $artistAlbums = $artistNode->get('field_albums')->referencedEntities();
      $artistAlbums[] = $album;
      $artistNode->set('field_albums', $artistAlbums);
      $artistNode->save();
    }

  }

  private function songAlreadyInAlbum($song)
  {
    $query = $this->nodeStorage->getQuery();
    $album_ids = $query->condition('field_song', $song->id())
      ->execute();
    return !empty($album_ids);
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

  public function setLoggerFactory(LoggerChannelFactoryInterface $logger)
  {
    $this->logger = $logger->get(get_class($this));
  }


}
