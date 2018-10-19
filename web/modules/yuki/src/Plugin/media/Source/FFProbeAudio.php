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
use Drupal\yuki\Entity\PathInfoMapper;
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

	const METADATA_ATTRIBUTE_TITLE = 'title';

	const METADATA_ATTRIBUTE_ARTIST = 'artist';

	const METADATA_ATTRIBUTE_DATE = 'date';

	const METADATA_ATTRIBUTE_GENRE = 'genre';

	const METADATA_ATTRIBUTE_COMMENT = 'comment';

	const METADATA_ATTRIBUTE_TRACK = 'track';

	const METADATA_ATTRIBUTE_ALBUM = 'album';

  const METADATA_ATTRIBUTE_DISC_NUM = 'disc';

  const METADATA_ATTRIBUTE_DISC_TOTAL = 'disc_total';

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
    MapperCollection $mapper)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $entity_field_manager, $field_type_manager, $config_factory, $file_system, $ffprobe);

    $this->mapper = $mapper;
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
      $container->get('yuki.mapper_chain'));
  }



	/**
	 * {@inheritdoc}
	 */
	public function getMetadataAttributes() {

		$attributes = [
			static::METADATA_ATTRIBUTE_DATE         => $this->t('Date'),
			static::METADATA_ATTRIBUTE_TITLE        => $this->t('Title'),
			static::METADATA_ATTRIBUTE_ARTIST       => $this->t('Artist'),
			static::METADATA_ATTRIBUTE_GENRE        => $this->t('Genre'),
			static::METADATA_ATTRIBUTE_COMMENT      => $this->t('Comment'),
			static::METADATA_ATTRIBUTE_TRACK        => $this->t('Track'),
      static::METADATA_ATTRIBUTE_ALBUM        => $this->t('Album'),
      static::METADATA_ATTRIBUTE_DISC_NUM     => $this->t('Disc Nº'),
      static::METADATA_ATTRIBUTE_DISC_TOTAL   => $this->t('Disc Total'),
    ];

		return $attributes + parent::getMetadataAttributes();

	}

  public function getPath() {

	  return $this->path;
  }

  public function getTag($tagName) {

	  return $this->tags[$tagName];
  }


	/**
	 * {@inheritdoc}
	 */
	public function getMetadata(MediaInterface $media, $attribute_name) {
	  /** @var \Drupal\file\FileInterface $file */
		$file = $media->get($this->configuration['source_field'])->entity;

		if (!$file) {

			return parent::getMetadata($media, $attribute_name);
		}

    $uri = $file->getFileUri();

		$path = $this->fileSystem->realpath($uri);

		$format = $this->ffprobe->format($path);
		$data = $format->get('tags');

		$this->tags = $data;

    if($attribute_name === 'name')
    {
      return empty($data['TITLE']) ? $data['title'] : $data['TITLE'];
    }

		switch ($attribute_name) {
      case self::METADATA_ATTRIBUTE_DATE:
      case strtoupper(self::METADATA_ATTRIBUTE_DATE):
        return empty($data['DATE']) ? $data['date'] : $data['DATE'];
      break;

      case self::METADATA_ATTRIBUTE_TITLE:
      case strtoupper(self::METADATA_ATTRIBUTE_TITLE):
        return empty($data['TITLE']) ? $data['title'] : $data['TITLE'];
      break;

      case self::METADATA_ATTRIBUTE_ARTIST:
      case strtoupper(self::METADATA_ATTRIBUTE_ARTIST):
        return empty($data['ARTIST']) ? $data['artist'] : $data['ARTIST'];
      break;

      case self::METADATA_ATTRIBUTE_ALBUM:
      case strtoupper(self::METADATA_ATTRIBUTE_ALBUM):
        return empty($data['ALBUM']) ? $data['album'] : $data['ALBUM'];
      break;
    }

		if(array_key_exists($attribute_name, $data)) {

		   return $data[$attribute_name];
		}

    if($value = $this->mapper->map($attribute_name, $this)) {

      return $value;
    }

    return parent::getMetadata($media, $attribute_name);
	}

	/**
	 * {@inheritdoc}
	 */
	public function createSourceField(MediaTypeInterface $type) {

		return parent::createSourceField($type)->set('settings', ['file_extensions' => 'mp3 wav aac flac ape alac m4a webm']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function prepareViewDisplay(MediaTypeInterface $type, EntityViewDisplayInterface $display) {
		$display->setComponent($this->getSourceFieldDefinition($type)->getName(), [
			'type' => 'file_audio',
		]);
	}

}