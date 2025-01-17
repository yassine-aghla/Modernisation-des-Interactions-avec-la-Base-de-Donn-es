<?php
class Players {
    private $conn;

    public function __construct($host, $dbname, $username, $password) {
        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // INSERT
    public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        foreach ($data as $key => $value) {
            $stmt->bindParam(":$key", $data[$key]);
        }

        return $stmt->execute();
    }

    // READ
    public function select($table, $columns = "*", $conditions = null) {
        $query = "SELECT $columns FROM $table";
        if ($conditions) {
            $query .= " WHERE $conditions";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // UPDATE
    public function update($table, $data, $conditions) {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $fieldsString = implode(", ", $fields);

        $query = "UPDATE $table SET $fieldsString WHERE $conditions";
        $stmt = $this->conn->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindParam(":$key", $data[$key]);
        }

        return $stmt->execute();
    }

    // DELETE
    public function delete($table, $conditions) {
        $query = "DELETE FROM $table WHERE $conditions";
        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }
}

// Exemple d'utilisation
try {
    // Initialiser la base de données
    $db = new Players('localhost', 'joueurs', 'root', '');

    // Ajouter un joueur
    $db->insert('players', [
        'name' => 'yassine aghla',
        'photo' => 'ronaldo.jpg',
        'position' => 'ST',
        'club' => 'Real madrid chichaoua',
        'nationality' => 'Portugal',
        'rating' => 99
    ]);

    // Lire tous les joueurs
    $players = $db->select('players');
    echo "<pre>";
    print_r($players);
    echo "</pre>";

    // Mettre à jour un joueur
    $db->update('players', [
        'name' => 'yahya',
        'club' => 'ocs',
        'position' => 'MR',
        'nationality' => 'MAROC',
        'rating' => 76
    ], "player_id = 76");

    // Supprimer un joueur
    $db->delete('players', "player_id = 104");

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
