<section class="py-5 bg-black" style="min-height: 80vh;">
    <div class="container">
        <h1 class="font-mono text-danger mb-2">>_ OPERATIVE_TERMINAL</h1>
        <p class="text-secondary font-share mb-5">Welcome, Operative <span class="text-white glitch" data-text="<?php echo \App\Core\Security::escape($user['username']); ?>"><?php echo \App\Core\Security::escape($user['username']); ?></span>. Secure connection established.</p>
        
        <?php if (isset($_GET['error']) && $_GET['error'] === 'capacity_reached'): ?>
            <div class="alert bg-black border border-danger text-danger font-mono alert-dismissible fade show" role="alert">
                > ALERT: MISSION_CAPACITY_REACHED. Enrollment denied.
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['success']) && $_GET['success'] === 'registered'): ?>
            <div class="alert bg-black border border-success text-success font-mono alert-dismissible fade show" role="alert">
                > SUCCESS: ENROLLMENT_CONFIRMED. See you on the battlefield.
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Custom Tabs -->
        <ul class="nav nav-pills mb-4 border-bottom border-danger pb-2 font-mono" id="operativeTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active bg-transparent text-secondary hover-danger rounded-0 px-4" id="missions-tab" data-bs-toggle="pill" data-bs-target="#missions" type="button" role="tab">
                    [MISSIONS]
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link bg-transparent text-secondary hover-danger rounded-0 px-4" id="vault-tab" data-bs-toggle="pill" data-bs-target="#vault" type="button" role="tab">
                    [VAULT]
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link bg-transparent text-secondary hover-danger rounded-0 px-4" id="intel-tab" data-bs-toggle="pill" data-bs-target="#intel" type="button" role="tab">
                    [INTEL]
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link bg-transparent text-secondary hover-danger rounded-0 px-4" id="profile-tab" data-bs-toggle="pill" data-bs-target="#profile" type="button" role="tab">
                    [PROFILE]
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="operativeTabsContent">
            
            <!-- MISSIONS TAB -->
            <div class="tab-pane fade show active animate-in slide-in-from-bottom-10" id="missions" role="tabpanel" tabindex="0">
                <h3 class="font-mono text-white mb-4"><span class="text-danger">[*]</span> ACTIVE_OPERATIONS</h3>
                <div class="row g-4">
                    <?php if (empty($upcoming_events)): ?>
                        <div class="col-12"><p class="text-secondary font-share">No active operations available at this time.</p></div>
                    <?php else: ?>
                        <?php foreach ($upcoming_events as $event): ?>
                            <?php $isRegistered = in_array($event['id'], $registered_event_ids); ?>
                            <div class="col-md-6">
                                <div class="card card-hacker p-4 h-100 rounded-0 <?php echo $isRegistered ? 'border-success' : 'border-danger'; ?> transition-all hover-scale">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h4 class="font-mono text-white mb-0"><?php echo \App\Core\Security::escape($event['title']); ?></h4>
                                        <?php if ($isRegistered): ?>
                                            <span class="badge bg-success text-black font-mono">ENROLLED</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger text-black font-mono pulse-anim">OPEN</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-secondary font-share small mb-3">
                                        <i class="fa fa-calendar me-2"></i>T-Minus: <span class="countdown-timer text-danger font-mono fw-bold" data-date="<?php echo $event['event_date']; ?>"><?php echo date('F j, Y - H:i', strtotime($event['event_date'])); ?></span>
                                    </p>
                                    <p class="text-gray-300 font-share mb-4"><?php echo \App\Core\Security::escape($event['description']); ?></p>
                                    
                                    <div class="mt-auto">
                                        <?php if (!$isRegistered): ?>
                                            <form action="/dashboard/event-register" method="POST">
                                                <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                                <button type="submit" class="btn btn-outline-danger font-mono w-100 btn-hacker">> ENROLL_NOW</button>
                                            </form>
                                        <?php else: ?>
                                            <button class="btn btn-outline-success font-mono w-100 disabled border-success text-success" style="opacity: 0.8;">> MISSION_ACCEPTED</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <h3 class="font-mono text-white mt-5 mb-4"><span class="text-secondary">[-]</span> PAST_OPERATIONS</h3>
                <div class="row g-4">
                    <?php foreach ($completed_events as $event): ?>
                        <div class="col-md-4">
                            <div class="card bg-black border border-secondary p-3 h-100 rounded-0 hover-lift">
                                <h5 class="font-mono text-secondary mb-1"><?php echo \App\Core\Security::escape($event['title']); ?></h5>
                                <p class="text-secondary font-share small mb-3">Completed: <?php echo date('M j, Y', strtotime($event['event_date'])); ?></p>
                                
                                <?php if (!empty($event['gallery'])): ?>
                                    <div class="d-flex flex-wrap gap-2 mt-auto">
                                        <?php foreach ($event['gallery'] as $img): ?>
                                            <a href="<?php echo $img['image_path']; ?>" target="_blank">
                                                <img src="<?php echo $img['image_path']; ?>" alt="Gallery Image" class="img-thumbnail border-secondary bg-black" style="width: 50px; height: 50px; object-fit: cover;">
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-secondary small font-mono mt-auto mb-0">> NO_VISUAL_DATA_FOUND</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- VAULT TAB -->
            <div class="tab-pane fade animate-in slide-in-from-bottom-10" id="vault" role="tabpanel" tabindex="0">
                <?php if (empty($vault_certs)): ?>
                    <div class="card card-hacker p-5 rounded-0 border-danger text-center" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255, 0, 60, 0.05) 10px, rgba(255, 0, 60, 0.05) 20px);">
                        <div class="display-1 text-danger mb-3" style="opacity: 0.5;">🔒</div>
                        <h3 class="font-mono text-white mb-3">[ CERTIFICATE_VAULT ]</h3>
                        <p class="text-secondary font-share max-w-2xl mx-auto">
                            Your digital credentials and captured flags will be stored here. Participate in operations to unlock cryptographically signed PDF certificates.
                        </p>
                        <button class="btn btn-outline-secondary font-mono mt-3 disabled">> VAULT_EMPTY</button>
                    </div>
                <?php else: ?>
                    <h3 class="font-mono text-white mb-4"><span class="text-danger">[*]</span> CRYPTOGRAPHIC_CREDENTIALS</h3>
                    <div class="row g-4">
                        <?php foreach ($vault_certs as $cert): ?>
                            <div class="col-md-6">
                                <div class="card bg-black border-danger p-3 h-100 rounded-0 hover-scale">
                                    <h5 class="font-mono text-danger mb-1"><?php echo \App\Core\Security::escape($cert['event_title']); ?></h5>
                                    <p class="text-secondary font-share small mb-3">Issued: <?php echo date('M j, Y', strtotime($cert['generated_at'])); ?></p>
                                    
                                    <div class="mb-3 text-center bg-dark p-2 border border-secondary">
                                        <img src="<?php echo $cert['cert_path']; ?>" class="img-fluid" alt="Certificate Preview">
                                    </div>
                                    <a href="<?php echo $cert['cert_path']; ?>" download class="btn btn-outline-danger font-mono w-100 mt-auto">> DOWNLOAD_CREDENTIAL</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- INTEL TAB -->
            <div class="tab-pane fade animate-in slide-in-from-bottom-10" id="intel" role="tabpanel" tabindex="0">
                <h3 class="font-mono text-white mb-4"><span class="text-danger">></span> SHARED_REPOSITORIES</h3>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card bg-surface p-4 rounded-0 border-start border-danger border-4 h-100 hover-lift">
                            <h5 class="font-mono text-white">Kali Linux Scripts</h5>
                            <p class="text-secondary font-share small">Custom bash scripts for automated reconnaissance.</p>
                            <a href="#" class="text-danger font-mono text-decoration-none small">> PULL_DATA</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-surface p-4 rounded-0 border-start border-danger border-4 h-100 hover-lift">
                            <h5 class="font-mono text-white">CTF Write-ups</h5>
                            <p class="text-secondary font-share small">Archive of solutions to previous hackathon challenges.</p>
                            <a href="#" class="text-danger font-mono text-decoration-none small">> PULL_DATA</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-surface p-4 rounded-0 border-start border-danger border-4 h-100 hover-lift">
                            <h5 class="font-mono text-white">Web Exploitation Guide</h5>
                            <p class="text-secondary font-share small">PDF manual compiled by the Core Faculty.</p>
                            <a href="#" class="text-danger font-mono text-decoration-none small">> PULL_DATA</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PROFILE TAB -->
            <div class="tab-pane fade animate-in slide-in-from-bottom-10" id="profile" role="tabpanel" tabindex="0">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-hacker p-4 rounded-0 border-danger text-center h-100">
                            <div class="mx-auto mb-3 border border-danger p-1" style="width: 120px; height: 120px; border-radius: 50%;">
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['username']); ?>&background=1a1a1a&color=FF003C&size=120" class="w-100 h-100 rounded-circle" alt="Avatar">
                            </div>
                            <h4 class="font-mono text-white mb-1"><?php echo \App\Core\Security::escape($user['username']); ?></h4>
                            <p class="text-danger font-share small mb-3">Clearance: OPERATIVE</p>
                            <div class="d-flex justify-content-center gap-2">
                                <span class="badge bg-black border border-secondary text-secondary">Web Exploiter</span>
                                <span class="badge bg-black border border-secondary text-secondary">Beginner</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 mt-4 mt-md-0">
                        <div class="card card-hacker p-4 rounded-0 border-danger h-100">
                            <h4 class="font-mono text-white mb-4">UPDATE_MATRIX</h4>
                            <form>
                                <div class="mb-3">
                                    <label class="form-label text-danger font-mono small">>_ ALIAS</label>
                                    <input type="text" class="form-control bg-transparent text-white border-secondary rounded-0 font-share shadow-none" value="<?php echo \App\Core\Security::escape($user['username']); ?>" disabled>
                                    <div class="form-text text-secondary small">Alias cannot be changed after initiation.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-danger font-mono small">>_ HACKTHEBOX_URL</label>
                                    <input type="url" class="form-control bg-transparent text-white border-danger rounded-0 font-share shadow-none" placeholder="https://app.hackthebox.com/users/...">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-danger font-mono small">>_ GITHUB_URL</label>
                                    <input type="url" class="form-control bg-transparent text-white border-danger rounded-0 font-share shadow-none" placeholder="https://github.com/...">
                                </div>
                                <button type="button" class="btn btn-outline-danger font-mono btn-hacker">> SAVE_CHANGES</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
    /* Custom Animations for Operative Dashboard */
    .nav-pills .nav-link.active {
        background-color: transparent !important;
        color: var(--ck-danger) !important;
        border-bottom: 2px solid var(--ck-danger);
        text-shadow: 0 0 8px rgba(255, 0, 60, 0.5);
    }
    .hover-scale {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-scale:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(255, 0, 60, 0.1);
        z-index: 10;
    }
    .hover-lift {
        transition: transform 0.2s ease, background-color 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateX(5px);
        background-color: rgba(255,0,60,0.05) !important;
    }
    .pulse-anim {
        animation: pulse-red 2s infinite;
    }
    @keyframes pulse-red {
        0% { box-shadow: 0 0 0 0 rgba(255, 0, 60, 0.4); }
        70% { box-shadow: 0 0 0 6px rgba(255, 0, 60, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 0, 60, 0); }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const timers = document.querySelectorAll('.countdown-timer');
        
        setInterval(function() {
            const now = new Date().getTime();
            
            timers.forEach(timer => {
                const eventDateStr = timer.getAttribute('data-date');
                // Replace dashes with slashes for Safari compatibility if needed, or just parse
                const eventDate = new Date(eventDateStr.replace(' ', 'T')).getTime();
                const distance = eventDate - now;

                if (distance < 0) {
                    timer.innerHTML = "MISSION LIVE";
                    timer.classList.remove('text-danger');
                    timer.classList.add('text-success');
                    timer.classList.add('pulse-anim');
                    return;
                }

                const d = Math.floor(distance / (1000 * 60 * 60 * 24));
                const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((distance % (1000 * 60)) / 1000);

                timer.innerHTML = `-${d}d ${h}h ${m}m ${s}s`;
            });
        }, 1000);
    });
</script>
