<?php
    include 'dbcon.php';
    // insert
    if(isset($_POST['inserts']))    //when click on insert button
    {
        $emails = $_POST['ipemail'];    //take value from email field in form
        $passwordsFlag = $_POST['ippassword']; //take value from password field in form
        $passwords=hash('sha256', $passwordsFlag); //convert password to hashed password
        if (filter_var($emails, FILTER_VALIDATE_EMAIL)) //check email is valid or invalid
        {
            if(strlen($passwordsFlag)>5)    //check if password is in required length
            {   
                $target_dir = "photos/";    //directory where the file will be stored
                $image = $_FILES['file']['name'];   //take the path from file type input in form.
                $target_file = $target_dir.basename($_FILES["file"]["name"]); //checking start if file is valid
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $extensions_arr = array("jpg","jpeg","png","gif"); //file ext which are to be uploadable
                if(in_array($imageFileType,$extensions_arr) )   //checking end if file is valid 
                {
                    $qr="INSERT INTO users (Email,Password,photo) VALUES('".$emails."','".$passwords."','".$image."')";

                    if(mysqli_query($con,$qr))  //update the database
                    {
                        move_uploaded_file($_FILES['file']['tmp_name'], $target_dir."$image"); //add the updated file in dir
                    }   
                }
                else
                {
                    print("Please Input a valid Photo!!!");
                }
            }
            else
            {
                print("Password length must be at least 5 charecters.");
            }   
        } 
        else 
        {
          echo("$emails is not a valid email address");
        }
    }
?> 
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
        <div class="container">
            <h1 class="text-center">CRUD Operation</h1>
            <form method="post" class="mx-auto pt-5" style="max-width:700px;" enctype='multipart/form-data'>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="ipemail" class="form-control">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="ippassword" class="form-control">
                </div>
                <div class="form-group">
                    <label>Upload Photo</label>
                    <input id="file" name="file" type="file" class="form-control-file">
                </div>
                <button name="inserts" type="submit" class="btn btn-success">Insert</button>
            </form>

            <?php
            $RRsql = "SELECT * FROM users";
            $rresult = mysqli_query($con,$RRsql)or die(mysqli_error($con));
            echo "<table class='table table-striped table-bordered mx-auto pt-5' style='width:100%;max-width:700px;'>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    <tbody>
                        <tr>";
                            while($row = mysqli_fetch_array($rresult))
                            {
                              $id = $row['Email'];
                              echo "<tr>";
                              echo "<td>".$row['Email']."</td>";
                              // echo "<td><button id='update' class='btn btn-primary'>Edit</button></td>";

                              echo "<td><a class='btn btn-primary' href='update.php?id=".$row['Email']."'>Update</a></td>";

                              echo "<td><a class='btn btn-danger' href='delete.php?id=".$row['Email']."'>Delete</a></td>";
                              echo "</tr>";
                            }
                        echo "</tr>
                    </tbody>
                </thead>
                </table>";
                mysqli_close($con);
            ?>

        </div>
    </body>
</html>