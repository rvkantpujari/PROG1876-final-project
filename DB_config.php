<?php
    class DB
    {
        // Config values to interact with DB
        const DB_USER = 'root';
        const DB_PASSWORD = '';
        const DB_HOST = 'localhost'; // MySQL runs on PORT 3308 instead of default port number 3306
        const DB_NAME = 'surway';

        private $dbc;
        private $sqlQuery;
        private $dataset = [];

        // public data members
        public $table;
        public $select = [];

        // To configure the DB connection
        function __construct() {
            $this->dbc = @mysqli_connect(
                self::DB_HOST,
                self::DB_USER,
                self::DB_PASSWORD,
                self::DB_NAME
            )
            OR die(
                'Could not connect to MySQL: ' . mysqli_connect_error()
            );

            mysqli_set_charset($this->dbc, 'utf8');
        }

        // To prepare string value
        function prepare_string($string) {
            $string = strip_tags($string);
            $string = mysqli_real_escape_string($this->dbc, trim($string));
            return $string;
        }

        // To get DB Connection String
        function get_dbc() {
            return $this->dbc;
        }

        // To set table name
        function table($table) {
            $this->table = $table;
            return $this;
        }

        // To select all or specified columns
        function select($select = ['*']) {
            $this->select = $select;
            return $this;
        }

        // To get all records
        function getAll() {
            // reset dataset array
            $this->dataset = array();
            $this->sqlQuery = "SELECT * FROM $this->table";
            $results = mysqli_query($this->dbc, $this->sqlQuery);

            while($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                array_push($this->dataset, $row);
            }
            return $this -> dataset;
        }

        // To add WHERE clause
        function where($cols, $vals, $oper, $colsType, $logOper = []) {
            // reset dataset array
            $this->dataset = array();
            // check if arguments are passed correctly or not.
            if((count($cols) === count($vals)) && (count($cols) === strlen($colsType)))
            {
                $whereConditions = [];

                for($i = 0; $i < count($cols); $i++) {
                    array_push($whereConditions, " $cols[$i] $oper[$i] ? ");
                    ($i < 1) ? "" : (!empty($logOper) ? $whereConditions[count($whereConditions)-2] .= $logOper[($i-1)] : "");
                }

                $whereClause = implode(" ", $whereConditions);
                
                $this->select = implode(", ", $this->select);

                // SELECT query
                $this->sqlQuery = "SELECT $this->select FROM $this->table WHERE $whereClause";

                // prepare string values
                for($i = 0; $i < strlen($colsType); $i++) {
                    $colsType[$i] === 's' ? $vals[$i] = "%".$this->prepare_string($vals[$i])."%" : "";
                }

                // prepare query and bind parameters
                $stmt = $this->get_custom_result($colsType, $vals);

                // execute statement
                if(!$stmt->execute()) 
                    return false;

                // get results post statement execution
                $results = $stmt->get_result();

                // prepare dataset from results
                while($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                    array_push($this->dataset, $row);
                }

                // return dataset
                return $this -> dataset;
            } 
            else {
                return "Error: Unequal columns count in the query.";
            }
        }

        // To Insert data
        function insert($cols, $vals, $colsType) {
            // check if columns' and values' count is equal or not
            if(count($cols) !== count($vals)) {
                return "Error: Unequal columns count in the query.";
            } else {
                // prepare SQL Query structure
                $columns = implode(", ", $cols);
                $values = array_fill(0, count($vals), '?');
                $values = implode(", ", $values);

                // INSERT query
                $this->sqlQuery = "INSERT INTO $this->table ($columns) VALUES($values)";
                
                // prepare string values
                for($i = 0; $i < strlen($colsType); $i++) {
                    $colsType[$i] == 's' ?  $vals[$i] = $this->prepare_string($vals[$i]) : "";
                }

                // prepare query and bind parameters
                $stmt = $this->get_custom_result($colsType, $vals);

                // execute statement
                $result = mysqli_stmt_execute($stmt);

                // return true if query executed successfully otherwise false
                return $result;
            }
        }

        // To prepare query and bind parameters
        function get_custom_result($types = null, $params = null) 
        {
            // prepare query for execution
            $stmt = mysqli_prepare($this->dbc, $this->sqlQuery);
            $stmt->bind_param($types, ...$params);
            return $stmt;
        }
        
        function __destruct() {
            mysqli_close($this->dbc);
        }
    }
?>
