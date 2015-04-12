<?php
/**
 * mysqlDb class abstracts the creation of the db into a singleton. The object is a singleton.
 * To use, call getInstance() to get an instance of the class.
 * Then use getConnection() to get the connection to the database. Returns a PDO object.
 */
class MySQLDb
{
    private $username = "root";
    private $password = "root";
    private $dbName = "qascanner";

    private $conn;
    protected static $instance;

    protected function  __construct()
    {
        try
        {
            $this->conn = new PDO("mysql:host=localhost;dbname=$this->dbName", $this->username, $this->password);
        }
        catch(PDOException $e)
        {
            echo "Caught PDO exception: " . $e->getMessage();
        }
    }


    final static function getInstance()
    {
        if(!isset(self::$instance))
        {
            self::$instance = new MySQLDb();
        }
        return self::$instance;
    }

    /**
     * Gets a PDO object connection
     * @return PDO
     */
    public function getConnection()
    {
        if(isset($this->conn))
        {
            return $this->conn;
        }
        else
        {
            return null;
        }
    }

}
