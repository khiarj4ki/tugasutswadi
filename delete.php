<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['file_id'])) {
    $file_id = $_POST['file_id'];
    $user_id = $_SESSION['user_id'];

    // Ambil file path dari database untuk file yang akan dihapus
    $stmt = $conn->prepare("SELECT file_path FROM uploads WHERE id = :file_id AND user_id = :user_id");
    $stmt->execute(['file_id' => $file_id, 'user_id' => $user_id]);
    $file = $stmt->fetch();

    if ($file) {
        $file_path = $file['file_path'];

        // Hapus file dari direktori uploads
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Hapus data dari database
        $stmt = $conn->prepare("DELETE FROM uploads WHERE id = :file_id AND user_id = :user_id");
        $stmt->execute(['file_id' => $file_id, 'user_id' => $user_id]);

        header("Location: dashboard.php");
        exit();
    } else {
        echo "File not found or you're not authorized to delete this file.";
    }
}
?>
