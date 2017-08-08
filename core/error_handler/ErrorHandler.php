<?php

namespace core\error_handler;

use controller\BaseController;
    

/**
 * Description of ErrorHandler
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class ErrorHandler implements ErrorHandlerInterface
{

    private $ctrl;
    private $logger;
    private $dev;

    public function __construct(BaseController $ctrl, Logger $logger = null, $dev = true)
    {
        $this->ctrl = $ctrl;
        $this->logger = $logger;
        $this->dev = $dev;
    }

    public function handle(\Exception $e, $message)
    {
        if (isset($this->logger)) {
            $this->logger->write(sprintf("%s\n%s", $e->getMessage(), $e->getTraceAsString()), 'ERROR');
        }

        $msg = sprintf('<h1>%s</h1>', $message);

        if ($this->dev) {
            $msg = sprintf('%s<h2>%s</h2><p>%s</p>', $msg, $e->getMessage(), $e->getTraceAsString());
        }

        $this->ctrl->er404Action($msg);
        $this->ctrl->response();
    }

}
