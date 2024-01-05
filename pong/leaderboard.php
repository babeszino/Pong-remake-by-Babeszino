<html lang = "en">

<head>
    <meta charset = "UTF-8">
    <title> Leaderboard </title>
    <link rel = "stylesheet" type = "text/css" href = "leaderboard.css">
</head>

<body>
    <div class="wrapper">
        <?php
        require_once "config.php";

        $sql = "SELECT username, wins, losses FROM users ORDER BY wins DESC";
        $result = mysqli_query($link, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<th>Player</th>";
            echo "<th>Games Won</th>";
            echo "<th>Games Lost</th>";
            echo "</tr>";
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["username"] . "</td>";
                echo "<td>" . $row["wins"] . "</td>";
                echo "<td>" . $row["losses"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>There are no results yet.</p>";
        }

        mysqli_close($link);
        ?>
    </div>
</body>

</html>