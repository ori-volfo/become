<?php

const DBNAME = 'become_ori';
const USERNAME = 'ori_user';
const PASSWORD = 'become_ori_2019';
const HOST = '194.213.4.152';


class DB {
    private static $_instance = null;
    private $_stmt;

    public function __construct(){
        try {
            $this->dbhost = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, USERNAME, PASSWORD);
        } catch(PDOException $e){
            $this->error = $e->getMessage();
        }
    }


    public static function getDbCon() {
        if(!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

    public function query($query) {
        $this->_stmt = $this->dbhost->prepare($query);
    }

    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->_stmt->bindValue($param, $value, $type);
    }
    public function execute() {
        return $this->_stmt->execute();
    }
    public function resultSet() {
        $this->execute();
        return $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function rowCount() {
        return $this->_stmt->rowCount();
    }
    public function single() {
        $this->execute();
        return $this->_stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert_multi_rows($table, $keys, $data){
        $sql_query = $this->get_insert_query($table, $keys, $data);
        $this->query($sql_query);
        return $this->resultSet();
    }

    public function get_insert_query($table, $keys, $data){
        $value = '';
        $numItems = count($data);
        $index = 0;
        foreach ($data as $key => $row ) {
            $items = count($row);
            $i = 0;
            $value .= '(' ;
            foreach ($row as $item ) {
                $value .= (gettype($item) == 'integer' ? $item : '"'.$item.'"');
                $value .= (++$i === $items ? '' : ',');
            }
            $value .= (++$index === $numItems ? ')' : '),');
        }
        $sql = 'INSERT INTO '.$table.' ('.$keys.') VALUES '.$value.'';
        return $sql;
    }

    public function select_all($table){
        $this->query("SELECT * FROM ".$table);
        return $this->resultSet();
    }

    public function insert_user($user){
        $this->query("INSERT INTO `users` (first_name,last_name,email,birth_date,phone,city_id) 
                                        VALUES (:first_name,:last_name,:email,:birth_date,:phone,:city_id)
                                        ON DUPLICATE KEY UPDATE 
                                            `first_name` = :first_name,
                                            `last_name` = :last_name,
                                            `birth_date` = :birth_date,
                                            `phone` = :phone,
                                            `city_id` = :city_id;");
        $this->bind(':first_name', $user->first_name);
        $this->bind(':last_name', $user->last_name);
        $this->bind(':email', $user->email);
        $this->bind(':birth_date', $user->birth_date);
        $this->bind(':phone', $user->phone);
        $this->bind(':city_id', $user->city_id);
    }

    public function get_users_data(){
        $this->query("SELECT first_name, last_name, email, birth_date, phone, city_name, country_name
                            FROM users u, cities ci, countries co
                            WHERE u.city_id = ci.city_id
                                AND ci.country_id = co.country_id");
        return $this->resultSet();
    }

    public function  get_users_stats(){
        $this->query("SELECT birth_date, city_name, country_name
                            FROM users u, cities ci, countries co
                            WHERE u.city_id = ci.city_id
                                AND ci.country_id = co.country_id");
        return $this->resultSet();
    }
}
