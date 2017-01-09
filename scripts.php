<?php
// create a new PDO connection 
try {
	$db = new PDO("mysql:host=uguru.dev.fast.sheridanc.on.ca;dbname=uguru_blogs;charset=utf8","uguru_blogUser","WhyMustItBeThisLong?");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $error) {
	echo "ERROR: ".$error->getMessage();
}

// insert new blog names and urls into the database
if(isset($_POST['save_new'])) {
	$blogname =$_POST['blogname'];
	$blogurl = $_POST['blogurl'];

	$sql = "INSERT INTO blogs(name, url) VALUES(:blogname, :blogurl)";
	$query = $db->prepare( $sql );

	$query->bindparam(':blogname', $blogname);
	$query->bindparam(':blogurl', $blogurl);
	$query->execute();
}


// if there is an existing blog id that matches the one gotten from
// the for, delete the selected blog id. This will delete blogname and url
// associated with the id.
if(isset($_GET['delete_blog_id'])) {
	$bid = $_GET['delete_blog_id'];

	$sql = "DELETE FROM blogs WHERE bid=:bid";
	$query = $db->prepare( $sql );
	$query->execute(array(':bid' => $bid));
	header("Location: mainblogs.php");
}

// edit the blog id for a particular blog entry as well as the accompanying
// blog name and url.
if(isset($_GET['edit_blog_id'])) {
	$sql = "SELECT * FROM blogs WHERE bid=:bid";

	$query = $db->prepare( $sql );
	$query->execute(array(':bid' => $_GET['edit_blog_id']));
	$rowEdit=$query->FETCH(PDO::FETCH_ASSOC);
}

// get the variable update_blog from the html form, and use it to update any current blogname and url
// entry in the database.
if(isset($_POST['update_blog'])) {
	$blogname = $_POST['blogname']; // get the variable from the html form if the user has inputed anything into the form field
	$blogurl = $_POST['blogurl']; // get the variable from the html form if the user has inputed anything into the form field
	$bid = $_GET['edit_blog_id']; // this is the same variable defined in the edit_blog_id statement.


	$sql = "UPDATE blogs SET name=:blogname, url=:blogurl WHERE bid=:bid";
	$query = $db->prepare( $sql );
	
	$query->bindparam(':blogname', $blogname);
	$query->bindparam(':blogurl', $blogurl);
	$query->bindparam(':bid', $bid);
	$query->execute();
	header("Location: mainblogs.php");
}

// create the search variable that will be used to sanitize and prevent code injection.
if( isset($_GET['search']) ) {
	$search = "%".$_GET['search']."%";
} else {
	$search = "%";
}
?>