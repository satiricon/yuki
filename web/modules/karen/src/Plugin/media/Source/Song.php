<?php

namespace Drupal\karen\Plugin\media\Source;

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
use FFMpeg\FFMpeg;

/**
 * Media source wrapping around an audio file.
 *
 * @see \Drupal\file\FileInterface
 *
 * @MediaSource(
 *   id = "song_file",
 *   label = @Translation("Song"),
 *   description = @Translation("Ebin song."),
 *   allowed_field_types = {"song"},
 *   default_thumbnail_filename = "audio.png"
 * )
 */
class Song extends File {


    const METADATA_ATTRIBUTE_TITLE = 'title';

    const METADATA_ATTRIBUTE_ARTIST = 'artist';

    const METADATA_ATTRIBUTE_DATE = 'date';

    const METADATA_ATTRIBUTE_GENRE = 'genre';

    const METADATA_ATTRIBUTE_COMMENT = 'comment';

    const METADATA_ATTRIBUTE_TRACK = 'track';

    /**
     * The file system service.
     *
     * @var \Drupal\Core\File\FileSystem
     */
    protected $fileSystem;


    /**
     * @var FFmpeg
     */
    protected $ffmpeg;


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
     */
    public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, EntityFieldManagerInterface $entity_field_manager, FieldTypePluginManagerInterface $field_type_manager, ConfigFactoryInterface $config_factory, FileSystem $file_system, FFMpeg $ffmpeg)
    {
        parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $entity_field_manager, $field_type_manager, $config_factory);

        $this->fileSystem = $file_system;
        $this->ffmpeg = $ffmpeg;
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
            $container->get('ffmpeg'));
    }


    /**
     * {@inheritdoc}
     */
    public function getMetadataAttributes() {

        $attributes = [
            static::METADATA_ATTRIBUTE_DATE    => $this->t('Date'),
            static::METADATA_ATTRIBUTE_TITLE   => $this->t('Title'),
            static::METADATA_ATTRIBUTE_ARTIST  => $this->t('Artist'),
            static::METADATA_ATTRIBUTE_GENRE   => $this->t('Genre'),
            static::METADATA_ATTRIBUTE_COMMENT => $this->t('Comment'),
            static::METADATA_ATTRIBUTE_TRACK   => $this->t('Track')
        ];

        return $attributes + parent::getMetadataAttributes();

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
        $audio = $this->ffmpeg->open($path);
        $data = $audio->getFFProbe()->format($path)->get('tags');

        if(array_key_exists($attribute_name, $data)){
            return $data[$attribute_name];
        }

        return parent::getMetadata($media, $attribute_name);

    }


    /**
     * {@inheritdoc}
     */
    public function createSourceField(MediaTypeInterface $type) {

        return parent::createSourceField($type)->set('settings', ['file_extensions' => 'mp3 wav aac flac']);
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
