<?php
require_once __DIR__ . "/../../core/handler_auth.php";
require_once __DIR__ . "/../../core/handler_request.php";
session_start();
require_once __DIR__ . "/../../core/handler_csrf.php";
only_access_with_and(["GET", "POST"], true);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["logout"])) {
    do_logout($db, $_SESSION["username"] ?? "");
    header("Location: /index.php", true, 302);
    exit;
}

if (!isset($_SESSION["username"]) || !is_string($_SESSION["username"])) {
    if (($_SERVER["HTTP_HX_REQUEST"] ?? "") === "true") {
        http_response_code(401);
        header("HX-Redirect: /index.php");
        exit;
    }

    header("Location: /index.php", true, 302);
    exit;
}

$features = [
    "overview" => [
        "label" => "Beranda",
        "file" => __DIR__ . "/features/overview.php",
    ],
    "profile" => [
        "label" => "Profil",
        "file" => __DIR__ . "/features/profile.php",
    ],
    "uri_short" => [
        "label" => "Pemendek URL",
        "file" => __DIR__ . "/features/uri_short.php",
    ],
];
if (($_SESSION["role"] ?? "user") === "admin") {
    $features["mailbox"] = [
        "label" => "Mailbox",
        "file" => __DIR__ . "/features/mailbox.php",
    ];
}
$feature = $_GET["feature"] ?? "overview";

if (!is_string($feature) || !isset($features[$feature])) {
    http_response_code(404);
    echo "Fitur tidak ditemukan.";
    exit;
}

$username = htmlspecialchars($_SESSION["username"], ENT_QUOTES, "UTF-8");

if (($_SERVER["HTTP_HX_REQUEST"] ?? "") === "true"
    || ($_SERVER["REQUEST_METHOD"] === "POST"
        && str_contains($_SERVER["HTTP_ACCEPT"] ?? "", "application/json"))) {
    require $features[$feature]["file"];
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f2f5f0">
    <title><?= htmlspecialchars($features[$feature]["label"], ENT_QUOTES, "UTF-8") ?> | Simple Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/app.css">
    <script src="https://unpkg.com/htmx.org@2.0.4" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body class="dashboard-page">
    <div class="container-fluid">
        <div class="row min-vh-100">
            <aside class="col-lg-3 col-xl-2 p-0 dashboard-sidebar">
                <div class="offcanvas-lg offcanvas-start h-100 bg-white border-end" tabindex="-1" id="dashboard-sidebar" aria-labelledby="sidebar-title">
                    <div class="offcanvas-header border-bottom">
                        <h2 class="offcanvas-title fs-5" id="sidebar-title">Simple Login</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#dashboard-sidebar" aria-label="Tutup menu"></button>
                    </div>
                    <div class="offcanvas-body d-flex flex-column p-3">
                        <a class="brand mb-4" href="/dashboard/dashboard.php">
                            <span class="brand-mark" aria-hidden="true">S</span>
                            <span>Simple Login</span>
                        </a>
                        <p class="eyebrow mb-2">MENU</p>
                        <nav class="nav nav-pills flex-column gap-1" aria-label="Menu dashboard">
                            <?php foreach ($features as $key => $item): ?>
                                <a
                                    class="nav-link <?= $feature === $key ? "active" : "link-dark" ?>"
                                    href="/dashboard/dashboard.php?feature=<?= urlencode($key) ?>"
                                    hx-get="/dashboard/dashboard.php?feature=<?= urlencode($key) ?>"
                                    hx-target="#dashboard-content"
                                    hx-swap="innerHTML"
                                    hx-push-url="true"
                                    data-dashboard-feature="<?= htmlspecialchars($key, ENT_QUOTES, "UTF-8") ?>"
                                    <?= $feature === $key ? 'aria-current="page"' : "" ?>
                                ><?= htmlspecialchars($item["label"], ENT_QUOTES, "UTF-8") ?></a>
                            <?php endforeach; ?>
                        </nav>
                        <div class="mt-auto pt-4 border-top">
                            <p class="small text-secondary mb-0">Masuk sebagai</p>
                            <p class="fw-semibold text-break mb-3"><?= $username ?></p>
                            <form method="POST">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(create_csrf_token(), ENT_QUOTES, "UTF-8") ?>">
                                <button class="btn btn-outline-secondary w-100" type="submit" name="logout" value="1">Keluar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>
            <main class="col-lg-9 col-xl-10 p-0">
                <header class="navbar bg-white border-bottom px-3 px-lg-4 py-3">
                    <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#dashboard-sidebar" aria-controls="dashboard-sidebar" aria-label="Buka menu">
                        Menu
                    </button>
                    <span class="navbar-text ms-auto">Halo, <?= $username ?></span>
                </header>
                <div class="container-fluid p-3 p-lg-4">
                    <div id="dashboard-content" aria-live="polite">
                        <?php require $features[$feature]["file"]; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        function updateDashboardNavigation() {
            const feature = new URL(window.location.href).searchParams.get("feature") || "overview";

            document.querySelectorAll("[data-dashboard-feature]").forEach((link) => {
                const isActive = link.dataset.dashboardFeature === feature;
                link.classList.toggle("active", isActive);
                link.classList.toggle("link-dark", !isActive);

                if (isActive) {
                    link.setAttribute("aria-current", "page");
                } else {
                    link.removeAttribute("aria-current");
                }
            });
        }

        document.body.addEventListener("htmx:afterSettle", updateDashboardNavigation);
        window.addEventListener("popstate", updateDashboardNavigation);
    </script>
</body>
</html>
