<?php

namespace core\providers;

use core\ServiceContainer,
    model\PostModel,
    model\UserModel,
    model\SessionModel,
    model\PrivModel,
    model\RoleModel,
    model\Privs_to_RolesModel,
    core\database\DB,
    core\database\DBDriver,
    core\module\Validator;

class ModelProvider
{
    public function register(ServiceContainer &$container)
    {
        $driver = new DBDriver(DB::get());
        
        $container->register('model.post', function() use ($driver) {
            return new PostModel(
                    $driver,
                    new Validator()
            );
        });

        $container->register('model.user', function() use ($driver) {
            return new UserModel(
                    $driver,
                    new Validator()
            );
        });

        $container->register('model.session', function() use ($driver) {
            return new SessionModel(
                    $driver,
                    new Validator()
            );
        });
        
        $container->register('model.priv', function() use ($driver) {
            return new PrivModel(
                    $driver,
                    new Validator()
            );
        });
        
        $container->register('model.role', function() use ($driver) {
            return new RoleModel(
                    $driver,
                    new Validator()
            );
        });
        
         $container->register('model.privs_to_roles', function() use ($driver) {
            return new Privs_to_RolesModel(
                    $driver,
                    new Validator()
            );
        });
    }
}