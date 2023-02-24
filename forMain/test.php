<!DOCTYPE html>
<html>
<head>
	<title>Folder Preview</title>
	<style>
		body {
			font-family: Arial, sans-serif;
		}

		h1 {
			margin-top: 20px;
		}

		ul {
			list-style: none;
			margin: 0;
			padding: 0;
		}

		li {
			padding: 5px;
			border: 1px solid #ccc;
			border-radius: 5px;
			margin-bottom: 5px;
		}

		li a {
			display: block;
			text-decoration: none;
			color: #333;
		}

		li a:hover {
			background-color: #f0f0f0;
		}
	</style>
</head>
<body>
	<h1>Folder Preview</h1>
	<ul>
		<?php
			$dir = ".";
			$files = scandir($dir);
			foreach ($files as $file) {
				if ($file != "." && $file != ".." && is_dir($file)) {
					echo "<li><a href='$file'>$file</a></li>";
				}
			}
		?>
	</ul>
</body>
</html>