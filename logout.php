<?php
// Mulai session
session_start();

// Hapus semua data session
session_unset();

// Hancurkan session
session_destroy();

// Redirect ke halaman utama dengan parameter sukses
header("Location: index.php?success=1");
exit;
