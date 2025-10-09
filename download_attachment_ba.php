<?php
require 'includes/db_connect.php';
$id = isset($_GET['id'])? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("SELECT attachment, attachment_type, applicant_name FROM building_applications WHERE id=? LIMIT 1");
$stmt->execute([$id]);
$row = $stmt->fetch();
if (!$row || !$row['attachment']) { exit('No file.'); }
$ctype = $row['attachment_type'] ?: 'application/octet-stream';
header("Content-Type: $ctype");
$filename = preg_replace('/[^a-z0-9_.-]/i','_', ($row['applicant_name'] ?: 'attachment')) . "_$id";
header("Content-Disposition: inline; filename=\"$filename\"");
echo $row['attachment'];
exit;
