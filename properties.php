<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properties - Airbnb Clone</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #f7f7f7; }
        .header { background: linear-gradient(135deg, #ff385c, #e61e4d); color: white; padding: 20px; text-align: center; }
        .filters { max-width: 1200px; margin: 20px auto; display: flex; gap: 10px; }
        .filters select, .filters input { padding: 10px; border-radius: 5px; border: 1px solid #ddd; }
        .property-list { max-width: 1200px; margin: 20px auto; }
        .property-item { display: flex; background: white; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .property-item img { width: 300px; height: 200px; object-fit: cover; border-radius: 10px 0 0 10px; }
        .property-details { padding: 20px; flex: 1; }
        .property-details h3 { font-size: 1.5em; margin-bottom: 10px; }
        .property-details p { color: #555; margin-bottom: 5px; }
        .book-btn { background: #ff385c; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
        .book-btn:hover { background: #e61e4d; }
        @media (max-width: 768px) { .property-item { flex-direction: column; } .property-item img { width: 100%; border-radius: 10px 10px 0 0; } .filters { flex-direction: column; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>Available Properties</h1>
    </div>
    <div class="filters">
        <select id="sort">
            <option value="price_asc">Price: Low to High</option>
            <option value="price_desc">Price: High to Low</option>
            <option value="rating_desc">Best Rated</option>
        </select>
        <input type="number" id="min_price" placeholder="Min Price">
        <input type="number" id="max_price" placeholder="Max Price">
        <select id="property_type">
            <option value="">All Types</option>
            <option value="Apartment">Apartment</option>
            <option value="House">House</option>
            <option value="Villa">Villa</option>
        </select>
    </div>
    <div class="property-list">
        <?php
        include 'db.php';
        $destination = isset($_GET['destination']) ? $conn->real_escape_string(urldecode($_GET['destination'])) : '';
        $query = "SELECT * FROM properties WHERE location LIKE '%$destination%'";
        if (isset($_GET['min_price']) && $_GET['min_price'] !== '') {
            $min_price = (float)$_GET['min_price'];
            $query .= " AND price >= $min_price";
        }
        if (isset($_GET['max_price']) && $_GET['max_price'] !== '') {
            $max_price = (float)$_GET['max_price'];
            $query .= " AND price <= $max_price";
        }
        if (isset($_GET['property_type']) && $_GET['property_type'] !== '') {
            $property_type = $conn->real_escape_string(urldecode($_GET['property_type']));
            $query .= " AND property_type = '$property_type'";
        }
        if (isset($_GET['sort'])) {
            if ($_GET['sort'] == 'price_asc') $query .= " ORDER BY price ASC";
            elseif ($_GET['sort'] == 'price_desc') $query .= " ORDER BY price DESC";
            elseif ($_GET['sort'] == 'rating_desc') $query .= " ORDER BY rating DESC";
        }
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            echo "<div class='property-item'>";
            echo "<img src='https://via.placeholder.com/300x200' alt='Property'>";
            echo "<div class='property-details'>";
            echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
            echo "<p>Location: " . htmlspecialchars($row['location']) . "</p>";
            echo "<p>Price: $" . htmlspecialchars($row['price']) . "/night</p>";
            echo "<p>Rating: " . htmlspecialchars($row['rating']) . "/5</p>";
            echo "<p>" . htmlspecialchars($row['description']) . "</p>";
            echo "<button class='book-btn' onclick=\"bookNow(" . $row['id'] . ")\">Book Now</button>";
            echo "</div></div>";
        }
        $conn->close();
        ?>
    </div>
    <script>
        function bookNow(propertyId) {
            const checkin = encodeURIComponent('<?php echo isset($_GET['checkin']) ? $_GET['checkin'] : ''; ?>');
            const checkout = encodeURIComponent('<?php echo isset($_GET['checkout']) ? $_GET['checkout'] : ''; ?>');
            window.location.href = `booking.php?property_id=${propertyId}&checkin=${checkin}&checkout=${checkout}`;
        }
        document.getElementById('sort').addEventListener('change', applyFilters);
        document.getElementById('min_price').addEventListener('input', applyFilters);
        document.getElementById('max_price').addEventListener('input', applyFilters);
        document.getElementById('property_type').addEventListener('change', applyFilters);
        function applyFilters() {
            const sort = document.getElementById('sort').value;
            const minPrice = document.getElementById('min_price').value;
            const maxPrice = document.getElementById('max_price').value;
            const propertyType = document.getElementById('property_type').value;
            const destination = encodeURIComponent('<?php echo isset($_GET['destination']) ? $_GET['destination'] : ''; ?>');
            const checkin = encodeURIComponent('<?php echo isset($_GET['checkin']) ? $_GET['checkin'] : ''; ?>');
            const checkout = encodeURIComponent('<?php echo isset($_GET['checkout']) ? $_GET['checkout'] : ''; ?>');
            let url = `properties.php?destination=${destination}&checkin=${checkin}&checkout=${checkout}`;
            if (sort) url += `&sort=${sort}`;
            if (minPrice) url += `&min_price=${minPrice}`;
            if (maxPrice) url += `&max_price=${maxPrice}`;
            if (propertyType) url += `&property_type=${propertyType}`;
            window.location.href = url;
        }
    </script>
</body>
</html>
