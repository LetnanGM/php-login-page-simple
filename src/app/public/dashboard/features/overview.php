<section aria-labelledby="dashboard-title">
    <p class="eyebrow">RUANG AKUN</p>
    <h1 class="h2 mb-2" id="dashboard-title">Selamat datang, <?= $username ?>.</h1>
    <p class="text-secondary mb-4">Kamu berhasil masuk. Kelola akunmu dari dashboard ini.</p>
    <div class="row g-3">
        <div class="col-md-6 col-xl-4">
            <article class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 card-title">Akun aktif</h2>
                    <p class="card-text text-secondary mb-0">Kamu masuk sebagai <?= $username ?>.</p>
                </div>
            </article>
        </div>
        <div class="col-md-6 col-xl-4">
            <article class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 card-title">Profil</h2>
                    <p class="card-text text-secondary">Lihat informasi nama pengguna akunmu.</p>
                    <a class="btn btn-sm btn-outline-success" href="/dashboard/dashboard.php?feature=profile" hx-get="/dashboard/dashboard.php?feature=profile" hx-target="#dashboard-content" hx-swap="innerHTML" hx-push-url="true">Lihat profil</a>
                </div>
            </article>
        </div>
    </div>
</section>
