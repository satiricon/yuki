<?php

namespace Drupal\yuki\Plugin\media\Source;

use Drupal\media\MediaInterface;
use Drupal\media\Plugin\media\Source\File;
use Drupal\media\MediaTypeInterface;
/**
 * Class LogFile
 * @package Drupal\yuki\Plugin\media\Source
 *
 * @see \Drupal\file\FileInterface
 *
 * @MediaSource(
 *   id = "yuki_logfile",
 *   label = @Translation("Yuki Log File"),
 *   description = @Translation("Log file."),
 *   allowed_field_types = {"file"},
 *   default_thumbnail_filename = "generic.png"
 * )
 *
 *
 */
class LogFile extends File {

  const METADATA_ATTRIBUTE_CONTENT = "content";


  public function getMetadataAttributes()
  {

    $attributes = [
      static::METADATA_ATTRIBUTE_CONTENT => $this->t('Content'),
    ];


    return $attributes + parent::getMetadataAttributes();
  }

  public function getMetadata(MediaInterface $media, $attribute_name) {

    /** @var \Drupal\file\FileInterface $file */
    $file = $media->get($this->configuration['source_field'])->entity;

    if (!$file) {
      return parent::getMetadata($media, $attribute_name);
    }

    if($attribute_name === 'content') {
      return file_get_contents($file->getFileUri());
    }

    return  parent::getMetadata($media, $attribute_name);
  }


  /**
   * {@inheritdoc}
   */
  public function createSourceField(MediaTypeInterface $type)
  {

    return parent::createSourceField($type)->set('settings', ['file_extensions' => 'log']);
  }

}