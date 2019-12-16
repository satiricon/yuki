<?php

namespace Drupal\yuki\Element;

use Drupal\Core\Render\Element\RenderElement;

/**
* @RenderElement("yuki.playlist")
*/
class Playlist extends RenderElement
{

  /**
   * Returns the element properties for this element.
   *
   * @return array
   *   An array of element properties. See
   *   \Drupal\Core\Render\ElementInfoManagerInterface::getInfo() for
   *   documentation of the standard properties of all elements, and the
   *   return value format.
   */
  public function getInfo()
  {
    $class = get_class($this);
    return [
      '#pre_render' => [
        [$class, 'preRender'],
      ],
      '#theme' => 'playlist',
    ];
  }

  public static function preRender($elements) {
    $elements['#attached']['library'][] = 'yuki/videojs';
    $elements['#attached']['library'][] = 'yuki/videojs.playlist';
    $elements['#attached']['library'][] = 'yuki/videojs.http-streaming';
    $elements['#attached']['library'][] = 'yuki/videojs.yuki';

    return $elements;
  }


}
