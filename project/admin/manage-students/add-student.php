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
        <title><?php echo $_SESSION['user']; ?> - Add Student | Enroll</title>
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
                                    Add Student
                                </span>

                                <div class="mt-2 not-italic">
                                    To add a student, you'll need to enter information such as name, email etc.
                                </div>
                            </div>
                        </div>

                        <div class="rounded-lg bg-white p-8 shadow-lg lg:col-span-4 lg:p-12">
                            <form class="grid grid-cols-8 gap-4" id="addStudentForm">
                                <div class="col-span-full md:col-span-4">
                                    <label class="text-sm text-gray-500" for="user_fname">First Name</label>
                                    <input class="w-full mt-2 rounded-lg border border-gray-300 p-3 text-md" placeholder="First Name" type="text" name="user_fname" id="user_fname"/>
                                </div>

                                <div class="col-span-full md:col-span-4">
                                    <label class="text-sm text-gray-500" for="user_lname">Last Name</label>
                                    <input class="w-full mt-2 rounded-lg border border-gray-300 p-3 text-md" placeholder="Last Name" type="text" name="user_lname" id="user_lname"/>
                                </div>

                                <div class="col-span-full">
                                    <input type="hidden" name="add_user" id="add_user">
                                    <label class="text-sm text-gray-500" for="user_email">Email</label>
                                    <input class="w-full mt-2 rounded-lg border border-gray-300 p-3 text-md" placeholder="Email" type="text" name="user_email" id="user_email"/>
                                </div>

                                <div class="col-span-full">
                                    <label class="text-sm text-gray-500" for="user_password">Password</label>
                                    <input class="w-full mt-2 rounded-lg border border-gray-300 p-3 text-md" placeholder="Password" type="password" name="user_password" id="user_password"/>
                                </div>

                                <div class="col-span-full mt-4 flex justify-between md:justify-start">
                                    <button type="submit" class="btn inline-block rounded-lg bg-black mt-4 px-4 py-2 font-medium text-white sm:w-auto">Add Student</button>
                                    <a href="students.php" class="inline-block rounded-lg bg-red-500 mt-4 ml-5 px-4 py-2 font-medium text-white sm:w-auto">Cancel</a>
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
                $( "#addStudentForm" ).validate({
                    rules: {
                        user_fname: {
                            required: true,
                            minlength: 2,
                            maxlength: 50,
                        },
                        user_lname: {
                            required: true,
                            minlength: 2,
                            maxlength: 50,
                        },
                        user_email: {
                            required: true,
                            email: true,
                        },
                        user_password: {
                            required: true,
                            minlength: 8,
                            maxlength: 32
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
                        add_user : true,
                        user_email : $('#user_email').val(), 
                        user_fname : $('#user_fname').val(),
                        user_lname : $('#user_lname').val(),
                        user_password : $('#user_password').val(),
                        user_type : "STU"
                    };

                    $.ajax({
                        type: "POST",
                        url: "handler.php",
                        data: formData,
                        dataType: "json",
                        encode: true,
                    }).done(function(data) {
                        if(data) {
                            sessionStorage.setItem("msg", "user-added");
                            window.location.href='students.php';
                        }
                    });
                }
            });
        </script>
    </body>
</html>