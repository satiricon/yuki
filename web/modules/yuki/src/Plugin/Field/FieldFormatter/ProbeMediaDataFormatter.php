<?php
namespace Drupal\yuki\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemInterface;

/**
* @FieldFormatter(
*   id = "default_probe_media_data_formatter",
*   label = @Translation("Default Prome Media Data formatter"),
*   field_types = {
*     "probe_media_data"
*   }
* )
*/
class ProbeMediaDataFormatter extends FormatterBase {


  /**
   * Builds a renderable array for a field value.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $items
   *   The field values to be rendered.
   * @param string $langcode
   *   The language that should be used to render the field.
   *
   * @return array
   *   A renderable array for $items, as an array of child elements keyed by
   *   consecutive numeric indexes starting from 0.
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = $this->viewValue($item);
    }

    return $elements;
  }

  protected function viewValue(FieldItemInterface $item) {

    return [
      '#theme' => 'item_list',
      '#items' => $item->getValu,
    ];
  }
}