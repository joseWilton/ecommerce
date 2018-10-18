<?php

namespace Hcode;

class Model{
    private $values = [];

    public function __call($name, $args){

        $method = substr($name, 0, 3); //identificando e separando só o metodo (get ou set)
        $fieldName = substr($name, 3, strlen($name)); //identificando de qual metodo se trata

        switch ($method)
        {
            case "get":
                return $this->values[$fieldName];
            break;

            case "set":
                return $this->values[$fieldName] = $args[0];
            break;
        }
    }

    public function setData($data = array()){

        foreach ($data as $key => $value) {

            $this->{"set".$key}($value);
        }
    }

    public function getValues(){
        return $this->values;
    }
}

?>