<?php

/**
 * Модель постов
 *
 * @author bumer
 */
class PostModel extends BaseModel
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->table = 'articles';
        $this->id = 'id_article';
    }
    
    /* добавление статьи */

    public function add(array $params)

    {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (title, text) VALUES(:title, :content)");
        return $stmt->execute($params);
    }
    
    /* редактирование статьи */

    public function edit(array $params)
    
    {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET title = :title, text = :content WHERE {$this->id} = :id");
        return $stmt->execute($params);
    }
   
}
