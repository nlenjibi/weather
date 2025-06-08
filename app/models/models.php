<?php
function getLatestTemperature() {
    $sql = "SELECT * FROM tbl_temperature WHERE 1 ORDER BY id DESC";
    $result = query($sql);
    return $result[0];
}
function fetchTemperatureData() {
    $sql = "SELECT * FROM tbl_temperature ORDER BY id DESC LIMIT 30";
    return query($sql);
  }