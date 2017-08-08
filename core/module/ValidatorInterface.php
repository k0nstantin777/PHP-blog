<?php

namespace core\module;

/**
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
interface ValidatorInterface
{

    public function setSchema(array $schema);

    public function run(array $fields);
}
