<?php
	session_start();
	//$currentpage="View Employees"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jim's Gyms</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<style type="text/css">
        .wrapper{
            width: 70%;
            margin:0 auto;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
		 $('.selectpicker').selectpicker();
    </script>
</head>
<body>
    <?php
        // Include config file
        require_once "config.php";
//		include "header.php";
	?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
		    <div class="page-header clearfix">
		     <h2> Jim's Gyms | CS 340 </h2> 
		       <h2 class="pull-left">Gyms</h2>
                        <a href="createEmployee.php" class="btn btn-success pull-right">Add New Employee</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Attempt select all employee query execution
					// *****
					// Insert your function for Salary Level
					/*
						$sql = "SELECT Ssn,Fname,Lname,Salary, Address, Bdate, PayLevel(Ssn) as Level, Super_ssn, Dno
							FROM EMPLOYEE";
					*/
                    $sql = "SELECT RouteID,Created,Difficulty,Color, OffColor, GymID
							FROM Route";
                    $difficulties = array(1 => '#c6ffc0', 2 => '#c3f7ad', 3 => '#c3ef9a', 4 => '#c4e687',
                                        5 => '#c6dd74', 6 => '#cad361', 7 => '#cec94f', 8=>'#d4be3e', 9=> '#d9b22d',
                                    10 => '#dfa51c', 11 => '#e5980d', 12 => '#eb8902', 13 => '#f7660d', 14 => '#fb5019',
                                15 => '#fc633c', 16=>'#ff5043');
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th width=10%>Route Number</th>";
                                        echo "<th width=10%>Gym</th>";
                                        echo "<th width=10%>Difficulty</th>";
                                        echo "<th width=25%>Color</th>";
										echo "<th width=25%>Secondary Color</th>";
                                        echo "<th width=10%>Created</th>";
										
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'><b>" . $row['RouteID'] . "</b></td>";
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'>" . $row['GymID'] . "</td>";
                                        echo "<td bgcolor='" . $difficulties[$row['Difficulty']] . "'>" . $row['Difficulty'] . "</td>";
										echo "<td bgcolor='" . $row['Color'] . "'>" . $row['Color'] . "</td>";									
										echo "<td bgcolor='" . ($row['OffColor'] ? $row['OffColor'] : 'transparent') . "'>" . $row['OffColor'] . "</td>";	
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'>" . $row['Created'] . "</td>";										
                                        //echo "<td>";
                                        //    echo "<a href='viewProjects.php?Ssn=". $row['Ssn']."&Lname=".$row['Lname']."' title='View Projects' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                        //    echo "<a href='updateEmployee.php?Ssn=". $row['Ssn'] ."' title='Update Record' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                        //    echo "<a href='deleteEmployee.php?Ssn=". $row['Ssn'] ."' title='Delete Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
										//	echo "<a href='viewDependents.php?Ssn=". $row['Ssn']."&Lname=".$row['Lname']."' title='View Dependents' data-toggle='tooltip'><span class='glyphicon glyphicon-user'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No Routes were Found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. <br>" . mysqli_error($link);
                    }
					echo "<br> <h2> Department Stats </h2> <br>";
					
                    // Select Department Stats
					// You will need to Create a DEPT_STATS table
					
                    $sql2 = "SELECT * FROM DEPT_STATS";
                    if($result2 = mysqli_query($link, $sql2)){
                        if(mysqli_num_rows($result2) > 0){
                            echo "<div class='col-md-4'>";
							echo "<table width=30% class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th width=20%>Dno</th>";
                                        echo "<th width = 20%>Number of Employees</th>";
                                        echo "<th width = 40%>Average Salary</th>";
	
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result2)){
                                    echo "<tr>";
                                        echo "<td>" . $row['Dnumber'] . "</td>";
                                        echo "<td>" . $row['Emp_count'] . "</td>";
                                        echo "<td>" . $row['Avg_salary'] . "</td>";
               
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result2);
                        } else{
                            echo "<p class='lead'><em>No records were found for Dept Stats.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql2. <br>" . mysqli_error($link);
                    }
					
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>

</body>
</html>
