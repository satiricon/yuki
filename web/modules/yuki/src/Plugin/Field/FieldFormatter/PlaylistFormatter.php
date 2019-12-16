<?php


namespace Drupal\yuki\Plugin\Field\FieldFormatter;


use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * @FieldFormatter(
 *   id = "playlist",
 *   label = @Translation("Playlist Formater for Songs"),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class PlaylistFormatter extends FormatterBase
{

  /**
   * Builds a renderable array for a field value.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $items
   *   The field values to be rendered.
   * @param string $langcode
   *   The language that should be used to render the field.
   *
   * @return array
   *   A renderable array for $items, as an array of2 child elements keyed by
   *   consecutive numeric indexes starting from 0.
   */
  public function viewElements(FieldItemListInterface $items, $langcode)
  {
    $elements = [
      "#type" => "yuki.playlist",
      "#items" => []
    ];

    foreach ($items as $item) {
      $tmp = [];
      $entity = $item->entity;
      $song = $entity->get('field_media_yuki_audio')->getEntity();
      $track = (int) $song->get('field_track')->get(0)->value;
      $tmp["track"] = $track;
      $tmp["name"] = $song->getName();
      $tmp["file"] = $entity->get('field_media_yuki_audio')->get(0)->entity;
      $tmp["url"] = file_create_url(str_replace('media://', 'transcode://mp3_v0', $tmp['file']->getFileUri()));
      $elements["#items"][$track] = $tmp;
    }

    ksort($elements["#items"]);

    return $elements;
  }
}
