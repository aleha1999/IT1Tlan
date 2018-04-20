<?php
class DB {
    private static $DB_host = "localhost";
    private static $DB_username = "root";
    private static $DB_password = "root";
    private static $DB_database = "TromsoLAN";

    //Function to return databse connection. Returns false on failure. Takes one variable
    //Vars:
    //  UTF:
    //      Whether or not the connection should use UTF8 charset. If false then
    //      It will not use the charset.
    public static function conn($utf = true) {
        $conn = mysqli_connect(self::$DB_host,self::$DB_username,self::$DB_password,self::$DB_database);
        if($conn->connect_errno)
            return false;
        if($utf)
            mysqli_set_charset($conn,"utf8");
        return $conn;
    }

    public static function add($table,$values,$rows) {
        $q = new QueryBuilder();
        $q->insert($rows)->into($table)->values($values);
        $q->build();
        $q->execute();
        return $q->success;
    }

    public static function insert($table,$rows,$values) {
        return self::add($table,$values,$rows);
    }

    public static function get($table,$rows,$where = null,$equals = null) {
        $q = new QueryBuilder();
        $q->select($rows)->from($table);
        if($where != null) {
             $q->where($where)->equals($equals);
        }
        $q->execute();
        if($q->success == false)
            return false;
        return $q->getResults();
    }

    public static function delete($table,$where,$equals) {
        $q = new QueryBuilder();
        $q->delete()->from($table)->where($where)->equals($equals);
        return $q->execute()->success;
    }
}

//A class that will construct a query based on simple function calls
//For now the class is made for simple insert, update and select calls.
//For more advanced calls the query builder's query can be set manually to then
//be executed.
//Mainly this class is created to make prepared statements and queries less
//tedious to use.
class QueryBuilder {
    //What method the query will use
    public $method = NULL;
    //What columns the query will edit/update
    public $query_columns = NULL;
    //Table to query
    public $tables = NULL;
    //Condition to query. By default it is "true", meaning get all rows.
    public $where = "true";
    //Array that contains condition vars for binding with prepared statement
    public $vars = array();
    //The query to be executed by the builder. Can be overridden for easy stmt
    //exec, but is usually used with the build function.
    public $query = "";
    //Variable to store result of execute
    private $result = NULL;
    //Variable to store if the last query was successful or not
    public $success = NULL;
    //Variable to contain error of last mysql stmt
    public $exec_err = NULL;
    //Order by
    public $orderby = null;
    public $orderby_prepped = true;

    //Set the method of the class to be select and set the columns to select.
    public function select($columns = "*") {
        $this->method = "SELECT";
        $this->query_columns = $columns;
        return $this;
    }

    //Set the table to query.
    public function from($table) {
        if(is_array($table)) {
            $t_string = "";
            foreach($table as $key => $t) {
                if($key > 0)
                    $t_string .= ",";
                $t_string .= $t;
            }
        } else {
            $t_string = $table;
        }
        $this->tables = $t_string;
        return $this;
    }

    //Begin creating custom SQL WHERE statement. Set the first column to compare.
    public function where($col) {
        //If the string currently has the default statement then replace it
        if($this->where == "true")
            $this->where = "";
        if($this->where != "")
            $this->where .= " ";
        $this->where .= $col;
        return $this;
    }

    //Set the previously set value to compare as equal to a value.
    public function equals($value,$prepared = true) {
        if($prepared) {
            $this->where .= " = ?";
            array_push($this->vars,$value);
        } else {
            $this->where .= " = ".$value;
        }
        return $this;
    }

    public function like($value) {
        array_push($this->vars,$value);
        $this->where .= " LIKE ?";
        return $this;
    }

    //Add another value to compare.
    public function nd($col) {
        $this->where .= " AND ".$col;
        return $this;
    }

    //Function to define mode as insert and what columns to be inserted
    public function insert($cols) {
        $this->method = "INSERT";
        $this->query_columns = $cols;
        return $this;
    }

    //Designate what table to insert into
    public function into($table) {
        $this->tables = $table;
        return $this;
    }

    //Function to designate the variables to insert into the table
    public function values($values) {
        $this->vars = $values;
        return $this;
    }

    public function delete() {
        $this->method = "DELETE";
        return $this;
    }

    public function update($table) {
        $this->method = "UPDATE";
        $this->tables = $table;
        return $this;
    }

    public function set($vars,$equals) {
        $this->query_columns = $vars;
        $this->vars = $equals;
        return $this;
    }

    public function order_by($col,$prepped = true) {
        $this->orderby .= " ORDER BY ".$col;
        return $this;
    }

    public function asc() {
        $this->orderby .= " ASC";
        return $this;
    }

    public function desc() {
        $this->orderby .= " DESC";
        return $this;
    }

    //Function that builds the sql statement before it can be executed
    //Automatically called by execute if null.
    //TODO: Lots of variable checking.
    public function build() {
        if($this->method == "SELECT") {
            $query = "";
            $query .= $this->method." ";
            if(is_array($this->query_columns)) {
                $query .= self::getCommaSeparatedList($this->query_columns);
            } else {
                $query .= $this->query_columns;
            }
            $query .= " FROM ";
            if(is_array($this->tables))
                $query .= self::getCommaSeparatedList($this->tables);
            else
                $query .= $this->tables;
            $query .= " WHERE ";
            $query .= $this->where;
            $query .= $this->orderby;
            $this->query = $query;
        }

        if($this->method == "INSERT") {
            $query = "";
            $query .= $this->method. " INTO ";
            $query .= $this->tables." (";
            if(is_array($this->query_columns))
                $query .= self::getCommaSeparatedList($this->query_columns);
            else
                $query .= $this->query_columns;
            $query .= ") VALUES (";
            if(is_array($this->query_columns)) {
                for($i = 0; $i<count($this->query_columns); $i++) {
                    if($i > 0)
                        $query .= ",";
                    $query .= "?";
                }
            } else {
                $query.= "?";
            }
            $query .= ")";
            $this->query = $query;
        }

        if($this->method == "DELETE") {
            $query = "";
            $query .= $this->method." FROM ";
            $query .= $this->tables." WHERE ";
            $query .= $this->where;
            $this->query = $query;
        }

        if($this->method == "UPDATE") {
            $query = "";
            $query .= $this->method;
            $query .= " ".$this->tables." ";
            $query .= "SET ";
            foreach($this->query_columns as $key => $val) {
                if($key > 0)
                    $query .= ",";
                $query .= $val.= "= ?";
            }
            $query .= " WHERE ";
            $query .= $this->where;
            $this->query = $query;
        }
        return $this;
    }

    private static function getCommaSeparatedList($array) {
        $res = "";
        foreach($array as $I => $var) {
            if($I > 0)
                $res.= ",";
            $res .= $var;
        }
        return $res;
    }

    //Execute the built SQL statement.
    public function execute() {
        $conn = DB::conn();
        if($conn === false)
            return false;
        if($this->query == NULL)
            $this->build();
        //Prepare the statement
        $stmt = $conn->prepare($this->query);
        //Detect the types of the variables passed to the array and place them
        //in the type string that is passed to the prepared statement.
        $typestring = "";
        foreach($this->vars as $var) {
            if(is_int($var))
                $typestring.="i";
            else
                $typestring.="s";
        }
        //FOR NEWER VERSIONS OF PHP: $stmt->bind_param($typestring,...$this->vars);
        if(count($this->vars) > 0) {
            $a_params = array($typestring);
            foreach($this->vars as $var) {
                array_push($a_params,$var);
            }
            $ref = array();
            foreach($a_params as $key => $param) $ref[$key] = &$a_params[$key];
            call_user_func_array(array($stmt,"bind_param"),$ref);
        }
        $this->success = $stmt->execute();
        $this->result = $stmt->get_result();
        $res = $this->result;
        return $this;
    }

    public function getStmt() {
        return $this->result;
    }

    public function getResults($firstlineonly = false) {
        $data = array();
        while($row = $this->result->fetch_assoc()) {
            if($firstlineonly) return $row;
            array_push($data,$row);
        }
        return $data;
    }
}
?>
