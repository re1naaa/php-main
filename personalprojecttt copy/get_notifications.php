<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']!=='reinabeadini') exit;

$notifFile = 'notifications.json';
$notifications = [];

if(file_exists($notifFile)){
    $data = file_get_contents($notifFile);
    $notifications = json_decode($data,true);
    if(!is_array($notifications)) $notifications = [];
}

header('Content-Type: application/json');
echo json_encode($notifications);
