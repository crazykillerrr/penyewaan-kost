<?php
require_once '../includes/auth_admin.php';
require_once '../includes/db.php';
checkAdminAuth();

if (isset($_GET['id'])) {
    $kamar_id = $_GET['id'];
    
    // Get kamar data to delete photo
    $stmt = $pdo->prepare("SELECT foto FROM kamar WHERE id = ?");
    $stmt->execute([$kamar_id]);
    $kamar = $stmt->fetch();
    
    if ($kamar) {
        // Delete photo file if exists
        if ($kamar['foto'] && file_exists('../uploads/kamar/' . $kamar['foto'])) {
            unlink('../uploads/kamar/' . $kamar['foto']);
        }
        
        // Delete kamar record
        $stmt = $pdo->prepare("DELETE FROM kamar WHERE id = ?");
        $stmt->execute([$kamar_id]);
    }
}

header('Location: kamar_list.php');
exit();
?>
