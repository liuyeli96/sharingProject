<html>
<head>
	<meta charset="utf-8">
	<title>Account Page</title>
	<link href="styles.css" media="all" rel="Stylesheet" type="text/css"/>

</head>
<body>

	<div class="sect1">
	<h1>Welcome to Account Page!</h1>

	<h2>
	<?php
	session_start();
	if(!isset($_SESSION['user'])){
		echo "Please login <a href='FirstPage.php'>here</a>.";
		header("Location: /Login.php");
		exit();
	}else{
		$user = $_SESSION['user'];
		echo "Welcome ".$user."!";
	}
	?>
	</h2>

	<form>
		<select name="View">
			<option value="browsing">Browse Items</option>
			<option value="bid">Bids for my Items</option>
			<option value="history">Loan History</option>
			<option value="add">Add Item</option>
			<option value="sale">My Items</option>
		</select>
		<input type="submit" name="formSubmit" value="Go!">
	</form>

	<?php
	if(isset($_GET['formSubmit'])){
		$result = $_GET['View'];
		if($result=='history'){
			header("Location: /LoanHistory.php");
		}else if($result=='add'){
			header("Location: /StuffSharingObject.php");
		}else if($result=='sale'){
			header("Location: /UserItems.php");
		}else if($result=='browsing'){
			header("Location: /browsing.php");
		}else if($result=='bid'){
			header("Location: /AcceptBids.php");
		}
	}
	?>
	Copyright &#169; VYMMS

	<a href="Logout.php?logout">LOGOUT</a>
	</div>
</body>
</html>
