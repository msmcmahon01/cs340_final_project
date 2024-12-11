<?php
require_once "config.php";

//check if GymID is set in  get request
if (isset($_GET['GymID'])) {
    $gymID = intval($_GET['GymID']);

    mysqli_begin_transaction($link);

    try {
        //delete all routes associated with this gym
        $deleteRoutesSql = "DELETE FROM Route WHERE GymID = ?";
        $stmt = mysqli_prepare($link, $deleteRoutesSql);
        mysqli_stmt_bind_param($stmt, "i", $gymID);
        mysqli_stmt_execute($stmt);

        //delete the gym
        $deleteGymSql = "DELETE FROM Gym WHERE GymID = ?";
        $stmt = mysqli_prepare($link, $deleteGymSql);
        mysqli_stmt_bind_param($stmt, "i", $gymID);
        mysqli_stmt_execute($stmt);

        mysqli_commit($link);

        //redirect to the gyms page with a success message
        header("Location: index.php?message=Gym%20deleted%20successfully");
        exit;
    } catch (Exception $e) {
        //rollback transaction on error
        mysqli_rollback($link);

        //redirect to the gyms page with an error message
        header("Location: index.php?error=Could%20not%20delete%20gym");
        exit;
    }
} else {
    //redirect to the gyms page if GymID is not set
    header("Location: index.php?error=GymID%20not%20specified");
    exit;
}
?>
