<?php

namespace core;

/**
 * Валидация данных
 *
 * @author Noskov Konstantin <noskov.kos@gmail.com>
 */
class Validator implements ValidatorInterface
{

    public $schema;
    public $errors = [];
    public $clean = [];

    public function setSchema(array $schema)
    {
        $this->schema = $schema;
    }

    public function run(array $params)
    {
        foreach ($this->schema as $name => $rules) {
            
            //$params[$name] ?? null;
 
            //проверка на заполнение обязательных полей
            if (isset($params[$name]) && $rules['require'] && empty($params[$name])) {
                $this->errors[$name] = sprintf("Не заполненно обязательное поле %s!<br>", $rules['name'] );
            }
            
            
            if (isset($params[$name])) {
                               
                //проверка на длину поля
                if (isset($rules['length']) && iconv_strlen($params[$name]) > $rules['length'] && $rules['length'] != 'big')  {
                    $this->errors[$name] = sprintf('Превышен размер поля %s ! <br>', $rules['name']);
                }
                
                //проверка на запрещенные символы в поле
                if (!preg_match('/^([а-яА-ЯЁёa-zA-Z0-9- ]+)$/u', $params[$name]) && $rules['type'] === 'string'){
                
                    $this->errors[$name] = sprintf('Запрещенные символы в поле %s ! <br>', $rules['name']);    
                }    
            }
           
            //запись валидных данных в массив $clean
            if (!isset($this->errors[$name])) {
                $this->clean[$name] = isset($params[$name]) ? trim(htmlspecialchars($params[$name])): null;
            }
            
            //удаляем пустые валидные поля
            if ($this->clean[$name] == null){
                unset($this->clean[$name]);
            }
            
            
        }
    }

}
