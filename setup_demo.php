<?php
require_once 'config/db.php';

echo "<h2>Setting up Demo Data...</h2>";

try {
    // 1. Add some Vehicles
    $pdo->exec("INSERT INTO vehicles (vehicle_number, type, capacity, status) VALUES 
    ('MH-12-KH-7788', 'Bus', 50, 'Available'),
    ('MH-12-LH-1122', 'Van', 15, 'Available'),
    ('MH-12-XY-9900', 'Bus', 60, 'In-Use')");
    echo "Added 3 Vehicles.<br>";

    // 2. Add some Routes
    $pdo->exec("INSERT INTO routes (route_name, start_point, end_point, distance, fee) VALUES 
    ('North Route - A', 'Main Gate', 'North Hub', 12.5, 1200),
    ('South Route - B', 'Main Gate', 'City Center', 8.2, 800),
    ('East Route - C', 'Hostel Block', 'Railway Station', 15.0, 1500)");
    echo "Added 3 Routes.<br>";

    // 2.5 Add Stops (Villages)
    $pdo->exec("INSERT INTO stops (route_id, stop_name, stop_time, sequence_order) VALUES 
    (1, 'Village Rampur', '08:00:00', 1),
    (1, 'Ganga Nagar', '08:15:00', 2),
    (1, 'Shanti Chowk', '08:30:00', 3),
    (1, 'North Hub (Final)', '08:45:00', 4),
    (2, 'Green Valley', '08:10:00', 1),
    (2, 'River View', '08:25:00', 2),
    (2, 'City Center (Final)', '08:40:00', 3)");
    echo "Added Villages/Stops for routes.<br>";

    // 3. Add a Driver
    $pass = password_hash('password', PASSWORD_DEFAULT);
    $pdo->exec("INSERT INTO users (username, password, role, full_name, email) VALUES 
    ('driver1', '$pass', 'driver', 'John Doe', 'john@gmail.com')");
    $driver_id = $pdo->lastInsertId();
    $pdo->exec("INSERT INTO driver_details (user_id, vehicle_id, license_number) VALUES ($driver_id, 1, 'DL-TEST-001')");
    echo "Added Driver 'driver1' (pass: password).<br>";

    // 4. Add a Student
    $pdo->exec("INSERT INTO users (username, password, role, full_name, email) VALUES 
    ('student1', '$pass', 'student', 'Alice Williams', 'alice@edu.com')");
    $student_id = $pdo->lastInsertId();
    $pdo->exec("INSERT INTO allocations (user_id, route_id, vehicle_id, status) VALUES ($student_id, 1, 1, 'Active')");
    echo "Added Student 'student1' (pass: password).<br>";

    echo "<h3>Setup Complete!</h3>";
    echo "<p>Admin: admin / password</p>";
    echo "<p>Driver: driver1 / password</p>";
    echo "<p>Student: student1 / password</p>";
    echo "<a href='index.php'>Go to Login</a>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
