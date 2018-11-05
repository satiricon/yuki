<?php

namespace Drupal\yuki\Commands;

use Symfony\Component\Process\Process;

class WorkersCommands
{
  /**
   * @command yuki:transcode:start-workers
   * @aliases yuts
   *
   */
  public function startWorkers()
  {
    $worker = new \GearmanWorker();
    $worker->addServer();


    $worker->addFunction('transcode', function($job) {

      $workload = json_decode($job->workload());

      if(!file_exists($workload->output)){
        $process = new Process(
          array('ffmpeg',
            '-i',
            '/'.$workload->input,
            '-c:a',
            'libmp3lame',
            '-q:a', '0',
            '-f', 'dash', '-min_seg_duration', '2000000', '-use_template', '0',
            $workload->output));

        $process->run();

        dump($process->getOutput(), $process->getErrorOutput());
      }

    });

    while ($worker->work());

  }




}