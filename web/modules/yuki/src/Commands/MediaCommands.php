<?php

namespace Drupal\yuki\Commands;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Entity\Sql\SqlEntityStorageInterface;
use Drupal\file\FileStorageInterface;
use Drupal\media\Entity\Media;
use Drupal\Core\Language\LanguageInterface;

class MediaCommands
{


  /** @var FileStorageInterface */
  private $fileStorage;

  /** @var SqlEntityStorageInterface */
  private $mediaStorage;

  /**
   * @command yuki:media:update-albums
   * @aliases yuua
   *
   */
  public function updateAlbums()
  {
    $query = $this->mediaStorage->getQuery();

    $ids = $query->execute();

    foreach($ids as $id){
      $media = $this->mediaStorage->load($id);
      $this->update($media);
    }

  }

  public function update(Media $media){

    $translation = $media->getTranslation(LanguageInterface::LANGCODE_DEFAULT);

    $source = $media->getSource();

    foreach ($translation->bundle->entity->getFieldMap() as $metadataAttributeName => $entityFieldName) {
      $translation->set($entityFieldName, $source->getMetadata($translation, $metadataAttributeName));
    }
    $translation->setName($source->getMetadata($translation,'name'));

    $media->save();
  }

  public function setMediaStorage(SqlEntityStorageInterface $mediaStorage) {

    $this->mediaStorage = $mediaStorage;
  }

}