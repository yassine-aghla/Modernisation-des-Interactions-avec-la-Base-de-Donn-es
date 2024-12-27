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
        'name' => 'mouad',
        'photo' => 'ronaldo.jpg',
        'position' => 'Forward',
        'club' => 'Al-Nassr',
        'nationality' => 'Portugal',
        'rating' => 94
    ]);

    // Lire tous les joueurs
    $players = $db->select('players');
    print_r($players);
    echo "<br>";

    // Mettre à jour un joueur
    $db->update('players', [
        'name' => 'younnes',
        'club' => 'mas'
    ], "player_id = 39");

    // Supprimer un joueur
    // $db->delete('players', "player_id = 37");

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
