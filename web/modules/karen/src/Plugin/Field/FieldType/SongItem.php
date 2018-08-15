<?php

namespace Drupal\karen\Plugin\Field\FieldType;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Field\Annotation\FieldType;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\file\Plugin\Field\FieldType\FileItem;

/**
 * Class SongItem
 * @package Drupal\karen\Plugin\Field\FieldType
 *
 * @FieldType(
 *   id = "karen_song_item",
 *   label = @Translation("Song"),
 *   description= @Translation("Song Item"),
 *   module="Karen"
 *   category = @Translation("Karen"),
 *   default_widget="karen_song_widget"
 *   default_formatter="karen_song_formater"
 *   list_class = "\Drupal\file\Plugin\Field\FieldType\FileFieldItemList",
 *   constraints = {"ReferenceAccess" = {}, "FileValidation" = {}}
 *
 * )
 */
class SongItem extends FileItem
{

	public static function schema(FieldStorageDefinitionInterface $field_definition) {
		return [
			'columns' => [
				'target_id' => [
					'description' => 'The ID of the file entity.',
					'type'        => 'int',
					'unsigned'    => true
				],
				'title' => [
					'description' => 'The Song Title',
					'type'        => 'varchar',
					'length'      => 1024
				],

			],
			'indexes' => [
				'target_id' => ['target_id'],
			],
			'foreign keys' => [
				'target_id' => [
					'table' => 'file_managed',
					'columns' => ['target_id' => 'fid'],
				],
			],
		];

	}


}