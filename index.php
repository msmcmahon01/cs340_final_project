<?php
	session_start();
	//$currentpage="View Employees"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jim's Gyms</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="pico.slate.css">
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
        require_once "config.php";
	?>
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
    <article class="wrapper">
        <div class="container-fluid">
		    <div>
		        <h2>Gyms</h2>
                <form action="createEmployee.php">
                    <input type="submit" value="+ Add Gym" />
                </form>
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
                $sql = "SELECT GymID,Street,State,City,Zip
                        FROM Gym";
                if($result = mysqli_query($link, $sql)){
                    if(mysqli_num_rows($result) > 0){
                        echo "<table class='striped'>";
                            echo "<thead>";
                                echo "<tr>";
                                    echo "<th width=10%>Gym ID</th>";
                                    echo "<th width=20%>Street</th>";
                                    echo "<th width=10%>State</th>";
                                    echo "<th width=10%>City</th>";
                                    echo "<th width=20%>Zip</th>";
                                    echo "<th width=20%>Views</th>";
                                echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while($row = mysqli_fetch_array($result)){
                                echo "<tr>";
                                    echo "<td><b>" . $row['GymID'] . "</b></td>";
                                    echo "<td>" . $row['Street'] . "</td>";
                                    echo "<td>" . $row['State'] . "</td>";
                                    echo "<td>" . $row['City'] . "</td>";									
                                    echo "<td>" . $row['Zip'] . "</td>";									
                                    echo "<td>";
                                    echo '<a href="routes.php?GymID='.$row["GymID"].'"><button>Routes</button></a><br>';
                                    echo '<a href="setters.php?GymID='.$row["GymID"].'"><button class="contrast">Setters</button></a>';
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
                echo "<h2>Statistics</h2>";
                $sql2 = "SELECT Gym.GymID, COUNT(Route.RouteId) AS Route_Count, AVG(Route.Difficulty) AS Average_Difficulty
                            FROM Gym
                            NATURAL JOIN Route
                            GROUP BY Gym.GymID;";
                if($result = mysqli_query($link, $sql2)){
                    if(mysqli_num_rows($result) > 0){
                        echo "<table class='striped'>";
                            echo "<thead>";
                                echo "<tr>";
                                    echo "<th width=20%>Gym ID</th>";
                                    echo "<th width=40%>Route Count</th>";
                                    echo "<th width=40%>Average Difficulty</th>";
                                echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while($row = mysqli_fetch_array($result)){
                                echo "<tr>";
                                    echo "<td><b>" . $row['GymID'] . "</b></td>";
                                    echo "<td>" . $row['Route_Count'] . "</td>";
                                    echo "<td>" . $row['Average_Difficulty'] . "</td>";
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
                // Close connection
                mysqli_close($link);
                ?>
    </article>
</body>
</html>
