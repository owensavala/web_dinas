<?php
include_once 'koneksi.php';
include_once 'agenda_fungsi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Berita</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">Input Berita</h1>
    <div class="mb-3">
        <a href="index.php?halaman=agenda" class="btn btn-secondary">&laquo; Kembali ke halaman admin</a>
    </div>
    <?php
    $judul = $gambar = $tanggal = $kegiatan = $error = $sukses = $gambar_name = "";

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = "";
    }

    if ($id != "") {
        $sql1 = "SELECT * FROM agenda WHERE id = '$id'";
        $q1 = mysqli_query($koneksi, $sql1);
        $r1 = mysqli_fetch_array($q1);
        $judul = $r1['judul'];
        $kegiatan = $r1['kegiatan'];
        $tanggal = $r1['tanggal'];
        $gambar = $r1['gambar_judul'];
    }

    if (isset($_POST['simpan'])) {
        $judul = $_POST['judul'];
        $tanggal = $_POST['tanggal'];
        $kegiatan = $_POST['kegiatan'];

        if ($judul == '') {
            $error = "Silakan masukkan semua data yakni adalah data kegiatan dan judul.";
        }
        if ($_FILES['gambar']['name']) {
            $gambar_name = $_FILES['gambar']['name'];
            $gambar_file = $_FILES['gambar']['tmp_name'];
            $detail_files = pathinfo($gambar_name);
            $gambar_ekstensi = $detail_files['extension'];
            $valid_file = array("jpg", "png", "gif", "jpeg");
            if (!in_array($gambar_ekstensi, $valid_file)) {
                $error = "Ekstensi yang diizinkan adalah jpg, jpeg, png, gif.";
            }
        }

        if (empty($error)) {
            if ($gambar_name) {
                $folder = "../asset/img";
                $gambar_name = 'agenda' . time() . '_' . $gambar_name;
                move_uploaded_file($gambar_file, $folder . "/" . $gambar_name);
            }

            if ($id != "") {
                if ($gambar_name) {
                    $sql1 = "UPDATE agenda SET judul = '$judul', gambar_judul = '$gambar_name', tanggal = '$tanggal', kegiatan = '$kegiatan' WHERE id = '$id'";
                } else {
                    $sql1 = "UPDATE agenda SET judul = '$judul', tanggal = '$tanggal', kegiatan = '$kegiatan' WHERE id = '$id'";
                }
            } else {
                $sql1 = "INSERT INTO agenda (judul, gambar_judul, kegiatan, tanggal) VALUES ('$judul', '$gambar_name', '$kegiatan', '$tanggal')";
            }

            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Sukses memasukkan data";
            } else {
                $error = "Gagal memasukkan data";
            }
        }
    }
    ?>
    <?php if ($error) { ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error ?>
        </div>
    <?php } ?>
    <?php if ($sukses) { ?>
        <div class="alert alert-primary" role="alert">
            <?php echo $sukses ?>
        </div>
    <?php } ?>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="judul" class="form-label">Judul</label>
            <input type="text" class="form-control" id="judul" name="judul" value="<?php echo $judul ?>">
        </div>
        <div class="mb-3">
            <label for="gambar" class="form-label">Gambar</label>
            <?php if ($gambar != '') { ?>
                <img src="../asset/img/<?php echo $gambar ?>" class="img-thumbnail mb-2" style="max-height: 100px;">
            <?php } ?>
            <input type="file" class="form-control" id="gambar" name="gambar">
        </div>
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo $tanggal ?>">
        </div>
        <div class="mb-3">
            <label for="kegiatan" class="form-label">Kegiatan</label>
            <textarea class="form-control" id="summernote" name="kegiatan"><?php echo $kegiatan ?></textarea>
        </div>
        <div class="mb-3 text-end">
            <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIqPIoifvFfRVLYPRFIgGhZlO5nbE3z6KepwETk5I7tA62C7A5f" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 300,
            toolbar: [
                ["style", ["style"]],
                ["font", ["bold", "underline", "clear"]],
                ["color", ["color"]],
                ["para", ["ul", "ol", "paragraph"]],
                ["table", ["table"]],
                ["insert", ["link", "picture"]],
                ["view", ["fullscreen", "codeview", "help"]]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    for (var i = 0; i < files.length; i++) {
                        uploadImage(files[i]);
                    }
                }
            }
        });

        function uploadImage(file) {
            var data = new FormData();
            data.append("file", file);
            $.ajax({
                url: 'upload_gambar.php',
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                type: "POST",
                success: function(url) {
                    $('#summernote').summernote("insertImage", url);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
    });
</script>
</body>
</html>
