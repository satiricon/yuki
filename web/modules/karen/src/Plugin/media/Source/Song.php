<?php

namespace Drupal\karen\Plugin\media\Source;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\media\MediaTypeInterface;
use Drupal\media\Plugin\media\Source\File;

/**
 * Media source wrapping around an audio file.
 *
 * @see \Drupal\file\FileInterface
 *
 * @MediaSource(
 *   id = "song_file",
 *   label = @Translation("Song"),
 *   description = @Translation("Ebin song."),
 *   allowed_field_types = {"file"},
 *   default_thumbnail_filename = "audio.png"
 * )
 */
class Song extends File {

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
