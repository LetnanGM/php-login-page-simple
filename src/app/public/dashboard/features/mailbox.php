<?php
require_once __DIR__ . "/../../../core/handler_password_reset.php";
$requests = get_password_reset_requests($db);
?>
<section aria-labelledby="mailbox-title">
    <p class="eyebrow">PANEL ADMIN</p>
    <h1 class="h2 mb-2" id="mailbox-title">Mailbox permintaan</h1>
    <p class="text-secondary mb-4">Permintaan pemulihan yang masuk dari formulir publik. Email belum dikirim melalui SMTP.</p>
    <?php if ($requests === []): ?>
        <div class="alert alert-info" role="status">Belum ada permintaan pemulihan password.</div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Email pemohon</th>
                            <th scope="col">Diterima</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td><?= htmlspecialchars($request["email"], ENT_QUOTES, "UTF-8") ?></td>
                                <td><?= htmlspecialchars($request["created_at"], ENT_QUOTES, "UTF-8") ?></td>
                                <td><span class="badge text-bg-warning"><?= htmlspecialchars($request["status"], ENT_QUOTES, "UTF-8") ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</section>
