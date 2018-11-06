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
        /*$process = new Process(
          array('ffmpeg',
            '-i',
            '/'.$workload->input,
            '-c:a',
            'libmp3lame',
            '-q:a', '0', '-vn','-threads:0', '2',
            '-f', 'dash', '-min_seg_duration', '2000000', '-use_template', '0',
            $workload->output));*/

        $filename = pathinfo($workload->output, PATHINFO_FILENAME);

        $process = new Process('ffmpeg -i "/'.$workload->input.'" -i "/'.$workload->input.'" -c:a libmp3lame -q:0:a 0 -q:1:a 4 -vn -map 0:a -map 1:a -f dash -min_seg_duration 9000000 -adaptation_sets "id=0,streams=a" -use_timeline 1 -use_template 1 -init_seg_name "'.$filename.'\$RepresentationID\$.m4s" -media_seg_name "'.$filename.'\$RepresentationID\$-\$Number%05d\$.m4s" "'.$workload->output.'"');

        $process->run();

        dump($process->getOutput(), $process->getErrorOutput());
      }

    });

    while ($worker->work());

  }




}