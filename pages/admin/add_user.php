<?php
// Logic for adding a new user.
// Boilerplate is handled by the main index.php.

$username = $password = $confirm_password = $nama_lengkap = "";
$username_err = $password_err = $confirm_password_err = $nama_lengkap_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        $sql = "SELECT id FROM users WHERE username = ?";

        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("s", $param_username);
            $param_username = trim($_POST["username"]);

            if($stmt->execute()){
                $stmt->store_result();

                if($stmt->num_rows == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            $stmt->close();
        }
    }

    if(empty(trim($_POST["nama_lengkap"]))){
        $nama_lengkap_err = "Please enter your full name.";
    } else{
        $nama_lengkap = trim($_POST["nama_lengkap"]);
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    if(empty($username_err) && empty($password_err) && empty($nama_lengkap_err)){

        $sql = "INSERT INTO users (username, nama_lengkap, password, role) VALUES (?, ?, ?, 'member')";

        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("sss", $param_username, $param_nama_lengkap, $param_password);

            $param_username = $username;
            $param_nama_lengkap = $nama_lengkap;
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            if($stmt->execute()){
                // Redirect to the manage users page using the main router
                header("location: index.php?page=admin_manage_users");
                exit;
            } else{
                echo "Something went wrong. Please try again later.";
            }

            $stmt->close();
        }
    }
}
?>

<h2>Add New Member</h2>
<p>Fill out the form to add a new member.</p>
<form action="index.php?page=admin_add_user" method="post">
    <div class="form-group mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
        <span class="invalid-feedback"><?php echo $username_err; ?></span>
    </div>
    <div class="form-group mb-3">
        <label>Nama Lengkap</label>
        <input type="text" name="nama_lengkap" class="form-control <?php echo (!empty($nama_lengkap_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nama_lengkap; ?>">
        <span class="invalid-feedback"><?php echo $nama_lengkap_err; ?></span>
    </div>
    <div class="form-group mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
        <span class="invalid-feedback"><?php echo $password_err; ?></span>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Submit">
        <a href="index.php?page=admin_manage_users" class="btn btn-secondary">Cancel</a>
    </div>
</form>