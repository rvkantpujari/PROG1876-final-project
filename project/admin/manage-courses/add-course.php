<?php
    include('../../config/DB_config.php');

    // Start Session
    session_start();

    // Redirect if user not logged in
    if(!isset($_SESSION['loggedIn']) || $_SESSION['user'] !== 'Admin') {
        echo "<script>window.location.href='../../index.php';</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $_SESSION['user']; ?> - Add Course | Enroll</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            .error {color: red; font-size: 0.8rem;}
        </style>
    </head>
    <body>
        <!-- Header/Navbar -->
        <?php require ('../../layout/header.php'); ?>
        <!-- Main Section -->
        <section class="w-full space-4 px-8 py-12 bg-white">
            <section class="bg-gray-100">
                <div class="mx-auto max-w-screen-xl px-4 py-16 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 gap-x-16 gap-y-8 lg:grid-cols-6">
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

                        <div class="rounded-lg bg-white p-8 shadow-lg lg:col-span-4 lg:p-12">
                            <form class="grid grid-cols-8 gap-4" id="addCourseForm">
                                <div class="col-span-full md:col-span-2">
                                    <input type="hidden" name="add_course" id="add_course">
                                    <label class="text-sm text-gray-500" for="course_id">Course ID</label>
                                    <input class="w-full mt-2 rounded-lg border border-gray-300 p-3 text-md" placeholder="Course ID" type="text" name="course_id" id="course_id"/>
                                </div>

                                <div class="col-span-full md:col-span-4">
                                    <label class="text-sm text-gray-500" for="course_title">Course Title</label>
                                    <input class="w-full mt-2 rounded-lg border border-gray-300 p-3 text-md" placeholder="Course Title" type="text" name="course_title" id="course_title"/>
                                </div>

                                <div class="col-span-full md:col-span-2">
                                    <label class="text-sm text-gray-500" for="course_credits">Course Credits</label>
                                    <input class="w-full mt-2 rounded-lg border border-gray-300 p-3 text-md" placeholder="Course Credits" type="text" name="course_credits" id="course_credits"/>
                                </div>

                                <div class="col-span-full">
                                    <label class="text-sm text-gray-500" for="coruse_desc">Course Description</label>
                                    <textarea class="w-full mt-2 rounded-lg border border-gray-300 p-3 text-md" placeholder="Description" rows="5" name="course_desc" id="course_desc"></textarea>
                                </div>

                                <div class="col-span-full flex justify-between md:justify-start">
                                    <button class="btn inline-block rounded-lg bg-black mt-4 px-4 py-2 font-medium text-white sm:w-auto">Add Course</button>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"></script>
        <script>
            $(document).ready(function () 
            {
                // Form Validations
                $( "#addCourseForm" ).validate({
                    rules: {
                        course_id: {
                            required: true,
                            minlength: 8,
                            maxlength: 8,
                        },
                        course_title: {
                            required: true,
                            minlength: 8,
                            maxlength: 50
                        },
                        course_credits: {
                            min: 1,
                            max: 4
                        },
                        course_desc: {
                            required: true,
                            minlength: 50,
                            maxlength: 500
                        }
                    },
                    submitHandler: function(form)
                    {
                        let btn = $('.btn').on('click', btnClicked);
                    }
                });
                
                function btnClicked(event) 
                {
                    event.preventDefault();
                    
                    let formData = { 
                        add_course : true,
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
                            sessionStorage.setItem("msg", "course-added");
                            window.location.href='courses.php';
                        }
                    });
                }
            });
        </script>
    </body>
</html>