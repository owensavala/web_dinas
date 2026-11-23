<?php
include_once 'koneksi.php';
include_once 'agenda_fungsi.php';

?>
    <link rel="stylesheet" href="../asset/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>


    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <link href="../asset/css/summernote-image-list.min.css">
    <script src="../asset/js/summernote-image-list.min.js"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

<?php
$sukses = "";
$katakunci = (isset($_GET['katakunci'])) ? $_GET['katakunci'] : "";
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if ($op == 'delete') {
    $id = $_GET['id'];
    $sql1   = "delete from agenda_b where id = '$id'";
    $q1     = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses     = "Berhasil hapus data";
    }
}
?>
<h1>Berita</h1>
<p>
    <a href="index.php?halaman=agenda-baru">
        <input type="button" class="btn btn-primary" value="Buat Berita Baru" />
    </a>
</p>
<?php
if ($sukses) {
?>
    <div class="alert alert-primary" role="alert">
        <?php echo $sukses ?>
    </div>
<?php
}
?>
<form class="row g-3" method="get">
    <div class="col-auto">
        <input type="text" class="form-control" placeholder="Masukkan Kata Kunci" name="katakunci" value="<?php echo $katakunci ?>" />
    </div>
    <div class="col-auto">
        <input type="submit" name="cari" value="Cari" class="btn btn-secondary" />
    </div>
</form>
<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-1">#</th>
            <th>Judul</th>
            <th>Gambar</th>
            <th>Tanggal</th>
            <th class="col-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sqltambahan = "";
        $per_agenda = 10;
        if ($katakunci != '') {
            $array_katakunci = explode(" ", $katakunci);
            for ($x = 0; $x < count($array_katakunci); $x++) {
                $sqlcari[] = "(judul like '%" . $array_katakunci[$x] . "%' or  kegiatan like '%" . $array_katakunci[$x] . "%')";
            }
            $sqltambahan    = " where " . implode(" or ", $sqlcari);
        }
        $sql1   = "select * from agenda_b $sqltambahan";
        $page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $mulai  = ($page > 1) ? ($page * $per_agenda) - $per_agenda : 0;
        $q1     = mysqli_query($koneksi, $sql1);
        $total  = mysqli_num_rows($q1);
        $pages  = ceil($total / $per_agenda);
        $nomor  = $mulai + 1;
        $sql1   = $sql1 . " order by id desc limit $mulai,$per_agenda";

        $q1     = mysqli_query($koneksi, $sql1);

        while ($r1 = mysqli_fetch_array($q1)) {
        ?>
            <tr>
                <td><?php echo $nomor++ ?></td>
                <td><?php echo $r1['judul'] ?></td>
                <td><img src="../asset/img/<?php echo agenda_gambar($r1['id']) ?>" style="max-height: 100px;"></td>
                <td><?php echo $r1['tanggal'] ?></td>
                <td>
                    <a href="index.php?halaman=berita&id=<?php echo $r1['id'] ?>">
                        <span class="badge bg-warning text-dark">Edit</span>
                    </a>

                    <a href="index.php?halaman=delete_agenda&op=delete&id=<?php echo $r1['id'] ?>" onclick="return confirm('Apakah yakin mau hapus data bro?')">
                        <span class="badge bg-danger">Delete</span>
                    </a>
                </td>
            </tr>
        <?php
        }
        ?>

    </tbody>
</table>

<nav aria-label="Page navigation example">
    <ul class="pagination">
        <?php
        $cari = isset($_GET['cari']) ? $_GET['cari'] : "";

        for ($i = 1; $i <= $pages; $i++) {
        ?>
            <li class="page-item">
                <a class="page-link" href="agenda.php?katakunci=<?php echo $katakunci ?>&cari=<?php echo $cari ?>&page=<?php echo $i ?>"><?php echo $i ?></a>
            </li>
        <?php
        }
        ?>
    </ul>
</nav>
