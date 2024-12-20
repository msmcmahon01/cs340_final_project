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

                if (isset($_POST['deleteRoutes'])) {
                    $deleteDate = $_POST['deleteDate'];
                    $deleteGym = $_POST['deleteGym'];

                    // Debugging: Print out the values being passed
                    echo "<p>Deleting routes on date: $deleteDate for Gym ID: $deleteGym</p>";

                    // Check for matching records before deletion
                    $checkSql = "SELECT RouteID FROM Route WHERE Created = '$deleteDate' AND GymID = $deleteGym;";
                    if ($checkResult = mysqli_query($link, $checkSql)) {
                        if (mysqli_num_rows($checkResult) > 0) {
                            echo "<p>Routes found for deletion:</p>";
                            while ($row = mysqli_fetch_array($checkResult)) {
                                echo "<p>Route ID: " . $row['RouteID'] . "</p>";
                            }

                            // Call the stored procedure to delete routes
                            $sql = "CALL DeleteRoutesByDate('$deleteDate', $deleteGym);";

                            if (mysqli_query($link, $sql)) {
                                echo "<p>Routes created on $deleteDate at Gym $deleteGym have been deleted.</p>";
                            } else {
                                echo "ERROR: Could not execute $sql. " . mysqli_error($link);
                            }
                        } else {
                            echo "<p>No routes found for the specified date and gym.</p>";
                        }
                        mysqli_free_result($checkResult);
                    } else {
                        echo "ERROR: Could not execute $checkSql. " . mysqli_error($link);
                    }
                    exit();
                }

                $queries = array();
                parse_str($_SERVER['QUERY_STRING'], $queries);
                $selectedgym ='';
                $selectedsetter ='';
                if ($queries['GymID']){
                    $selectedgym = $queries['GymID'];
                }
                if ($queries['SetterID']){
                    $selectedsetter = $queries['SetterID'];
                }
                if ($selectedgym == "" && $selectedsetter ==""){
                    # No selections
                    echo "<h2>Routes</h2>";

                    $sql = "SELECT Route.RouteID,Setter.Name,Created,Difficulty,Color, OffColor, GymID, COALESCE(SUM(RouteHold.Amount),0) AS Total
                            FROM Route
                            NATURAL JOIN RouteHold
                            NATURAL JOIN RouteSetter
                            NATURAL JOIN Setter
                            GROUP BY Route.RouteID
                            ORDER BY Route.RouteID;";
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
                                        echo "<th width=20%>Setter</th>";
                                        echo "<th width=10%>Color</th>";
                                        echo "<th width=10%>Secondary Color</th>";
                                        echo "<th width=10%>Total Holds</th>";
                                        echo "<th width=15%>Created</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'><b>" . $row['RouteID'] . "</b></td>";
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'>" . $row['GymID'] . "</td>";
                                        echo "<td bgcolor='" . $difficulties[$row['Difficulty']] . "'>" . $row['Difficulty'] . "</td>";
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'>" . $row['Name'] . "</td>";
                                        echo "<td bgcolor='" . $row['Color'] . "'>" . $row['Color'] . "</td>";
                                        echo "<td bgcolor='" . ($row['OffColor'] ? $row['OffColor'] : 'transparent') . "'>" . $row['OffColor'] . "</td>";
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'>" . $row['Total'] . "</td>";
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
                    // Close connection
                    mysqli_close($link);
                } elseif ($selectedgym!='' && $selectedsetter=='') {
                    # Only Gym
                    echo "<h2>Routes from Gym ". $selectedgym ."</h2>";
                    $sql = "SELECT Route.RouteID,Setter.Name,Created,Difficulty,Color, OffColor, GymID, COALESCE(SUM(RouteHold.Amount),0) AS Total
                            FROM Route
                            NATURAL JOIN RouteHold
                            NATURAL JOIN RouteSetter
                            NATURAL JOIN Setter
                            WHERE Route.GymID=$selectedgym
                            GROUP BY Route.RouteID
                            ORDER BY Route.RouteID;";
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
                                        echo "<th width=20%>Setter</th>";
                                        echo "<th width=10%>Color</th>";
                                        echo "<th width=10%>Secondary Color</th>";
                                        echo "<th width=10%>Total Holds</th>";
                                        echo "<th width=15%>Created</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'><b>" . $row['RouteID'] . "</b></td>";
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'>" . $row['GymID'] . "</td>";
                                        echo "<td bgcolor='" . $difficulties[$row['Difficulty']] . "'>" . $row['Difficulty'] . "</td>";
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'>" . $row['Name'] . "</td>";
                                        echo "<td bgcolor='" . $row['Color'] . "'>" . $row['Color'] . "</td>";
                                        echo "<td bgcolor='" . ($row['OffColor'] ? $row['OffColor'] : 'transparent') . "'>" . $row['OffColor'] . "</td>";
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'>" . $row['Total'] . "</td>";
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'>" . $row['Created'] . "</td>";

                                        echo "<td>";
                                            $hsc = htmlspecialchars(basename($_SERVER['REQUEST_URI']));
                                            $query_string = http_build_query(array(
                                                'deleteDate' => $row['Created'],
                                                'deleteGym' => $row['GymID'],
                                                'deleteRoutes' => 'Delete Routes On Date'
                                            ));
                                            $deleteurl = $hsc . '?' . $query_string;
                                            echo '<form action="' . $deleteurl . '"method="post">';
                                            echo '<input type="hidden" name="deleteDate" value="'.$row['Created'].'">';
                                            echo '<input type="hidden" name="deleteGym" value="'.$row['GymID'].'">';
                                            echo '<input type="submit" name="deleteRoutes" value="Delete Routes On Date">';
                                            echo "</form>";
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
                    mysqli_close($link);

                    echo "<h2>Delete Routes</h2>";
                    echo '<form action="" method="post">';
                    echo '<label for="deleteDate">Select Date:</label>';
                    echo '<input type="date" id="deleteDate" name="deleteDate" required>';
                    echo '<input type="hidden" name="deleteGym" value="' . $selectedgym . '">';
                    echo '<input type="submit" name="deleteRoutes" value="Delete Routes">';
                    echo '</form>';

                    // Close connection
                    mysqli_close($link);
                } elseif ($selectedgym=='' && $selectedsetter!='') {
                    # Only Setter
                    echo "<h2>Routes from Setter ". $selectedsetter ."</h2>";
                    $sql = "SELECT Route.RouteID,Setter.Name,Created,Difficulty,Color, OffColor, GymID, COALESCE(SUM(RouteHold.Amount),0) AS Total
                            FROM Route
                            NATURAL JOIN RouteHold
                            NATURAL JOIN RouteSetter
                            NATURAL JOIN Setter
                            WHERE Setter.SetterID=$selectedsetter
                            GROUP BY Route.RouteID
                            ORDER BY Route.RouteID;";
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
                                        echo "<th width=20%>Setter</th>";
                                        echo "<th width=10%>Color</th>";
                                        echo "<th width=10%>Secondary Color</th>";
                                        echo "<th width=10%>Total Holds</th>";
                                        echo "<th width=15%>Created</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'><b>" . $row['RouteID'] . "</b></td>";
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'>" . $row['GymID'] . "</td>";
                                        echo "<td bgcolor='" . $difficulties[$row['Difficulty']] . "'>" . $row['Difficulty'] . "</td>";
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'>" . $row['Name'] . "</td>";
                                        echo "<td bgcolor='" . $row['Color'] . "'>" . $row['Color'] . "</td>";									
                                        echo "<td bgcolor='" . ($row['OffColor'] ? $row['OffColor'] : 'transparent') . "'>" . $row['OffColor'] . "</td>";
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'>" . $row['Total'] . "</td>";	
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'>" . $row['Created'] . "</td>";	
                                                                            
                                        echo "<td>";
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
                    mysqli_close($link);
                } else {
                    # Gym and Setter
                    echo "<h2>Routes from Gym ". $selectedgym ." and Setter ". $selectedsetter ."</h2>";
                    $sql = "SELECT Route.RouteID,Setter.Name,Created,Difficulty,Color, OffColor, GymID, COALESCE(SUM(RouteHold.Amount),0) AS Total
                            FROM Route
                            NATURAL JOIN RouteHold
                            NATURAL JOIN RouteSetter
                            NATURAL JOIN Setter
                            WHERE Route.GymID=$selectedgym AND Setter.SetterID=$selectedsetter
                            GROUP BY Route.RouteID
                            ORDER BY Route.RouteID;";
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
                                        echo "<th width=20%>Setter</th>";
                                        echo "<th width=10%>Color</th>";
                                        echo "<th width=10%>Secondary Color</th>";
                                        echo "<th width=10%>Total Holds</th>";
                                        echo "<th width=15%>Created</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'><b>" . $row['RouteID'] . "</b></td>";
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'>" . $row['GymID'] . "</td>";
                                        echo "<td bgcolor='" . $difficulties[$row['Difficulty']] . "'>" . $row['Difficulty'] . "</td>";
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'>" . $row['Name'] . "</td>";
                                        echo "<td bgcolor='" . $row['Color'] . "'>" . $row['Color'] . "</td>";									
                                        echo "<td bgcolor='" . ($row['OffColor'] ? $row['OffColor'] : 'transparent') . "'>" . $row['OffColor'] . "</td>";
                                        echo "<td bgcolor='" . ($row['GymID']%2 ? 'FFFFFF':'F1F1F1'). "'>" . $row['Total'] . "</td>";	
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
                    mysqli_close($link);
                }
                ?>
        </article>

</body>
</html>
