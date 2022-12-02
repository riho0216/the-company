<?php
    require_once "Database.php";

    class User extends Database{

        public function store($request){
            $first_name = $request['first_name'];
            $last_name = $request['last_name'];
            $username = $request['username'];
            $password = $request['password'];

            # Apply password_hash
            $password = password_hash($password, PASSWORD_DEFAULT);

            # Query String
            $sql = "INSERT INTO users(`first_name`, `last_name`, `username`, `password`) VALUES ('$first_name', '$last_name', '$username', '$password')";

            # Execute the query
            if ($this->conn->query($sql)){
                header('location:../views');
                exit();
            }else{
                die("Error in creating user." . $this->conn->error);
            }
        }

        public function login($request){
            $username = $_POST['username'];
            $password = $_POST['password'];

            #Query String
            $sql = "SELECT * FROM users WHERE username='$username'";

            #Execute the query
            if ($result = $this->conn->query($sql)){
                # check if the username is available
                if ($result->num_rows == 1){
                    # check if the password is correct
                    $user = $result->fetch_assoc();
                    //$user[id =>1, first_name => 'john', last_name => 'smith', username... password...]

                    if (password_verify($password, $user['password'])){
                        # Create session variables for future use
                        session_start();
                        $_SESSION['id'] = $user['id']; //3
                        $_SESSION['username'] = $user['username']; //john
                        $_SESSION['full_name'] = $user['first_name'] . " " . $user['last_name'];

                        header('location:../views/dashboard.php');
                        exit;
                    }else{
                        die("Password is incorrect.");
                    }
                }else{
                    die("Username not found.");
                }
            }
        }

        public function logout(){
            session_start();
            session_unset();
            session_destroy();

            header('location:../views');
            exit;
        }

        public function getAllUsers(){
            $sql = "SELECT id, first_name, last_name, username, photo FROM users";

            # Excute the query
            if ($result = $this->conn->query($sql)){
                return $result;
            }else{
                die("Error retrieving the users " . $this->conn->error);
            }
        }

        # Get the details of the current login user
        public function getUser(){
            $id = $_SESSION['id'];

            # Query String
            $sql = "SELECT first_name, last_name, username, photo FROM users WHERE id='$id'";
            if ($result = $this->conn->query($sql)){
                return $result->fetch_assoc();
            }else{
                die("Error retrieving the user. " . $this->conn->error);
            }
        }

        # Implement the actual update
        public function update($request, $files){
            session_start();

            $id = $_SESSION['id'];
            $first_name = $request['first_name'];
            $last_name = $request['last_name'];
            $username = $request['username'];
            $photo = $files['photo']['name'];
            $tmp_photo = $files['photo']['tmp_name']; // The path of the file inside the temporary folder -- destination folder

            # Query String for update
            $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', username = '$username' WHERE id = '$id'";

            # Execute the query
            if ($this->conn->query($sql)){ // if the execution of this line is okay
                $_SESSION['username'] = $username;
                $_SESSION['full_name'] = "$first_name $last_name";

                /*Check if there an upload file/photo, if there is a file/photo,
                 save it to the database and move/save the file to the "images" folder */

                if ($photo) { // if the execution is true　↓
                    $sql = "UPDATE users SET photo = '$photo' WHERE id = '$id'";
                    $destination = "../assets/images/$photo"; // just the destination, not move yet

                    # Save the image to the db
                    if ($this->conn->query($sql)){
                        # Move the images to the folder
                        if (move_uploaded_file($tmp_photo, $destination)){
                            header('location: ../views/dashboard.php');
                            exit;
                        }else{
                            die("Error moving the file.");
                        }
                    }else{
                        die("Error uploading the photo." . $this->conn->error);
                    }
                }
                header('location: ../views/dashboard.php');
                exit;

            }else{
                die("Error updating the user." . $this->conn->error);
            }
        }

        # Delete user method
        public function delete(){
            session_start();
            $id = $_SESSION['id'];

            # Query String
            $sql = "DELETE FROM users WHERE id = '$id'";

            if($this->conn->query($sql)){
                $this->logout();
            }else{
                die("Error in deleting the account.");
            }
        }

    }

?>