<?php
    include('../../config/DB_config.php');

    // Start Session
    session_start();

    // Redirect if user not logged in
    if(!isset($_SESSION['loggedIn']) || $_SESSION['user'] !== 'Student') {
        echo "<script>window.location.href='../../index.php';</script>";
    }
    
    // Create DB Object
    $db = new DB;

    if(isset($_POST['enroll_course'])) {
        // fetch data from POST request
        $id = $_POST['course_id'];
        // execute query to Add Course
        $res = $db->table('enrolled')->insert(['course_id', 'stu_email'], [$id, $_SESSION['user_email']], 'ss');
    }

    echo json_encode($res);
?>