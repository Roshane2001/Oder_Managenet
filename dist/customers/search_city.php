<?php
include "/order_management/dist/connection/db_connection.php.php"; // your DB connection

if (isset($_POST['search'])) {
    $search = $_POST['search'];

    $query = "SELECT city_name FROM city_table ";
    
    $stmt = $conn->prepare($query);
    $param = "%$search%";
    $stmt->bind_param("s", $param);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='suggest-item'>" . $row['word'] . "</div>";
        }
    } else {
        echo "<div class='suggest-item no-result'>No results</div>";
    }
}
?>
