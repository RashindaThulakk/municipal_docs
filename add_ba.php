<?php
require 'includes/auth.php';
require_login();
require 'includes/db_connect.php';

if (!in_array($_SESSION['role'], ['Admin','Clerk'])) {
    die('Permission denied');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard_ba.php');
    exit;
}

$id = !empty($_POST['id']) ? intval($_POST['id']) : null;
$ba_no = trim($_POST['ba_no'] ?? '');
$year = trim($_POST['year'] ?? '');
$rack_number = trim($_POST['rack_number'] ?? '');
$applicant_name = trim($_POST['applicant_name'] ?? '');
$applicant_id = trim($_POST['applicant_id'] ?? '');
$assessment_no = trim($_POST['assessment_no'] ?? '');
$street_name = trim($_POST['street_name'] ?? '');
$assessment_ward = !empty($_POST['assessment_ward']) ? intval($_POST['assessment_ward']) : null;
$officer_received = trim($_POST['officer_received'] ?? '');
$date_taken = !empty($_POST['date_taken']) ? $_POST['date_taken'] : null;
$notes = trim($_POST['notes'] ?? '');

// File handling
$attachment_data = null;
$attachment_type = null;
if (!empty($_FILES['attachment']['name'])) {
    if ($_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['application/pdf','image/jpeg','image/png'];
        $mtype = $_FILES['attachment']['type'];
        if (!in_array($mtype, $allowed)) {
            die('Invalid file type');
        }
        $attachment_data = file_get_contents($_FILES['attachment']['tmp_name']);
        $attachment_type = $mtype;
    } else {
        die('File upload error');
    }
}

// Basic server validation
if (!$ba_no || !$year) {
    die('BA No and Year are required.');
}

if ($id) {
    // UPDATE
    if ($attachment_data !== null) {
        $sql = "UPDATE building_applications 
                SET ba_no=?, year=?, rack_number=?, applicant_name=?, applicant_id=?, assessment_no=?, street_name=?, assessment_ward=?, officer_received=?, date_taken=?, attachment=?, attachment_type=?, notes=? 
                WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $ba_no);
        $stmt->bindValue(2, $year);
        $stmt->bindValue(3, $rack_number);
        $stmt->bindValue(4, $applicant_name);
        $stmt->bindValue(5, $applicant_id);
        $stmt->bindValue(6, $assessment_no);
        $stmt->bindValue(7, $street_name);
        $stmt->bindValue(8, $assessment_ward);
        $stmt->bindValue(9, $officer_received);
        if ($date_taken === null) {
            $stmt->bindValue(10, null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(10, $date_taken);
        }
        $stmt->bindValue(11, $attachment_data, PDO::PARAM_LOB);
        $stmt->bindValue(12, $attachment_type);
        $stmt->bindValue(13, $notes);
        $stmt->bindValue(14, $id, PDO::PARAM_INT);
    } else {
        // keep existing attachment
        $sql = "UPDATE building_applications 
                SET ba_no=?, year=?, rack_number=?, applicant_name=?, applicant_id=?, assessment_no=?, street_name=?, assessment_ward=?, officer_received=?, date_taken=?, notes=? 
                WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $params = [$ba_no, $year, $rack_number, $applicant_name, $applicant_id, $assessment_no, $street_name, $assessment_ward, $officer_received, $date_taken, $notes, $id];
        if ($date_taken === null) {
            $params[9] = null;
        }
        $stmt->execute($params);
        header('Location: dashboard_ba.php?msg=updated');
        exit;
    }
    $stmt->execute();
    header('Location: dashboard_ba.php?msg=updated');
    exit;
} else {
    // INSERT
    $sql = "INSERT INTO building_applications 
            (ba_no, year, rack_number, applicant_name, applicant_id, assessment_no, street_name, assessment_ward, officer_received, date_taken, attachment, attachment_type, notes)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $ba_no);
    $stmt->bindValue(2, $year);
    $stmt->bindValue(3, $rack_number);
    $stmt->bindValue(4, $applicant_name);
    $stmt->bindValue(5, $applicant_id);
    $stmt->bindValue(6, $assessment_no);
    $stmt->bindValue(7, $street_name);
    $stmt->bindValue(8, $assessment_ward);
    $stmt->bindValue(9, $officer_received);
    if ($date_taken === null) {
        $stmt->bindValue(10, null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(10, $date_taken);
    }
    $stmt->bindValue(11, $attachment_data, PDO::PARAM_LOB);
    $stmt->bindValue(12, $attachment_type);
    $stmt->bindValue(13, $notes);
    $stmt->execute();
    header('Location: dashboard_ba.php?msg=added');
    exit;
}
