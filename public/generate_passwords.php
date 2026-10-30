
<?php

$passwords = [
    "sam" => "password123",
    "james" => "password123",
    "lisa" => "password123",
    "simon" => "password123"
];

foreach ($passwords as $user => $password) {

    $hash = password_hash($password, PASSWORD_DEFAULT);

    echo "<h3>" . htmlspecialchars($user) . "</h3>";
    echo "<textarea rows='3' cols='90' readonly>";
    echo htmlspecialchars($hash);
    echo "</textarea><br><br>";

}

?>
