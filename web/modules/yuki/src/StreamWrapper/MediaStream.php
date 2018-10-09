<?php

namespace Drupal\yuki\StreamWrapper;

use Drupal\Core\Routing\UrlGeneratorTrait;
use Drupal\Core\StreamWrapper\LocalStream;
use Drupal\Core\StreamWrapper\StreamWrapperInterface;

class MediaStream extends LocalStream {

  use UrlGeneratorTrait;
  /**
   * Gets the path that the wrapper is responsible for.
   *
   * @todo Review this method name in D8 per https://www.drupal.org/node/701358.
   *
   * @return string
   *   String specifying the path.
   */
  public function getDirectoryPath() {
     return '/';
  }

  public static function getType(){
    return StreamWrapperInterface::READ;
  }

  /**
   * Returns the name of the stream wrapper for use in the UI.
   *
   * @return string
   *   The stream wrapper name.
   */
  public function getName() {
    return t('Media files');
  }

  /**
   * Returns the description of the stream wrapper for use in the UI.
   *
   * @return string
   *   The stream wrapper description.
   */
  public function getDescription() {
    return t('Media files served by the webserver.');
  }

  /**
   * Returns a web accessible URL for the resource.
   *
   * This function should return a URL that can be embedded in a web page
   * and accessed from a browser. For example, the external URL of
   * "youtube://xIpLd0WQKCY" might be
   * "http://www.youtube.com/watch?v=xIpLd0WQKCY".
   *
   * @return string
   *   Returns a string containing a web accessible URL for the resource.
   */
  public function getExternalUrl() {
    $path = str_replace('\\', '/', $this->getTarget());
    return $this->getUrlGenerator()->generateFromRoute('yuki.media_file_download',  ['filepath' => $path], ['absolute' => TRUE, 'path_processing' => FALSE]);
  }
}