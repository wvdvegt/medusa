<?php
namespace Khepin\Medusa;

use Symfony\Component\Process\Process;

class UpdateServerInfo
{
    protected $package;

    public function __construct($package)
    {
        $this->package = $package;
    }

    public function update($in_dir)
    {
        $cmd = 'git update-server-info';
        $dir = $in_dir.'/'.$this->package.'.git';
        if(!is_dir($dir)){
            return;
        }
        $process = new Process($cmd, $dir);
        $process->setTimeout(3600);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new \Exception($process->getErrorOutput());
        }
    }
}
