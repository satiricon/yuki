<?php

namespace Drupal\yuki\StreamWrapper;

use Drupal\Core\Routing\UrlGeneratorTrait;
use Drupal\Core\StreamWrapper\LocalStream;
use Drupal\Core\StreamWrapper\StreamWrapperInterface;
use Symfony\Component\Process\Process;

class TranscodeStream extends LocalStream {


  protected function getPreset($uri = NULL)
  {
    $target = $this->getTarget($uri);

    list($preset,) = explode('/', $target, 2);

    return $preset;
  }

  protected function getLocalPath($uri = NULL)
  {
    if (!isset($uri)) {
      $uri = $this->uri;
    }

    $path = $this->getDirectoryPath() . '/' . $this->getTarget($uri);
    $directory = pathinfo($path, PATHINFO_DIRNAME);

    // @todo: hay que injectar?
    $process = new Process(array('mkdir', '-p', $directory));
    $process->mustRun();

    return $path;
  }

  /**
   * {@inheritdoc}
   */
  public function stream_open($uri, $mode, $options, &$opened_path) {
    $this->uri = $uri;
    $path = $this->getLocalPath();
    dump($path);
    die();
    $this->handle = ($options & STREAM_REPORT_ERRORS) ? fopen($path, $mode) : @fopen($path, $mode);

    if ((bool) $this->handle && $options & STREAM_USE_PATH) {
      $opened_path = $path;
    }

    return (bool) $this->handle;
  }

  public function url_stat($uri, $flags)
  {
    $this->uri = $uri;
    $path = $this->getLocalPath();

    list(, $target) = explode('://', $uri, 2);

    list(, $inputPath) = explode('/', $target, 2);

    $process = new Process(
      array('ffmpeg', '-i', '/'.$inputPath, '-y' ,'-c:a', 'libmp3lame', '-q:a', '0', $path));


    $process->run();

    dump($process->getErrorOutput());


    return array(); // TODO: Devuelvo un array vacio o creo aca el archivo?
  }


  /**
   * {@inheritdoc}
   */
  public function getDirectoryPath()
  {
    return '/tmp/yuki';
  }



  /**
   * {@inheritdoc}
   */
  public function getName()
  {
    return t('Transcode Media files');
  }



  /**
   * {@inheritdoc}
   */
  public function getDescription()
  {
    return t('On the fly transcode media files.');
  }



  /**
   * {@inheritdoc}
   */
  public function getExternalUrl()
  {
    $path = str_replace('\\', '/', $this->getTarget());
    return $this->getUrlGenerator()
      ->generateFromRoute(
        'yuki.media.transcode',
        ['filepath' => $path, 'preset' => $this->getPreset()],
        ['absolute' => TRUE, 'path_processing' => FALSE]);
  }
}