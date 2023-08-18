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
        <title><?php echo $_SESSION['user']; ?> - Manage Students | Enroll</title>
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
            <div class="flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 md:w-10 md:h-10">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
                <span class="ml-3 text-xl md:text-3xl font-semibold">Manage Students</span>
            </div>
            <div class="my-12 md:mt-0 md:mb-16 lg:mt-8 flex justify-center md:justify-start">
                <a href="add-student.php" class="px-4 py-2 text-white bg-indigo-500 rounded-md hover:scale-105">Add Student</a>
            </div>
            <table id="tbl-users" class="w-full display" style="width: 100%;">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  
                        $users = $db->table('users')->join('user_types', 'user_types.user_type_id', 'users.user_type')->where(['users.user_type'], ['ADM'], ['NOT LIKE'], 's');
                        // $users = $db->table('users')->selectAll();
                        // Find enrolled students
                        $enrolled_stu = $db->table('users')->join('enrolled', 'enrolled.stu_email', 'users.user_email')->selectAll();

                        //
                        $enrolled_students = [];
                        foreach($enrolled_stu as $stu) {
                            array_push($enrolled_students, $stu['user_email']);
                        }

                        foreach($users as $user) {
                            echo "<tr>
                                    <td>".$user['user_fname']."</td>
                                    <td>".$user['user_lname']."</td>
                                    <td>".$user['user_email']."</td>
                                    <td class='flex'>
                                        <form method='POST' action='edit-student.php'>
                                            <input type='hidden' name='user_email' value='".$user['user_email']."'>
                                            <input type='submit' value='Edit' name='edit_user' id='".$user['user_email']."' class='px-4 py-2 text-sm md:text-md text-white bg-indigo-500 rounded-md hover:scale-105 cursor-pointer' />
                                        </form>
                                    ";
                                    if (in_array($user['user_email'], $enrolled_students)) {
                                        echo "<input type='submit' disabled title='Enrolled Student record cannot be deleted.' value='Delete' name='delete_user' id='del-".$user['user_email']."' class='del-btn ml-5 px-4 py-2 text-sm md:text-md text-white bg-red-300 rounded-md hover:scale-105 cursor-pointer'/>";
                                    }
                                    else {
                                        echo "<input type='submit' value='Delete' name='delete_user' id='del-".$user['user_email']."' class='del-btn ml-5 px-4 py-2 text-sm md:text-md text-white bg-red-500 rounded-md hover:scale-105 cursor-pointer'/>";
                                    }
                            echo    "</td>";
                            echo "</tr>";
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
                                delete_user : true,
                                user_email : (event.target.id).substring(4) 
                            };

                            $.ajax({
                                type: "POST",
                                url: "handler.php",
                                data: formData,
                                dataType: "json",
                                encode: true,
                            }).done(function(data) {
                                if(data) {
                                    sessionStorage.setItem("msg", "user-deleted");
                                    window.location.href='students.php';
                                }
                            });
                        }
                    });
                }

                let table = $('#tbl-users').DataTable({
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
                        "emptyTable": "No students have joined yet. Please check again later. 😐"
                    },
                    responsive: true,
                    "scrollX": true
                });
            });

            if(sessionStorage.getItem("msg") == 'user-added') {
                swal({ title: 'New Student Added!', text: 'Congratulations.. New Student Added Successfully!', icon: 'success', timer: 2000 })
                sessionStorage.setItem("msg", "");
            }
            else if(sessionStorage.getItem("msg") == 'user-updated') {
                swal({ title: 'Student Updated!', text: 'Student updated successfully!', icon: 'success', timer: 2000 })
                sessionStorage.setItem("msg", "");
            }
            else if(sessionStorage.getItem("msg") == 'user-deleted') {
                swal({ title: 'Student Deleted!', text: 'Student deleted successfully!', icon: 'error', timer: 2000 })
                sessionStorage.setItem("msg", "");
            }
        </script>
    </body>
</html>