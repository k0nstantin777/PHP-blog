<?php

namespace core\providers;

use core\ServiceContainer;
use controller\client\PublicController;
use controller\client\PostController;
use controller\admin\AdminPostController;
use controller\admin\AdminUserController;
use controller\client\UserController;
use controller\client\PageController;

class ControllerProvider
{
    public function register(ServiceContainer &$container)
    {
        $container->register('controller.public', function($request) use ($container) {
            return new PublicController($request, $container);
        });
        
        $container->register('controller.post', function($request) use ($container) {
            return new PostController($request, $container);
        });
        $container->register('controller.user', function($request) use ($container) {
            return new UserController($request, $container);
        });
        $container->register('controller.page', function($request) use ($container) {
            return new PageController($request, $container);
        });
        $container->register('controller.admin.post', function($request) use ($container) {
            return new AdminPostController($request, $container);
        });
        $container->register('controller.admin.user', function($request) use ($container) {
            return new AdminUserController($request, $container);
        });
        $container->register('controller.admin', function($request) use ($container) {
            return new AdminController($request, $container);
        });
        
    }
}
