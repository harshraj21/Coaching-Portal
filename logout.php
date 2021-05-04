<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
if (!(empty($_GET["token"]))) {
    $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "optimus-3133335204", "superlol@123", "optimus-3133335204");
    $token = mysqli_real_escape_string($link, $_GET['token']);
    $sql1 = "SELECT * FROM `students` WHERE sessionid = '$token'";
    $result1 = $link->query($sql1);
    if ($result1->num_rows == 1) {
        $row1 = $result1->fetch_assoc();
        $name = $row1['name'];
        $sql2 = "UPDATE `students` SET sessionid=0 WHERE name='$name'";
        if (mysqli_query($link, $sql2)) {
            unset($_SESSION['ssid']);
            unset($_SESSION['name']);
            unset($_SESSION['batch']);
            session_destroy();
            header("Location: login.php?return=%2F");
            exit;
        } else {
            session_destroy();
            header("Location: login.php");
            exit;
        }
    } else {
        session_destroy();
        header("Location: login.php");
        exit;
    }
}
?>