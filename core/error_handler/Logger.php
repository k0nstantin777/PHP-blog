<?php

namespace core\error_handler;

/**
 * Logger
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class Logger
{

    private $file;
    private $dir;
    private $fullpath;

    public function __construct($filename, $dir)
    {
        $this->file = sprintf('%s.log', $filename);
        $this->dir = $dir;
        $this->fullpath = sprintf('%s/%s', $dir, $this->file);

        $this->prepareDir();
    }

    public function write($log, $level = '')
    {
        $date = date('Y-m-d H:i:s');
        $body = sprintf("\n\nDate: %s\nLevel: %s\nText: %s\n*****************", $date, $level, $log);

        file_put_contents($this->fullpath, $body, FILE_APPEND);
    }

    protected function prepareDir()
    {
        if (!file_exists($this->dir)) {
            if (!mkdir($this->dir, 0777, true)) {
                throw new \RuntimeException("Log dir can't be created by path \"{$this->dir}\" ");
            }
        }
    }

}
