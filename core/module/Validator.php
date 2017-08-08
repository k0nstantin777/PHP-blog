<?php

namespace core\module;

use core\helper\ArrayHelper;


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
        /* валидация данных формы */
     
        foreach ($this->schema as $name => $rules) {
          
            if (isset($params[$name])) {
                
                //проверка на заполнение обязательных полей
                if ($rules['require'] && empty($params[$name])){
                    $this->errors[$name] = sprintf("Заполните обязательное поле %s!", ArrayHelper::get($rules, 'name'));
                
                } else {
                    //проверка типа отправленных данных
                    if (isset($rules['type'])) {
                        if ($rules['type'] === 'string' && !is_string($params[$name])){
                            $this->errors[$name] = sprintf("Неверный тип данных %s, разрешен строчный тип данных!", gettype($params[$name]));
                        }
                    }
       
                    //проверка на длину поля
                    if (isset($rules['length']) && $rules['length'] !== 'big')  {
                        $strlen = strlen($params[$name]);                        
                        
                        $min = ArrayHelper::getPath($rules, 'length.0', 0);
                        $max = ArrayHelper::getPath($rules, 'length.1', ArrayHelper::get($rules, 'length'));
                                                
                        if ($strlen < $min){
                            $this->errors[$name] = sprintf('Минимальная длина поля не может быть менее %s, текущее значение %s', $min, $strlen);
                        }
                        
                        if ($strlen > $max){
                            $this->errors[$name] = sprintf('Максимальная длина поля не может быть более %s, текущее значение %s', $max, $strlen);
                        }
                        
                    }

                    //проверка на запрещенные символы в поле Имя статьи
                    if (!preg_match('/^([а-яА-ЯЁёa-zA-Z0-9- ]+)$/u', $params[$name]) && $name === 'title'){
                        $this->errors[$name] = sprintf('Запрещенные символы в поле %s ! ', $rules['name']);    
                    } 
                    
                    //проверка на запрещенные символы в поле Логин
                    if (!preg_match('/^([a-zA-Z0-9-_.]+)$/', $params[$name]) && $name === 'login'){
                        $this->errors[$name] = sprintf('Запрещенные символы в поле %s ! ', $rules['name']);    
                    } 
                
                    
                }
            } 
 
            //запись валидных данных в массив $this->clean
            if (!isset($this->errors[$name]) && isset($params[$name]) && !isset($rules['check'])) {
                $this->clean[$name] = trim(htmlspecialchars($params[$name]));
            } 
        }
      
    }

}
