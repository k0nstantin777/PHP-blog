<?php

namespace core\error_handler;
  

/**
 * Description of ErrorHandler
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class ErrorHandler implements ErrorHandlerInterface
{

    private $logger;
    private $dev;

    public function __construct(Logger $logger = null, $dev = true)
    {
        $this->logger = $logger;
        $this->dev = $dev;
    }

    public function handle($ctrl, \Exception $e, $message)
    {
        if (isset($this->logger)) {
            $this->logger->write(sprintf("%s\n%s", $e->getMessage(), $e->getTraceAsString()), 'ERROR');
        }

        $msg = sprintf('<h1>%s</h1>', $message);

        if ($this->dev) {
            $msg = sprintf('%s<h2>%s</h2><p>%s</p>', $msg, $e->getMessage(), $e->getTraceAsString());
        }
        
        $ctrl->er404Action($msg);
        $ctrl->response();
    }

}
