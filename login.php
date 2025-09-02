<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
    $_SESSION['username'] = $username;
    header("Location: admin.php?login=success");
    exit;
    } else {
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { box-sizing: border-box; }

        body {
    font-family: 'Poppins', sans-serif;
    margin: 0; padding: 0;
    height: 100vh;
    /* Gradien animasi */
    background: linear-gradient(135deg, #ffb347, #ffcc33, #ff7f50, #e65c00);
    background-size: 400% 400%; /* Agar animasi lebih halus */
    animation: gradientShift 15s ease infinite;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Keyframes animasi gradien */
@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

        .login-box {
            background: rgba(255, 255, 255, 0.85);
            padding: 35px 30px;
            border-radius: 14px;
            backdrop-filter: blur(8px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
            width: 320px;
            animation: fadeIn 1s ease;
            text-align: center;
        }

        .login-box img {
    width: 60px;
    margin-bottom: 10px;
    border-radius: 50%; /* Bikin bulat */
    background: none;   /* Hilangkan latar tambahan */
}


        .login-box h2 {
            margin-bottom: 22px;
            font-weight: 600;
            color: #d35400;
            font-size: 20px;
        }

        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 11px 13px;
            margin: 8px 0 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.3s;
        }

        .login-box input:focus {
            border-color: #ff7f50;
            box-shadow: 0 0 6px rgba(255, 127, 80, 0.4);
            outline: none;
        }

        .show-password {
            display: flex;
            align-items: center;
            font-size: 13px;
            margin: -6px 0 14px;
            color: #555;
        }

        .show-password input {
            margin-right: 6px;
        }

        .login-box button {
            width: 100%;
            padding: 11px;
            background: linear-gradient(135deg, #ff7f50, #ff6f00);
            border: none;
            color: white;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
            cursor: pointer;
        }

        .login-box button:hover {
            background: linear-gradient(135deg, #ff8c42, #e65100);
            box-shadow: 0 4px 10px rgba(255, 140, 66, 0.4);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* SweetAlert kecil */
        .small-popup { padding: 1.2em !important; font-size: 14px; }
        .small-title { font-size: 16px !important; }
        .small-text { font-size: 13px !important; }
        .small-button { font-size: 13px !important; padding: 6px 12px !important; }
    </style>
</head>
<body>
    <div class="login-box">
        <img src="sar.jpg" alt="Logo"> 
        <h2>Login Admin</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Masukkan Username" required>
            <input type="password" id="password" name="password" placeholder="Masukkan Password" required>

            <div class="show-password">
                <input type="checkbox" id="togglePassword" onclick="togglePasswordVisibility()">
                <label for="togglePassword">Tampilkan Password</label>
            </div>

            <button type="submit">Masuk</button>
        </form>
    </div>

    <?php if (isset($error) && $error): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal Login',
            text: 'Username atau password salah!',
            confirmButtonColor: '#ff7f50',
            width: '300px',
            customClass: {
                popup: 'small-popup',
                title: 'small-title',
                confirmButton: 'small-button',
                htmlContainer: 'small-text'
            }
        });
    </script>
    <?php endif; ?>

    <script>
        function togglePasswordVisibility() {
            var input = document.getElementById("password");
            input.type = (input.type === "password") ? "text" : "password";
        }
    </script>
</body>
</html>
