<?php
namespace App\Models;
use PDO;
use App\Core\SQL;

class User extends SQL
{


    private ?int $id = null;
    protected string $firstname;
    protected string $lastname;
    protected string $email;
    protected string $password;
    protected int $status = 0;
    protected ?string $reset_token = null;
    protected ?string $reset_token_expiry = null;

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

    public function setResetToken($email, $resetToken): void
    {
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expire dans 1 heure
        $sql = "UPDATE " . $this->table . " SET reset_token = :reset_token, reset_token_expiry = :expiry WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':reset_token', $resetToken, PDO::PARAM_STR);
        $stmt->bindParam(':expiry', $expiry, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function resetPassword($token, $newPassword): bool
    {
        $sql = "SELECT id FROM " . $this->table . " WHERE reset_token = :token AND reset_token_expiry > NOW()";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        if ($user = $stmt->fetch()) {
            $newPasswordHashed = password_hash($newPassword, PASSWORD_BCRYPT);
            $sqlUpdate = "UPDATE " . $this->table . " SET password = :password, reset_token = NULL, reset_token_expiry = NULL WHERE id = :id";
            $stmtUpdate = $this->pdo->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':password', $newPasswordHashed, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':id', $user['id'], PDO::PARAM_INT);
            $stmtUpdate->execute();
            return true;
        }

        return false;
    }
}