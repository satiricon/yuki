<?php

namespace Drupal\yuki\Commands;

use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\Core\Entity\Sql\SqlEntityStorageInterface;
use Symfony\Component\Process\Process;

class WorkersCommands
{

  /**
   * @var SqlEntityStorageInterface
   */
  public $presetStorage;

  /**
   * @command yuki:transcode:start-workers
   * @aliases yuts
   *
   */
  public function startWorkers()
  {
    $worker = new \GearmanWorker();
    $worker->addServer();

    $storage = $this->presetStorage;
    $worker->addFunction('transcode', function($job) use ($storage) {

      $values = json_decode($job->workload(), true);

      $filename = pathinfo($values['output'], PATHINFO_FILENAME);

      $values['filename'] = $filename;

      dump($values);
      /** @var $preset \Drupal\yuki\Entity\Preset */
      $preset = $storage->load($values['preset']);
      $preset->setConfigurationValues($values);
      dump($preset->getCommand());
      $process = new Process($preset->getCommand());
      $process->mustRun();

      dump($process->getOutput(), $process->getErrorOutput());

    });

    while ($worker->work());

  }

  public function setPresetStorage(ConfigEntityStorageInterface $presetStorage){

    $this->presetStorage = $presetStorage;
  }




}