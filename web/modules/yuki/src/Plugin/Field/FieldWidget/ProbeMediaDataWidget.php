<?php

namespace Drupal\yuki\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'default_license_plate_widget' widget.
 *
 * @FieldWidget(
 *   id = "default_probe_media_data_widget",
 *   label = @Translation("Default Probe Media Data Widget"),
 *   field_types = {
 *     "probe_media_data"
 *   }
 * )
 */
class ProbeMediaDataWidget extends WidgetBase {


  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $element['data'] = [
        '#type' => 'details',
        '#title' => $element['#title'],
        '#open' => false,
        '#description' => $element['#description'],
      ] + $element;

    $element['data']['streams'] = [
      '#type' => 'number',
      '#title' => t('Streams'),
      '#default_value' => isset($items[$delta]->streams) ? $items[$delta]->streams : 0,
      '#description' => '',
      '#required' => false,
    ];

    $element['data']['format'] = [
      '#type' => 'textfield',
      '#title' => t('Format'),
      '#default_value' =>  isset($items[$delta]->format) ? $items[$delta]->format : '',
      '#description' => '',
      '#required' => false,
    ];

    $element['data']['format_long'] = [
      '#type' => 'textfield',
      '#title' => t('Format Long'),
      '#default_value' =>  isset($items[$delta]->format_long) ? $items[$delta]->format_long : '',
      '#description' => '',
      '#required' => false,
    ];

    $element['data']['duration'] = [
      '#type' => 'number',
      '#title' => t('Duration'),
      '#default_value' =>  isset($items[$delta]->duration) ? $items[$delta]->duration : 0,
      '#description' => '',
      '#required' => false,
    ];

    $element['data']['bitrate'] = [
      '#type' => 'number',
      '#title' => t('Bitrate'),
      '#default_value' =>  isset($items[$delta]->bitrate) ? $items[$delta]->bitrate : 0,
      '#description' => '',
      '#required' => false,
    ];

    $element['data']['probe_score'] = [
      '#type' => 'number',
      '#title' => t('Probe Score'),
      '#default_value' =>  isset($items[$delta]->probe_score) ? $items[$delta]->probe_score : 0,
      '#description' => '',
      '#required' => false,
    ];

    return $element;

  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as &$value) {
      $value['streams'] = $value['data']['streams'];
      $value['format'] = $value['data']['format'];
      $value['format_long'] = $value['data']['format_long'];
      $value['duration'] = $value['data']['duration'];
      $value['bitrate'] = $value['data']['bitrate'];
      $value['probe_score'] = $value['data']['probe_score'];

      unset($value['data']);
    }

    return $values;
  }

}
