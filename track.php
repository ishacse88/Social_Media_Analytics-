<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Engagement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Enter Engagement Data</h2>
        <form method="POST" action="track.php">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Platform</label>
                <input type="text" name="platform" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Post ID</label>
                <input type="text" name="post_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Likes</label>
                <input type="number" name="likes" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Comments</label>
                <input type="number" name="comments" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Shares</label>
                <input type="number" name="shares" class="form-control">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <?php
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $platform = $_POST['platform'];
        $post_id = $_POST['post_id'];
        $likes = $_POST['likes'];
        $comments = $_POST['comments'];
        $shares = $_POST['shares'];

        $user_check_sql = "SELECT user_id FROM users WHERE username = '$username' AND platform = '$platform'";
        $user_check_result = $conn->query($user_check_sql);

        if ($user_check_result && $user_check_result->num_rows > 0) {
            // User exists, get user_id
            $user_row = $user_check_result->fetch_assoc();
            $user_id = $user_row['user_id'];
        } else {
            // User does not exist, insert new user
            $insert_user_sql = "INSERT INTO users (username, platform) VALUES ('$username', '$platform')";
            if ($conn->query($insert_user_sql) === TRUE) {
                $user_id = $conn->insert_id; // Get the newly inserted user_id
            } else {
                echo "<div class='alert alert-danger'>Error inserting user: " . $conn->error . "</div>";
                exit;
            }
        }

        // Insert engagement data
        $insert_engagement_sql = "INSERT INTO engagement (user_id, post_id, likes, comments, shares)
                                  VALUES ('$user_id', '$post_id', '$likes', '$comments', '$shares')";
        if ($conn->query($insert_engagement_sql) === TRUE) {
            echo "<div class='alert alert-success'>Engagement data inserted successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error inserting engagement: " . $conn->error . "</div>";
        }
    }

    // Close the connection after all operations are complete
    $conn->close();
    ?>
</body>
</html>
