<?php
	include 'dbcon.php';
	$id = $_GET['id'];	//take the id(email) from url by GET method.
	$flag=0;
	if(isset($_POST['updates']))	//when click on Update button
	{
		$vemail = $_POST['ipemail'];	//take value from email field in form
		$vpass = $_POST['oldpassword'];	//take value from current password field in form
		$vpass=hash('sha256', $vpass);  //convert temp password to hash to check its current or not.
		$dis=mysqli_query($con,"select * from users"); //checking start if current password is valid
	    while($row=mysqli_fetch_array($dis))
	    {
	        if($row["Email"]==$vemail && $row["Password"]==$vpass)//checking end if current password is valid	
	        {
	        	$deletedImage = $row["photo"];	//temp var to store current photo to delete before update.
	            $flag = 1;
							
				if (isset($_POST['checkBox']))	//check if checkbox is checked.
				{
					$passwordsFlag = $_POST['oldpassword'];		//password remain unchanged
					$newpassword=hash('sha256', $passwordsFlag);//unchanged pass to hash
					$_POST['newpassword'] = $passwordsFlag;
					$backup = $_POST['oldpassword'];
				}
				else
				{
					$passwordsFlag = $_POST['newpassword'];	//store original password before hashing.
					$newpassword=hash('sha256', $passwordsFlag);	//hashing password in new var.
				}
                ///-------------------------------------------------------------------------------
				if(strlen($passwordsFlag)>5)	//check whether the password is in required length.
				{
					$target_dir = "photos/";					//directory to store photos physically
               		$image = $_FILES['file']['name'];			//take the path from file type input in form.

					$target_file = $target_dir.basename($_FILES["file"]["name"]);//checking start if file is valid 
					$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
					$extensions_arr = array("jpg","jpeg","png","gif");	
					if( in_array($imageFileType,$extensions_arr) )	//checking end if file is valid 
					{		
		                $qr="UPDATE users SET Email='$id', Password='$newpassword', photo='$image'  WHERE Email='$id' ";
		                if(mysqli_query($con,$qr)) //update the database.
		                {
						    if(file_exists("photos/".$deletedImage))	//check of file exist in the directory
							{
								unlink(dirname(__FILE__) . "/photos/".$deletedImage);// delete the file from dir
							}
		                    move_uploaded_file($_FILES['file']['tmp_name'], $target_dir."$image"); //add the updated file in dir
		                    header('location:index.php');	//go back to the index page.
		                }  
					}
					else
	                {
	                    print("Please Input a valid Photo!!!");
	                }
				}
				else
				{
					print("Password must be at least 5 charecters!!!");
				}
	     	}            
	    }
	    if($flag==0)
	    {
	    	print("Incorrect Password!!!");
	    }	
	 }
?>
<!DOCTYPE html>
<html>
<head>
  <title>BiTechX Test</title>
        <link rel="shortcut icon" type="image/png" href="https://bitechx.com/images/favicon/bitechx.png">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <!-- Data Table CSS & JS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.foundation.min.css">
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.foundation.min.js"></script>
</head>
<body>

 <div class="col-lg-6 m-auto">
 	<h1 class="text-center">BiTechX Test</h1>
	<form method="post" class="mx-auto pt-5" style="max-width:700px;" enctype='multipart/form-data'>

    	<div class="form-group">
        <label>Email</label>
        <input value="<?= $_GET['id']; ?>" type="email" name="ipemail" class="form-control" readonly>
        </div>

        <div class="form-group">
        <label>Current Password</label>
        <input id="oldpassword1" type="password" name="oldpassword" class="form-control">
        </div>

        <div class="form-group">
        <label>New Password</label>
        <input id="newpassword1" type="password" name="newpassword" class="form-control">
        </div>
        
        <input id="checkbox1" type="checkbox" name="checkBox" value="">
  		<label for="checkBox">Unchanaged Current Password</label><br><br>

        <div class="form-group">
        <label>Upload file</label>
        <input id="file" name="file" type="file" class="form-control-file">
        </div>

        <button class="btn btn-success" type="submit" name="updates">Update</button><br>
    </form>    	
 	
 </div>
<!-- below is the JQuery library files must be there in order to JQuery to work perfectly -->
<script src="js/jquery-3.4.1.min.js">
</script>  

<!-- below is the additional functionality using JQuery -->
<script>
	// var x = "<?php echo $id; ?>";
	$('#checkbox1').click(function(){
	    if($(this).prop("checked")){
	    	$('#newpassword1').val("************");
	    }
	    else{
	    	$('#newpassword1').val("");
	    }
   	})
</script>  


</body>
</html>