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

    if(isset($_POST['remove_enrollment'])) {
        // fetch data from POST request
        $course_id = $_POST['course_id'];
        $stu_email = $_POST['stu_email'];
        // execute query to Remove Enrollment
        $res = $db->table('enrolled')->delete()->where(['course_id', 'stu_email'], [$course_id, $stu_email], ['LIKE', 'LIKE'], 'ss', ['AND']);
    }

    echo json_encode($res);
?>