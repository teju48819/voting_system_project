<?php
session_start();
$conn = new mysqli("localhost", "root", "", "voting_system");

$message = "";
$showOtpForm = false;

// OTP generator
function generateOTP($length = 6) {
    return rand(pow(10, $length - 1), pow(10, $length) - 1);
}

// Registration form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['verify_otp'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $voter_id = $_POST['voter_id'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
 // Validate phone number (10 digits only)
 if (!preg_match('/^\d{10}$/', $phone)) {
    $message = "<div class='message error'>📵 Invalid phone number. Please enter a 10-digit number.</div>";
} else {
    // Check for duplicate email or voter ID
    $check = $conn->prepare("SELECT * FROM users WHERE email = ? OR voter_id = ?");
    $check->bind_param("ss", $email, $voter_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $message = "<div class='message error'>⚠️ Email or Voter ID already registered.</div>";
    } else {
        // Generate OTP
        $otp = generateOTP();
        $_SESSION['otp'] = $otp;
        $_SESSION['reg_data'] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'voter_id' => $voter_id,
            'password' => $password
        ];
        $showOtpForm = true;

        // Simulate OTP send
        $message = "<div class='message success'>📲 OTP sent to phone: <strong>$phone</strong><br> (Simulated OTP: <strong>$otp</strong>)</div>";
    }
}
}
// Verify OTP
if (isset($_POST['verify_otp'])) {
    $enteredOtp = $_POST['otp'];
    if ($_SESSION['otp'] == $enteredOtp) {
        $data = $_SESSION['reg_data'];
        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, voter_id, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $data['name'], $data['email'], $data['phone'], $data['voter_id'], $data['password']);

        if ($stmt->execute()) {
            $message = "<div class='message success'>✅ Registration successful!</div>";
        } else {
            $message = "<div class='message error'>❌ Something went wrong. Please try again.</div>";
        }

        unset($_SESSION['otp']);
        unset($_SESSION['reg_data']);
    } else {
        $message = "<div class='message error'>❌ Invalid OTP. Please try again.</div>";
        $showOtpForm = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register | Online Voting System</title>
    <style>
        body {
            background: #f0f4f8;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 340px;
        }

        form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        form button {
            width: 100%;
            padding: 10px;
            background: #0066cc;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        form button:hover {
            background: #004a99;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 6px;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        h2 {
            text-align: center;
            margin-bottom: 15px;
        }

        .home-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            color: #0066cc;
            text-decoration: none;
        }

        .home-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <?= $message ?>

    <?php if ($showOtpForm): ?>
        <!-- OTP Form -->
        <form method="POST">
            <h2>Verify OTP</h2>
            <input type="text" name="otp" placeholder="Enter OTP" required>
            <button type="submit" name="verify_otp">Verify OTP</button>
        </form>
    <?php else: ?>
        <!-- Registration Form -->
        <form method="POST">
            <h2>Register</h2>
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email ID" required>
            <input type="text" name="phone" placeholder="Phone Number" pattern="\d{10}" 
            title="Please enter a 10-digit phone number" required>
            <input type="text" name="voter_id" placeholder="Voter ID Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Send OTP</button>
            <a class="home-link" href="index.html">🏠 Back to Home</a>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
