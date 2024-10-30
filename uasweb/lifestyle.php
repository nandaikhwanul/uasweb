<?php
// Database connection parameters
$host = "localhost"; // Change if needed
$user = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$database = "berita"; // Name of your database

// Create a connection
$conn = new mysqli($host, $user, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to update the view count
function updateViewCount($conn, $postId) {
    $sql = "UPDATE posts SET view = view + 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error: " . $conn->error); // Error handling
    }
    $stmt->bind_param("i", $postId);
    if ($stmt->execute()) {
        $stmt->close();
        return true; // Successfully updated
    } else {
        echo "Failed to update view count: " . $stmt->error; // Error handling
        $stmt->close();
        return false; // Failed to update
    }
}

// Function to fetch recent lifestyle posts
function fetchRecentLifestylePosts($conn, $limit, $offset) {
    $sql = "SELECT p.id, p.judul, p.isi, p.images, p.view, p.tanggal_publikasi, p.kategori 
            FROM posts p 
            WHERE p.kategori = 'lifestyle' 
            ORDER BY p.tanggal_publikasi DESC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error: " . $conn->error); // Error handling
    }
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    return $stmt->get_result();
}

// Function to fetch trending lifestyle posts
function fetchTrendingLifestylePosts($conn) {
    $sql = "SELECT id, judul, isi, images, view, tanggal_publikasi, kategori 
            FROM posts 
            WHERE kategori = 'lifestyle' 
            ORDER BY view DESC LIMIT 6"; // Adjust limit as needed
    return $conn->query($sql);
}

// Pagination logic
$limit = 6; // Number of posts per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$offset = ($page - 1) * $limit; // Calculate offset

// Fetch recent lifestyle posts and trending lifestyle posts
$recentPosts = fetchRecentLifestylePosts($conn, $limit, $offset);
$trendingPosts = fetchTrendingLifestylePosts($conn);

// Fetch total number of lifestyle posts for pagination
$totalPostsResult = $conn->query("SELECT COUNT(*) as total FROM posts WHERE kategori = 'lifestyle'");
$totalPosts = $totalPostsResult->fetch_assoc()['total'];
$totalPages = ceil($totalPosts / $limit); // Total number of pages

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lifestyle News</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clifford: '#da373d',
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                }
            }
        }

        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('hidden');
            dropdown.classList.toggle('opacity-0');
            dropdown.classList.toggle('transition-all');
            dropdown.classList.toggle('duration-300');
            dropdown.classList.toggle('ease-out');
        }

        // Function to handle scroll and set navbar style
        window.onscroll = function() {
            const navbar = document.getElementById('navbar');
            if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                navbar.classList.add('backdrop-sepia-0', 'bg-white/30', 'backdrop-blur-md', 'z-10', 'md:bg-white/50');
            } else {
                navbar.classList.remove('backdrop-sepia-0', 'bg-white/30', 'backdrop-blur-md', 'z-10', 'md:bg-white/50');
            }
        };
    </script>
</head>
<body class="font-poppins">
        
    <!-- Navbar -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 bg-white shadow-md py-4 z-50 transition-all duration-300">
        <div class="container mx-auto flex justify-between items-center px-6">
            <a href="#" class="text-2xl font-bold">Pemrograman Web</a>
            <ul class="flex space-x-6 items-center">
                <li><a href="index.php" class="text-gray-700 hover:text-gray-900">Home</a></li>
                <li class="relative group">
                    <a href="#" onclick="toggleDropdown()" class="text-gray-700 hover:text-gray-900 flex items-center">
                        Categories
                        <i class="fas fa-chevron-down ml-2"></i>
                    </a>
                    <div id="dropdownMenu" class="absolute left-0 mt-2 hidden bg-white shadow-lg rounded-lg w-48 transition-all duration-300 ease-out opacity-0">
                        <ul class="py-2">
                            <li><a href="technology.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Technology Post</a></li>
                            <li><a href="lifestyle.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Lifestyle Post</a></li>
                        </ul>
                    </div>
                </li>
                <li><a href="crud.php" class="text-gray-700 hover:text-gray-900">Admin Dashboard</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section with Carousel and Trending Posts -->
    <section class="pt-24 pb-12 bg-gray-50">
        <div class="container mx-auto flex flex-col md:flex-row gap-6">
            <!-- Carousel Slider -->
            <div class="w-full md:w-2/3 relative">
                <!-- Carousel Slider -->
<div class="swiper mySwiper h-[590px] text-center">
    <div class="swiper-wrapper">
        <?php while ($recentPost = $recentPosts->fetch_assoc()): ?>
        <div class="swiper-slide">
            <h1 class="text-4xl font-bold uppercase mb-6">
                <a href="artikel.php?id=<?php echo $recentPost['id']; ?>"><?php echo htmlspecialchars($recentPost['judul']); ?></a>
            </h1>
            <div class="flex flex-col items-center">
                <?php if ($recentPost['images']): ?>
                <img src="uploads/<?php echo htmlspecialchars($recentPost['images']); ?>" alt="<?php echo htmlspecialchars($recentPost['judul']); ?>" class="w-full h-96 object-cover mb-4">
                <?php endif; ?>
                <p class="text-gray-600 max-w-xl mb-4"><?php echo nl2br(htmlspecialchars($recentPost['isi'])); ?></p>
                <button class="mt-2 px-6 py-2 bg-black text-white font-semibold rounded-full">Continue Reading</button>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <!-- Add Pagination -->
    <div class="swiper-pagination"></div>
</div>

            </div>

            <!-- Trending Posts -->
           <!-- Trending Posts -->
<div>
    <h2 class="text-xl font-bold mb-4">Trending Posts</h2>
    <div class="h-[600px] overflow-y-scroll bg-white rounded-lg shadow-lg p-4">
        <?php while ($trendingPost = $trendingPosts->fetch_assoc()): ?>
        <div class="mb-2 border-b border-gray-200 pb-2">
            <p class="text-xs text-gray-500 mb-1">Published on: <?php echo date('d F Y', strtotime($trendingPost['tanggal_publikasi'])); ?></p>
            <?php if ($trendingPost['images']): ?>
                <img src="uploads/<?php echo htmlspecialchars($trendingPost['images']); ?>" alt="<?php echo htmlspecialchars($trendingPost['judul']); ?>" class="w-full h-20 object-cover mb-1">
            <?php endif; ?>
            <h3 class="text-sm font-semibold">
                <a href="artikel.php?id=<?php echo $trendingPost['id']; ?>" class="text-lg font-semibold hover:text-blue-600">
                    <?php echo htmlspecialchars($trendingPost['judul']); ?>
                </a>
            </h3>
            <p class="mt-1 text-gray-600 text-xs"><?php echo nl2br(htmlspecialchars($trendingPost['isi'])); ?></p>
            <!-- Display view count -->
            <p class="text-xs text-gray-500 mt-2">Views: <?php echo $trendingPost['view']; ?></p>
        </div>
        <?php endwhile; ?>
    </div>
</div>

    </section>

    <section>
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-center my-8">Recent Lifestyle Posts</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php // Resetting the cursor for recentPosts to display them in the grid layout
            $recentPosts->data_seek(0); // Reset pointer to the beginning of the result set
            while ($post = $recentPosts->fetch_assoc()): ?>
            <div class="border rounded-lg overflow-hidden shadow-md">
                <img src="uploads/<?= htmlspecialchars($post['images']); ?>" alt="<?= htmlspecialchars($post['judul']); ?>" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h2 class="text-xl font-bold"><a href="artikel.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['judul']); ?></a></h2>
                    <p class="text-gray-600"><?= substr($post['isi'], 0, 100); ?>...</p>
                    <div class="text-sm text-gray-500 mt-2">Category: <?= htmlspecialchars($post['kategori']); ?></div>
                    <div class="text-sm text-gray-500 mt-2">Views: <?= $post['view']; ?></div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="mt-6 flex justify-center items-center h-72">
  <!-- Previous Button -->
  <a href="?page=<?php echo max(1, $page - 1); ?>" class="px-3 py-1 border border-gray-400 text-gray-700 rounded-lg <?php echo $page == 1 ? 'cursor-not-allowed opacity-50' : ''; ?>" <?php echo $page == 1 ? 'disabled' : ''; ?>>
    &laquo; Previous
  </a>

  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?page=<?php echo $i; ?>" class="px-3 py-1 border border-gray-400 text-gray-700 rounded-lg <?php echo $page == $i ? 'bg-blue-500 text-white' : ''; ?>">
      <?php echo $i; ?>
    </a>
  <?php endfor; ?>

  <!-- Next Button -->
  <a href="?page=<?php echo min($totalPages, $page + 1); ?>" class="px-3 py-1 border border-gray-400 text-gray-700 rounded-lg <?php echo $page == $totalPages ? 'cursor-not-allowed opacity-50' : ''; ?>" <?php echo $page == $totalPages ? 'disabled' : ''; ?>>
    Next &raquo;
  </a>
</div>
    </div>
</main>

<script>
    const swiper = new Swiper('.mySwiper', {
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      autoplay: {
        delay: 1500, // Time in milliseconds before moving to the next slide
        disableOnInteraction: false, // Autoplay will not be disabled after user interactions
      },
    });
</script>
</body>
</html>
