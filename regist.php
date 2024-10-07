<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .register-container {
            width: 350px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: left; 
        }
        .register-container h2 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .register-container label {
            display: inline-block;
            width: 120px;
            font-size: 14px;
            text-align: left;
        }
        .register-container input, select {
            width: 60%;
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: left; 
        }
        .register-container input[type="radio"] {
            width: auto;
        }
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .gender-options {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 60%;
            text-align: left; 
        }
        .gender-options label {
            margin-left: 10px;
        }
        .register-container button {
            width: 40%;
            padding: 10px;
            margin: 10px 5%;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-align: center; 
        }
        .register-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Form Registrasi</h2>
    <form action="proses_regist.php" method="POST">
        
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Username" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>

        <div class="form-group">
            <label for="retype_password">Retype Password:</label>
            <input type="password" id="retype_password" name="retype_password" placeholder="Retype Password" required>
        </div>

        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" placeholder="E-mail" required>
        </div>

        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required>
        </div>

        <!-- Gender -->
        <div class="form-group">
            <label>Gender:</label>
            <div class="gender-options">
                <input type="radio" id="male" name="gender" value="Male" required> 
                <label for="male">Male</label>
                <input type="radio" id="female" name="gender" value="Female" required style="margin-left: 20px;">
                <label for="female">Female</label>
            </div>
        </div>

        <!-- Address Dropdown -->
        <div class="form-group">
            <label for="address">Address:</label>
            <select id="address" name="address" required>
                <option value="">--Select Address--</option>
                <option value="Jakarta">Jakarta</option>
                <option value="Bandung">Bandung</option>
                <option value="Surabaya">Surabaya</option>
                <option value="Medan">Medan</option>
                <option value="Denpasar">Denpasar</option>
            </select>
        </div>

        <div class="form-group">
            <label for="contact">Contact No:</label>
            <input type="text" id="contact" name="contact" placeholder="Contact No" required>
        </div>

        <div class="form-group">
            <label for="paypal">Pay-pal ID:</label>
            <input type="text" id="paypal" name="paypal" placeholder="Pay-pal ID" required>
        </div>

        <div class="form-group" style="text-align: center;">
            <button type="submit">Submit</button>
            <button type="reset">Clear</button>
        </div>
    </form>
</div>

</body>
</html>
