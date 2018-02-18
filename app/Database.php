<?php

class Database {

    /**
     * @var PDO
     * @var string
     */
    private static $connection;
    private static $file;

    public static function open($file):void {
        self::$file = __DIR__ . DIRECTORY_SEPARATOR . str_replace(str_split('\\/'), DIRECTORY_SEPARATOR, $file);

        self::$connection = new PDO('sqlite:' . self::$file);
        self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function close():void {
        self::$connection = null;
    }

    public static function is_open():bool {
        return !empty(self::$connection);
    }

    public static function create(string $table, array $columns):bool {
        try {
            $query = 'CREATE TABLE ';
            $query .= $table;
            $query .= '(';
            $query .= implode(',', array_keys($columns));
            $query .= ')';

            $stmt = self::$connection->prepare($query);
            $stmt->execute();

            return true;
        } catch(PDOException $e) {
            return false;
        }
    }

    public static function insert(string $table, array $values):?string {
        try {
            $query = 'INSERT INTO ';
            $query .= $table;
            $query .= '(';
            $query .= implode(',', array_keys($values));
            $bindArgs = array_values($values);
            $query .= ')';

            $query .= ' VALUES (';
            for($i = 0; $i < count($values); $i++) {
                $query .= (($i > 0) ? ",?" : "?");
            }
            $query .= ')';

            $stmt = self::$connection->prepare($query);
            $stmt->execute($bindArgs);

            return self::$connection->lastInsertId();
        } catch(PDOException $e) {
            return null;
        }
    }

    public static function update(string $table, array $values, ?string $clause = null, ?array $where_args = null):?int {
        try {
            $query = 'UPDATE ';
            $query .= $table;
            $query .= ' SET ';

            $bindArgs = [];
            $i = 0;
            foreach($values as $column => $value) {
                $query .= ($i++ > 0) ? "," : "";
                $query .= $column;
                $query .= "=?";
                array_push($bindArgs, $value);
            }

            if(!empty($where_args)) {
                $query .= ' WHERE ';
                $query .= $clause;

                foreach($where_args as $arg) {
                    array_push($bindArgs, $arg);
                }
            }

            $stmt = self::$connection->prepare($query);
            $stmt->execute($bindArgs);

            return $stmt->rowCount();
        } catch(PDOException $e) {
            return null;
        }
    }

    public static function replace(string $table, array $values):?string {
        try {
            $query = 'REPLACE INTO ';
            $query .= $table;
            $query .= '(';
            $query .= implode(',', array_keys($values));
            $bindArgs = array_values($values);
            $query .= ')';

            $query .= ' VALUES (';
            for($i = 0; $i < count($values); $i++) {
                $query .= (($i > 0) ? ",?" : "?");
            }
            $query .= ')';

            $stmt = self::$connection->prepare($query);
            $stmt->execute($bindArgs);

            return self::$connection->lastInsertId();
        } catch(PDOException $e) {
            return null;
        }
    }

    public static function delete(string $table, ?string $clause = null, ?array $where_args = null):?int {
        try {
            $query = 'DELETE FROM ';
            $query .= $table;

            $bindArgs = [];
            if(!empty($where_args)) {
                $query .= ' WHERE ';
                $query .= $clause;

                foreach($where_args as $arg) {
                    array_push($bindArgs, $arg);
                }
            }

            $stmt = self::$connection->prepare($query);
            $stmt->execute($bindArgs);

            return $stmt->rowCount();
        } catch(PDOException $e) {
            return null;
        }
    }

    public static function get(string $table, ?array $columns = null, ?string $clause = null, ?array $where_args = null, ?string $group_by = null, ?string $having = null, ?string $order_by = null, ?int $limit = null, bool $distinct = false):?array {
        try {
            $query = 'SELECT ';

            if($distinct)
                $query .= 'DISTINCT ';

            $query .= implode(', ', $columns);
            $query .= ' FROM ';
            $query .= $table;

            $bindArgs = [];
            if(!empty($where_args)) {
                $query .= ' WHERE ';
                $query .= $clause;

                foreach($where_args as $arg) {
                    array_push($bindArgs, $arg);
                }
            }

            if(!empty($group_by)) {
                $query .= ' GROUP BY ';
                $query .= $group_by;

                if(!empty($having)) {
                    $query .= ' HAVING ';
                    $query .= $having;
                }
            }

            if(!empty($order_by)) {
                $query .= ' ORDER BY ';
                $query .= $order_by;
            }

            if(!empty($limit)) {
                $query .= ' LIMIT ';
                $query .= $limit;
            }

            $stmt = self::$connection->prepare($query);
            $stmt->execute($bindArgs);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch(PDOException $e) {
            return null;
        }
    }
}
