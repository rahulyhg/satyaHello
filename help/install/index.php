<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Fuse Installation</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Le styles -->
		<link href="../css/bootstrap.css" rel="stylesheet">

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="../assets/js/html5shiv.js"></script>
		<![endif]-->

	</head>

	<body>

		<div class="container">

			<h1>Fuse Installation</h1>
			<br>

			<div class="row-fluid">

				<p>
					To install Fuse, simply provide the following information
				</p>

				<?php
				//$password =
				// '08gv6gemSKxv09EImCsPLO94Nje6ZVAlFnuc13kohFwvC0kh/ESFEn1lEwL9bbSbrlym/UrO6wM9p0CGnjJHzg==';
				// == admin

				$domain = $_SERVER["HTTP_HOST"];
				$self = $_SERVER["PHP_SELF"];
				$path = $domain . $self;
				$url = 'http://' . substr($path, 0, -18);

				if (!is_writable('../application/config/database.php'))
				{
					echo '<div class="alert alert-danger">application/config/database.php file is not writable. Please chomod 777 before to continue</div>';
					die();
				}
				if (!is_writable('../application/config/config.php'))
				{
					echo '<div class="alert alert-danger">application/config/config.php file is not writable. Please chomod 777 before to continue</div>';
					die();
				}

				if ($_POST)
				{

					$host = $_POST['host'];
					$username = $_POST['username'];
					$password = $_POST['password'];
					$url = $_POST['url'];
					$database = $_POST['database'];
					$email = $_POST['email'];

					if (!$host OR !$username OR !$password OR !$url OR !$database)
					{
						echo '<div class="alert alert-danger">Please enter all the fields</div>';
					}
					else
					{
						$link = @mysql_connect($host, $username, $password);

						if (!$link)
						{
							echo '<div class="alert alert-danger">Connection to MySQL failed. Please enter correct MySQL data</div>';
						}
						else
						{
							mysql_select_db($database);

							if (!is_file('fuse.sql'))
							{
								echo '<div class="alert alert-danger">SQL file not found</div>';
							}
							else
							{
								$templine = '';
								// Read in entire file
								$lines = file('fuse.sql');
								foreach ($lines as $line)
								{
									if (substr($line, 0, 2) == '--' || $line == '')
										continue;
									$templine .= $line;
									if (substr(trim($line), -1, 1) == ';')
									{
										mysql_query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
										$templine = '';
									}

								}
							}

							mysql_query("UPDATE tblstaffs SET email = '$email'");
							$db_file = file_get_contents('database.php');
							$db_file = str_replace('%hostname%', $host, $db_file);
							$db_file = str_replace('%username%', $username, $db_file);
							$db_file = str_replace('%password%', $password, $db_file);
							$db_file = str_replace('%database%', $database, $db_file);
							file_put_contents('../application/config/database.php', $db_file);

							$db_file = file_get_contents('config.php');
							$db_file = str_replace('%url%', $url, $db_file);
							file_put_contents('../application/config/config.php', $db_file);
							echo '<div class="alert alert-success">Fuse has been installed successfully. You may access the 
							administration control panel at <a target="_blank" href="' . $url . '/admin">' . $url . '/admin</a> using the admin email and password <b>admin</b>';
							die();

						}
					}
				}
				?>

				<form action="index.php" method="post">

				<fieldset>
				<label>Script URL</label>
				<input type="text" name="url" value="<?php echo $url; ?>" required="required"  />
				</fieldset>

				<fieldset>
				<label>MySQL Host</label>
				<input type="text" name="host" value="localhost" required="required"  />
				</fieldset>
				<fieldset>
				<label>MySQL Database</label>
				<input type="text" name="database" value="" required="required"  />
				</fieldset>

				<fieldset>
				<label>MySQL Username</label>
				<input type="text" name="username" value="" required="required"  />
				</fieldset>

				<fieldset>
				<label>MySQL Password</label>
				<input type="text" name="password" value="" required="required"  />
				</fieldset>

				<fieldset>
				<label>Administrator Email</label>
				<input type="text" name="email" value="" required="required"  />
				</fieldset>

				<fieldset>
				<input class="btn" type="submit" value="Install" name="submit">
				</fieldset>

				</form>
				</div>

				<hr>
			</div>
			<!-- /container -->

	</body>
</html>
