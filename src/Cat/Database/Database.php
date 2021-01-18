<?php


namespace Cat\Database;


use PDO;

class Database
{

    private $pdo;

    private static $instance;

    /**
     * DatabaseConnection constructor.
     * @param $database
     * @param string $user
     * @param string $pass
     * @param string $host
     */
    public function __construct(private $database, private $user = "root", private $pass = "root", private $host = "localhost") { }

    public static function instance() {
        if(self::$instance === null) {
            self::$instance = new Database(
                config('database.credentials.database'),
                config('database.credentials.username'),
                config('database.credentials.password'),
                config('database.credentials.hostname')
            );
        }
        return self::$instance;
    }

    private function getPDO(): PDO
    {
        if($this->pdo === null) {
            $pdo = new \PDO('mysql:dbname=' . $this->database . ';host=' . $this->host, $this->user, $this->pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $this->pdo = $pdo;
        }

        return $this->pdo;
    }

    public function query($statement, $className = null, $one = false) {
        $req = $this->getPDO()->query($statement);

        if(
            str_starts_with($statement, 'UPDATE') ||
            str_starts_with($statement, 'INSERT') ||
            str_starts_with($statement, 'DELETE')

        ) {
            return $req;
        }

        if($className) {
            $req->setFetchMode(PDO::FETCH_CLASS, $className);
        } else {
            $req->setFetchMode(PDO::FETCH_OBJ);
        }

        return $one ? $req->fetch() : $req->fetchAll();
    }

    public function prepare($statement, $attributes, $className = null, $one = false) {
        $req = $this->getPDO()->prepare($statement);
        $res = $req->execute($attributes);

        if(
            str_starts_with($statement, 'UPDATE') ||
            str_starts_with($statement, 'INSERT') ||
            str_starts_with($statement, 'DELETE')

        ) {
            return $res;
        }

        if($className) {
            $req->setFetchMode(PDO::FETCH_CLASS, $className);
        } else if(!$one) {
            $req->setFetchMode(PDO::FETCH_OBJ);
        }

        return $one ? $req->fetch() : $req->fetchAll();
    }

    public function getLastInsertedId(): string
    {
        return $this->getPDO()->lastInsertId();
    }


}