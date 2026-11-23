<?php
include_once("koneksi.php");

// Query untuk memperoleh semua judul agenda
$sql1   = "SELECT * FROM judul_agenda";
$q1     = mysqli_query($koneksi, $sql1);

// Looping untuk setiap judul agenda
while ($r1 = mysqli_fetch_array($q1)) {
    ?>
    <div class="card mb-3" style="max-width: 100%;">
        <div class="row g-0">
            <div class="col-md-8 d-flex align-items-center">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $r1['judul'] ?></h5>
                    <h5 class="card-title"><?php echo $r1['tanggal'] ?></h5>
                    <?php
                    // Query untuk memperoleh kegiatan dari judul agenda tertentu
                    $id_judul = $r1['id']; // Ambil ID judul untuk query kedua
                    $sql2 = "SELECT * FROM kegiatan WHERE judul = $id_judul";
                    $q2 = mysqli_query($koneksi, $sql2);
                    
                    // Looping untuk setiap kegiatan
                    while ($r2 = mysqli_fetch_array($q2)) {
                        ?>
                        <h5 class="card-title"><?php echo $r2['kegiatan'] ?></h5>
                        <?php
                    }
                    ?>
                    
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
