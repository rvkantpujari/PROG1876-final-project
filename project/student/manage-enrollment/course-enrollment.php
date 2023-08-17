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
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $_SESSION['user']; ?> - Manage Enrollment | Enroll</title>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </head>
    <body class="h-screen bg-white">
        <!-- Header/Navbar -->
        <section class="h-[10vh]"><?php require ('../../layout/header.php'); ?></section>
        <!-- Main Section -->
        <section class="w-full space-4 px-6 py-8 md:px-16 lg:px-20">
            <h1 class="text-3xl text-center font-semibold mt-4 mb-12">Available Courses 📖</h1>
            <table id="tbl-courses" class="w-full display" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Course ID</th>
                        <th>Course Title</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $user_email = $_SESSION['user_email'];
                        $courses = $db->execute_query("SELECT * FROM courses WHERE courses.course_id NOT IN (SELECT enrolled.course_id FROM enrolled WHERE stu_email LIKE '$user_email')");
                        foreach($courses as $course) {
                            echo "
                            <tr>
                                <td>".$course['course_id']."</td>
                                <td>".$course['course_title']."</td>
                                <td>".mb_strimwidth($course['course_desc'], 0, 80, '...')."</td>
                                <td>
                                    <button name='edit_course' id='".$course['course_id']."' class='enroll-btn px-4 py-2 text-sm md:text-md text-white bg-indigo-500 rounded-md hover:scale-105 cursor-pointer'>
                                        Enroll
                                    </button>
                                </td>
                            </tr>";
                        } 
                    ?>
                </tbody>
            </table>
        </section>
        <section class="mx-16 my-12 lg:px-4">
            <h1 class="text-3xl text-center font-semibold mt-4 mb-12">Enrolled Courses 📚</h1>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 space-8">
                <?php 
                    $enrolled_courses = $db->table('courses')->join('enrolled', 'enrolled.course_id', 'courses.course_id')->where(['stu_email'], [$_SESSION['user_email']], ['LIKE'], 's');
                    
                    if(count($enrolled_courses) > 0) {
                        foreach($enrolled_courses as $course) {
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
                                        <h3 class="mt-2 md:text-md font-medium"><?php echo mb_strimwidth($course['course_desc'], 0, 100, '...') ?></h3>
                                        <h3 class="mt-4 md:text-lg font-medium text-xl text-indigo-500"><span class="font-semibold text-gray-900">Enrolled on:</span> <?php echo $course['enrolled_on']; ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php 
                        }
                    }
                    else {
                        echo "<p class='col-span-full text-xl text-center'>You haven't enrolled into any courses yet.</p>";
                    }
                ?>
            </div>
        </section>
        <!-- Footer -->
        <section class="h-[10vh]"><?php require ('../../layout/footer.php'); ?></section>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.0/js/buttons.flash.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.0.3/js/buttons.print.min.js"></script>
        <script>
            $(document).ready(()=> {
                let enrollBtn = document.querySelectorAll('.enroll-btn');
                enrollBtn.forEach(btn => {
                    btn.addEventListener('click', deleteModal);
                });

                function deleteModal(event) 
                {
                    event.preventDefault();

                    let formData = { 
                        enroll_course : true,
                        course_id : event.target.id
                    };

                    console.log(formData);

                    $.ajax({
                        type: "POST",
                        url: "handler.php",
                        data: formData,
                        dataType: "json",
                        encode: true,
                    }).done(function(data) {
                        console.log(data);
                        if(data) {
                            sessionStorage.setItem("msg", "course-enrolled");
                            window.location.href='course-enrollment.php';
                        }
                    });
                }

                let table = $('#tbl-courses').DataTable({
                    dom: '<"my-4 py-0"lf><"mt-4 py-4"rt><"mb-4 py-4"Bp>',
                    buttons: [
                        'colvis',
                        {
                            extend: 'copyHtml5',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'pdf',
                            exportOptions: {
                                columns: ':visible'
                            }
                        }
                    ],
                    "lengthMenu": [ 5, 10, 15 ],
                    "language": {
                        "emptyTable": "No new courses available for enrollment. Please check again later. 😐"
                    },
                    responsive: true,
                    "scrollX": true
                });
            });

            if(sessionStorage.getItem("msg") == 'course-enrolled') {
                swal({ title: 'New Course Enrolled!', text: 'You\'ve been successfully enrolled to the course.', icon: 'success', timer: 2000 })
                sessionStorage.setItem("msg", "");
            }
        </script>
    </body>
</html>