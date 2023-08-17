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

    if(isset($_POST['add_course'])) {
        // fetch data from POST request
        $id = $_POST['course_id'];
        $title = $_POST['course_title'];
        $credits = $_POST['course_credits'];
        $desc = $_POST['course_desc'];
        // execute query to Add Course
        $res = $db->table('courses')->insert(['course_id', 'course_title', 'course_credits', 'course_desc'], [$id, $title, $credits, $desc], 'ssds');
    }
    else if(isset($_POST['update_course'])) {
        // fetch data from POST request
        $id = $_POST['course_id'];
        $title = $_POST['course_title'];
        $credits = $_POST['course_credits'];
        $desc = $_POST['course_desc'];
        // execute query to Update Course
        $res = $db->table('courses')->update(['course_title', 'course_credits', 'course_desc'], [$title, $credits, $desc], 'sds')
                    ->where(['course_id'], [$id], ['LIKE'], 's');
    }
    else if(isset($_POST['delete_course'])) {
        $course_id = $_POST['course_id'];
        // execute query to Delete Course
        $res = $db->table('courses')->delete()->where(['course_id'], [$course_id], ['LIKE'], 's');
    }

    echo json_encode($res);
?>