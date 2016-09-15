<?php
header("Content-Type : application/json");
$db = new PDO('mysql:host=localhost;dbname=oscom23', 'root', 'YOUR_DB_PASSWORD');
$param = "%{$_GET['q']}%";
$stmt = $db->prepare("SELECT name,name as value,location,avatar FROM profiles WHERE name LIKE :query ");
$stmt->bindValue(':query', $param);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
if(!empty($result)) {
	echo json_encode($result);
}
else{
	return false;
}
?>