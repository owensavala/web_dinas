<?php 
include_once 'koneksi.php';

$nama = '';
$mulai = '';
$selesai = '';
$error = '';

if (isset($_GET['id_kepala'])) {
    $id = $_GET['id_kepala'];
    
    // Prepare and execute the SQL query
    $sql1 = "SELECT * FROM nama_kepala WHERE id_kepala = $id";
    $stmt = mysqli_prepare($koneksi, $sql1);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    // Check if data is found
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $nama = $row['nama'];
        $mulai = $row['mulai'];
        $selesai = $row['selesai'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

?>

<?php if (!empty($error)) { ?>
    <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
<?php } ?>

<form method="post" action="">
    <div class="mb-3">
        <label for="nama">Nama Kepala:</label>
        <input type="text" name="nama" id="nama" value="<?php echo $nama; ?>" >
    </div>
    <div class="mb-3">
        <label for="mulai">Mulai Tugas:</label>
        <input type="text" name="mulai" id="mulai" value="<?php echo $mulai; ?>" required>
    </div>
    <div class="mb-3"> 
        <label for="selesai">Selesai Tugas:</label>
        <input type="text" name="selesai" id="selesai" value="<?php echo $selesai; ?>" required>
    </div>
    <button type="submit" name="edit_nama_kepala">Simpan</button>
</form>
