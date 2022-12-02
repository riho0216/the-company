<?php
    include "../classes/User.php";

    # Instantiate an object
    $user = new User;

    # call the method
    $user->store($_POST);

?>