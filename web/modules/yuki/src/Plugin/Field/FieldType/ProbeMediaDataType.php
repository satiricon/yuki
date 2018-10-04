<?php

namespace Drupal\yuki\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\TypedData\DataDefinitionInterface;
use Drupal\Core\TypedData\TypedDataInterface;
use Drupal\Core\Validation\ConstraintManager;

/**
 *
 * @FieldType(
 *   id = "probe_media_data",
 *   label = @Translation("Media Technical Data"),
 *   description = @Translation("This Type will manage the most technical pieces of data"),
 *   default_widget = "default_probe_media_data_widget",
 *   default_formatter = "default_probe_media_data_formatter"
 * )
 */
class ProbeMediaDataType extends FieldItemBase {

  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'streams' => [
          'type'     => 'int',
          'unsigned' => TRUE,
          'size'     => 'tiny',
        ],
        'format'  => [
          'type'    => 'varchar',
          'length'  => 255,
        ],
        'format_long' => [
          'type' => 'varchar',
          'length'  => 255,
        ],
        'duration' => [
          'type' => 'int',
          'size'    => 'normal',
        ],
        'bitrate' => [
          'type'  => 'int',
          'size'    => 'normal',
        ],
        'probe_score' => [
          'type'  => 'int',
          'size'    => 'normal',
        ]
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
    $properties['streams'] = DataDefinition::create('integer')
      ->setLabel(t('Streams'));

    $properties['format'] = DataDefinition::create('string')
      ->setLabel(t('Format'));

    $properties['format_long'] = DataDefinition::create('string')
      ->setLabel(t('Format Long'));

    $properties['duration'] = DataDefinition::create('integer')
      ->setLabel(t('Duration'));

    $properties['bitrate'] = DataDefinition::create('integer')
      ->setLabel(t('Bitrate'));

    $properties['probe_score'] = DataDefinition::create('integer')
      ->setLabel(t('Probe Score'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $values = array();
    $values[] = $this->get('streams')->getValue();
    $values[] = $this->get('format')->getValue();
    $values[] = $this->get('format_long')->getValue();
    $values[] = $this->get('duration')->getValue();
    $values[] = $this->get('bitrate')->getValue();
    $values[] = $this->get('probe_score')->getValue();

    $is_empty = true;

    foreach ($values as $value){
      if($value > 0 || !empty($value)){
        $is_empty = false;
      }
    }


    return $is_empty;
  }



}