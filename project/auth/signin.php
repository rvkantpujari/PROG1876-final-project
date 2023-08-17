<?php
    include('../config/DB_config.php');
    
    session_start();

    // Redirect if user logged in
    if(isset($_SESSION['loggedIn'])) {
        echo "<script>window.location.href='../index.php';</script>";
    }
    
    // If SignIn button is clicked
    if(isset($_POST['btnSignIn']))
    {
        // Assign data from POST request
        $email = $_POST['email'];
        $password = md5($_POST['password']);

        // Create instance of DB class
        $db = new DB;

        // Get user data using email id
        $result = $db->table('users')->select(['user_fname', 'user_lname'])->where(['user_email', 'user_password'], [$email, $password], ['LIKE', 'LIKE'], 'ss', ['AND']);
        
        if($result) {
            $_SESSION['loggedIn'] = 1;
            $user = $db->table('users')->join('user_types', 'users.user_type', 'user_types.user_type_id')->where(['user_email'], [$email], ['LIKE'], 's');
            $_SESSION['user'] = $user[0]['user_type'];
            $_SESSION['user_fname'] = $user[0]['user_fname'];
            $_SESSION['user_lname'] = $user[0]['user_lname'];
            $_SESSION['user_email'] = $email;
            echo "<script>window.location.href='../dashboard.php';</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign In - Enroll</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body>
        <section class="container h-screen flex justify-center items-center">
            <div class="w-full max-w-sm mx-2 md:mx-0 overflow-hidden bg-white rounded-lg shadow-md hover:shadow-lg">
                <div class="px-6 py-4">
                    <div class="flex justify-center mx-auto mt-8">
                        <a href="../index.php" class="flex title-font font-medium items-center text-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                            </svg> &nbsp;
                            <span class="text-2xl hover:underline">Enroll</span></span>
                        </a>
                    </div>

                    <form method="post" class="m-4 grid grid-cols-12">
                        <div class="col-span-full mt-4">
                            <input class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-500 bg-white border rounded-lg focus:border-blue-400 focus:ring-opacity-40 focus:outline-none focus:ring focus:ring-blue-300" type="email" placeholder="Email Address" name="email" aria-label="Email Address" />
                        </div>

                        <div class="col-span-full mt-1">
                            <input class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-500 bg-white border rounded-lg focus:border-blue-400 focus:ring-opacity-40 focus:outline-none focus:ring focus:ring-blue-300" type="password" placeholder="Password" name="password" aria-label="Password" />
                        </div>

                        <div class="col-span-full flex items-center justify-end mt-6">
                            <button name="btnSignIn" class="px-6 py-2 text-sm font-medium tracking-wide text-white capitalize transition-colors duration-500 transform bg-blue-500 rounded-md hover:bg-blue-600 hover:scale-105 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                                Sign In
                            </button>
                        </div>
                    </form>
                </div>

                <div class="flex items-center justify-center py-4 text-center bg-gray-50 dark:bg-gray-700">
                    <span class="text-sm text-gray-600 dark:text-gray-200">Don't have an account? </span>

                    <a href="signup.php" class="mx-2 text-sm font-bold text-blue-500 dark:text-blue-400 hover:underline">Sign Up</a>
                </div>
            </div>
        </section>
    </body>
</html>