<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airbnb Clone - Home</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #f7f7f7; }
        .header { background: linear-gradient(135deg, #ff385c, #e61e4d); color: white; padding: 20px; text-align: center; }
        .header h1 { font-size: 2.5em; margin-bottom: 10px; }
        .search-bar { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); max-width: 800px; margin: 20px auto; display: flex; justify-content: space-between; }
        .search-bar input, .search-bar button { padding: 10px; border: none; border-radius: 5px; }
        .search-bar input { flex: 1; margin-right: 10px; font-size: 1em; }
        .search-bar button { background: #ff385c; color: white; cursor: pointer; transition: background 0.3s; }
        .search-bar button:hover { background: #e61e4d; }
        .featured { max-width: 1200px; margin: 20px auto; }
        .featured h2 { text-align: center; margin-bottom: 20px; }
        .property-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .property-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .property-card:hover { transform: scale(1.05); }
        .property-card img { width: 100%; height: 200px; object-fit: cover; }
        .property-card h3 { padding: 10px; font-size: 1.2em; }
        .property-card p { padding: 0 10px 10px; color: #555; }
        @media (max-width: 768px) { .search-bar { flex-direction: column; } .search-bar input, .search-bar button { margin: 5px 0; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to Airbnb Clone</h1>
    </div>
    <div class="search-bar">
        <input type="text" id="destination" placeholder="Destination" required>
        <input type="date" id="checkin" required>
        <input type="date" id="checkout" required>
        <button onclick="searchProperties()">Search</button>
    </div>
    <div class="featured">
        <h2>Featured Stays</h2>
        <div class="property-grid">
            <?php
            include 'db.php';
            $sql = "SELECT * FROM properties LIMIT 4";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<div class='property-card'>";
                echo "<img src='https://via.placeholder.com/300x200' alt='Property'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p>$" . htmlspecialchars($row['price']) . "/night</p>";
                echo "</div>";
            }
            $conn->close();
            ?>
        </div>
    </div>
    <script>
        function searchProperties() {
            const destination = encodeURIComponent(document.getElementById('destination').value);
            const checkin = encodeURIComponent(document.getElementById('checkin').value);
            const checkout = encodeURIComponent(document.getElementById('checkout').value);
            window.location.href = `properties.php?destination=${destination}&checkin=${checkin}&checkout=${checkout}`;
        }
    </script>
</body>
</html>
