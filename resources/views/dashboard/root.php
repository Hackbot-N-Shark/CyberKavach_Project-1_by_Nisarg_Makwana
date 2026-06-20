<section class="py-5 bg-black" style="min-height: 80vh;">
    <div class="container-fluid px-4 px-md-5">
        <h1 class="font-mono text-danger mb-2">>_ ROOT_TERMINAL</h1>
        <p class="text-secondary font-share mb-5">Welcome, Director <span class="text-white glitch" data-text="<?php echo \App\Core\Security::escape($user['username']); ?>"><?php echo \App\Core\Security::escape($user['username']); ?></span>. Ultimate Oversight active.</p>

        <!-- Custom Tabs -->
        <ul class="nav nav-pills mb-4 border-bottom border-danger pb-2 font-mono" id="rootTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo !isset($_GET['reset_success']) ? 'active' : ''; ?> bg-transparent text-secondary hover-danger rounded-0 px-4" id="faculty-tab" data-bs-toggle="pill" data-bs-target="#faculty" type="button" role="tab">
                    [FACULTY_CONTROL]
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link bg-transparent text-secondary hover-danger rounded-0 px-4" id="audit-tab" data-bs-toggle="pill" data-bs-target="#audit" type="button" role="tab">
                    [SYSTEM_AUDIT]
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo isset($_GET['reset_success']) ? 'active' : ''; ?> bg-transparent text-secondary hover-danger rounded-0 px-4" id="override-tab" data-bs-toggle="pill" data-bs-target="#override" type="button" role="tab">
                    [EMERGENCY_OVERRIDE]
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link bg-transparent text-secondary hover-danger rounded-0 px-4" id="infra-tab" data-bs-toggle="pill" data-bs-target="#infra" type="button" role="tab">
                    [INFRASTRUCTURE]
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link bg-transparent text-secondary hover-danger rounded-0 px-4" id="comms-tab" data-bs-toggle="pill" data-bs-target="#comms" type="button" role="tab">
                    [COMMUNICATIONS]
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="rootTabsContent">
            
            <!-- FACULTY CONTROL TAB -->
            <div class="tab-pane fade <?php echo !isset($_GET['reset_success']) ? 'show active' : ''; ?> animate-in slide-in-from-bottom-10" id="faculty" role="tabpanel" tabindex="0">
                <div class="card card-hacker p-4 rounded-0 border-danger w-50 mx-auto">
                    <h4 class="font-mono text-white mb-4">PENDING_FACULTY_UPGRADES</h4>
                    <p class="text-secondary font-share small mb-4">The following operatives are requesting Core Faculty (Architect) clearance. Only Root can authorize this tier.</p>
                    <ul class="list-group list-group-flush bg-transparent font-share">
                        <?php if (!empty($pending_faculty)): ?>
                            <?php foreach ($pending_faculty as $req): ?>
                                <li class="list-group-item bg-transparent text-white border-secondary px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo \App\Core\Security::escape($req['username']); ?></strong><br>
                                        <small class="text-secondary"><?php echo \App\Core\Security::escape($req['email']); ?></small>
                                    </div>
                                    <form action="/root/manage-faculty" method="POST">
                                        <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                        <input type="hidden" name="user_id" value="<?php echo $req['id']; ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-sm btn-outline-success font-mono">> APPROVE</button>
                                        <button type="submit" name="action" value="reject" class="btn btn-sm btn-outline-danger font-mono">> DENY</button>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item bg-transparent text-secondary px-0 border-0">No pending requests for Architect clearance.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- SYSTEM AUDIT TAB -->
            <div class="tab-pane fade animate-in slide-in-from-bottom-10" id="audit" role="tabpanel" tabindex="0">
                <div class="card bg-black border-danger p-4 rounded-0 h-100" style="max-height: 600px; overflow-y: auto;">
                    <h4 class="font-mono text-danger mb-4"><span class="pulse-anim">></span> SYSTEM_AUDIT_LOG</h4>
                    <table class="table table-dark table-borderless font-mono text-secondary small">
                        <thead>
                            <tr class="text-danger border-bottom border-danger">
                                <th>TIMESTAMP</th>
                                <th>OPERATOR</th>
                                <th>ACTION</th>
                                <th>DETAILS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($system_logs)): ?>
                                <?php foreach ($system_logs as $log): ?>
                                    <tr>
                                        <td class="text-nowrap"><?php echo $log['created_at']; ?></td>
                                        <td><?php echo $log['username'] ? \App\Core\Security::escape($log['username']) : 'SYSTEM'; ?></td>
                                        <td class="<?php echo strpos($log['action'], 'EMERGENCY') !== false ? 'text-danger' : 'text-warning'; ?>">
                                            [<?php echo \App\Core\Security::escape($log['action']); ?>]
                                        </td>
                                        <td><?php echo \App\Core\Security::escape($log['details']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4">No logs recorded yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- EMERGENCY OVERRIDE TAB -->
            <div class="tab-pane fade <?php echo isset($_GET['reset_success']) ? 'show active' : ''; ?> animate-in slide-in-from-bottom-10" id="override" role="tabpanel" tabindex="0">
                <div class="card card-hacker p-4 rounded-0 border-danger mb-4">
                    <h4 class="font-mono text-white mb-4">GLOBAL_CLEARANCE_OVERRIDE</h4>
                    <p class="text-secondary font-share small mb-4">Select any user to forcefully promote, demote, or suspend their access. Use extreme caution.</p>
                    
                    <?php if (isset($_GET['reset_success'])): ?>
                        <div id="passkey-reset-alert" class="alert alert-warning font-mono small rounded-0 border-warning bg-transparent text-warning mb-4" style="word-break: break-all;">
                            [!] CRITICAL: PASSKEY OVERRIDDEN.<br><br>
                            Alias: <strong><?php echo \App\Core\Security::escape(base64_decode($_GET['u'] ?? '')); ?></strong><br>
                            New Passkey: <strong class="text-white bg-danger px-2"><?php echo \App\Core\Security::escape(base64_decode($_GET['p'] ?? '')); ?></strong><br><br>
                            >_ Ensure this passkey is transmitted to the operative via a secure channel immediately. It will not be shown again.
                        </div>
                        <script>
                            // Strip GET parameters from URL to prevent showing this again on reload
                            if (window.history.replaceState) {
                                window.history.replaceState(null, null, window.location.pathname);
                            }
                            // Remove alert from DOM when navigating away from this tab
                            document.addEventListener('DOMContentLoaded', function() {
                                var overrideTab = document.getElementById('override-tab');
                                if (overrideTab) {
                                    overrideTab.addEventListener('hide.bs.tab', function (event) {
                                        var alertBox = document.getElementById('passkey-reset-alert');
                                        if (alertBox) alertBox.remove();
                                    });
                                }
                            });
                        </script>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-dark table-borderless font-share">
                            <thead class="border-bottom border-danger text-danger font-mono">
                                <tr>
                                    <th>ALIAS</th>
                                    <th>CURRENT_ROLE</th>
                                    <th>CURRENT_STATUS</th>
                                    <th>OVERRIDE_ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($all_users as $u): ?>
                                    <tr>
                                        <td>
                                            <?php echo \App\Core\Security::escape($u['username']); ?>
                                            <?php if (in_array($u['email'], $reset_requests)): ?>
                                                <span class="badge bg-danger ms-2 font-mono blink">[!] RESET_REQUESTED</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo \App\Core\Security::escape(strtoupper($u['role'])); ?></td>
                                        <td class="<?php echo $u['status'] === 'suspended' ? 'text-danger' : 'text-success'; ?>"><?php echo \App\Core\Security::escape(strtoupper($u['status'])); ?></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <form action="/root/override-user" method="POST" class="d-flex gap-2">
                                                    <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                                    <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                                    <select name="role" class="form-select form-select-sm bg-black text-white border-secondary rounded-0 font-share" style="width: auto;">
                                                        <option value="operative" <?php echo $u['role']==='operative'?'selected':'';?>>Operative</option>
                                                        <option value="sudo" <?php echo $u['role']==='sudo'?'selected':'';?>>Sudo</option>
                                                        <option value="architect" <?php echo $u['role']==='architect'?'selected':'';?>>Architect</option>
                                                    </select>
                                                    <select name="status" class="form-select form-select-sm bg-black text-white border-secondary rounded-0 font-share" style="width: auto;">
                                                        <option value="active" <?php echo $u['status']==='active'?'selected':'';?>>Active</option>
                                                        <option value="suspended" <?php echo $u['status']==='suspended'?'selected':'';?>>Suspended</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-danger font-mono" onclick="return confirm('EXECUTE GLOBAL OVERRIDE ON USER?');">> EXECUTE</button>
                                                </form>
                                                
                                                <form action="/root/force-reset" method="POST">
                                                    <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                                    <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-warning font-mono" onclick="return confirm('FORCE PASSWORD RESET? This action cannot be undone.');">> RESET_PASSKEY</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- INFRASTRUCTURE TAB -->
            <div class="tab-pane fade animate-in slide-in-from-bottom-10" id="infra" role="tabpanel" tabindex="0">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card card-hacker p-5 rounded-0 border-danger text-center h-100">
                            <div class="display-1 text-danger mb-3" style="opacity: 0.5;">💾</div>
                            <h4 class="font-mono text-white mb-3">[ DATABASE_SNAPSHOT ]</h4>
                            <p class="text-secondary font-share small mb-4">Extract the raw `cyberkavach.sqlite` file. Ensure this file is kept strictly confidential.</p>
                            <a href="/root/download-backup" class="btn btn-outline-danger font-mono">> INITIATE_DOWNLOAD</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-hacker p-5 rounded-0 border-secondary text-center h-100" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255, 0, 60, 0.05) 10px, rgba(255, 0, 60, 0.05) 20px);">
                            <div class="display-1 text-secondary mb-3" style="opacity: 0.5;">🖋️</div>
                            <h4 class="font-mono text-white mb-3">[ ROOT_SIGNATURE_GATE ]</h4>
                            <p class="text-secondary font-share small mb-4">Review Faculty-approved Certificate templates. Apply the Director's final digital signature.</p>
                            
                            <?php if (!empty($pending_root_certs)): ?>
                                <div class="table-responsive mb-4 text-start">
                                    <table class="table table-dark table-borderless font-share align-middle mb-0">
                                        <thead>
                                            <tr class="text-secondary font-mono small border-bottom border-secondary">
                                                <th>EVENT</th>
                                                <th>FACULTY_SIGNED_PREVIEW</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pending_root_certs as $cert): ?>
                                            <tr>
                                                <td><?php echo \App\Core\Security::escape($cert['event_title']); ?></td>
                                                <td>
                                                    <a href="<?php echo \App\Core\Security::escape($cert['template_path']); ?>" target="_blank">
                                                        <img src="<?php echo \App\Core\Security::escape($cert['template_path']); ?>" alt="preview" style="max-height: 50px; border: 1px solid #444;">
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>

                            <form action="/cert/manage-workflow" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                <input type="hidden" name="action" value="sign_root">
                                
                                <div class="mb-3 text-start mx-auto w-75">
                                    <label class="form-label text-secondary font-mono small">>_ PENDING_FINALIZATIONS</label>
                                    <select name="cert_id" id="root_cert_select" class="form-select bg-black text-white border-secondary rounded-0 font-share shadow-none" required onchange="document.getElementById('root_download_btn').href=this.options[this.selectedIndex].getAttribute('data-path');">
                                        <option value="">-- Select Template --</option>
                                        <?php if (!empty($pending_root_certs)): ?>
                                            <?php foreach ($pending_root_certs as $cert): ?>
                                                <option value="<?php echo $cert['id']; ?>" data-path="<?php echo $cert['template_path']; ?>"><?php echo \App\Core\Security::escape($cert['event_title']); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <a id="root_download_btn" href="#" target="_blank" class="btn btn-sm btn-outline-danger font-mono w-75 mx-auto mb-3" <?php echo empty($pending_root_certs) ? 'disabled' : ''; ?>>> DOWNLOAD_FACULTY_TEMPLATE</a>

                                <div class="mb-4 text-start mx-auto w-75">
                                    <label class="form-label text-secondary font-mono small">>_ FINAL_SIGNED_PAYLOAD (JPG/PNG)</label>
                                    <input name="signed_image" class="form-control bg-transparent text-white border-secondary rounded-0 font-share shadow-none" type="file" accept=".jpg,.jpeg,.png" required>
                                </div>

                                <button type="submit" class="btn btn-outline-secondary font-mono w-75 mx-auto" <?php echo empty($pending_root_certs) ? 'disabled' : ''; ?>>> FINALIZE_TEMPLATE</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- COMMUNICATIONS TAB -->
            <div class="tab-pane fade animate-in slide-in-from-bottom-10" id="comms" role="tabpanel" tabindex="0">
                <h3 class="font-mono text-white mb-4">[ COMMUNICATIONS_TERMINAL ]</h3>
                
                <h5 class="font-mono text-danger mb-3">> INCOMING_TRANSMISSIONS</h5>
                <div class="row g-4">
                    <?php if (empty($contact_messages)): ?>
                        <div class="col-12"><p class="text-secondary font-mono">> NO_UNREAD_MESSAGES.</p></div>
                    <?php else: ?>
                        <?php foreach ($contact_messages as $msg): ?>
                            <div class="col-md-6">
                                <div class="card card-hacker p-4 border-secondary rounded-0 h-100">
                                    <div class="d-flex justify-content-between mb-3">
                                        <h5 class="font-mono text-white mb-0"><?php echo \App\Core\Security::escape($msg['name']); ?></h5>
                                        <span class="text-secondary small font-share"><?php echo date('M j, H:i', strtotime($msg['created_at'])); ?></span>
                                    </div>
                                    <p class="text-danger small font-mono mb-3">> SENDER: <?php echo \App\Core\Security::escape($msg['email']); ?></p>
                                    <div class="bg-black p-3 border border-secondary text-gray-300 font-share mb-4" style="height: 100px; overflow-y: auto;">
                                        <?php echo nl2br(\App\Core\Security::escape($msg['message'])); ?>
                                    </div>
                                    <button class="btn btn-outline-secondary font-mono w-100 mt-auto" onclick="this.innerHTML='> TRANSMISSION_CLEARED'; this.classList.remove('btn-outline-secondary'); this.classList.add('btn-secondary'); this.disabled=true;">> MARK_AS_READ</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <hr class="border-danger my-5 opacity-25">

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card card-hacker p-5 rounded-0 border-danger h-100">
                            <h3 class="font-mono text-white mb-4">[ BROADCAST_COMMUNICATIONS ]</h3>
                            <p class="text-secondary font-share small mb-4">Send encrypted alerts to all enrolled operatives of an event or globally to all users.</p>
                            <form action="/broadcast" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                <div class="mb-3">
                                    <label class="form-label text-danger font-mono small">>_ TARGET_EVENT</label>
                                    <select name="target" class="form-select bg-black text-white border-danger rounded-0 font-share shadow-none" required>
                                        <option value="general">[GLOBAL] All Operatives</option>
                                        <?php if (!empty($all_events)): ?>
                                            <?php foreach ($all_events as $event): ?>
                                                <option value="<?php echo $event['id']; ?>"><?php echo \App\Core\Security::escape($event['title']); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-danger font-mono small">>_ TRANSMISSION_PAYLOAD</label>
                                    <textarea name="message" rows="4" class="form-control bg-transparent text-white border-danger rounded-0 font-share shadow-none" placeholder="Enter alert message..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger font-mono w-100 btn-hacker">> TRANSMIT_ALERT</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-hacker p-5 rounded-0 border-danger text-center h-100" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255, 0, 60, 0.05) 10px, rgba(255, 0, 60, 0.05) 20px);">
                            <div class="display-1 text-danger mb-3" style="opacity: 0.5;">🗞️</div>
                            <h4 class="font-mono text-white mb-3">[ GLOBAL_NEWSLETTER_BLAST ]</h4>
                            <p class="text-secondary font-share small mb-4">
                                The Newsletter Engine is currently undergoing upgrades. Broadcast capability offline.
                            </p>
                            <button class="btn btn-outline-danger font-mono w-50 mx-auto disabled">> SYSTEM_OFFLINE</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
    /* Custom Admin Animations */
    .nav-pills .nav-link.active {
        background-color: transparent !important;
        color: var(--ck-danger) !important;
        border-bottom: 2px solid var(--ck-danger);
        text-shadow: 0 0 8px rgba(255, 0, 60, 0.5);
    }
    .pulse-anim {
        animation: pulse-red 2s infinite;
    }
    @keyframes pulse-red {
        0% { opacity: 1; }
        50% { opacity: 0; }
        100% { opacity: 1; }
    }
</style>
