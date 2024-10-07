<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .login-container {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            text-align: center;
        }
        .login-container input {
            width: 93%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border-radius: 10px;
            border: none;
            cursor: pointer;
        }
        .login-container button:hover {
            background-color: #218838;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
        }
        .register-link a {
            color: #007bff;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        .login-container .register-btn {
            background-color: #007bff;
            margin-top: 10px;
        }
        .login-container .register-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Selamat datang di <br> Releiver</h2>
    <form action="proses_login.php" method="POST">
        <label for="userID">User ID:</label>
        <input type="text" id="userID" name="userID" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit">LOGIN</button>
    </form>
    
    <div class="register-link">
        <p>Belum punya akun?</p>
        <a href="regist.php"><button class="register-btn">Daftar Sekarang</button></a>
    </div>
</div>

</body>
</html>
