<?php
// Database connection parameters
$host = "localhost"; // Change if needed
$user = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$database = "berita"; // Name of your database

// Create a connection
$conn = new mysqli($host, $user, $password, $database);
require_once 'functions.php'; // Use require_once to prevent redeclaration
// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch specific post based on ID
if (isset($_GET['id'])) {
    $postId = (int)$_GET['id'];
    $sql = "SELECT * FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $stmt->close();

    // If post not found
    if (!$post) {
        echo "<p>Post not found.</p>";
        exit;
    }
} else {
    echo "<p>No post selected.</p>";
    exit;
}

// Pagination setup
$limit = 5; // Number of posts per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$offset = ($page - 1) * $limit; // Calculate offset

// Fetch recent posts
$recentPosts = fetchRecentPosts($conn, $limit, $offset);
$trendingPosts = fetchTrendingPosts($conn);

// Get total number of posts for pagination
$totalPostsResult = $conn->query("SELECT COUNT(*) as count FROM posts");
$totalPosts = $totalPostsResult->fetch_assoc()['count'];
$totalPages = ceil($totalPosts / $limit);

// Update view count if a specific post is accessed
if (isset($_GET['id'])) {
    $postId = (int)$_GET['id'];
    updateViewCount($conn, $postId);
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo htmlspecialchars($post['judul']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 overflow-hidden">

    <!-- Navbar -->
    <header class="bg-white shadow">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <a href="index.php" class="text-2xl font-semibold text-gray-700 hover:text-blue-500">
                    <span class="fa fa-pencil-square-o"></span> Web Programming Blog
                </a>
            </div>
        </div>
    </header>

    <!-- Post Content -->
    <div class="container mx-auto px-6 py-8">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-4"><?php echo htmlspecialchars($post['judul']); ?></h2>
            <p class="text-gray-600 leading-relaxed mb-6"><?php echo nl2br(htmlspecialchars($post['isi'])); ?></p>
            
            <!-- Display Image if available -->
            <?php if ($post['images']): ?>
                <img src="<?php echo htmlspecialchars($post['images']); ?>" alt="Image" class="w-[250px] h-auto max-w-lg mx-auto mb-6 rounded-lg shadow-lg">
            <?php endif; ?>

            <!-- Additional Info -->
            <div class="text-gray-600 text-sm">
                <p><strong>Author:</strong> <?php echo htmlspecialchars($post['author']); ?></p>
                <p><strong>Published on:</strong> <?php echo htmlspecialchars($post['tanggal_publikasi']); ?></p>
                <p><strong>Views:</strong> <?php echo htmlspecialchars($post['view']); ?></p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white shadow mt-10">
        <div class="container mx-auto px-6 py-4">
            <p class="text-center text-gray-600">© 2024 Your Blog. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>

