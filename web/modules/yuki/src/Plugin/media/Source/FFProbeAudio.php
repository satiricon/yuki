<?php

namespace Drupal\yuki\Plugin\media\Source;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\media\Annotation\MediaSource as MediaSource;
use Drupal\media\MediaInterface;
use Drupal\media\MediaTypeInterface;

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
 *   default_thumbnail_filename = "generic.png"
 * )
 */
class FFProbeAudio extends FFProbeMediaFile
{
	const METADATA_ATTRIBUTE_TITLE = 'title';

	const METADATA_ATTRIBUTE_ARTIST = 'artist';

	const METADATA_ATTRIBUTE_DATE = 'date';

	const METADATA_ATTRIBUTE_GENRE = 'genre';

	const METADATA_ATTRIBUTE_COMMENT = 'comment';

	const METADATA_ATTRIBUTE_TRACK = 'track';

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
		$format = $this->ffprobe->format($path);

		$data = $format->get('tags');

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