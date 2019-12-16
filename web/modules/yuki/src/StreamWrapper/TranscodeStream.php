<?php

namespace Drupal\yuki\StreamWrapper;

use Drupal\Core\StreamWrapper\LocalStream;
use Symfony\Component\Process\Process;
use Drupal\Core\Url;

class TranscodeStream extends LocalStream {

  protected $fileCreated = false;

  protected function getPreset($uri = NULL)
  {
    if (!isset($uri)) {
      $uri = $this->uri;
    }

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
    $filename = pathinfo($path, PATHINFO_FILENAME);

    // @todo: hay que injectar?
    $process = new Process(array('mkdir', '-p', $directory));
    $process->mustRun();

    return $directory.'/'.$filename.'.mp3';
  }

  /**
   * {@inheritdoc}
   */
  public function stream_open($uri, $mode, $options, &$opened_path) {
    $this->uri = $uri;
    $path = $this->getLocalPath();

    if(!$this->fileCreated) {

      $this->createFile($uri);
    }

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

    if(!file_exists($path)) {

      $this->createFile($uri);
    }

    if ($flags & STREAM_URL_STAT_QUIET || !file_exists($path)) {
      return @stat($path);
    }
    else {
      return stat($path);
    }
  }

  protected function createFile($uri) {

    $path = $this->getLocalPath();
    if(!file_exists($path)) {
      list(, $target) = explode('://', $uri, 2);

      list(, $inputPath) = explode('/', $target, 2);

      $client = new \GearmanClient();
      $client->addServer();
      $client->doBackground('transcode',
        json_encode(array(
          'input' => '/'.$inputPath,
          'output' => $path,
          'preset'  => $this->getPreset())));
      sleep(1);
    }

    $this->fileCreated = true;

  }


  /**
   * {@inheritdoc}
   */
  public function getDirectoryPath()
  {
    //@hacer: hacer el directorio temporal configurable
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
    $preset = $this->getPreset();
    $path = str_replace($preset.'/', '', $this->getTarget());

    $url = Url::fromRoute('yuki.media.transcode', ['filepath' => $path, 'preset' => $preset],
      ['absolute' => TRUE, 'path_processing' => FALSE]);

    return $url->toString();
  }
}
