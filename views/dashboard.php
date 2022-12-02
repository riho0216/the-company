<?php 
    session_start();
    
    require_once '../classes/User.php';

    $user = new User;
    $all_users = $user->getAllUsers();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
     <!-- Bootstrap Link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- Fontawesome Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Login</title>

</head>
<body>

    <nav class="navbar navbar-expand navbar-dark bg-dark" style="margin-bottom: 50px">
        <div class="container">
            <a href="dashboard.php" class="navbar-brand">
                <h1 class="h3">The Company</h1>
            </a>
            <div class="navbar-nav">
                <span class="navbar-text"><?= $_SESSION['full_name'] ?></span>
                <form action="..//actions/logout.php" method="post" class="d-flex ms-2">
                    <button type="submit" class="text-danger bg-transparent border-0">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Display all the user here-->

    <main class="row justify-content-center gx-0">
        <div class="col-6">
            <h2 class="text-center">User Lists</h2>

            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>ID</th>
                        <th>FIRST NAME</th>
                        <th>LAST NAME</th>
                        <th>USERNAME</th>
                        <th>Action Buttons</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        while ($user = $all_users->fetch_assoc()){
                    ?>

                            <tr>
                                <td>
                                    <?php
                                    if ($user['photo']){
                                    ?>
                                        <img src="../assets/img/<?= $user['photo'] ?>" alt="<?= $user['photo'] ?>" class="d-block mx-auto" id="dashboard-icon">

                                    <?php
                                        }else{
                                    ?>
                                        <i class="fa-solid fa-user text-secondary d-block text-center dashboard-icon"></i>
                                    <?php
                                        }
                                    ?>
                                </td>

                                <td><?= $user['id'] ?></td>
                                <td><?= $user['first_name'] ?></td>
                                <td><?= $user['last_name'] ?></td>
                                <td><?= $user['username'] ?></td>
                                <td>
                                    <?php
                                        if($_SESSION['id'] == $user['id']){
                                    ?>

                                         <a href="../views/edit-user.php" class="btn btn-outline-warning btn-lg" title="Edit">
                                         <i class="fa-solid fa-pen-to-square"></i>
                                         </a>

                                         <a href="../views/delete-user.php" class="btn btn-outline-danger btn-lg" title="Delete">
                                         <i class="fa-solid fa-trash-can"></i>
                                         </a>

                                    <?php
                                        }
                                    ?>
                                </td>

                            </tr>
                        
                    <?php
                    }
                    ?>
                </tbody>

            </table>
        </div>

    </main>


    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>