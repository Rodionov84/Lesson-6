<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	</head>
	<body>
<?php
// форма, через которую на сервер можно загрузить JSON-файл c тестом

/*
Тест — это несколько вопросов.
Вопрос — это текст вопроса, плюс несколько вариантов ответа. Один или несколько вариантов помечены как правильные.
*/

define('LOCAL_JSON', 'tests.json');

if (!empty($_FILES['tests'])) {
	// обработка загруженного файла
	$success = false;
	if (!is_uploaded_file($_FILES['tests']['tmp_name'])) {
		echo "<p>Получена запись о файле, но файл не был загружен во временную папку!</p>";
	} else {
		$file_contents = file_get_contents($_FILES['tests']['tmp_name']);
		$json = json_decode($file_contents); // => структуры PHP
		// сохраняем в файле json, дописываем в существующий файл с тестами
		if (file_exists(LOCAL_JSON)) {
			$local_file = file_get_contents(LOCAL_JSON);
		} else {
			$local_file = '[]'; // пустой массив в JSON
		}
		if ($local_file) {
			$local_json = json_decode($local_file); // see json_last_error() if error
			$local_json[] = $json;
			$new_json = json_encode($local_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
			$success = file_put_contents(LOCAL_JSON, $new_json);
		}
	}

	if ($success !== false)
		echo "<p>Файл успешно сохранён!</p>";
}
?>
		<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" enctype="multipart/form-data">
			<p>Выберите файл JSON с тестами</p>
			<p><input type="file" name="tests"></p>
			<p><input type="submit" value="Отправить"></p>
		</form>
		<a href="list.php"><button>Выбрать тест для выполнения</button></a>
	</body>
</html>
