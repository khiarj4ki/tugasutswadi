<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT username FROM users WHERE id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);

    $stmt = $conn->prepare("INSERT INTO uploads (user_id, file_path) VALUES (:user_id, :file_path)");
    $stmt->execute(['user_id' => $user_id, 'file_path' => $target_file]);
}

$stmt = $conn->prepare("SELECT * FROM uploads WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$uploads = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2> 
        <form method="post" enctype="multipart/form-data">
        <input type="file" name="photo" required>
        <button type="submit">Upload</button>
    </form>

    <h3>Your Uploaded Photos</h3>
    <div class="gallery">
        <?php foreach ($uploads as $upload): ?>
            <div class="gallery-item">
                <img src="<?php echo $upload['file_path']; ?>" alt="Uploaded Image">
                <div class="button-group">
                    <form method="post" action="delete.php" style="display:inline;">
                        <input type="hidden" name="file_id" value="<?php echo $upload['id']; ?>">
                        <button type="submit" class="delete-button">Delete</button>
                    </form> 
                    <form method="get" action="<?php echo $upload['file_path']; ?>" style="display:inline;">
                        <button type="submit" class="download-button">Download</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <p><a href="logout.php">Logout</a></p>
    <script src="js/script.js"></script>
</body>
</html>