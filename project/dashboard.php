<?php
    include('config/DB_config.php');

    // Start Session
    session_start();

    // Redirect if user not logged in
    if(!isset($_SESSION['loggedIn'])) {
        echo "<script>window.location.href='index.php';</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $_SESSION['user']; ?> - Manage Enrollment | Enroll</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body>
        <section><?php require ('layout/header.php'); ?></section>
        <section class="mx-8 my-12 md:px-4 min-h-[70vh]">
            <?php if($_SESSION['user'] === 'Admin') { ?>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 space-8">
                    <!-- Manage Courses -->
                    <div class="col-span-full md:col-span-6 lg:col-span-3">
                        <a href="admin/manage-courses/courses.php" class="group relative block h-80 md:h-72 lg:h-64">
                            <span class="absolute inset-0 border-2 border-dashed border-black"></span>
                            <div class="relative flex h-full transform items-end border-2 border-black bg-white transition-transform group-hover:-translate-x-2 group-hover:-translate-y-2">
                                <div class="p-4 !pt-0 transition-opacity group-hover:absolute group-hover:opacity-0 sm:p-6 lg:p-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 md:w-10 md:h-10">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                    </svg>
                                    <h2 class="mt-4 md:text-xl font-semibold text-2xl">Manage Courses</h2>
                                </div>

                                <div class="absolute opacity-0 transition-opacity group-hover:relative group-hover:opacity-100 p-6 md:p-4 lg:p-8">
                                    <h3 class="mt-4 md:text-xl font-medium text-2xl">With manage courses, you can</h3>
                                    <p class="mt-4 md:text-sm text-base">
                                        <ul>
                                            <li>View Courses</li>
                                            <li>Edit Courses</li>
                                            <li>Delete Courses</li>
                                        </ul>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Manage Students -->
                    <div class="col-span-full md:col-span-6 lg:col-span-3">
                        <a href="admin/manage-students/students.php" class="group relative block h-80 md:h-72 lg:h-64">
                            <span class="absolute inset-0 border-2 border-dashed border-black"></span>
                            <div class="relative flex h-full transform items-end border-2 border-black bg-white transition-transform group-hover:-translate-x-2 group-hover:-translate-y-2">
                                <div class="p-4 !pt-0 transition-opacity group-hover:absolute group-hover:opacity-0 sm:p-6 lg:p-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 md:w-10 md:h-10">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                    </svg>
                                    <h2 class="mt-4 md:text-xl font-semibold text-2xl">Manage Students</h2>
                                </div>

                                <div class="absolute opacity-0 transition-opacity group-hover:relative group-hover:opacity-100 p-6 md:p-4 lg:p-8">
                                    <h3 class="mt-4 md:text-xl font-medium text-2xl">With manage students, you can</h3>
                                    <p class="mt-4 md:text-sm text-base">
                                        <ul>
                                            <li>View Students</li>
                                            <li>Edit Students</li>
                                            <li>Delete Students</li>
                                        </ul>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Manage Enrollments -->
                    <div class="col-span-full md:col-span-6 lg:col-span-3">
                        <a href="admin/manage-enrollment/course-enrollment.php" class="group relative block h-80 md:h-72 lg:h-64">
                            <span class="absolute inset-0 border-2 border-dashed border-black"></span>
                            <div class="relative flex h-full transform items-end border-2 border-black bg-white transition-transform group-hover:-translate-x-2 group-hover:-translate-y-2">
                                <div class="p-4 !pt-0 transition-opacity group-hover:absolute group-hover:opacity-0 sm:p-6 lg:p-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 md:w-10 md:h-10">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                                    </svg>
                                    <h2 class="mt-4 md:text-xl font-semibold text-2xl">Manage Enrollment</h2>
                                </div>

                                <div class="absolute opacity-0 transition-opacity group-hover:relative group-hover:opacity-100 p-6 md:p-4 lg:p-8">
                                    <h3 class="mt-4 md:text-xl font-medium text-2xl">With manage enrollment, you can:</h3>
                                    <p class="mt-4 md:text-sm text-base">
                                        <ul>
                                            <li>View Students' Enrolled Courses</li>
                                            <li>Remove Students from Courses</li>
                                        </ul>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php } else if($_SESSION['user'] === 'Student') { ?>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 space-8">
                    <!-- Manage Enrollments -->
                    <div class="col-span-full md:col-span-6 lg:col-span-3">
                        <a href="student/manage-enrollment/course-enrollment.php" class="group relative block h-80 md:h-72 lg:h-64">
                            <span class="absolute inset-0 border-2 border-dashed border-black"></span>
                            <div class="relative flex h-full transform items-end border-2 border-black bg-white transition-transform group-hover:-translate-x-2 group-hover:-translate-y-2">
                                <div class="p-4 !pt-0 transition-opacity group-hover:absolute group-hover:opacity-0 sm:p-6 lg:p-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 md:w-10 md:h-10">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                                    </svg>
                                    <h2 class="mt-4 md:text-xl font-semibold text-2xl">Manage Enrollment</h2>
                                </div>

                                <div class="absolute opacity-0 transition-opacity group-hover:relative group-hover:opacity-100 p-6 md:p-4 lg:p-8">
                                    <h3 class="mt-4 md:text-xl font-medium text-2xl">With manage enrollment, you can:</h3>
                                    <p class="mt-4 md:text-sm text-base">
                                        <ul>
                                            <li>View Enrolled Courses</li>
                                            <li>Enroll to New Courses</li>
                                        </ul>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </section>
        <?php
            require ('layout/footer.php');
        ?>
    </body>
</html>