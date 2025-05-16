
<?php
include 'config.inc';

class Conexion {
    private $hostname = SERVER;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $dbname = DB_CONN;
    private $conn;
    private $table;

    public function __construct($table) {
        $this->table = $table;
        $this->connect();
    }

    private function connect() {
        try {
          $this->conn = new PDO(
            "mysql:host={$this->hostname};dbname={$this->dbname}", 
            $this->username, 
            $this->password
          );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $err) {
            die("Error de la conexión: " . $err->getMessage());
        }
    }

    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($data);
            return $this->conn->lastInsertId();
        } catch(PDOException $e) {
            die("Error al crear registro: " . $e->getMessage());
        }
    }

    public function read($conditions = []) {
        $sql = "SELECT * FROM {$this->table}";
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "$key = :$key";
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($conditions);
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            die("Error al leer registros: " . $e->getMessage());
        }
    }

  
    public function getMaxId() {
    $sql = "SELECT MAX(Id_u) AS max_id FROM {$this->table}";
    
    try {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['max_id'];
    } catch(PDOException $e) {
        die("Error al obtener el máximo id: " . $e->getMessage());
    }
  }

  public function getUserPass() {
    $sql = "SELECT nombre, pwd FROM usuario";

    try {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        $loginData = [
            "Nombre" => $result["nombre"],
            "Pwd" => $result["pwd"]
        ];
        return $loginData;
    } catch(PDOException $e) {
        die("Error al obtener el usuario y contraseña: " . $e->getMessage());
    }
  }

    public function getConnection() {
        return $this->conn;
    }
}
?>
