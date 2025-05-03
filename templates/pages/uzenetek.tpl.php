<?php
session_start();
if (!isset($_SESSION['login'])) {
    exit('<p style="color:red;">Az üzenetek megtekintéséhez be kell jelentkeznie.</p>');
}

echo "<h2>Beérkezett üzenetek</h2>";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=margi8db', 'margi8db', 'Margi8', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $pdo->query('SET NAMES utf8 COLLATE utf8_hungarian_ci');

    $stmt = $pdo->query("SELECT nev, email, szoveg, datum FROM uzenetek ORDER BY datum DESC");
    $uzenetek = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($uzenetek) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Név</th><th>Email</th><th>Üzenet</th><th>Dátum</th></tr>";
        foreach ($uzenetek as $uzenet) {
            $nev = trim($uzenet['nev']) !== '' ? htmlspecialchars($uzenet['nev']) : 'Vendég';
            $email = htmlspecialchars($uzenet['email']);
            $szoveg = nl2br(htmlspecialchars($uzenet['szoveg']));
            $datum = $uzenet['datum'];
            echo "<tr>
                    <td>{$nev}</td>
                    <td>{$email}</td>
                    <td>{$szoveg}</td>
                    <td>{$datum}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Nincs egyetlen üzenet sem.</p>";
    }
} catch (PDOException $e) {
    echo "<p>Adatbázis hiba: " . $e->getMessage() . "</p>";
}
?>