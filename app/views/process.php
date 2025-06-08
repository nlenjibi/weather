<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$api_key = escape_data($_POST["api_key"]);

	if ($api_key === PROJECT_API_KEY) {
		$data = [];
		$data['temperature'] = escape_data($_POST["temperature"]);
		$data['humidity'] = escape_data($_POST["humidity"]);
		$data['created_date'] = date("Y-m-d H:i:s");

		$sql = "INSERT INTO tbl_temperature (temperature, humidity, created_date) 
				VALUES (:temperature, :humidity, :created_date)";
				//dd($data);
		$result = query($sql, $data);

		if ( $result === FALSE) {
			echo "Error: Error: data not inserted";
			session('error', 'Error: data not inserted');
		} else {
			echo "OK. INSERT ID: suscessfully inserted."; 
			session('success', 'suscessfully inserted.');
		}
	} else {
		echo "Wrong API Key";
	}
} else {
	echo "No HTTP POST request found";
}

