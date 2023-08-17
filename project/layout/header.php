<header class="text-gray-600 body-font px-8">
    <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
        <a href="/prog1876-final-project/project/dashboard.php" class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
            </svg>
            <span class="ml-3 text-3xl">Enroll</span></span>
        </a>
        <nav class="md:ml-auto flex flex-wrap items-center text-base justify-center font-semibold">
            <?php if(isset($_SESSION['loggedIn'])) { ?>
                <p class="mr-5 text-indigo-500 hover:text-gray-900"><?php echo "Hello, ".$_SESSION['user_fname']; ?></p>
                <a href="/prog1876-final-project/project/dashboard.php" class="mr-5 hover:text-gray-900 hover:scale-105">Dashboard</a>
                <form method="post">
                    <button name="btnSignOut" class="mr-5 hover:text-gray-900 hover:scale-105">Sign Out</button>
                </form>
            <?php } else { ?>
                <a href="auth/signin.php" class="mr-5 px-4 py-2 text-white bg-indigo-500 rounded-md hover:scale-105">Sign In</a>
                <a href="auth/signup.php" class="mr-5 px-4 py-2 text-white bg-indigo-500 rounded-md hover:scale-105">Sign Up</a>
            <?php } ?>
        </nav>
    </div>
</header>

<?php
    if(isset($_POST['btnSignOut'])) {
        session_destroy();
        echo "<script>window.location.href='/prog1876-final-project/project/index.php';</script>";
    }
?>