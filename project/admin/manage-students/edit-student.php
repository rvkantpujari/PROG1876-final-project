<?php
    include('../../config/DB_config.php');

    // Start Session
    session_start();

    // Redirect if user not logged in
    if(!isset($_SESSION['loggedIn']) || $_SESSION['user'] !== 'Admin') {
        echo "<script>window.location.href='../../index.php';</script>";
    }

    if (!isset($_POST['user_email'])) {
        echo "<script>window.location.href='students.php';</script>";
    }

    $db = new DB;

    $user_email = $_POST['user_email'];
    $record = $db->table("users")->select()->where(['user_email'], [$user_email], ['LIKE'], 's');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $_SESSION['user']; ?> - Edit Student | Enroll</title>
        <script src="https://cdn.tailwindcss.com"></script>
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
                                    Edit Student
                                </span>

                                <div class="mt-2 not-italic">
                                    To edit a user, you'll need to enter information such as user id, user title etc.
                                </div>
                            </div>
                        </div>

                        <div class="rounded-lg bg-white p-8 shadow-lg lg:col-span-4 lg:p-12">
                            <form class="grid grid-cols-8 gap-4">
                                <div class="col-span-full md:col-span-4">
                                    <label class="text-sm text-gray-500" for="user_fname">First Name</label>
                                    <input class="w-full mt-2 rounded-lg border border-gray-300 p-3 text-md" value="<?php echo $record[0]['user_fname']; ?>" placeholder="First Name" type="text" id="user_fname"/>
                                </div>

                                <div class="col-span-full md:col-span-4">
                                    <label class="text-sm text-gray-500" for="user_lname">Last Name</label>
                                    <input class="w-full mt-2 rounded-lg border border-gray-300 p-3 text-md" value="<?php echo $record[0]['user_lname']; ?>" placeholder="Last Name" type="text" id="user_lname"/>
                                </div>

                                <div class="col-span-full">
                                    <label class="text-sm text-gray-500" for="user_email">Email</label>
                                    <input class="w-full mt-2 rounded-lg border border-gray-300 p-3 text-md" value="<?php echo $record[0]['user_email']; ?>" placeholder="Email" type="text" id="user_email" readonly/>
                                </div>

                                <div class="col-span-full">
                                    <label class="text-sm text-gray-500" for="user_password">Password</label>
                                    <input class="w-full mt-2 rounded-lg border border-gray-300 p-3 text-md" placeholder="Password" type="password" id="user_password"/>
                                </div>

                                <div class="col-span-full mt-4 flex justify-between md:justify-start">
                                    <button type="submit" class="btn inline-block rounded-lg bg-black mt-4 px-4 py-2 font-medium text-white sm:w-auto">Update</button>
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
        <script>
            $(document).ready(function () 
            {
                let btn = $('.btn').on('click', btnClicked);
                
                function btnClicked(event) 
                {
                    event.preventDefault();

                    let formData = { 
                        update_user : true,
                        user_email : $('#user_email').val(),
                        user_fname : $('#user_fname').val(),
                        user_lname : $('#user_lname').val(),
                        user_password : $('#user_password').val(),
                        user_type : "STU"
                    };

                    console.log(formData);

                    $.ajax({
                        type: "POST",
                        url: "handler.php",
                        data: formData,
                        dataType: "json",
                        encode: true,
                    }).done(function(data) {
                        if(data) {
                            sessionStorage.setItem("msg", "user-updated");
                            window.location.href='students.php';
                        }
                    });
                }
            });
        </script>
    </body>
</html>