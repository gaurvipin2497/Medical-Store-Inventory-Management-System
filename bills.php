<?php
    session_start();
    if (!isset($_SESSION['doctor'])) {
        header("Location: index.html");
        exit();
    }

    $txnid = $_POST['txn_id'];

    include 'config.php';

    // Create connection
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sqlq = "SELECT * FROM (SELECT * FROM transaction WHERE id='".$txnid."') AS a NATURAL JOIN txn_on NATURAL JOIN medicine;";
    $result = $conn->query($sqlq);
    if (!$result) {
        echo "Query Failed.<br />";
        exit;
    }
    echo "<pre>Medicine Info:<br /><br />";
    echo "<table border=1><tr>";
    while ($field_info = mysqli_fetch_field($result)) {
        echo "<th>{$field_info->name}</th>";
    }
    echo "</tr>";
    while ($row = mysqli_fetch_row($result)) {
        echo "<tr>";
        foreach ($row as $_column) {
            echo "<td>{$_column}</td>";
        }
        echo "</tr>";
    }
    echo "</table></pre>";

    $sqlq = "SELECT * FROM (SELECT pid_person FROM txn_person WHERE id='".$txnid."') AS a JOIN person ON (a.pid_person=person.pid)";
    $result = $conn->query($sqlq);
    if (!$result) {
        echo "Query Failed.<br />";
        exit;
    }
    echo "<pre>Buyer/Seller Info:<br /><br />";
    echo "<table border=1><tr>";
    while ($field_info = mysqli_fetch_field($result)) {
        echo "<th>{$field_info->name}</th>";
    }
    echo "</tr>";
    while ($row = mysqli_fetch_row($result)) {
        echo "<tr>";
        foreach ($row as $_column) {
            echo "<td>{$_column}</td>";
        }
        echo "</tr>";
    }
    echo "</table></pre>";

    $txntype=-1;
    $sqlq = "SELECT * FROM transaction WHERE id='".$txnid."'";
    $result = $conn->query($sqlq);
    $sqlq = $result->fetch_assoc();
    if ($sqlq['buy_sell']=='B') {
        $txntype=1;
    } else if ($sqlq['buy_sell']=='S') {
        $txntype=0;
    }

    if ($txntype==0) {  // S
        $sqlq = "SELECT SUM(qty_buy_sell*sp) AS 'Received from Customer' FROM (SELECT * FROM transaction WHERE id='".$txnid."') AS a NATURAL JOIN txn_on NATURAL JOIN medicine;";
        $result = $conn->query($sqlq);
        if (!$result) {
            echo "Query Failed.<br />";
            exit;
        }
        echo "<pre>Bill:<br /><br />";
        echo "<table border=1><tr>";
        while ($field_info = mysqli_fetch_field($result)) {
            echo "<th>{$field_info->name}</th>";
        }
        echo "</tr>";
        while ($row = mysqli_fetch_row($result)) {
            echo "<tr>";
            foreach ($row as $_column) {
                echo "<td>{$_column}</td>";
           
