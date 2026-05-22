<!DOCTYPE html>
<html lang="en">
    <head>
	<title>pemilihan</title>
    </head>
    <body>
	<form action="" method="get">
	    <label>username:</label>
	    <input type="text" name="username"><br>
	    
	    <label>password:</label>
	    <input type="password" name="password"><br><br>

	    <label>Pilih kandidat:</label><br>
	    <input type="radio" name="pilihan" value="A"> A<br>
	    <input type="radio" name="pilihan" value="B"> B<br>
	    <input type="radio" name="pilihan" value="C"> C<br><br>
	    
	    <input type="submit" value="Login & Vote">
	</form>
    </body>
</html>

<?php
class Database {
    protected $conn;

    public function __construct($host, $user, $pass, $db) {
        $this->conn = new mysqli($host, $user, $pass, $db);
        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }
}

class VotingSystem extends Database {

    private $username;
    private $password;
    public $userData = null;

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getUsername() {
        return $this->username;
    }

    public function login() {
        if (!empty($this->username) && !empty($this->password)) {
            $query = "SELECT * FROM users WHERE username='{$this->username}' AND password='{$this->password}'";
            $result = $this->conn->query($query);

            if ($result && $result->num_rows > 0) {
                $this->userData = $result->fetch_assoc();
                return true;
            }
        }
        return false;
    }

    public function tambahVote($kandidat) {
        $this->conn->query("UPDATE votes SET jumlah = jumlah + 1 WHERE kandidat='$kandidat'");
    }

    public function tandaiSudahVote() {
        if ($this->userData) {
            $user = $this->userData['username'];
            $this->conn->query("UPDATE users SET sudah_vote = 1 WHERE username='$user'");
            $this->userData['sudah_vote'] = 1; // Update status objek
        }
    }

    public function tampilVote() {
        echo "<h3>Hasil Voting:</h3>";
        $result = $this->conn->query("SELECT * FROM votes");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                echo $row["kandidat"] . " : " . $row["jumlah"] . "<br>";
            }
        }
    }
}

$voting = new VotingSystem("localhost", "root", "", "vote");

if (isset($_GET["username"]) && isset($_GET["password"])) {
    
    $voting->setUsername($_GET["username"]);
    $voting->setPassword($_GET["password"]);

    if ($voting->login()) {
        if ($voting->userData["sudah_vote"] == 1) {
            echo "Kamu sudah pernah vote!<br>";
        } else {
            if (isset($_GET["pilihan"])) {
                $voting->tambahVote($_GET["pilihan"]);
                $voting->tandaiSudahVote();
                echo "Vote berhasil!<br>";
            }
        }
    } else {
        echo "Login gagal!<br>";
    }
}

$voting->tampilVote();
?>
