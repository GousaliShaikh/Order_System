<?php
$host = "localhost";
$username = "root";
$password = "Ali@20";
$dbname = "order_management";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=orders_export.xls");

echo "<table border='1'>
<tr>
  <th>User Name</th>
  <th>Phone</th>
  <th>Address</th>
  <th>Order ID</th>
  <th>Order Date</th>
  <th>Product</th>
  <th>Price</th>
  <th>Qty</th>
  <th>Total</th>
</tr>";

$sql = "SELECT 
            u.name AS user_name,
            u.phone,
            u.address,
            o.id AS order_id,
            o.order_date,
            p.name AS product_name,
            p.price,
            oi.quantity,
            (p.price * oi.quantity) AS total_amount
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        ORDER BY o.order_date DESC";

$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['user_name']}</td>
            <td>{$row['phone']}</td>
            <td>{$row['address']}</td>
            <td>{$row['order_id']}</td>
            <td>{$row['order_date']}</td>
            <td>{$row['product_name']}</td>
            <td>{$row['price']}</td>
            <td>{$row['quantity']}</td>
            <td>{$row['total_amount']}</td>
          </tr>";
}
echo "</table>";
$conn->close();
?>
