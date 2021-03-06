<?php

namespace Drupal\yuki\Plugin\media\Source;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\Core\File\FileSystem;
use Drupal\media\Annotation\MediaSource as MediaSource;
use Drupal\media\MediaInterface;
use Drupal\media\MediaTypeInterface;
use Drupal\yuki\Entity\FileNameMapper;
use Drupal\yuki\FPCalc\FpcalcProcess;
use Drupal\yuki\FPCalc\Options;
use Drupal\yuki\Mapper\MapperCollection;
use Drupal\yuki\Plugin\Mapper\HasPathInterface;
use Drupal\yuki\Plugin\Mapper\HasTagInterface;
use FFMpeg\FFProbe;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * File entity media source.
 *
 * @see \Drupal\file\FileInterface
 *
 * @MediaSource(
 *   id = "yuki_audio",
 *   label = @Translation("FFProbe Audio"),
 *   description = @Translation("Audio File with ffmpeg caps."),
 *   allowed_field_types = {"file"},
 *   default_thumbnail_filename = "audio.png"
 * )
 */
class FFProbeAudio extends FFProbeMediaFile implements HasPathInterface, HasTagInterface
{


  /** @var MapperCollection */
  protected $mapper;

  /** @var string */
  protected $path;

  /** @var array */
  protected $tags;

  /** @var FpcalcProcess */
  protected $fpCalc;

  const METADATA_ATTRIBUTE_TITLE = 'title';

  const METADATA_ATTRIBUTE_ARTIST = 'artist';

  const METADATA_ATTRIBUTE_DATE = 'date';

  const METADATA_ATTRIBUTE_GENRE = 'genre';

  const METADATA_ATTRIBUTE_COMMENT = 'comment';

  const METADATA_ATTRIBUTE_TRACK = 'track';

  const METADATA_ATTRIBUTE_ALBUM = 'album';

  const METADATA_ATTRIBUTE_DISC_NUM = 'disc';

  const METADATA_ATTRIBUTE_DISC_TOTAL = 'disc_total';

  const METADATA_ATTRIBUTE_CHROMA = 'chroma';

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
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManagerInterface $entity_type_manager,
    EntityFieldManagerInterface $entity_field_manager,
    FieldTypePluginManagerInterface $field_type_manager,
    ConfigFactoryInterface $config_factory,
    FileSystem $file_system,
    FFProbe $ffprobe,
    MapperCollection $mapper,
    FpcalcProcess $fpCalc)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $entity_field_manager, $field_type_manager, $config_factory, $file_system, $ffprobe);

    $this->mapper = $mapper;
    $this->fpCalc = $fpCalc;
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
      $container->get('ffprobe'),
      $container->get('yuki.mapper_chain'),
      $container->get('fpcalc'));
  }


  /**
   * {@inheritdoc}
   */
  public function getMetadataAttributes()
  {

    $attributes = [
      static::METADATA_ATTRIBUTE_DATE => $this->t('Date'),
      static::METADATA_ATTRIBUTE_TITLE => $this->t('Title'),
      static::METADATA_ATTRIBUTE_ARTIST => $this->t('Artist'),
      static::METADATA_ATTRIBUTE_GENRE => $this->t('Genre'),
      static::METADATA_ATTRIBUTE_COMMENT => $this->t('Comment'),
      static::METADATA_ATTRIBUTE_TRACK => $this->t('Track'),
      static::METADATA_ATTRIBUTE_ALBUM => $this->t('Album'),
      static::METADATA_ATTRIBUTE_DISC_NUM => $this->t('Disc Nº'),
      static::METADATA_ATTRIBUTE_DISC_TOTAL => $this->t('Disc Total'),
      static::METADATA_ATTRIBUTE_CHROMA => $this->t('Chroma Print'),
    ];

    return $attributes + parent::getMetadataAttributes();

  }

  public function getPath()
  {

    return $this->path;
  }

  public function getTag($tagName)
  {

    if (empty($this->tags[$tagName])) {
      $tagName = strtoupper($tagName);
    }

    $tagValue = empty($this->tags[$tagName]) ? null : $this->tags[$tagName];

    return empty($tagValue) ? null : $tagValue;
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

    $this->path = $path;

    $format = $this->ffprobe->format($path);
    $data = $format->get('tags');

    $this->tags = $data;

    if ($attribute_name === self::METADATA_ATTRIBUTE_CHROMA) {
      $value = $this->fpCalc->generateFingerprint([$path]);
    }

    if (array_key_exists($attribute_name, $data)) {

      $value = $data[$attribute_name];
    }

    if ($test = $this->mapper->map($attribute_name, $this)) {

      $value = $test;
    }

    return empty($value) ? parent::getMetadata($media, $attribute_name) : $value;
  }

  /**
   * {@inheritdoc}
   */
  public function createSourceField(MediaTypeInterface $type)
  {

    return parent::createSourceField($type)->set('settings', ['file_extensions' => 'mp3 wav aac flac ape alac m4a webm']);
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
