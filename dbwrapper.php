<?php

namespace Itrax\DbWrapper;


class dbwrapper{
    public $connection;
    public $query;
    public $sql;

    public function __construct($server,$user,$pass,$db,$port = 3306)
    {
        $this->connection = mysqli_connect("$server","$user","$pass","$db",$port);
    }

    public function select($table,$column){
        $this->sql = "SELECT $column FROM `$table` ";
        return $this;
    }

    public function where($column,$compair,$value){
        $this->sql .= " WHERE `$column` $compair '$value'";
        return $this;
    }

    public function andWhere($column,$compair,$value){
        $this->sql .= " AND  `$column` $compair '$value'";
        return $this;
    }

    public function orWhere($column,$compair,$value){
        $this->sql .= " OR  `$column` $compair '$value'";
        return $this;
    }

    public function getAll(){
        $this->query();
        while($row = mysqli_fetch_assoc($this->query)){
            $data[] = $row;
        }
        return $data;
    }

    public function getRow(){
        $this->query();
        $row = mysqli_fetch_assoc($this->query);
        return $row;
    }

    public function insert($table,$data){

        $columns = "";
        $values = "";
        foreach($data as $key=>$value){
            $columns .= " `$key` ,";
            $values .= " '$value' ,";
        }
        $columns = rtrim($columns,",");
        $values = rtrim($values,",");
        $this->sql = "INSERT INTO `$table` ($columns) VALUES ($values)";
        return $this;
    }

    public function update($table,$data){
        $row = "";

        foreach($data as $key => $value){
            $row .= " `$key` = '$value' ,";
        }
        $row = rtrim($row,",");
        $this->sql = "UPDATE `$table` SET $row";
        return $this; 
    }

    public function delete($table){
        $this->sql = "DELETE FROM `$table`";
        return $this; 
    }



    public function excute(){
        $this->query();
        if(mysqli_affected_rows($this->connection) > 0){
            return true;
        }else{
            return $this->showerror();
        }
    }

    public function query(){
        $this->sql = mysqli_query($this->connection,$this->sql);
        return $this->query;
    }

    public function showerror(){
        $errors = mysqli_error_list($this->connection);
            foreach($errors as $error){
                echo "<h2>Error<h2> : ".$error['error']."<br <h3>Error code : </h3>".$error['errno'];
            }
        }

        public function __destruct(){
            mysqli_close($this->connection);
        }

    }


    
