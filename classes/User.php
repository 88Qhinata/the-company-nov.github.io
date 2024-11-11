<?php
require 'Database.php';

class User extends Database {

    public function store($request) #store method
    {
        //$request will catch the value from $_POST from actions/resister.php
        $first_name  = $request['first_name'];
        $last_name   = $request['last_name'];
        $username    = $request['username'];
        $password    = $request['password'];

        $password    = password_hash($password, PASSWORD_DEFAULT); #password_hash creats $2y6...ramdom characters for user security

        $sql         = "INSERT INTO users (`first_name`, `last_name`, `username`, `password`) VALUES ('$first_name', '$last_name', '$username', '$password')";

        if ($this->conn->query($sql)) {
            header('location: ../views'); //go to index.php =redirect index.php
            exit;
        }else{
            die('Error creating the user: ' . $this>conn->error);
        }
    }

    public function login($request)
    {
        $username = $request['username'];
        $password = $request['password'];

        $sql = "SELECT * FROM users WHERE username = '$username'";

        $result = $this->conn->query($sql);

        #check the username
        if($result->num_rows == 1){
            #check if the password is correct

            $user = $result->fetch_assoc();

            if(password_verify($password, $user['password'])){
                #create session variables for future use
                // session variables - variables that can be use throgh multiple
                session_start(); #これのためにセッションバリアブル使う↓
                $_SESSION['id']         = $user['id'];
                $_SESSION['username']   = $user['username'];
                $_SESSION['full_name']   = $user['first_name']. " " .$user['last_name'];

                header('location: ../views/dashboard.php');
                exit;
            } else {
                die('Password is incorrect');
            }

            }else{
                die('Username is incorrect');

            }
        }

        public function logout()
        {
            session_start(); // use the session variables
            session_unset(); // to remove the value of your session variables
            session_destroy(); // terminate終了する the session

            header('location: ../views');
            exit;
        }

        public function getAllUsers()
        {
            $sql = "SELECT id, first_name, last_name, username, photo FROM users";
            
            if ($result = $this->conn->query($sql)) {
                return $result;
            }else{
                die('Error retriveing all users: '. $this->conn->error);
            }
        }

        public function getUser($id){
            # $id is the ID of the user that we want to edit
            $sql = "SELECT * FROM users WHERE id = $id";

            if ($result = $this->conn->query($sql)) {
                return $result->fetch_assoc();
            }else{
                die('Error retrieving the user: '. $this->conn->error);
            }
        }

        /* UPDATE method*/
        public function update($request, $files)
        {
            session_start();
            $id           = $_SESSION['id'];
            $first_name   = $request['first_name'];
            $last_name    = $request['last_name'];
            $username     = $request['username'];
            $photo        = $files['photo']['name'];
            $tmp_photo    = $files['photo']['tmp_name'];
            
            $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', username = '$username' WHERE id = $id";

            if ($this->conn->query($sql)) {
                $_SESSION['username'] = $username;
                $_SESSION['full_name'] = "$first_name $last_name";

                #if there is an uploaded photo, save it to the db and save the file to the images folder
                if ($photo) {
                    $sql = "UPDATE users SET photo = '$photo' WHERE id = $id";
                    $destination = "../assets/images/$photo";

                    // save the image to db
                    if ($this->conn->query($sql)) {
                        // save the file to images folder
                        if (move_uploaded_file($tmp_photo, $destination)) {
                            header('location: ../views/dashboard.php');
                            exit;
                        } else {
                            die('Error moving the photo.');
                        }
                    } else {
                        die('Error updating the photo: ' . $this->conn->error);
                    }
                }

                header('location: ../views/dashboard.php');
                exit;
            }else{
                die('Error updating the user: ' . $this->conn->error);
            }

        }

        /* delete method*/
        public function delete()
        {
            session_start();
            // $id is the id of the logged in user
            $id = $_SESSION['id'];

            $sql = "DELETE FROM users WHERE id = $id";

            if ($this->conn->query($sql)) {
                $this->logout();
            } else {
                die('Error deleting your account: ' . $this->conn->error);
            }
            
        }



        
    }


?>