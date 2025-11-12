<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']!=='reinabeadini') exit;

$id = $_POST['id'] ?? '';
if(!$id) exit;

$notifFile = 'notifications.json';
$notifications = [];

if(file_exists($notifFile)){
    $data = file_get_contents($notifFile);
    $notifications = json_decode($data,true);
    if(!is_array($notifications)) $notifications = [];
}

$notifications = array_values(array_filter($notifications, fn($n)=>$n['id']!==$id));
file_put_contents($notifFile,json_encode($notifications, JSON_PRETTY_PRINT));
