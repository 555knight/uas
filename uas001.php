<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sistem Pemilihan</title>
    </head>
    <body bgcolor="#f4f4f9" text="#333333">
        <center>
            <br>
            <h1>Sistem Pemilihan Kandidat</h1>
            <hr width="50%">
            <br>


            <fieldset>
                <legend><h2>Form Login & Voting</h2></legend>
                <form action="" method="get">
                    <table cellpadding="8" cellspacing="0" border="0">
                        <tr>
                            <td align="right"><b><label>Username:</label></b></td>
                            <td align="left"><input type="text" name="username" size="30"></td>
                        </tr>
                        <tr>
                            <td align="right"><b><label>Password:</label></b></td>
                            <td align="left"><input type="password" name="password" size="30"></td>
                        </tr>
                        <tr>
                            <td colspan="2"><hr></td>
                        </tr>
                        <tr>
                            <td align="right" valign="top"><b><label>Pilih kandidat:</label></b></td>
                            <td align="left">
                                <input type="radio" name="pilihan" value="A"> Kandidat A<br>
                                <input type="radio" name="pilihan" value="B"> Kandidat B<br>
                                <input type="radio" name="pilihan" value="C"> Kandidat C<br>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <br>
                                <input type="submit" value="Login & Vote">
                            </td>
                        </tr>
                    </table>
                </form>
            </fieldset>

            <br><br>


            <fieldset>
                <legend><h3>Status & Hasil</h3></legend>
                
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
            </fieldset>
        </center>
    </body>
</html>
