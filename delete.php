<?php
    include 'db.php';
    $db = new Model();
    $id = $_GET['id'];

    $sql = "delete from blogs where id = '$id'";

    $val = $db -> query($sql);
    if ($val) {
        header('location: index.php');
    };