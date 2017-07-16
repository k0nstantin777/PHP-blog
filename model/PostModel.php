<?php

/**
 * Модель постов
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
namespace model;
use model\BaseModel,
    core\DBDriverInterface,
    core\ValidatorInterface;


class PostModel extends BaseModel
{
    public function __construct(DBDriverInterface $db, ValidatorInterface $validator)
    {
        parent::__construct($db, $validator);
        $this->table = 'articles';
        $this->id_name = 'id_article';
        $this->validator->setSchema([
			
			'id_article' => [
				'type' => 'integer',
				'require' => false,
			],

			'title' => [
				'type' => 'string',
				'length' => [8, 60],
				'require' => true,
                                'name' => 'Имя статьи'
			],

			'text' => [
				'type' => 'string',
				'length' => 2000,
				'require' => true,
                                'name' => 'Текст статьи'
			],
                        
                        'dt' => [
				'type' => 'string',
				'length' => 20,
				'require' => false
  			]

		]);
        
    }
}
