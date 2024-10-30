<?php
// Koneksi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "berita";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Inisialisasi variabel
$id = $judul = $isi = $kategori = $author = $tanggal_publikasi = $images = '';
$success_message = $error_message = '';

// Handle form submission (Create & Update)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $kategori = $_POST['kategori'];
    $author = $_POST['author'];
    $tanggal_publikasi = $_POST['tanggal_publikasi'];
    $images = '';

    // Mengupload gambar
    if (isset($_FILES['images']) && $_FILES['images']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["images"]["name"]);
        
        // Cek apakah file gambar adalah gambar
        $check = getimagesize($_FILES["images"]["tmp_name"]);
        if ($check === false) {
            $error_message = "File yang diupload bukan gambar.";
        } else {
            // Cek ukuran file (misalnya maksimum 2MB)
            if ($_FILES["images"]["size"] > 2000000) {
                $error_message = "Ukuran file terlalu besar.";
            } else {
                // Pindahkan file ke folder tujuan
                if (move_uploaded_file($_FILES["images"]["tmp_name"], $target_file)) {
                    $images = basename($_FILES["images"]["name"]); // Simpan nama file gambar
                } else {
                    $error_message = "Gagal mengupload file.";
                }
            }
        }
    } else {
        if ($_FILES['images']['error'] != 4) { // Cek jika ada kesalahan lain
            $error_message = "Gagal mengupload file: " . $_FILES['images']['error'];
        }
    }

    if (empty($error_message)) {
        if (isset($_POST['create'])) {
            $stmt = $conn->prepare("INSERT INTO posts (judul, isi, kategori, author, tanggal_publikasi, images) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssss', $judul, $isi, $kategori, $author, $tanggal_publikasi, $images);

            if ($stmt->execute()) {
                $success_message = "Post berhasil ditambahkan!";
            } else {
                $error_message = "Gagal menambahkan post: " . $stmt->error;
            }
            $stmt->close();
        } elseif (isset($_POST['update'])) {
            $id = $_POST['id'];
            $stmt = $conn->prepare("UPDATE posts SET judul=?, isi=?, kategori=?, author=?, tanggal_publikasi=?, images=? WHERE id=?");
            $stmt->bind_param('ssssssi', $judul, $isi, $kategori, $author, $tanggal_publikasi, $images, $id);

            if ($stmt->execute()) {
                $success_message = "Post berhasil diupdate!";
            } else {
                $error_message = "Gagal mengupdate post: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Handle edit action
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $result = $conn->query("SELECT * FROM posts WHERE id=$id");

    if ($result && $post = $result->fetch_assoc()) {
        $judul = $post['judul'];
        $isi = $post['isi'];
        $kategori = $post['kategori'];
        $author = $post['author'];
        $tanggal_publikasi = $post['tanggal_publikasi'];
        $images = $post['images'];
    } else {
        $error_message = "Post tidak ditemukan.";
    }
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($conn->query("DELETE FROM posts WHERE id=$id")) {
        $success_message = "Post berhasil dihapus!";
    } else {
        $error_message = "Gagal menghapus post: " . $conn->error;
    }
}

// Baca semua post
$result = $conn->query("SELECT * FROM posts");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Blog</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f0f5;
        }
    </style>
</head>
<body>

<div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold text-center mb-6">Blog Posts</h1>

    <?php if ($success_message): ?>
        <div class="bg-green-200 text-green-800 p-4 rounded mb-4 text-center"><?php echo $success_message; ?></div>
    <?php elseif ($error_message): ?>
        <div class="bg-red-200 text-red-800 p-4 rounded mb-4 text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="mb-6">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-2">Judul</label>
                <input type="text" name="judul" value="<?php echo $judul; ?>" required class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div>
                <label class="block font-semibold mb-2">Kategori</label>
                <select name="kategori" class="w-full p-2 border border-gray-300 rounded">
                    <option value="Technology" <?php echo ($kategori == 'Technology') ? 'selected' : ''; ?>>Technology</option>
                    <option value="Lifestyle" <?php echo ($kategori == 'Lifestyle') ? 'selected' : ''; ?>>Lifestyle</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-2">Author</label>
                <input type="text" name="author" value="<?php echo $author; ?>" required class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div>
                <label class="block font-semibold mb-2">Tanggal Publikasi</label>
                <input type="date" name="tanggal_publikasi" value="<?php echo $tanggal_publikasi; ?>" required class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="col-span-2">
                <label class="block font-semibold mb-2">Isi</label>
                <textarea name="isi" required class="w-full p-2 border border-gray-300 rounded"><?php echo $isi; ?></textarea>
            </div>
            <div>
                <label class="block font-semibold mb-2">Gambar</label>
                <input type="file" name="images" accept="image/*" required class="w-full p-2 border border-gray-300 rounded">
            </div>
        </div>
        <button type="submit" name="<?php echo $id ? 'update' : 'create'; ?>" class="mt-4 bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
            <?php echo $id ? 'Update' : 'Create'; ?>
        </button>
    </form>
        
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="bg-gray-100">
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">Judul</th>
                <th class="py-2 px-4 border-b">Isi</th>
                <th class="py-2 px-4 border-b">Kategori</th>
                <th class="py-2 px-4 border-b">Author</th>
                <th class="py-2 px-4 border-b">Tanggal Publikasi</th>
                <th class="py-2 px-4 border-b">Gambar</th>
                <th class="py-2 px-4 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="py-2 px-4 border-b"><?php echo $row['id']; ?></td>
                    <td class="py-2 px-4 border-b"><?php echo $row['judul']; ?></td>
                    <td class="py-2 px-4 border-b"><?php echo $row['isi']; ?></td>
                    <td class="py-2 px-4 border-b"><?php echo $row['kategori']; ?></td>
                    <td class="py-2 px-4 border-b"><?php echo $row['author']; ?></td>
                    <td class="py-2 px-4 border-b"><?php echo $row['tanggal_publikasi']; ?></td>
                    <td class="py-2 px-4 border-b">
                        <img src="uploads/<?php echo $row['images']; ?>" alt="Image" class="w-16 h-16 object-cover">
                    </td>
                    <td class="py-2 px-4 border-b">
                        <a href="?edit=<?php echo $row['id']; ?>" class="text-blue-500 hover:underline">Edit</a>
                        <a href="?delete=<?php echo $row['id']; ?>" class="text-red-500 hover:underline ml-2" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php $conn->close(); ?>
