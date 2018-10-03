<?php

namespace Drupal\yuki\Plugin\media\Source;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\Core\File\FileSystem;
use Drupal\media\MediaTypeInterface;
use Drupal\media\Plugin\media\Source\File;
use Drupal\media\MediaInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use FFMpeg\FFProbe;

abstract class FFProbeMediaFile extends File
{

  const METADATA_ATTRIBUTE_STREAMS = "streams";

  const METADATA_ATTRIBUTE_FORMAT = "format";

  const METADATA_ATTRIBUTE_FORMAT_LONG = "format_long";

  const METADATA_ATTRIBUTE_DURATION = "duration";

  const METADATA_ATTRIBUTE_BRATE = "bitrate";

  const METADATA_ATTRIBUTE_SCORE = "probe_score";

  /**
   * The file system service.
   *
   * @var \Drupal\Core\File\FileSystem
   */
  protected $fileSystem;


  /**
   * @var FFProbe
   */
  protected $ffprobe;

  /**
   * Constructs a new class instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager service.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   Entity field manager service.
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $field_type_manager
   *   The field type plugin manager service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \Drupal\Core\File\FileSystem $file_system
   *   The file system service.
   * @param \FFMpeg\FFProbe
   *    The FFmpeg Probe
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, EntityFieldManagerInterface $entity_field_manager, FieldTypePluginManagerInterface $field_type_manager, ConfigFactoryInterface $config_factory, FileSystem $file_system, FFProbe $ffprobe)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $entity_field_manager, $field_type_manager, $config_factory);

    $this->fileSystem = $file_system;
    $this->ffprobe = $ffprobe;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('entity_field.manager'),
      $container->get('plugin.manager.field.field_type'),
      $container->get('config.factory'),
      $container->get('file_system'),
      $container->get('ffprobe'));
  }


  /**
   * {@inheritdoc}
   */
  public function getMetadataAttributes()
  {

    $attributes = [
      static::METADATA_ATTRIBUTE_STREAMS => $this->t('Streams Number'),
      static::METADATA_ATTRIBUTE_FORMAT => $this->t('Format'),
      static::METADATA_ATTRIBUTE_FORMAT_LONG => $this->t('Format Long'),
      static::METADATA_ATTRIBUTE_DURATION => $this->t('Duration'),
      static::METADATA_ATTRIBUTE_BRATE => $this->t('Bit Rate'),
      static::METADATA_ATTRIBUTE_SCORE => $this->t('Probe Score')
    ];

    return $attributes + parent::getMetadataAttributes();
  }

  /**
   * {@inheritdoc}
   */
  public function getMetadata(MediaInterface $media, $attribute_name)
  {
    /** @var \Drupal\file\FileInterface $file */
    $file = $media->get($this->configuration['source_field'])->entity;

    if (!$file) {
      return parent::getMetadata($media, $attribute_name);
    }

    $uri = $file->getFileUri();
    $path = $this->fileSystem->realpath($uri);

    $format = $this->ffprobe->format($path);

    switch ($attribute_name) {
      case self::METADATA_ATTRIBUTE_FORMAT:
        return $format->get('format_name');
      break;

      case self::METADATA_ATTRIBUTE_BRATE:
        return $format->get('bit_rate');
      break;

      case self::METADATA_ATTRIBUTE_STREAMS:
        return $format->get('nb_streams');
      break;

      case self::METADATA_ATTRIBUTE_FORMAT_LONG:
        return $format->get('format_long_name');
      break;

      case self::METADATA_ATTRIBUTE_DURATION:
        return $format->get('duration');
      break;

      case self::METADATA_ATTRIBUTE_SCORE:
        return $format->get('probe_score');
      break;
    }

    $data = $format->get($attribute_name);


    return empty($data) ? parent::getMetadata($media, $attribute_name) : $data;

  }

  /**
   * {@inheritdoc}
   */
  public function createSourceField(MediaTypeInterface $type)
  {

    return parent::createSourceField($type)->set('settings', ['file_extensions' => 'mp3 wav aac flac']);
  }

  /**
   * {@inheritdoc}
   */
  public function prepareViewDisplay(MediaTypeInterface $type, EntityViewDisplayInterface $display)
  {
    $display->setComponent($this->getSourceFieldDefinition($type)->getName(), [
      'type' => 'file_audio',
    ]);
  }

}