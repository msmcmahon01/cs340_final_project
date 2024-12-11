<?php
	session_start();	
// Include config file
	require_once "config.php";
 
// Note: You can not update SSN  
// Define variables and initialize with empty values
$setterID = $_GET['SetterID'];
$Name="";
$Name_err="";
// Form default values
ini_set('display_errors', 1);
error_reporting(E_ALL);
if(!empty(trim($setterID))){
	$sid = $setterID;

    // Prepare a select statement
    $sql1 = "SELECT * FROM Setter WHERE SetterID = ?";
  
    if($stmt1 = mysqli_prepare($link, $sql1)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt1, "s", $param_SID);      
        // Set parameters
       $param_SID = $sid;

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt1)){
            $result1 = mysqli_stmt_get_result($stmt1);
			if(mysqli_num_rows($result1) > 0){
				$row = mysqli_fetch_array($result1);
				$Name = $row['Name'];
                $DOB = $row['DOB'];
			}
		}
	}
}

// Post information about the employee when the form is submitted
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){	
    // Validate Dependent name
    $Name = trim($_POST["Name"]);
    if(empty($Name)){
        $Name_err = "Please enter a Name.";
    } elseif(!filter_var($Name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $Name_err = "Names may only contain letters and spaces.";
    } 

    // Check input errors before inserting into database
    if(empty($Name_err)){
        // Prepare an update statement

        $sql = "UPDATE Setter SET Name=? WHERE SetterID=?";
    
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_Sname,$param_SID);
            
            // Set parameters
            $param_Sname = $Name;
            $param_SID = $sid;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("Location:setters.php");
                exit();
            }
        }    
	
        // Close statement
        mysqli_stmt_close($stmt);
    }
	
    // Close connection
    mysqli_close($link);

} 
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Setter</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="pico.slate.css">
    <style type="text/css">
        .wrapper{
            width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<article class="outline contrast">
    <a href="index.php"><h1><u>Jim's Gyms | CS 340</u></h1></a>
    <nav>
        <ul>
            <li><a href="index.php">Gyms</a></li>
            <li><a href="routes.php">Routes</a></li>
            <li><a href="setters.php">Setters</a></li>
        </ul>
    </nav>
    </article>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <article class="col-md-12">
                    <div class="page-header">
                        <h3>Update Name for Setter <?php echo $_GET["SetterID"]; ?>, <?php echo $Name; ?> </H3>
                    </div>
                    <p>Please modify the name of the setter.
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
						<div>
                            <label>Setter's Name</label>
                            <input type="text" name="Name" class="form-control" value="<?php echo $Name; ?>">
                            <span style="color:#D93526"><?php echo $Name_err;?></span>
                        </div>
                        <div>
                            <label>Date of Birth</label>
                            <input type="text" value="<?php echo $DOB; ?>" disabled>
                            <span style="color:#8891a4">(Date of Birth Cannot Be Modified)</span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="setters.php" class="btn btn-default">Cancel</a>
                    </form>						
                </article>
            </div>        
        </div>
    </div>
</body>
</html>