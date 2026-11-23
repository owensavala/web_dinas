<?php 
session_start();
if (!empty($_SESSION['admin_username'])) {
    header("Location: index.php");
    exit();
}
require_once __DIR__ . '/koneksi.php';

$username = "";
$err      = "";

if (isset($_POST['Login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $err = "Silakan masukkan username dan password.";
    } else {
        $stmt = mysqli_prepare($koneksi, "SELECT id, user, pass FROM admin WHERE user = ? LIMIT 1");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row    = mysqli_fetch_assoc($result);

            if ($row) {
                $stored_hash = $row['pass'];
                $is_valid = false;

                if (password_verify($password, $stored_hash)) {
                    $is_valid = true;
                } elseif ($stored_hash === md5($password)) {
                    $is_valid = true;
                    $new_hash = password_hash($password, PASSWORD_BCRYPT);
                    $up_stmt = mysqli_prepare($koneksi, "UPDATE admin SET pass = ? WHERE id = ?");
                    if ($up_stmt) {
                        mysqli_stmt_bind_param($up_stmt, "si", $new_hash, $row['id']);
                        mysqli_stmt_execute($up_stmt);
                    }
                }

                if ($is_valid) {
                    session_regenerate_id(true);
                    $_SESSION['admin_username'] = $row['user'];
                    $_SESSION['admin_id']       = $row['id'];
                    header("Location: index.php");
                    exit();
                } else {
                    $err = "Password yang Anda masukkan salah.";
                }
            } else {
                $err = "Akun administrator tidak ditemukan.";
            }
        } else {
            $err = "Terjadi gangguan koneksi database.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator | Dinas Kebudayaan Kota Ternate</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script src="../asset/js/tailwind-config.js"></script>
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="h-full flex items-center justify-center p-4 font-sans text-slate-200 antialiased relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-brand-950">

    <!-- Subtle Background Glows -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-brand-800/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-gold-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Card Login -->
        <div class="bg-slate-900/90 backdrop-blur-xl rounded-3xl border border-slate-800 shadow-2xl overflow-hidden p-8 sm:p-10 space-y-6">
            <!-- Header Brand -->
            <div class="text-center space-y-3">
                <div class="inline-block p-2 rounded-2xl bg-white/5 border border-white/10 shadow-inner">
                    <img src="https://tse4.mm.bing.net/th?id=OIP.kN9Rodvz86zqoyN7QrYdagHaI0&pid=Api&P=0&h=220" alt="Logo Pemkot Ternate" class="w-14 h-14 object-contain mx-auto">
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-white tracking-tight">PORTAL ADMINISTRATOR</h1>
                    <p class="text-xs text-gold-400 font-semibold uppercase tracking-widest mt-0.5">Dinas Kebudayaan Kota Ternate</p>
                </div>
            </div>

            <!-- Error Notification -->
            <?php if (!empty($err)): ?>
                <div class="p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-300 text-xs flex items-center space-x-2.5">
                    <i class="bi bi-exclamation-octagon-fill text-rose-400 text-base shrink-0"></i>
                    <span><?= htmlspecialchars($err) ?></span>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="" method="POST" autocomplete="off" class="space-y-4">
                <div>
                    <label for="username" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 text-sm pointer-events-none">
                            <i class="bi bi-person-fill"></i>
                        </span>
                        <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required autofocus placeholder="Masukkan username" class="w-full pl-10 text-xs rounded-xl bg-slate-950/70 border-slate-700/80 text-white placeholder-slate-500 focus:border-brand-500 focus:ring-brand-500 py-3">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 text-sm pointer-events-none">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full pl-10 text-xs rounded-xl bg-slate-950/70 border-slate-700/80 text-white placeholder-slate-500 focus:border-brand-500 focus:ring-brand-500 py-3">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" name="Login" class="w-full py-3.5 rounded-xl text-xs font-bold uppercase tracking-wider text-white bg-gradient-to-r from-brand-700 via-brand-600 to-amber-600 hover:from-brand-800 hover:to-amber-700 shadow-lg shadow-brand-900/30 transition transform hover:-translate-y-0.5 active:translate-y-0">
                        <i class="bi bi-box-arrow-in-right mr-1.5"></i> Masuk ke Dashboard
                    </button>
                </div>
            </form>

            <div class="pt-2 text-center border-t border-slate-800">
                <a href="../index.php" class="text-xs text-slate-400 hover:text-gold-400 transition inline-flex items-center space-x-1.5">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke Beranda Publik</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
