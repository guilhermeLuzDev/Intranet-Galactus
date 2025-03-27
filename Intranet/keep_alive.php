<?php
session_start();
if (isset($_SESSION['user_id'])) {
    $_SESSION['last_activity'] = time();
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'logged_out']);
}
?>