<?php

namespace Drupal\yuki\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 *
 * @FieldType(
 *   id = "probe_media_data_type",
 *   label = @Translation("Media Technical Data"),
 *   description = @Translation("This Type will manage the most technical pieces of data"),
 *   default_widget = "default_probe_media_data_widget",
 *   default_formatter = "default_probe_media_data_formater"
 * )
 */
class ProbeMediaDataType extends FieldItemBase {

  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'streams' => [
          'type'     => 'int',
          'unsigned' => true,
          'size'     => 'tiny'
        ],
        'format'  => [
          'type'    => 'varchar',
        ],
        'format_long' => [
          'type' => 'varchar',
        ]
      ],


      'columns' => [
        'number' => [
          'type' => 'varchar',
          'length' => (int) $field_definition->getSetting('number_max_length'),
        ],
        'code' => [
          'type' => 'varchar',
          'length' => (int) $field_definition->getSetting('code_max_length'),
        ],
      ],
    ];

    return $schema;
  }


  /**
   * Defines field item properties.
   *
   * Properties that are required to constitute a valid, non-empty item should
   * be denoted with \Drupal\Core\TypedData\DataDefinition::setRequired().
   *
   * @return \Drupal\Core\TypedData\DataDefinitionInterface[]
   *   An array of property definitions of contained properties, keyed by
   *   property name.
   *
   * @see \Drupal\Core\Field\BaseFieldDefinition
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition)
  {
    // TODO: Implement propertyDefinitions() method.
  }
}