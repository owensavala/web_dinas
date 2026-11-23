<?php
include 'header.php';

$sql1   = "select * from agenda";
$q1     = mysqli_query($koneksi, $sql1);
while ($r1 = mysqli_fetch_array($q1)) {
?>
    <div class="card mb-3" style="max-width: 100%;">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="../asset/img/<?php echo agenda_gambar($r1['id']) ?>" style="max-height: 200px;" class="img-fluid rounded-start" >
            </div>
            <div class="col-md-8 d-flex align-items-center">
                <div class="card-body ">
                    <h5 class="card-title  "><?php echo $r1['judul'] ?></h5>
                    <a href="halaman.php?id=<?php echo $r1['id'] ?>" class="btnx btn-infox">selengkapnya</a>
                    <p class="card-text"><small class="text-muted"></small></p>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
<?php include 'footer.php'; ?>