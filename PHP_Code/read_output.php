$data = file_get_contents('test_output3.txt');
$data = trim($data, " \t\n\r\0\x0B\xEF\xBB\xBF");
$json = json_decode($data, true);
echo $json['details'] ?? $data;
