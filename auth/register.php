<?php
require_once "../config/database.php";
require_once "../includes/functions.php";

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

    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($nama_lengkap_err)){

        $sql = "INSERT INTO users (username, nama_lengkap, password) VALUES (?, ?, ?)";

        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("sss", $param_username, $param_nama_lengkap, $param_password);

            $param_username = $username;
            $param_nama_lengkap = $nama_lengkap;
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            if($stmt->execute()){
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            $stmt->close();
        }
    }

    $mysqli->close();
}
?>

<?php include '../includes/header.php'; ?>

<h2>Register</h2>
<p>Please fill this form to create an account.</p>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
    <div class="form-group mb-3">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Submit">
        <input type="reset" class="btn btn-secondary" value="Reset">
    </div>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</form>

<?php include '../includes/footer.php'; ?>
