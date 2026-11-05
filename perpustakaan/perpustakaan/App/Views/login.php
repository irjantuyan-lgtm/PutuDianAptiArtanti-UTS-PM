
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Petugas - Perpustakaan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">

        <div class="col-md-5 col-lg-4">

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-primary">Perpustakaan</h3>
                        <p class="text-muted mb-0">Login Petugas</p>
                    </div>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger">
                            <?php if ($_GET['error'] === 'kosong'): ?>
                                Username dan password wajib diisi.
                            <?php else: ?>
                                Username atau password salah.
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?action=prosesLogin" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input
                                type="text"
                                name="username"
                                class="form-control"
                                placeholder="Masukkan username"
                                required
                                autofocus
                            >
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Masukkan password"
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Login
                        </button>

                    </form>

                </div>
            </div>

            <p class="text-center text-muted mt-3 small">
                Sistem Informasi Perpustakaan
            </p>

        </div>
    </div>

</body>
</html>
