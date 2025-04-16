<?php
$host = "localhost";
$username = "root";
$password = "Ali@20";
$database = "order_management";

// DB connect
$conn = new mysqli("localhost", "root", "Ali@20", "order_management");
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Form data
$name = $_POST['name'];
$email = $_POST['email'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// 1. Check if user exists
$sql = "SELECT id FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_id = $user['id'];
} else {
    // Add new user
    $conn->query("INSERT INTO users (name, email, password) VALUES ('$name', '$email', 'default')");
    $user_id = $conn->insert_id;
}

// 2. Insert into orders
$conn->query("INSERT INTO orders (user_id) VALUES ($user_id)");
$order_id = $conn->insert_id;

// 3. Insert into order_items
$conn->query("INSERT INTO order_items (order_id, product_id, quantity) 
              VALUES ($order_id, $product_id, $quantity)");



$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #d4fc79, #96e6a1);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }
        .success-box {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: popUp 0.5s ease-in-out;
            z-index: 10;
            position: relative;
        }
        .success-box h2 {
            color: #28a745;
            font-size: 28px;
        }
        .success-box p {
            color: #333;
            font-size: 18px;
        }
        .check-icon {
            font-size: 50px;
            color: #28a745;
            margin-bottom: 15px;
        }
        .btn-home {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .btn-home:hover {
            background-color: #218838;
        }
        @keyframes popUp {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        canvas {
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <canvas id="confetti"></canvas>
    <div class="success-box">
        <div class="check-icon">✅</div>
        <h2>Order Placed Successfully!</h2>
        <p>Thank you, <b><?php echo htmlspecialchars($name); ?></b>. We've received your order.</p>
        <a href="index.html" class="btn-home">Back to Home</a>
    </div>

    <!-- 🎉 Confetti Script -->
    <script>
        const canvas = document.getElementById('confetti');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        let confetti = [];
        const confettiCount = 100;
        for (let i = 0; i < confettiCount; i++) {
            confetti.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height - canvas.height,
                r: Math.random() * 6 + 4,
                d: Math.random() * confettiCount,
                color: `hsl(${Math.random() * 360}, 100%, 50%)`,
                tilt: Math.random() * 10 - 10
            });
        }

        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            confetti.forEach(c => {
                ctx.beginPath();
                ctx.lineWidth = c.r;
                ctx.strokeStyle = c.color;
                ctx.moveTo(c.x + c.tilt + c.r / 2, c.y);
                ctx.lineTo(c.x + c.tilt, c.y + c.r);
                ctx.stroke();
            });
            update();
        }

        function update() {
            confetti.forEach(c => {
                c.y += Math.cos(c.d) + 2;
                c.x += Math.sin(c.d);
                if (c.y > canvas.height) {
                    c.y = 0;
                    c.x = Math.random() * canvas.width;
                }
            });
        }

    </script>
</body>
</html>

