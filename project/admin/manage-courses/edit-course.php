<?php
    include('../../config/DB_config.php');

    // Start Session
    session_start();

    // Redirect if user not logged in
    if(!isset($_SESSION['loggedIn']) || $_SESSION['user'] !== 'Admin') {
        echo "<script>window.location.href='../../index.php';</script>";
    }

    if (!isset($_POST['course_id'])) {
        echo "<script>window.location.href='courses.php';</script>";
    }

    $db = new DB;

    $course_id = $_POST['course_id'];
    $record = $db->table("courses")->select()->where(['course_id'], [$course_id], ['LIKE'], 's');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $_SESSION['user']; ?> - Edit Course | Enroll</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body>
        <!-- Header/Navbar -->
        <?php require ('../../layout/header.php'); ?>
        <!-- Main Section -->
        <section class="w-full space-4 px-8 py-12 bg-white">
            <section class="bg-gray-100">
                <div class="mx-auto max-w-screen-xl px-4 py-16 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 gap-x-16 gap-y-8 lg:grid-cols-5">
                        <div class="lg:col-span-2 lg:py-12">
                            <div class="mt-0">
                                <span href="" class="text-2xl font-bold text-indigo-600">
                                    Add Course
                                </span>

                                <div class="mt-2 not-italic">
                                    To add a course, you'll need to enter information such as course id, course title etc.
                                </div>
                            </div>
                        </div>

                        <div class="rounded-lg bg-white p-8 shadow-lg lg:col-span-3 lg:p-12">
                            <form class="grid grid-cols-8 gap-4">
                                <div class="col-span-full md:col-span-2">
                                    <label class="text-sm text-gray-500" for="course_id">Course ID</label>
                                    <input class="w-full mt-2 rounded-lg border border-gray-300 p-3 text-md" value="<?php echo $record[0]['course_id']; ?>" placeholder="Course ID" type="text" id="course_id" readonly/>
                                </div>

                                <div class="col-span-full md:col-span-4">
                                    <label class="text-sm text-gray-500" for="course_title">Course Title</label>
                                    <input class="w-full mt-2 rounded-lg border border-gray-300 p-3 text-md" value="<?php echo $record[0]['course_title']; ?>" placeholder="Course Title" type="text" id="course_title"/>
                                </div>

                                <div class="col-span-full md:col-span-2">
                                    <label class="text-sm text-gray-500" for="course_credits">Course Credits</label>
                                    <input class="w-full mt-2 rounded-lg border border-gray-300 p-3 text-md" value="<?php echo $record[0]['course_credits']; ?>" placeholder="Course Credits" type="text" id="course_credits"/>
                                </div>
                                
                                <div class="col-span-full">
                                    <label class="text-sm text-gray-500" for="coruse_desc">Course Description</label>
                                    <textarea class="w-full mt-2 rounded-lg border border-gray-300 p-3 text-md" placeholder="Description" rows="8" id="course_desc"><?php echo $record[0]['course_desc']; ?></textarea>
                                </div>

                                <div class="mt-4 flex justify-between md:justify-start">
                                    <button type="submit" class="btn inline-block rounded-lg bg-black mt-4 px-4 py-2 font-medium text-white sm:w-auto">Update</button>
                                    <a href="courses.php" class="inline-block rounded-lg bg-red-500 mt-4 ml-5 px-4 py-2 font-medium text-white sm:w-auto">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </section>
        <!-- Footer -->
        <?php require ('../../layout/footer.php'); ?>
        <!-- Script -->
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function () 
            {
                let btn = $('.btn').on('click', btnClicked);
                
                function btnClicked(event) 
                {
                    event.preventDefault();
                    
                    // alert('clicked');

                    let formData = { 
                        update_course : true,
                        course_id : $('#course_id').val(), 
                        course_title : $('#course_title').val(),
                        course_credits : $('#course_credits').val(),
                        course_desc : $('#course_desc').val() 
                    };

                    $.ajax({
                        type: "POST",
                        url: "handler.php",
                        data: formData,
                        dataType: "json",
                        encode: true,
                    }).done(function(data) {
                        if(data) {
                            sessionStorage.setItem("msg", "course-updated");
                            window.location.href='courses.php';
                        }
                    });
                }
            });
        </script>
    </body>
</html>