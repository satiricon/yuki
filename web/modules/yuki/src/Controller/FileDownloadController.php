<?php

namespace Drupal\yuki\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\File\Stream;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class FileDownloadController extends ControllerBase {


  /**
   * Handles private file transfers.
   *
   * Call modules that implement hook_file_download() to find out if a file is
   * accessible and what headers it should be transferred with. If one or more
   * modules returned headers the download will start with the returned headers.
   * If a module returns -1 an AccessDeniedHttpException will be thrown. If the
   * file exists but no modules responded an AccessDeniedHttpException will be
   * thrown. If the file does not exist a NotFoundHttpException will be thrown.
   *
   * @see hook_file_download()
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   * @param string $scheme
   *   The file scheme, defaults to 'private'.
   *
   * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
   *   The transferred file as response.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
   *   Thrown when the requested file does not exist.
   * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
   *   Thrown when the user does not have access to the file.
   */
  public function download(Request $request, $scheme = 'media') {

    $target = $request->query->get('file');
    $preset  = $request->query->get('preset');

    $uri = $scheme . '://' . $preset . $target;

    dump(file_exists($uri));
    die();
    if (file_stream_wrapper_valid_scheme($scheme) && file_exists($uri)) {

      $headers = $this->moduleHandler()->invokeAll('file_download', [$uri]);

      foreach ($headers as $result) {
        if ($result == -1) {
          throw new AccessDeniedHttpException();
        }
      }

      if (count($headers)) {

        $stream  = new Stream($uri);

        return new BinaryFileResponse($stream, 200, $headers, $scheme !== 'private');
      }

      throw new AccessDeniedHttpException();
    }

    throw new NotFoundHttpException();
  }

}