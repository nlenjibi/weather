<?php
$sql = "SELECT * FROM tbl_temperature ORDER BY id DESC LIMIT 30";

$result = query($sql);

if ($result) {
   
    echo json_encode($result[0]);
} else {
    echo json_encode(['error' => 'Failed to fetch data']);
}