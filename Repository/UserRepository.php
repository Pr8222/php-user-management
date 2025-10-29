<?php

namespace Repository;
use Config\db;
use PDO;
use PDOException;

require_once "../Config/db.php";

class UserRepository implements IUserRepository
{
    private $db;
    private $conn;
    public function __construct(){
        $this->db = new db();
        $this->conn = $this->db->getConnection();
    }

    public function getAllUsers(): array
    {
        $query = "SELECT * FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        $query = "SELECT * FROM users WHERE id = $id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByUsername($username) {
        $query = "SELECT * FROM users WHERE username = '$username'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addNewUser(array $data): bool
    {
        try {
            $query = "INSERT INTO users (username, email, password, created_at)
                    VALUES (:username, :email, :password, NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->execute($data);
            return true;
        }
        catch (PDOException $e) {
            return false;
        }
    }

    public function updateUser($id, array $data) : bool
    {
        try {
            $query = "UPDATE users SET username = :username, email = :email, password = :password";
            $stmt = $this->conn->prepare($query);
            $stmt->execute($data);
            return true;
        }
        catch (PDOException $e){
            return false;
        }
    }

    public function deleteUser($id) : bool
    {
        try {
            $query = "DELETE FROM users WHERE id = $id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return true;
        }
        catch (PDOException $e){
            return false;
        }
    }
}