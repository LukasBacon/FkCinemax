<?php
include 'Parser.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<title>FK Cinemax Doľany</title>
		<meta charset="UTF-8">
	</head>
	<body>
		<form method="post">
		<input type="text" name="url" style="width: 500px">
		<input type="submit" name="button">
		<br>
		</form>
		<?php
			// 
			// http://bfz.futbalnet.sk/sutaz/2532/print?part=4034							prip
			// http://obfz-bratislava-vidiek.futbalnet.sk/sutaz/2443/print					muzi teraz	
			// http://obfz-bratislava-vidiek.futbalnet.sk/sutaz/1578/print 					muzi minule
			if (isset($_POST["url"])){
				$parsovac = new Parser;
				$ok = $parsovac->parsuj($_POST["url"]);					
				if ($ok){
					echo $parsovac->nazovLigy . "<br>";
		 			echo $parsovac->rocnik . "<br>";
	 			}
	 		
 		}
		?>
	</body>
</html>