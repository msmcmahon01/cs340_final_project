<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$Street = $State = $City = $Zip = "";
$Street_err = $State_err = $City_err = $Zip_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate Street
    $Street = trim($_POST["Street"]);
    if(empty($Street)){
        $Street_err = "Please enter a street.";
    }

    // Validate State (2-letter abbreviation)
    $State = trim($_POST["State"]);
    if(empty($State)){
        $State_err = "Please enter a state.";
    } elseif(!preg_match("/^[A-Z]{2}$/", $State)){
        $State_err = "Please enter a valid 2-letter state abbreviation.";
    }

    // Validate City
    $City = trim($_POST["City"]);
    if(empty($City)){
        $City_err = "Please enter a city.";
    }

    // Validate Zip
    $Zip = trim($_POST["Zip"]);
    if(empty($Zip)){
        $Zip_err = "Please enter a zip code.";
    } elseif(!is_numeric($Zip)){
        $Zip_err = "Please enter a valid zip code.";
    }

    // Check input errors before inserting into database
    if(empty($Street_err) && empty($State_err) && empty($City_err) && empty($Zip_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO Gym (Street, State, City, Zip) VALUES (?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_Street, $param_State, $param_City, $param_Zip);

            // Set parameters
            $param_Street = $Street;
            $param_State = $State;
            $param_City = $City;
            $param_Zip = $Zip;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Retrieve the GymID
                $newGymID = mysqli_insert_id($link);

                // Redirect to landing page
                header("location: index.php?GymID=$newGymID");
                exit();
            } else{
                echo "Error: Could not execute the query: $sql. " . mysqli_error($link);
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
    <title>Add Gym</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Add Gym</h2>
                    </div>
                    <p>Please fill this form and submit to add a new gym to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($Street_err)) ? 'has-error' : ''; ?>">
                            <label>Street</label>
                            <input type="text" name="Street" class="form-control" value="<?php echo $Street; ?>">
                            <span class="help-block"><?php echo $Street_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($State_err)) ? 'has-error' : ''; ?>">
                            <label>State (2-letter abbreviation)</label>
                            <input type="text" name="State" class="form-control" value="<?php echo $State; ?>">
                            <span class="help-block"><?php echo $State_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($City_err)) ? 'has-error' : ''; ?>">
                            <label>City</label>
                            <input type="text" name="City" class="form-control" value="<?php echo $City; ?>">
                            <span class="help-block"><?php echo $City_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($Zip_err)) ? 'has-error' : ''; ?>">
                            <label>Zip</label>
                            <input type="text" name="Zip" class="form-control" value="<?php echo $Zip; ?>">
                            <span class="help-block"><?php echo $Zip_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
