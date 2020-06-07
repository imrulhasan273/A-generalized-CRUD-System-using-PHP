<?php
	include 'dbcon.php';
	$id = $_GET['id'];

	//Delete the the photo from directory when deletting user.
	$dis=mysqli_query($con,"select * from users"); //checking start if current password is valid
	while($row=mysqli_fetch_array($dis))
	{
	   	if($id==$row["Email"])//checking end if current password is valid	
	    {
	    	$deletedImage = $row["photo"];
	    	print($deletedImage);
	    	if(file_exists("photos/".$deletedImage))	//check of file exist in the directory
			{
				unlink(dirname(__FILE__) . "/photos/".$deletedImage);// delete the file from dir
			}
	    }
	}
	//-------------------------------------------------------

	//deleting user from database-----------------
	$q = "DELETE FROM `users` WHERE Email='$id'";
	mysqli_query($con, $q);
	//------------------------------------
	
	header('location:index.php'); //back to the index page after executing all the task


?>