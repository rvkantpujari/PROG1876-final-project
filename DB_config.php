<?php
    class DB
    {
        // Config values to interact with DB
        const DB_USER = 'root';
        const DB_PASSWORD = '';
        const DB_HOST = 'localhost:3308'; // MySQL runs on PORT 3308 instead of default port number 3306
        const DB_NAME = 'surway';

        private $dbc;
        private $sqlQuery;
        private $queryType;
        private $dataset = [];

        private $COLS = [];
        private $VALS = [];
        private $COLSTYPE = '';

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
            // empty private properties
            $this->COLS = []; $this->VALS = []; $this->COLSTYPE = '';
            return $this;
        }

        // To select all or specified columns
        function select($select = ['*']) {
            $this->select = $select;
            $this->queryType = "select";
            return $this;
        }

        // To select all records
        function selectAll() {
            // reset dataset array
            $this->dataset = array();
            // Select ALL Query
            $this->sqlQuery = "SELECT * FROM $this->table";
            // Execute Query
            $results = mysqli_query($this->dbc, $this->sqlQuery);
            // Fetch and Assign Row from Results one by one
            while($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                array_push($this->dataset, $row);
            }
            // Return dataset
            return $this -> dataset;
        }

        // To Insert data
        function insert($cols, $vals, $colsType) {
            // check if columns' and values' count is equal or not
            if(count($cols) !== count($vals)) {
                return "Error: Unequal columns count in the query.";
            } 
            else {
                $this->queryType = "insert";

                // prepare SQL Query structure
                $columns = implode(", ", $cols);
                $values = array_fill(0, count($vals), '?');
                $values = implode(", ", $values);

                // INSERT query
                $this->sqlQuery = "INSERT INTO $this->table ($columns) VALUES($values)";
                
                // prepare string values
                for($i = 0; $i < strlen($colsType); $i++) {
                    $this->COLSTYPE .= $colsType[$i];
                    $colsType[$i] === 's' ? array_push($this->VALS, $this->prepare_string($vals[$i])) : array_push($this->VALS, $vals[$i]);
                }

                // prepare query and bind parameters
                $stmt = mysqli_prepare($this->dbc, $this->sqlQuery);
                $stmt->bind_param($this->COLSTYPE, ...$this->VALS);

                // execute statement
                $result = mysqli_stmt_execute($stmt);

                // return true if query executed successfully otherwise false
                return $result;
            }
        }

        // To Update data
        function update($cols, $vals, $colsType) {
            // check if columns' and values' count is equal or not
            if(count($cols) !== count($vals)) {
                return "Error: Unequal columns count in the query.";
            } 
            else {
                $this->queryType = "update";

                $columns = [];
                for($i = 0; $i < count($cols); $i++) {
                    array_push($columns, " $cols[$i] = ?");
                }

                $columns = implode(", ", $columns);

                // UPDATE query
                $this->sqlQuery = "UPDATE $this->table SET $columns";
                
                // prepare string values
                for($i = 0; $i < strlen($colsType); $i++) {
                    $this->COLSTYPE .= $colsType[$i];
                    $colsType[$i] === 's' ? array_push($this->VALS, $this->prepare_string($vals[$i])) : array_push($this->VALS, $vals[$i]);
                }
            }
            return $this;
        }

        // To execute UPDATE/DELETE query
        function execute() {
            if($this->queryType === "update") {
                // prepare query and bind parameters
                $stmt = mysqli_prepare($this->dbc, $this->sqlQuery);
                $stmt->bind_param($this->COLSTYPE, ...$this->VALS);

                // execute update query
                $result = mysqli_stmt_execute($stmt);
            }

            unset($this->COLS, $this->VALS, $this->COLSTYPE);

            return $result;
        }
        
        // To add WHERE clause
        function where($cols, $vals, $oper, $colsType, $logOper = []) {
            // reset dataset array
            $this->dataset = array();
            // check if arguments are passed correctly or not.
            if((count($cols) === count($vals)) && (count($cols) === strlen($colsType)))
            {
                $whereConditions = [];
                $result = '';

                for($i = 0; $i < count($cols); $i++) {
                    array_push($whereConditions, " $cols[$i] $oper[$i] ? ");
                    ($i < 1) ? "" : (!empty($logOper) ? $whereConditions[count($whereConditions)-2] .= $logOper[($i-1)] : "");
                }

                $whereClause = implode(" ", $whereConditions);
                
                // prepare string values
                for($i = 0; $i < strlen($colsType); $i++) {
                    $this->COLSTYPE .= $colsType[$i];
                    $colsType[$i] === 's' ? array_push($this->VALS, "%".$this->prepare_string($vals[$i])."%") : array_push($this->VALS, $vals[$i]);
                }
                
                if($this->queryType === "select") {
                    // split values by , (comma)
                    $this->select = implode(", ", $this->select);
                    // SELECT query
                    $this->sqlQuery = "SELECT $this->select FROM $this->table WHERE $whereClause";
                }
                else if($this->queryType === "update") {
                    // UPDATE query
                    $this->sqlQuery .= " WHERE $whereClause";
                }

                // prepare query and bind parameters
                $stmt = mysqli_prepare($this->dbc, $this->sqlQuery);
                $stmt->bind_param($this->COLSTYPE, ...$this->VALS);

                // Execute query based on queryType
                if($this->queryType === "select") {
                    // execute statement
                    if(!$stmt->execute())
                        return false;
                    
                    // get results post statement execution
                    $results = $stmt->get_result();

                    // prepare dataset from results
                    while($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                        array_push($this->dataset, $row);
                    }

                    // Assign dataset to result variable
                    $result = $this->dataset;

                    // unset dataset variable
                    unset($this->dataset);
                }
                else if($this->queryType === "update") {
                    // execute update query
                    $result = mysqli_stmt_execute($stmt);
                }

                return $result;
            } 
            else {
                return "Error: Unequal columns count in the query.";
            }
        }

        function __destruct() {
            mysqli_close($this->dbc);
        }
    }
?>
