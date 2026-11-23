<?php

include_once 'koneksi.php';
include_once 'agenda_fungsi.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/styles/tailwind.css">
    <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">
    <link href="../asset/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="asset/css/wtx.css">
    <title>Admin Company Profile</title>
    <link rel="stylesheet" href="../asset/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>


    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <link href="../asset/css/summernote-image-list.min.css">
    <script src="../asset/js/summernote-image-list.min.js"></script>

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

    <style>
        .image-list-content .col-lg-3 {
            width: 100%;
        }

        .image-list-content img {
            float: left;
            width: 20%
        }

        .image-list-content p {
            float: left;
            padding-left: 20px
        }

        .image-list-item {
            padding: 10px 0px 10px 0px
        }
    </style>
</head>

<body class="container">
    <div class="">
        <header class=" shadow-lg bg-light bg-opacity-50">
            <!-- tampilan call dan email -->
            <div class="container">
                <div class=" row text-center row-cols-auto ">

                    <div class="row col row-cols-auto">
                        <span class="material-symbols-outlined col-1"> call </span>
                        <div>085218403629</div>
                    </div>
                    <div class="row col row-cols-auto">
                        <span class="material-symbols-outlined col-1"> mail </span>
                        <div>satarbarakati4321@gmail.com</div>
                    </div>
                </div>

            </div>
    </div>

    <!-- logo dan menu -->
    <div class="row align-items-center  sticky-md-top shadow-sm">
        <nav class="navbar navbar-expand-lg bg-light bg-opacity-75 " style="width: 100%; ">
            <div class="container">
                <div class="col-md-5 col">
                    <img src="https://tse4.mm.bing.net/th?id=OIP.kN9Rodvz86zqoyN7QrYdagHaI0&pid=Api&P=0&h=220" class="rounded d-inline" style="width: 60px" alt="..." />
                    <a class="navbar-brand fw-bold" href="#" style="font-family:  'Work Sans', sans-serif;">DINAS
                        KEBUDAYAAN</a>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class=" collapse navbar-collapse text-capitalize h6 fw-bold" id="navbarTogglerDemo02">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link text-color " href="#">Beranda</a>
                        </li>
                        <li class="nav-item dropdown ">
                            <a class="nav-link text-color dropdown-toggle-split " href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                Profil
                            </a>
                            <ul class="dropdown-content dropdown-menu table-hover">
                                <li><a class="dropdown-item" href="#">visi misi</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  text-color " href="">Informasi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-color" href="">galeri</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-color " href="">pengaduan</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-color" href="">kontak</a>
                        </li>
                    </ul>
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn text-color fw-bold btn-shine" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>
    </div>

    <div class="container monitor  bg-light opacity-50">
        <p class="sliding-text h3  text-capitalize text-center shadow-lg ">selamat datang di website resmi dinas
            kebudayaan
            kota
            ternate</p>
    </div>
    </header>
    </div>
    <main>