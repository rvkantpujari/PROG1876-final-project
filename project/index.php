<?php
    include('config/DB_config.php');

    session_start();

    if(isset($_SESSION['loggedIn'])) {
        echo "<script>window.location.href='/prog1876-final-project/project/dashboard.php';</script>";
    }

    // Create DB instance
    $db = new DB;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Homepage - Enroll</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="h-screen">
        <!-- Header/Navbar -->
        <section class="h-[10vh]"><?php require ('layout/header.php'); ?></section>
        <!-- Main Section -->
        <section class="min-h-[80vh] w-full space-4 px-8 py-12 bg-white">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 space-8">
                <?php
                    $courses = $db->table('courses')->selectAll();
                    foreach($courses as $course) {
                ?>
                    <!-- Manage Courses -->
                    <div class="col-span-full md:col-span-6 lg:col-span-3">
                        <div class="group relative block h-80 md:h-72 lg:h-64">
                            <span class="absolute inset-0 border-2 border-dashed border-black"></span>
                            <div class="relative flex h-full transform items-end border-2 border-black bg-white transition-transform group-hover:-translate-x-2 group-hover:-translate-y-2">
                                <div class="p-4 !pt-0 transition-opacity group-hover:absolute group-hover:opacity-0 sm:p-6 lg:p-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 md:w-10 md:h-10">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                    </svg>
                                    <h2 class="mt-2 md:text-xl font-semibold text-2xl"><?php echo $course['course_id'] ?></h2>
                                    <h2 class="md:text-xl font-bold text-xl"><?php echo $course['course_title'] ?></h2>
                                </div>

                                <div class="absolute opacity-0 transition-opacity group-hover:relative group-hover:opacity-100 p-6 md:p-4 lg:p-8">
                                    <h3 class="mt-4 md:text-xl font-semibold">Course Description</h3>
                                    <h3 class="mt-2 md:text-md font-medium"><?php echo mb_strimwidth($course['course_desc'], 0, 200, '...') ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    }
                ?>
            </div>
        </section>
        <!-- Footer -->
        <section class="h-[10vh]"><?php require ('layout/footer.php'); ?></section>
    </body>
</html>