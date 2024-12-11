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
            <div class="row">
                <div class="col-md-12">
		    <div>
		        
            </div>
                <?php
                // Include config file
                //error_reporting(E_ALL);
                //ini_set('display_errors', '1');
                $queries = array();
                parse_str($_SERVER['QUERY_STRING'], $queries);
                $selectedgym ='';
                if ($queries['GymID']){
                    $selectedgym = $queries['GymID'];
                }
                if ($selectedgym == ""){
                    echo "<h2>Setters</h2>";
                    echo '<form action="createEmployee.php">';
                    echo    '<input type="submit" value="+ Add Setter" />';
                    echo '</form>';
                    $sql = "SELECT SetterID,Name,DOB,COUNT(GymSetter.GymID) AS Total
                            FROM Setter
                            NATURAL JOIN GymSetter
                            GROUP BY Setter.SetterID;";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th width=15%>SetterID</th>";
                                        echo "<th width=25%>Name</th>";
                                        echo "<th width=20%>Date of Birth</th>";
                                        echo "<th width=10%>Total Gyms</th>";
                                        echo "<th width=25%>Views</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td><b>" . $row['SetterID'] . "</b></td>";
                                        echo "<td>" . $row['Name'] . "</td>";
                                        echo "<td>" . $row['DOB'] . "</td>";
                                        echo "<td>" . $row['Total'] . "</td>";
                                        echo "<td>";
                                        echo '<a href="routes.php?SetterID='.$row["SetterID"].'"><button>Routes</button></a><br>';
                                        echo "</td>";                                    
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
                            echo "<p class='lead'><em>No Setters were Found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. <br>" . mysqli_error($link);
                    }
                    // Close connection
                    mysqli_close($link);
                } else {
                    echo "<h2>Setters from Gym ". $selectedgym ."</h2>";
                    echo '<form action="createEmployee.php">';
                    echo    '<input type="submit" value="+ Add Setter" />';
                    echo '</form>';
                    $sql = "SELECT Setter.SetterID,Name,DOB,Total
                            FROM Setter
                            INNER JOIN(
                                SELECT Setter.SetterID, GymSetter.GymID, COUNT(GymSetter.GymID) AS Total
                                FROM Setter
                                INNER JOIN GymSetter ON Setter.SetterID=GymSetter.SetterID
                                GROUP BY Setter.SetterID
                            ) AS s ON s.SetterID = Setter.SetterID
                            WHERE GymID=$selectedgym;";
                            # The most painful query to figure out -Kali
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th width=15%>SetterID</th>";
                                        echo "<th width=25%>Name</th>";
                                        echo "<th width=20%>Date of Birth</th>";
                                        echo "<th width=10%>Total Gyms</th>";
                                        echo "<th width=25%>Views</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td><b>" . $row['SetterID'] . "</b></td>";
                                        echo "<td>" . $row['Name'] . "</td>";
                                        echo "<td>" . $row['DOB'] . "</td>";
                                        echo "<td>" . $row['Total'] . "</td>";
                                        echo "<td>";
                                        echo '<a href="routes.php?SetterID='.$row["SetterID"].'"><button>Routes</button></a><br>';
                                        echo '<a href="routes.php?GymID='.$selectedgym.'&SetterID='.$row["SetterID"].'"><button class="contrast">Routes + Gym</button></a>';
                                        echo "</td>";                                    
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
                    // Close connection
                    mysqli_close($link);
                }
                ?>
        </article>

</body>
</html>
