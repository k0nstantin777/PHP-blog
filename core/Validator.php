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
        /* проверка целостности отправленной формы */
        if (isset($params['fields'])) {
            $formCrash = false;
            // проверка количества отправленых полей
            if (count($params['fields']) !== count($params) -1 ){
               $formCrash = true;
            }
            
            // проверка аттрибута name отправленных полей из формы HTML
            foreach ($params as $key => $value){
                if (!in_array($key, $params['fields']) && $key !== 'fields' || $value === null){
                    $formCrash = true;
                }
            }
            
            if ($formCrash){
                $this->errors[] = 'Не пытайтесь подделать форму!';
                return $this->errors;
            }
        }
        
        /* валидация данных формы */
        foreach ($this->schema as $name => $rules) {
          
            if (isset($params[$name])) {
                
                //проверка на заполнение обязательных полей
                if ($rules['require'] && empty($params[$name])){
                    $this->errors[$name] = sprintf("Не заполненно обязательное поле %s!", $rules['name'] );
                
                } else {
                    //проверка на длину поля
                    if (isset($rules['length']) && iconv_strlen($params[$name]) > $rules['length'] && $rules['length'] != 'big')  {
                        $this->errors[$name] = sprintf('Превышен размер поля %s ! ', $rules['name']);
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
            if (!isset($this->errors[$name])) {
                $this->clean[$name] = isset($params[$name]) ? trim(htmlspecialchars($params[$name])): '';
                //удаляем пустые валидные поля для 
                if ($this->clean[$name] == ''){
                    unset($this->clean[$name]);
                };
            } 
        }
      
    }

}
