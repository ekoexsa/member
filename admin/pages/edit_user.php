<?php
$username = $nama_lengkap = "";
$username_err = $nama_lengkap_err = $password_err = "";
$user_id = 0;

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $user_id = trim($_GET["id"]);

    $sql = "SELECT username, nama_lengkap FROM users WHERE id = ?";
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("i", $param_id);
        $param_id = $user_id;

        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows == 1){
                $row = $result->fetch_assoc();
                $username = $row['username'];
                $nama_lengkap = $row['nama_lengkap'];
            } else {
                echo "User not found.";
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
} else {
    // If no ID, redirect to manage users page
    header("location: index.php?pg=manage_users");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['id'];

    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else {
        $sql = "SELECT id FROM users WHERE username = ? AND id != ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("si", $param_username, $param_id);
            $param_username = trim($_POST["username"]);
            $param_id = $user_id;

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

    $password = trim($_POST["password"]);
    if(!empty($password) && strlen($password) < 6){
        $password_err = "Password must have atleast 6 characters.";
    }

    if(empty($username_err) && empty($nama_lengkap_err) && empty($password_err)){
        if(!empty($password)){
            $sql = "UPDATE users SET username = ?, nama_lengkap = ?, password = ? WHERE id = ?";
        } else {
            $sql = "UPDATE users SET username = ?, nama_lengkap = ? WHERE id = ?";
        }

        if($stmt = $mysqli->prepare($sql)){
            if(!empty($password)){
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt->bind_param("sssi", $username, $nama_lengkap, $hashed_password, $user_id);
            } else {
                $stmt->bind_param("ssi", $username, $nama_lengkap, $user_id);
            }

            if($stmt->execute()){
                header("location: index.php?pg=manage_users");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
}
?>

<h2>Edit Member</h2>
<p>Update member details.</p>
<form action="index.php?pg=edit_user&id=<?php echo $user_id; ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $user_id; ?>"/>
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
        <label>New Password (leave blank to keep current password)</label>
        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
        <span class="invalid-feedback"><?php echo $password_err; ?></span>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Update">
        <a href="index.php?pg=manage_users" class="btn btn-secondary">Cancel</a>
    </div>
</form>
