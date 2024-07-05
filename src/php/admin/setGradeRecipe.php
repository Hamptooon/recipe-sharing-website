<?php
header('Content-Type: application/json');
require '../../php/dbConnect.php';

$response = ['success' => false, 'message' => '', 'grade' => 0];

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipe_id']) && isset($_POST['grade'])) {
    $recipe_id = (int) $_POST['recipe_id'];
    $new_rating = (int) $_POST['grade'];

    // // if ($new_rating < 1 || $new_rating > 5) {
    // //     $response['message'] = "Неверная оценка.";
    // //     echo json_encode($response);
    // //     exit;
    // // }

    // // $result = $mysqli->query("UPDATE recipes SET grade = $new_rating WHERE recipe_id = ?");
    // $stmt = $mysqli->prepare("UPDATE recipes SET grade = ? WHERE recipe_id = ?");
    // $stmt->bind_param("ssiii", $new_rating, $recipe_id);
    // $stmt->execute();

    // if ($result->num_rows > 0) {
    //     $row = $result->fetch_assoc();
    //     $current_rating = $row['grade'];

    //     $total_rating = ($current_rating  + $new_rating) / 2;

    //     $updateStmt = $mysqli->prepare("UPDATE recipes SET grade = ? WHERE recipe_id = ?");
    //     $updateStmt->bind_param('dii', $total_rating, $recipe_id);
        
    //     $updateStmt->execute();
    //         $response['current_rating'] = $current_rating;
    //         $response['success'] = true;
    //         $response['grade'] = $total_rating;
    $result = $mysqli->query("SELECT * FROM recipes WHERE recipe_id = $recipe_id");
    $row = $result->fetch_assoc();
    $current_rating = $row['grade'];

    $total_rating = ($current_rating  + $new_rating) / 2;
    $stmt = $mysqli->prepare("UPDATE recipes SET grade = ? WHERE recipe_id = ?");
    $stmt->bind_param("ii", $new_rating, $recipe_id);
    $stmt->execute();
    // } else {
        $response['current_rating'] = $current_rating;
        $response['new_rating'] = $total_rating;
        $response['recipe_id'] = $recipe_id;
    // }
// } else {
//     $response['message'] = "Неверный запрос.";
// }

// $mysqli->close();
echo json_encode($response);
?>
