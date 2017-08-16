<?php

namespace core\providers;

use core\ServiceContainer,
    model\PostModel,
    model\UserModel,
    model\SessionModel,
    model\PrivModel,
    model\RoleModel,
    core\database\DB,
    core\database\DBDriver,
    core\module\Validator;

class ModelProvider
{
    public function register(ServiceContainer &$container)
    {
        $driver = new DBDriver(DB::get());
        $validator = new Validator();

        $container->register('model.post', function() use ($driver, $validator) {
            return new PostModel(
                    $driver,
                    new Validator()
            );
        });

        $container->register('model.user', function() use ($driver, $validator) {
            return new UserModel(
                    $driver,
                    new Validator()
            );
        });

        $container->register('model.session', function() use ($driver, $validator) {
            return new SessionModel(
                    $driver,
                    new Validator()
            );
        });
        
        $container->register('model.priv', function() use ($driver, $validator) {
            return new PrivModel(
                    $driver,
                    new Validator()
            );
        });
        
        $container->register('model.role', function() use ($driver, $validator) {
            return new RoleModel(
                    $driver,
                    new Validator()
            );
        });
       
    }
}