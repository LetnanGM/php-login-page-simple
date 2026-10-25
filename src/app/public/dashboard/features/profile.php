<section aria-labelledby="profile-title">
    <p class="eyebrow">AKUN</p>
    <h1 class="h2 mb-2" id="profile-title">Profil</h1>
    <p class="text-secondary mb-4">Informasi akun yang sedang masuk.</p>
    <div class="row">
        <div class="col-md-8 col-xl-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Nama pengguna</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($_SESSION["username"] ?? "", ENT_QUOTES, "UTF-8") ?></dd>
                    </dl>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($_SESSION["email"] ?? "", ENT_QUOTES, "UTF-8") ?></dd>
                    </dl>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Terakhir masuk</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($_SESSION["last_login"] ?? "-", ENT_QUOTES, "UTF-8") ?></dd>
                    </dl>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Terakhir keluar</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($_SESSION["last_logout"] ?? "-", ENT_QUOTES, "UTF-8") ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</section>
