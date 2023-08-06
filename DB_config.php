<?php
    class DB
    {
        // Configuration to interact with DB
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

        function prepare_string($string) {
            $string = strip_tags($string);
            $string = mysqli_real_escape_string($this->dbc, trim($string));
            return $string;
        }

        // get DB Connection String;
        function get_dbc() {
            return $this->dbc;
        }

        // function set table name
        function table($table) {
            $this->table = $table;
            return $this;
        }

        // function to select the columns
        function select($select = ['*']) {
            $this->select = $select;
            return $this;
        }

        // function to all records
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

        // function to add WHERE clause
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

                $this->sqlQuery = "SELECT $this->select FROM $this->table WHERE $whereClause";

                // prepare string values
                for($i = 0; $i < strlen($colsType); $i++) {
                    $colsType[$i] === 's' ? $vals[$i] = "%".$this->prepare_string($vals[$i])."%" : "";
                }

                $results = $this->get_custom_result($colsType, $vals);

                while($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                    array_push($this->dataset, $row);
                }

                return $this -> dataset;
            } 
            else {
                return "Error: Unequal columns count in the query.";
            }
        }

        function get_custom_result($types = null, $params = null) 
        {
            $stmt = mysqli_prepare($this->dbc, $this->sqlQuery);

            $stmt->bind_param($types, ...$params);
            
            if(!$stmt->execute()) 
                return false;
                            
            return $stmt->get_result();
        }
        
        function __destruct() {
            mysqli_close($this->dbc);
        }
    }
?>
