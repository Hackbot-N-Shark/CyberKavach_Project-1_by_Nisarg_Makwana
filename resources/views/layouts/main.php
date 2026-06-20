<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'CyberKavach V2.0' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Hacker style (Share Tech Mono + Fira Code) -->
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;600;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <!-- AOS Library for Scroll Animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="bg-black text-white crt-effect" data-page="<?= $pageId ?? 'home' ?>">

    <!-- Loading Screen (Hacker Boot Sequence) -->
    <div id="loading-screen">
        <div class="terminal-loader">
            <div id="boot-text" class="font-mono text-primary text-start fs-6 mb-3"></div>
            <div id="boot-animation"></div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-black sticky-top border-bottom border-danger shadow-neon-danger">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center glitch-container" href="/">
                <strong class="font-mono text-danger fs-3 glitch" data-text="CyberKavach">Cyber<span class="text-white">Kavach</span></strong>
            </a>
            <button class="navbar-toggler border-danger" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="filter: invert(1) sepia(1) saturate(5) hue-rotate(300deg);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto font-mono fw-bold">
                    <li class="nav-item">
                        <a class="nav-link text-white hover-danger px-3" href="/">[HOME]</a>
                    </li>
                    <?php if (!\App\Core\Auth::isGuest()): ?>
                        <li class="nav-item">
                            <a class="nav-link text-white hover-danger px-3" href="/about">[ABOUT]</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white hover-danger px-3" href="/community">[COMMUNITY]</a>
                        </li>
                        <?php if (\App\Core\Auth::user()['role'] === 'root'): ?>
                            <li class="nav-item">
                                <a class="nav-link text-white hover-danger px-3" href="/blog">[LOGS]</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link text-white hover-danger px-3" href="/gallery">[GALLERY]</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-mono px-3 hover-danger" href="/team">[TEAM]</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="d-flex ms-lg-3 mt-3 mt-lg-0">
                    <?php if (\App\Core\Auth::isGuest()): ?>
                        <a href="/login" class="btn btn-outline-danger font-mono btn-hacker w-100 me-2">ACCESS_SYS</a>
                        <a href="/register" class="btn btn-danger font-mono fw-bold rounded-0 px-4 btn-hacker">INIT_JOIN</a>
                    <?php else: ?>
                        <div class="d-flex w-100 gap-2">
                            <div class="dropdown">
                                <button class="btn btn-outline-warning font-mono btn-hacker position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="bellBtn">
                                    🔔
                                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle d-none" id="notificationBadge">
                                        <span class="visually-hidden">New alerts</span>
                                    </span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end bg-black border-warning rounded-0 font-mono small shadow-neon-danger" style="width: 320px; max-height: 400px; overflow-y: auto;" id="notificationList">
                                    <li class="dropdown-item text-secondary text-center">No new transmissions</li>
                                </ul>
                            </div>
                            <div class="dropdown flex-grow-1">
                                <button class="btn btn-outline-danger font-mono btn-hacker w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    [ <?php echo \App\Core\Security::escape(\App\Core\Auth::user()['username']); ?> ]
                                </button>
                                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end bg-black border-danger rounded-0 w-100 font-mono small">
                                    <li><a class="dropdown-item text-white hover-danger" href="/dashboard">> COMMAND_CENTER</a></li>
                                    <li><a class="dropdown-item text-danger hover-danger" href="/logout">> DISCONNECT</a></li>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        {{content}}
    </main>

    <!-- Footer -->
    <footer class="bg-black py-5 mt-5 border-top border-danger shadow-neon-danger-top relative z-10">
        <div class="container text-center">
            <h4 class="font-mono text-danger mb-3 glitch" data-text="CyberKavach">CyberKavach</h4>
            <p class="text-secondary font-mono">> Defend the Digital Frontier. Empowering the next generation of cybersecurity leaders.</p>
            <div class="d-flex justify-content-center gap-4 mt-4 font-mono small">
                <a href="/newsletter" class="text-secondary hover-danger text-decoration-none">[NEWSLETTER]</a>
                <a href="/contact" class="text-secondary hover-danger text-decoration-none">[CONTACT_COMMAND]</a>
            </div>
            <hr class="border-danger my-4 opacity-50">
            <p class="text-muted small font-mono">EOF // &copy; <?= date('Y') ?> CyberKavach CHARUSAT. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Custom JS -->
    <script src="/assets/js/main.js"></script>
</body>
</html>
