<?php
    include 'koneksi.php';

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $query = "SELECT * FROM berita WHERE id = '$id'";
        $result = mysqli_query($koneksi, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $file_name = $row['file'];
            $file_path = "image/" . $file_name;

            echo "<embed src='$file_path' type='application/pdf' width='100%' height='600px' />";
        } else {
            echo "File tidak ditemukan.";
        }
    } else {
        echo "Parameter tidak valid.";
    }
    ?>