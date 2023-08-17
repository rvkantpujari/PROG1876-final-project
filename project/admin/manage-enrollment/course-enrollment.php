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
        <section class="lg:h-[80vh] w-full space-4 px-6 py-8 md:px-16 lg:px-20">
            <h1 class="text-3xl text-center font-semibold mt-4 mb-12">Student Enrollments 🎓</h1>
            <table id="tbl-courses" class="w-full display" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Course ID</th>
                        <th>Course Title</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $courses = $db->table('enrolled')->join('courses', 'courses.course_id', 'enrolled.course_id')
                                        ->join('users', 'users.user_email', 'enrolled.stu_email')->selectAll();
                        
                        foreach($courses as $course) {
                            echo "
                            <tr>
                                <td>".$course['user_fname'].' '.$course['user_lname']."</td>
                                <td>".$course['course_id']."</td>
                                <td>".$course['course_title']."</td>
                                <td>".mb_strimwidth($course['course_desc'], 0, 80, '...')."</td>
                                <td>
                                    <button id='".$course['course_id']."' name=".$course['stu_email']." class='del-btn ml-5 px-4 py-2 text-sm md:text-md text-white bg-red-500 rounded-md hover:scale-105 cursor-pointer'>
                                        Remove
                                    </button>
                                </td>
                            </tr>";
                        } 
                    ?>
                </tbody>
            </table>
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
                let enrollBtn = document.querySelectorAll('.del-btn');
                enrollBtn.forEach(btn => {
                    btn.addEventListener('click', btnClicked);
                });

                function btnClicked(event) 
                {
                    event.preventDefault();
                    
                    let formData = { 
                        remove_enrollment : true,
                        stu_email : event.target.name,
                        course_id : event.target.id
                    };

                    swal({
                        title: 'Are you sure? 😥',
                        text: "You won't be able to revert this action!",
                        icon: 'warning',
                        buttons: {
                            cancel: true,
                            confirm: {
                                text: "Yes, delete it!",
                                value: true,
                                className: "bg-red-500 hover:bg-red-700",
                            },
                        }
                    })
                    .then((willDelete) => {
                        if (willDelete) 
                        {
                            $.ajax({
                                type: "POST",
                                url: "handler.php",
                                data: formData,
                                dataType: "json",
                                encode: true,
                            }).done(function(data) {
                                console.log(data);
                                if(data) {
                                    sessionStorage.setItem("msg", "course-enrollment-removed");
                                    window.location.href='course-enrollment.php';
                                }
                            });
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
                        "emptyTable": "No student enrollments are there to display. Please check again later. 😐"
                    },
                    responsive: true,
                    "scrollX": true
                });
            });

            if(sessionStorage.getItem("msg") == 'course-enrollment-removed') {
                swal({ title: 'Enrollment Removed!', text: 'Student enrollment has been removed.', icon: 'error', timer: 2000 })
                sessionStorage.setItem("msg", "");
            }
        </script>
    </body>
</html>