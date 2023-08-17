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
        <title><?php echo $_SESSION['user']; ?> - Manage Courses | Enroll</title>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </head>
    <body class="h-screen bg-white">
        <!-- Header/Navbar -->
        <section class="h-[10vh]"><?php require ('../../layout/header.php'); ?></section>
        <!-- Main Section -->
        <section class="h-[80vh] w-full space-4 px-6 py-8 md:px-16 lg:px-28">
            <div class="my-12 md:mt-0 md:mb-16 lg:mt-8 flex justify-center md:justify-start">
                <a href="add-course.php" class="px-4 py-2 text-white bg-indigo-500 rounded-md hover:scale-105">Add Course</a>
            </div>
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
                        $courses = $db->table('courses')->selectAll();
                        foreach($courses as $course) {
                            echo "
                            <tr>
                                <td>".$course['course_id']."</td>
                                <td>".$course['course_title']."</td>
                                <td>".mb_strimwidth($course['course_desc'], 0, 80, '...')."</td>
                                <td>
                                    <form method='POST' action='edit-course.php' class='flex'>
                                        <input type='hidden' name='course_id' value='".$course['course_id']."'>
                                        <input type='submit' value='Edit' name='edit_course' id='".$course['course_id']."' class='px-4 py-2 text-sm md:text-md text-white bg-indigo-500 rounded-md hover:scale-105 cursor-pointer' />
                                        <input type='submit' value='Delete' name='delete_course' id='del-".$course['course_id']."' class='del-btn ml-5 px-4 py-2 text-sm md:text-md text-white bg-red-500 rounded-md hover:scale-105 cursor-pointer' />
                                    </form>
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
                let delBtns = document.querySelectorAll('.del-btn');
                delBtns.forEach(btn => {
                    btn.addEventListener('click', deleteModal);
                });

                function deleteModal(event) 
                {
                    event.preventDefault();

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
                            let formData = { 
                                delete_course : true,
                                course_id : (event.target.id).substring(4) 
                            };

                            $.ajax({
                                type: "POST",
                                url: "handler.php",
                                data: formData,
                                dataType: "json",
                                encode: true,
                            }).done(function(data) {
                                if(data) {
                                    sessionStorage.setItem("msg", "course-deleted");
                                    window.location.href='courses.php';
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
                        "emptyTable": "Sorry!! There are no courses available yet. Please check again later. 😐"
                    },
                    responsive: true,
                    "scrollX": true
                });
            });

            if(sessionStorage.getItem("msg") == 'course-added') {
                swal({ title: 'New Course Added!', text: 'Congratulations.. New Course Added Successfully!', icon: 'success', timer: 2000 })
                sessionStorage.setItem("msg", "");
            }
            else if(sessionStorage.getItem("msg") == 'course-updated') {
                swal({ title: 'Course Updated!', text: 'Course updated successfully!', icon: 'success', timer: 2000 })
                sessionStorage.setItem("msg", "");
            }
            else if(sessionStorage.getItem("msg") == 'course-deleted') {
                swal({ title: 'Course Deleted!', text: 'Course deleted successfully!', icon: 'error', timer: 2000 })
                sessionStorage.setItem("msg", "");
            }
        </script>
    </body>
</html>