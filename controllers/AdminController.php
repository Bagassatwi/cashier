<?php
include_once 'Database.php';
include_once 'models/Admins.php';

class AdminController
{
  private mysqli $db;

  public function __construct()
  {
    $this->db = Database::getConnection();
  }

  public function authenticate(string $username, string $password): ?Admin
  {
    $stmt = $this->db->prepare("SELECT admin_id, username, password, fullname FROM admins WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$res) return null;
    return new Admin($res['username'], $res['password'], $res['fullname'], (string)$res['admin_id']);
  }

  public function findById(int $adminId): ?Admin
  {
    $stmt = $this->db->prepare("SELECT admin_id, username, password, fullname FROM admins WHERE admin_id = ?");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$res) return null;
    return new Admin($res['username'], $res['password'], $res['fullname'], (string)$res['admin_id']);
  }

  public function updateProfile(int $adminId, string $fullname): bool
  {
    $stmt = $this->db->prepare("UPDATE admins SET fullname = ?,updated_at = ? WHERE admin_id = ?");
    $updated_at = new DateTime()->format('Y-m-d H:i:s');
    $stmt->bind_param("ssi", $updated_at, $fullname, $adminId);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
  }

  public function updatePassword(int $adminId, string $newPassword): bool
  {
    $stmt = $this->db->prepare("UPDATE admins SET password = ? WHERE admin_id = ?");
    $stmt->bind_param("si", $newPassword, $adminId);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
  }
}
