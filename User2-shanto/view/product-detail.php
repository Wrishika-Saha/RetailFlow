<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once '../Model/DatabaseConnection.php';

$db = new DatabaseConnection();
$conn = $db->openConnection();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("Invalid Product ID");
}

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$product = $res->fetch_assoc();
$stmt->close();

if (!$product) {
    die("Product not found");
}

$db->closeConnection($conn);

$title = htmlspecialchars($product['title']);
$category = htmlspecialchars($product['category']);
$price = htmlspecialchars($product['price']);
$stock = htmlspecialchars($product['stock']);
$description = htmlspecialchars($product['description']);
$imageSrc = '../uploads/' . $product['image'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - Product Details</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .detail-wrap{
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
        }
        .detail-card{
            background:#fff;
            border-radius:12px;
            box-shadow:0 6px 18px rgba(0,0,0,0.08);
            overflow:hidden;
            display:flex;
            gap:20px;
            padding:20px;
        }
        .detail-card img{
            width:320px;
            height:320px;
            object-fit:cover;
            border-radius:12px;
            background:#e5e7eb;
        }
        .detail-info h1{
            font-size:26px;
            margin-bottom:10px;
        }
        .detail-info p{
            margin:8px 0;
            color:#374151;
        }
        .detail-info .price{
            font-weight:700;
            color:#16a34a;
            font-size:18px;
        }
        .back-btn{
            display:inline-block;
            margin-top:15px;
            text-decoration:none;
            background:#2563eb;
            color:#fff;
            padding:10px 14px;
            border-radius:8px;
        }
        .back-btn:hover{ background:#1d4ed8; }
        @media(max-width: 800px){
            .detail-card{ flex-direction:column; }
            .detail-card img{ width:100%; height:260px; }
        }
    </style>
</head>
<body>

<?php include('header.php'); ?>

<div class="detail-wrap">
    <div class="detail-card">
        <img src="<?php echo $imageSrc; ?>" alt="<?php echo $title; ?>">
        <div class="detail-info">
            <h1><?php echo $title; ?></h1>
            <p><b>Category:</b> <?php echo $category; ?></p>
            <p class="price">BDT <?php echo $price; ?></p>
            <p><b>Stock:</b> <?php echo $stock; ?></p>
            <p><b>Description:</b> <?php echo $description; ?></p>

            <a class="back-btn" href="index.php">← Back to Products</a>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>

</body>
</html>
