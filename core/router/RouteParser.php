<?php

namespace core\router;

class RouteParser
{
    public $basic;
    public $clean;
    public $params = [];

    public function handleRoute(string $route)
    {
        $this->basic = $route;

        $this->prepare($this->basic);
    }

    private function prepare(string $route)
    {
        $withParams = false;
        $this->params = [];
        while ($openParam = strpos($route, '{')) {
            $withParams = true;
            $closeParam = strpos($route, '}');

            $paramName = substr($route, $openParam + 1, $closeParam - $openParam - 1);
            $this->params[] = $paramName;
            $route = str_replace('{' . $paramName . '}', '', $route);
        }

        $this->clean = $this->deleteLastSlash($route);

        if ($withParams) {
            $this->clean .= '__';
        }
    }

    private function deleteLastSlash(string $route)
    {
        if (strlen($route) === 0) {
            return $route;
        }
        
        while ($route[strlen($route) - 1] === '/') {
            $route = substr($route, 0, -1);
        }

        return $route;
    }
}