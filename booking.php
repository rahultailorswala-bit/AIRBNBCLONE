<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - Airbnb Clone</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #f7f7f7; }
        .header { background: linear-gradient(135deg, #ff385c, #e61e4d); color: white; padding: 20px; text-align: center; }
        .booking-container { max-width: 600px; margin: 20px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .booking-container h2 { margin-bottom: 20px; }
        .booking-container label { display: block; margin: 10px 0 5px; }
        .booking-container input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .booking-container button { background: #ff385c; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-top: 20px; transition: background 0.3s; }
        .booking-container button:hover { background: #e61e4d; }
        .confirmation { text-align: center; color: green; margin-top: 20px; }
        @media (max-width: 768px) { .booking-container { padding: 15px; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>Book Your Stay</h1>
    </div>
    <div class="booking-container">
        <?php
        include 'db.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $property_id = (int)$_POST['property_id'];
            $checkin = $conn->real_escape_string(urldecode($_POST['checkin']));
            $checkout = $conn->real_escape_string(urldecode($_POST['checkout']));
            $guest_name = $conn->real_escape_string($_POST['guest_name']);
            $guest_email = $conn->real_escape_string($_POST['guest_email']);
            $sql = "INSERT INTO bookings (property_id, checkin_date, checkout_date, guest_name, guest_email) VALUES ($property_id, '$checkin', '$checkout', '$guest_name', '$guest_email')";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='confirmation'>Booking confirmed! You'll receive a confirmation email soon.</p>";
            } else {
                echo "<p>Error: " . $conn->error . "</p>";
            }
            $conn->close();
        } else {
            $property_id = (int)$_GET['property_id'];
            $checkin = isset($_GET['checkin']) ? urldecode(htmlspecialchars($_GET['checkin'])) : '';
            $checkout = isset($_GET['checkout']) ? urldecode(htmlspecialchars($_GET['checkout'])) : '';
            $sql = "SELECT title, price FROM properties WHERE id = $property_id";
            $result = $conn->query($sql);
            $property = $result->fetch_assoc();
        ?>
        <h2>Booking for <?php echo htmlspecialchars($property['title']); ?></h2>
        <form method="POST">
            <input type="hidden" name="property_id" value="<?php echo $property_id; ?>">
            <label>Check-in Date</label>
            <input type="date" name="checkin" value="<?php echo $checkin; ?>" required>
            <label>Check-out Date</label>
            <input type="date" name="checkout" value="<?php echo $checkout; ?>" required>
            <label>Your Name</label>
            <input type="text" name="guest_name" required>
            <label>Your Email</label>
            <input type="email" name="guest_email" required>
            <button type="submit">Confirm Booking</button>
        </form>
        <?php
            $conn->close();
        }
        ?>
    </div>
</body>
</html>
