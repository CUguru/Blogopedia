<?php

require_once 'scripts.php'; // include the file blogscripts.php once, and whenever the page loads, check if the file
// has already been included.

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Blogs Database</title>
		<link rel="stylesheet" href="style.css">
		<meta charset="utf-8">
		<!-- <meta http-equiv="refresh" content="25"> --> <!-- refresh page every 25 seconds so that that url changes back to the main one -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

	</head>
	<body>
	<div class="first-section">
		<h2>Contribute to our crowd curated list of interesting<br>blogs and websites to read and follow.</h2>
		<!-- create a form in that asks the user for a blog/website name as well as the url. The entered inputs are then added to the database, and are given unique blog_id's. -->
		<form method="post" class="search-entries-form">
			<!-- use a table format because it was the most straight forward to implement -->
			<table class="blog-entries">
				<tr>
					<td>
						<!-- this input field takes in the name of the blog you want to add or update -->
						<input class="blog-entries-input" type="text" name="blogname" placeholder="enter a blog/website name here" value="<?php if(isset($_GET['edit_blog_id'])){print($rowEdit['name']); } ?>" >
					</td>
				</tr>
				<tr>
					<td>
						<!-- this input field takes in the url of the blog yout want to add or update. It has a value set by the php bindparam function -->
						<input class="blog-entries-input" type="text" name="blogurl" placeholder="enter the url" value="<?php if(isset($_GET['edit_blog_id'])){print($rowEdit['url']); } ?>" >

					</td>
				</tr>
				<tr>
					<td>
						<!-- if there is no error in getting the blog_id, the user can then update any entry that is already within the database -->
						<?php
						if(isset($_GET['edit_blog_id'])) {
							?>
							<button type="submit" name="update_blog" class="btn-update">UPDATE ENTRY</button>
							<?php
						// else they can choose to create a new entry and add that to the database
						} else {
							?>
							<button type="submit" name="save_new" class="btn-update">SAVE NEW ENTRY</button>
							<?php
						} ?>
					</td>
				</tr>
			</table>
		</form>
		</div>
		<br><br>

		<!-- create a form to be used when searching the database. It takes in the name of the blog but not the url of the blog -->

		<div class="second-section">
		<h3 class="browse-header">Browse our collection</h3>
		<!-- use a form to search the database for a specific blog -->
		<form method='get' action='mainblogs.php' class="search-blogs">
			<input type='text' name='search' placeholder="Search Blogopedia">
			<button type='submit' class="search-btn">SEARCH</button>
		</form>
		
		<!-- if the field for the search form is filled in, we can then search for the blogname, else the search doesn't run. -->
		<?php
	
		// use this query to search the database for the name of the blog and its url.
		$sql =  "SELECT bid, name, url FROM blogs WHERE name LIKE :search"; 
		$query = $db->prepare( $sql );
		$query->bindparam( ':search' , $search );
		$query->execute();
		?>

		<!-- put all the names and urls in one table so that the users can see the complete list of what is on the database -->
		<table class="blog-names-table">
			<?php
			// if the amount of rows in the database is equal to what the sql query asked for, we loop through each row and
			// add each row to the table. each row will include, the name of the blog, the link, and will give the user
			// the choice of editing any entry, as well as deleting any entry if they make a mistake typing it in.
			if($query->rowCount() > 0) { // if($query->rowCount() >0) {
				while($data = $query->fetch(PDO::FETCH_ASSOC)) {
					?>
					<tr>
						<td><?php print($data['name']); ?></td>
						<td><a target="_blank" href="http://<?php print($data['url']); ?>"><?php print($data['url']); ?></a></td>
						<td><a onclick="return confirm('Are you sure you want to edit this entry?')" href="mainblogs.php?edit_blog_id=<?php print($data['bid']); ?>" class="edit-link"><img src="images/edit.svg" class="edit-delete">Edit</a></td>
						<td><a onclick="return confirm('Are you sure you want to edit this entry?')" href="mainblogs.php?delete_blog_id=<?php print($data['bid']); ?>" class="edit-link"><img src="images/delete.svg" class="edit-delete">Delete</a></td>
					</tr>
					<?php
				}
				// if there is nothing in the database, we enter the else case.
			} else {
				?>
				<tr>
					<td class="error-message"><?php print("That entry doesn't exist in the database."); ?></td>
				</tr>
				<?php
			}
			?>
		</table>
		</div>
		<!-- <script src="script.js"></script> -->
	</body>
</html>