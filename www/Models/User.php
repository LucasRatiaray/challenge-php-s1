<?php
namespace App\Models;
use PDO;
use App\Core\SQL;

class User extends SQL
{

    const ROLE_ADMIN = 'admin';
    const ROLE_AUTHOR = 'author';

    private ?int $id = null;
    protected string $firstname;
    protected string $lastname;
    protected string $email;
    protected string $password;
    protected string $role = self::ROLE_AUTHOR; // Rôle par défaut

    protected int $status = 0;
    protected ?string $token = null;
    protected ?string $token_expiry = null;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = ucwords(strtolower(trim($firstname)));
    }
    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = strtolower(trim($role));
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = strtoupper(trim($lastname));
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = strtolower(trim($email));
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }


    public function exists($email): bool
    {
        $sql = "SELECT id FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch() !== false;
    }

    public function getFullName($email): string
    {
        $sql = "SELECT firstname, lastname FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();
        return $user['firstname'] . ' ' . $user['lastname'];
    }

    public function setToken($email, $token): void
    {
        $this->token = $token;
        $this->token_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
    }

    public function confirmAccount($token): bool
    {
        $sql = "SELECT id FROM " . $this->table . " WHERE token = :token AND token_expiry > NOW()";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        if ($user = $stmt->fetch()) {
            $sqlUpdate = "UPDATE " . $this->table . " SET status = 1, token = NULL, token_expiry = NULL WHERE id = :id";
            $stmtUpdate = $this->pdo->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':id', $user['id'], PDO::PARAM_INT);
            $stmtUpdate->execute();
            return true;
        }

        return false;
    }

    public function activateAccount($email): bool
    {
        $sql = "SELECT id FROM " . $this->table . " WHERE email = :email AND status = 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($user = $stmt->fetch()) {
            $sqlUpdate = "UPDATE " . $this->table . " SET status = 1, token = NULL, token_expiry = NULL WHERE id = :id";
            $stmtUpdate = $this->pdo->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':id', $user['id'], PDO::PARAM_INT);
            $stmtUpdate->execute();
            return true;
        }

        return false;
    }

    public function resetPassword($token, $newPassword): bool
    {
        $sql = "SELECT id FROM " . $this->table . " WHERE token = :token AND token_expiry > NOW()";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        if ($user = $stmt->fetch()) {
            $newPasswordHashed = password_hash($newPassword, PASSWORD_BCRYPT);
            $sqlUpdate = "UPDATE " . $this->table . " SET password = :password, token = NULL, token_expiry = NULL WHERE id = :id";
            $stmtUpdate = $this->pdo->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':password', $newPasswordHashed, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':id', $user['id'], PDO::PARAM_INT);
            $stmtUpdate->execute();
            return true;
        }

        return false;
    }

    public function getUserById(int $id): array
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
                    $this->id = $user['id']; // Assurez-vous que l'ID utilisateur est défini dans l'objet
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
    public function update(): bool
    {
        $sql = "UPDATE chall_user SET firstname = :firstname, lastname = :lastname, email = :email, role = :role, date_updated = NOW() WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':firstname', $this->firstname, PDO::PARAM_STR);
        $stmt->bindParam(':lastname', $this->lastname, PDO::PARAM_STR);
        $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindParam(':role', $this->role, PDO::PARAM_STR);

        return $stmt->execute();
    }
    public function getAllUsers(): array
    {
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function delete(): bool
    {
        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }


    public function save(): bool
    {
        $sql = "INSERT INTO chall_user (firstname, lastname, email, password, role, date_inserted, date_updated)
                VALUES (:firstname, :lastname, :email, :password, :role, NOW(), NOW())";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':firstname', $this->firstname, PDO::PARAM_STR);
        $stmt->bindParam(':lastname', $this->lastname, PDO::PARAM_STR);
        $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $this->password, PDO::PARAM_STR);
        $stmt->bindParam(':role', $this->role, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function getCount(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM chall_user");
        return (int) $stmt->fetchColumn();
    }


}