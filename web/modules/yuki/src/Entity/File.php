<?php

namespace Drupal\yuki\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\file\Entity\File as BaseFile;


/**
 * Defines the file entity class.
 *
 * @ingroup file
 *
 * @ContentEntityType(
 *   id = "yuki.file",
 *   label = @Translation("Yuki File"),
 *   handlers = {
 *     "storage" = "Drupal\file\FileStorage",
 *     "storage_schema" = "Drupal\file\FileStorageSchema",
 *     "access" = "Drupal\file\FileAccessControlHandler",
 *     "views_data" = "Drupal\file\FileViewsData",
 *   },
 *   base_table = "file_managed",
 *   entity_keys = {
 *     "id" = "fid",
 *     "label" = "filename",
 *     "langcode" = "langcode",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *   }
 * )
 */
class File extends BaseFile
{

  /**
   * {@inheritdoc}
   */
  public static function preDelete(EntityStorageInterface $storage, array $entities)
  {

    foreach ($entities as $entity) {
      // Delete all remaining references to this file.
      $file_usage = \Drupal::service('file.usage')->listUsage($entity);
      if (!empty($file_usage)) {
        foreach ($file_usage as $module => $usage) {
          \Drupal::service('file.usage')->delete($entity, $module);
        }
      }
    }

  }


}
