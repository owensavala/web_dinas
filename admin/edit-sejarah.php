<?php
ob_start(); // Mulai penangguhan output

include_once("koneksi.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sejarah_baru = $_POST["sejarah"];
    $sql_update = "UPDATE sejarah SET sejarah='$sejarah_baru' WHERE id_sejarah=1";

    if (mysqli_query($koneksi, $sql_update)) {
        echo "<script>location='index.php?halaman=sejarah';</script>";
    } else {
        $error = "Error: " . $sql_update . "<br>" . mysqli_error($koneksi);
    }
}

$sql = "SELECT * FROM sejarah";
$result = mysqli_query($koneksi, $sql);
$row = mysqli_fetch_assoc($result);
$sejarah = $row['sejarah'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sejarah</title>
</head>

<body>
    <h1>Edit Sejarah</h1>
    <?php if (isset($error)) { ?>
        <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
    <?php } ?>
    <?php if (isset($message)) { ?>
        <div class="alert alert-success" role="alert"><?php echo $message; ?></div>
    <?php } ?>
    <form method="post" action="">
        <div class="mb-3">
            <label for="sejarah" class="form-label">Sejarah</label>
            <textarea style="min-height: 500px;" class="form-control" id="sejarah" name="sejarah" rows="5"><?php echo $sejarah; ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</body>

</html>

<?php
ob_end_flush(); // Akhiri penangguhan output dan kirimkan ke browser
?>