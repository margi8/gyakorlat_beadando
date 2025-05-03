<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	<?php
// Szerveroldali ellenőrzés
if (!isset($_POST['nev']) || strlen($_POST['nev']) < 5) {
    exit("Hibás név: " . $_POST['nev']);
}

$re = '/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/';
if (!isset($_POST['email']) || !preg_match($re, $_POST['email'])) {
    exit("Hibás email: " . $_POST['email']);
}

if (!isset($_POST['szoveg']) || empty($_POST['szoveg'])) {
    exit("Hibás szöveg: " . $_POST['szoveg']);
}

// Kiíratás
echo "<h2>Kapott adatok:</h2>";
echo "<p><strong>Név:</strong> " . htmlspecialchars($_POST['nev']) . "</p>";
echo "<p><strong>Email:</strong> " . htmlspecialchars($_POST['email']) . "</p>";
echo "<p><strong>Üzenet:</strong> " . nl2br(htmlspecialchars($_POST['szoveg'])) . "</p>";

// Adatbázisba mentés
try {
    $pdo = new PDO('mysql:host=localhost;dbname=margi8db', 'margi8db', 'Margi8', array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ));
    $pdo->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');

    $sql = "INSERT INTO uzenetek (nev, email, szoveg) VALUES (:nev, :email, :szoveg)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nev' => $_POST['nev'],
        ':email' => $_POST['email'],
        ':szoveg' => $_POST['szoveg']
    ]);

    echo "<p><strong>Az üzenet sikeresen mentésre került az adatbázisba.</strong></p>";

} catch (PDOException $e) {
    echo "<p><strong>Adatbázis hiba:</strong> " . $e->getMessage() . "</p>";
}
echo "<a class='btn' href='http://margi8.nhely.hu/gyakorlat_beadando/kapcsolat'>Új üzenet küldése</a>";
echo "</div>";
?>
	</body>
</html>
