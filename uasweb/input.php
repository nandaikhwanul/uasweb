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

// Array untuk kategori
$kategoris = ['Lifestyle', 'Technology'];

// Data untuk kategori Lifestyle
$lifestyle_posts = [
    ["Menjaga Kesehatan Mental di Era Digital", "Dalam dunia yang semakin terhubung melalui teknologi, menjaga kesehatan mental menjadi lebih penting dari sebelumnya."],
    ["Tips Sehat untuk Makanan Ringan", "Makanan ringan tidak selalu harus tidak sehat."],
    ["Membangun Rutinitas Pagi yang Efektif", "Rutinitas pagi yang baik dapat meningkatkan produktivitas Anda sepanjang hari."],
    ["Cara Mengatur Keuangan Pribadi dengan Bijak", "Mengatur keuangan pribadi bisa menjadi tantangan."],
    ["Manfaat Olahraga bagi Kesehatan Tubuh", "Olahraga adalah kunci untuk menjaga kesehatan tubuh dan pikiran."],
];

// Data untuk kategori Technology
$technology_posts = [
    ["Inovasi Terbaru dalam Teknologi Smartphone", "Teknologi smartphone terus berkembang dengan pesat."],
    ["Mengapa Kecerdasan Buatan Penting untuk Bisnis?", "Kecerdasan buatan (AI) semakin menjadi bagian penting dalam strategi bisnis."],
    ["Panduan Lengkap Memilih Laptop untuk Pelajar", "Memilih laptop yang tepat sangat penting bagi pelajar."],
    ["Teknologi Blockchain dan Masa Depan Keamanan Data", "Teknologi blockchain menawarkan solusi inovatif untuk masalah keamanan data."],
    ["Internet of Things: Mengubah Cara Kita Hidup", "Internet of Things (IoT) semakin mengubah cara kita berinteraksi dengan dunia sekitar."],
];

// Fungsi untuk menghasilkan nama penulis acak
function generateAuthorName() {
    $names = ['Alice', 'Bob', 'Charlie', 'David', 'Eve', 'Frank', 'Grace', 'Hannah', 'Isaac', 'Jack'];
    return $names[array_rand($names)];
}

// Gabungkan data untuk 20 post
$posts = [];
$total_needed = 20; // Total jumlah posting yang diinginkan
$image_counter = 1; // Mengatur penomoran gambar dari 1 hingga 20

while (count($posts) < $total_needed) {
    // Pilih acak dari lifestyle
    if (count($posts) < $total_needed) {
        $post = $lifestyle_posts[array_rand($lifestyle_posts)];
        $posts[] = array_merge($post, [
            'kategori' => 'Lifestyle',
            'image' => "assets/images/$image_counter.jpg", // Gambar dari direktori lokal
            'author' => generateAuthorName(),
            'tanggal_publikasi' => date('Y-m-d', strtotime("-" . rand(1, 30) . " days"))
        ]);
        $image_counter++;
    }

    // Pilih acak dari technology
    if (count($posts) < $total_needed) {
        $post = $technology_posts[array_rand($technology_posts)];
        $posts[] = array_merge($post, [
            'kategori' => 'Technology',
            'image' => "assets/images/$image_counter.jpg", // Gambar dari direktori lokal
            'author' => generateAuthorName(),
            'tanggal_publikasi' => date('Y-m-d', strtotime("-" . rand(1, 30) . " days"))
        ]);
        $image_counter++;
    }
}

// Insert into database
foreach ($posts as $post) {
    $judul = $post[0];
    $isi = $post[1];
    $kategori = $post['kategori'];
    $author = $post['author'];
    $tanggal_publikasi = $post['tanggal_publikasi'];
    $image = $post['image'];

    // Prepare statement for insertion
    $stmt = $conn->prepare("INSERT INTO posts (judul, isi, kategori, author, tanggal_publikasi, images) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssss', $judul, $isi, $kategori, $author, $tanggal_publikasi, $image);

    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error . "<br>";
    }
}

echo "Data berhasil ditambahkan!";

$stmt->close();
$conn->close();
?>
