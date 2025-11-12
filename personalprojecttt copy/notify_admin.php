<?php
session_start();
if(!isset($_SESSION['user'])) exit;

$user = $_SESSION['user'];
$flavor = $_POST['flavor'] ?? '';
if(!$flavor) exit;

$notifFile = 'notifications.json';
$notifications = [];

if(file_exists($notifFile)){
    $data = file_get_contents($notifFile);
    $notifications = json_decode($data,true);
    if(!is_array($notifications)) $notifications = [];
}

$notifications[] = [
    'id' => uniqid(),
    'user' => $user,
    'flavor' => $flavor,
    'time' => date('H:i:s')
];

file_put_contents($notifFile,json_encode($notifications, JSON_PRETTY_PRINT));
