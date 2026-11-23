<?php 
require_once __DIR__ . '/koneksi.php';
if (file_exists(__DIR__ . '/url_dasar.php')) {
    require_once __DIR__ . '/url_dasar.php';
}

function agenda_gambar($id)
{
    global $koneksi;
    $stmt = mysqli_prepare($koneksi, "SELECT gambar FROM agenda_b WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $r1 = mysqli_fetch_assoc($res);
    $foto = $r1['gambar'] ?? '';

    if ($foto) {
        return $foto;
    } else {
        return 'diskeb.png';
    }
}

function dapatkan_id()
{
    $id = "";
    if (isset($_SERVER['PATH_INFO'])) {
        $id = dirname($_SERVER['PATH_INFO']);
        $id = preg_replace("/[^0-9]/", "", $id);
    }
    return $id;
}

function set_isi($isi)
{
    $isi = str_replace("../asset/img/","../asset/img/", $isi);
    return $isi;
}
