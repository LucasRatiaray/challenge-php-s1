<?php

namespace App\Core;

use PDO;
use PDOException;

class SQL
{
    private static $instance = null; // Static instance to hold the database connection
    protected $pdo;
    protected $table;
    private $dbname = 'challenge';
    private $id;
    private $db_user = 'challengers';
    private $db_pass = 'qlslfho097IhOIHD8681298';

    public function __construct()
    {
        $this->connect();
        try {
            $this->pdo = new PDO("pgsql:host=db;dbname=".$this->dbname.";port=5432", $this->db_user, $this->db_pass);
        } catch (\Exception $e) {
            die("Erreur SQL : " . $e->getMessage());
        }

        $classChild = get_called_class();
        $this->table = "chall_" . strtolower(str_replace("App\\Models\\", "", $classChild));
    }

    private function connect()
    {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO("pgsql:host=db;dbname=".$this->dbname.";port=5432", $this->db_user, $this->db_pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (\PDOException $e) {
                die("Erreur SQL : " . $e->getMessage());
            }
        }
        $this->pdo = self::$instance;
    }

    public static function getConnection()
    {
        if (self::$instance === null) {
            (new self())->connect();
        }
        return self::$instance;
    }

    public function save()
    {
        $columnsAll = get_object_vars($this);
        $columnsToDelete = get_class_vars(get_class());
        $columns = array_diff_key($columnsAll, $columnsToDelete);

        if (empty($this->getId())) {
            $sql = "INSERT INTO " . $this->table . " (" . implode(', ', array_keys($columns)) . ")  
            VALUES (:" . implode(',:', array_keys($columns)) . ")";
        } else {
            foreach ($columns as $column => $value) {
                $sqlUpdate[] = $column . "=:" . $column;
            }

            $sql = "UPDATE " . $this->table . " SET " . implode(',', $sqlUpdate) . " WHERE id=" . $this->getId();
        }
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute($columns);
    }

    public function login(string $email, string $password): array
    {
        $sql = "SELECT id, password, status FROM " . $this->table . " WHERE email = :email";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die('SQL Error: ' . $e->getMessage());
        }

        if ($user = $stmt->fetch()) {
            if (password_verify($password, $user['password'])) {
                if ($user['status'] == 1) {
                    $_SESSION['user_id'] = $user['id'];
                    return [
                        'success' => true,
                        'message' => 'Vous êtes connecté avec votre adresse email ' . $email
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Votre compte n\'est pas activé.'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Mot de passe incorrect.'
                ];
            }
        }
        return [
            'success' => false,
            'message' => 'Adresse email inconnue.'
        ];
    }

    public function getId()
    {
        return $this->id;
    }
}
