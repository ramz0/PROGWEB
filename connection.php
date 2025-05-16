<?php
require_once 'config.inc.php';

class DatabaseConnection {
  private const DB_CHARSET = 'utf8mb4';
  private static $instance = null;
  private $pdo;

  private function __construct() {
    try {
      $dsn = "mysql:host=" . SERVER . ";dbname=" . DB_CONN . ";charset=" . self::DB_CHARSET;
      
      $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_PERSISTENT        => true 
      ];

      $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        
    } catch (PDOException $e) {
      error_log('Error de conexión: ' . $e->getMessage());
      
      die('Error al conectar con la base de datos...');
    }
  }

  public static function getInstance() {
    if (self::$instance === null) 
      self::$instance = new self();
    
    return self::$instance;
  }

  public function getConnection() {
    return $this->pdo;
  }

  private function __clone() {}
  
  public function __wakeup() {
    throw new Exception("No se puede deserializar una conexión a la base de datos");
  }
}

function getDBConnection() {
  return DatabaseConnection::getInstance()->getConnection();
}

// Ejemplo de uso en otros archivos:
// $pdo = getDBConnection();
// $stmt = $pdo->prepare("SELECT * FROM usuarios");
// $stmt->execute();
// $resultados = $stmt->fetchAll();
?>