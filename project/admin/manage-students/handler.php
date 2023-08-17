<?php
    include('../../config/DB_config.php');

    // Start Session
    session_start();

    // Redirect if user not logged in
    if(!isset($_SESSION['loggedIn']) || $_SESSION['user'] !== 'Admin') {
        echo "<script>window.location.href='../../index.php';</script>";
    }
    
    // Create DB Object
    $db = new DB;

    if(isset($_POST['add_user'])) {
        // fetch data from POST request
        $email = $_POST['user_email'];
        $fname = $_POST['user_fname'];
        $lname = $_POST['user_lname'];
        $password = md5($_POST['user_password']);
        $type = $_POST['user_type'];

        // execute query to Add User
        $res = $db->table('users')->insert(['user_email', 'user_fname', 'user_lname', 'user_password', 'user_type'], [$email, $fname, $lname, $password, $type], 'sssss');
    }
    else if(isset($_POST['update_user'])) {
        // fetch data from POST request
        $email = $_POST['user_email'];
        $fname = $_POST['user_fname'];
        $lname = $_POST['user_lname'];
        $password = md5($_POST['user_password']);
        $type = $_POST['user_type'];

        // execute query to Update User
        $res = $db->table('users')->update(['user_fname', 'user_lname', 'user_password', 'user_type'], [$fname, $lname, $password, $type], 'ssss')
                    ->where(['user_email'], [$email], ['LIKE'], 's');
    }
    else if(isset($_POST['delete_user'])) {
        // fetch data from POST request
        $user_email = $_POST['user_email'];
        
        // execute query to Delete User
        $res = $db->table('users')->delete()->where(['user_email'], [$user_email], ['LIKE'], 's');
    }

    echo json_encode($res);
?>