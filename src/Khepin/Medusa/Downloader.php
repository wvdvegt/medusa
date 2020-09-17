<?php
/**
 * @copyright 2013 Sébastien Armand
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace Khepin\Medusa;

use Symfony\Component\Process\Process;

class Downloader
{
    protected $url;

    protected $package;

    public function __construct($package, $url)
    {
        $this->package = $package;
        $this->url = preg_replace('~^git@github.com:~', 'git://github.com/', $url);
    }

    public function download($in_dir)
    {
        $repo = $in_dir . '/' . $this->package . ".git";

        if (is_dir($repo)) {
            return;
        }

        $cmd = 'git clone --mirror %s %s';

        $process = Process::fromShellCommandline(sprintf($cmd, $this->url, $repo));
        $process->setTimeout(3600);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception($process->getErrorOutput());
        }

        $cmd = 'cd %s && git update-server-info -f';

        $process = Process::fromShellCommandline(sprintf($cmd, $repo));
        $process->setTimeout(3600);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception($process->getErrorOutput());
        }

        $cmd = 'cd %s && git fsck';
        $process = Process::fromShellCommandline(sprintf($cmd, $repo));
        $process->setTimeout(3600);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception($process->getErrorOutput());
        }
    }
}
