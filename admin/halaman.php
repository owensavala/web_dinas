<?php
include_once("koneksi.php");
include_once("agenda_fungsi.php");
//http://localhost/website-company-profile/halaman.php/8/tetap-sehat-tetap-semangat
//print_r($_SERVER);
if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $sql1   = "select * from agenda where id = '$id'";
    $q1     = mysqli_query($koneksi, $sql1);
    $n1     = mysqli_num_rows($q1);
    $r1     = mysqli_fetch_array($q1);
}
$judul_halaman  = $r1['judul'];
?>
<?php include_once("header.php") ?>

<?php
if ($judul_halaman == '') {
    echo "<div><p>Maaf data yang kamu maksud tidak ditemukan :(</p></div>";
} else {
?>
    <div class="text-center card">
        <h1><?php echo $r1['judul'] ?></h1>
        <img class="rounded mx-auto d-block" style="max-width: 400px;" src="../asset/img/<?php echo $r1['gambar_judul'] ?>" alt="" srcset="">
        <?php echo set_isi($r1['kegiatan']) ?>
    </div>
<?php
}
?>

<?php include_once("footer.php") ?>