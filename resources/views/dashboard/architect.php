<section class="py-5 bg-black" style="min-height: 80vh;">
    <div class="container">
        <h1 class="font-mono text-danger mb-2">>_ ARCHITECT_TERMINAL</h1>
        <p class="text-secondary font-share mb-5">Welcome, Core Faculty <span class="text-white glitch" data-text="<?php echo \App\Core\Security::escape($user['username']); ?>"><?php echo \App\Core\Security::escape($user['username']); ?></span>. Gatekeeper clearance active.</p>

        <!-- Custom Tabs -->
        <ul class="nav nav-pills mb-4 border-bottom border-danger pb-2 font-mono" id="architectTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active bg-transparent text-secondary hover-danger rounded-0 px-4" id="events-tab" data-bs-toggle="pill" data-bs-target="#events" type="button" role="tab">
                    [EVENT_APPROVALS]
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link bg-transparent text-secondary hover-danger rounded-0 px-4" id="roles-tab" data-bs-toggle="pill" data-bs-target="#roles" type="button" role="tab">
                    [CLEARANCE_CONTROL]
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link bg-transparent text-secondary hover-danger rounded-0 px-4" id="intel-tab" data-bs-toggle="pill" data-bs-target="#intel" type="button" role="tab">
                    [INTEL_QUEUE]
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link bg-transparent text-secondary hover-danger rounded-0 px-4" id="certs-tab" data-bs-toggle="pill" data-bs-target="#certs" type="button" role="tab">
                    [CERTIFICATES]
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link bg-transparent text-secondary hover-danger rounded-0 px-4" id="blog-tab" data-bs-toggle="pill" data-bs-target="#blog" type="button" role="tab">
                    [BLOG_CMS]
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link bg-transparent text-secondary hover-danger rounded-0 px-4" id="broadcast-tab" data-bs-toggle="pill" data-bs-target="#broadcast" type="button" role="tab">
                    [BROADCAST]
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="architectTabsContent">
            
            <!-- EVENTS TAB -->
            <div class="tab-pane fade show active animate-in slide-in-from-bottom-10" id="events" role="tabpanel" tabindex="0">
                <div class="card card-hacker p-4 rounded-0 border-danger mb-4">
                    <h4 class="font-mono text-white mb-4">PROPOSED_EVENTS_QUEUE</h4>
                    <div class="table-responsive">
                        <table class="table table-dark table-borderless font-share">
                            <thead class="border-bottom border-danger text-danger font-mono">
                                <tr>
                                    <th>EVENT_TITLE</th>
                                    <th>DATE</th>
                                    <th>DESCRIPTION</th>
                                    <th>AUTHORIZATION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($pending_events)): ?>
                                    <?php foreach ($pending_events as $event): ?>
                                        <tr>
                                            <td><?php echo \App\Core\Security::escape($event['title']); ?></td>
                                            <td><?php echo date('M j, Y H:i', strtotime($event['event_date'])); ?></td>
                                            <td class="text-truncate" style="max-width: 250px;"><?php echo \App\Core\Security::escape($event['description']); ?></td>
                                            <td>
                                                <form action="/architect/manage-event" method="POST" class="d-inline">
                                                    <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                                    <button type="submit" name="action" value="publish" class="btn btn-sm btn-outline-success font-mono">> PUBLISH</button>
                                                </form>
                                                <form action="/event/delete" method="POST" class="d-inline">
                                                    <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger font-mono" onclick="return confirm('OBLITERATE EVENT?');">> DELETE</button>
                                                </form>
                                                <a href="/event/export-csv?event_id=<?php echo $event['id']; ?>" class="btn btn-sm btn-secondary font-mono mt-1">> EXPORT_CSV</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="text-secondary text-center">No pending event proposals.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card card-hacker p-4 rounded-0 border-danger mb-4">
                    <h4 class="font-mono text-white mb-4">ACTIVE_EVENTS_MATRIX</h4>
                    <div class="table-responsive">
                        <table class="table table-dark table-borderless font-share">
                            <thead class="border-bottom border-danger text-danger font-mono">
                                <tr>
                                    <th>EVENT_TITLE</th>
                                    <th>DATE</th>
                                    <th>AUTHORIZATION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($active_events)): ?>
                                    <?php foreach ($active_events as $event): ?>
                                        <tr>
                                            <td><?php echo \App\Core\Security::escape($event['title']); ?></td>
                                            <td><?php echo date('M j, Y H:i', strtotime($event['event_date'])); ?></td>
                                            <td>
                                                <form action="/event/complete" method="POST" class="d-inline">
                                                    <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-warning font-mono" onclick="return confirm('CONCLUDE EVENT?');">> END_EVENT</button>
                                                </form>
                                                <form action="/event/delete" method="POST" class="d-inline">
                                                    <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger font-mono" onclick="return confirm('OBLITERATE EVENT?');">> DELETE</button>
                                                </form>
                                                <a href="/event/export-csv?event_id=<?php echo $event['id']; ?>" class="btn btn-sm btn-secondary font-mono mt-1">> EXPORT_CSV</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="3" class="text-secondary text-center">No active events found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- CLEARANCE CONTROL TAB -->
            <div class="tab-pane fade animate-in slide-in-from-bottom-10" id="roles" role="tabpanel" tabindex="0">
                <div class="row">
                    <!-- Pending Requests -->
                    <div class="col-md-6 mb-4">
                        <div class="card card-hacker p-4 rounded-0 border-danger h-100">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="font-mono text-white mb-0">PENDING_SUDO_REQUESTS</h4>
                                <?php $count = count($pending_roles); ?>
                                <span class="badge <?php echo $count >= 5 ? 'bg-danger pulse-anim' : 'bg-warning text-black'; ?> font-mono">
                                    <?php echo $count; ?>/5 QUEUE
                                </span>
                            </div>
                            <?php if ($count >= 5): ?>
                                <div class="alert bg-black border border-danger text-danger font-mono small">
                                    > ALERT: MAX_CAPACITY REACHED. REGISTRATIONS TEMPORARILY CLOSED.
                                </div>
                            <?php endif; ?>
                            
                            <ul class="list-group list-group-flush bg-transparent font-share">
                                <?php if (!empty($pending_roles)): ?>
                                    <?php foreach ($pending_roles as $user_req): ?>
                                        <li class="list-group-item bg-transparent text-white border-secondary px-0 d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong><?php echo \App\Core\Security::escape($user_req['username']); ?></strong><br>
                                                <small class="text-secondary"><?php echo \App\Core\Security::escape($user_req['email']); ?></small>
                                            </div>
                                            <form action="/architect/manage-role" method="POST">
                                                <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                                <input type="hidden" name="user_id" value="<?php echo $user_req['id']; ?>">
                                                <button type="submit" name="action" value="approve" class="btn btn-sm btn-outline-success font-mono">> APPROVE</button>
                                                <button type="submit" name="action" value="reject" class="btn btn-sm btn-outline-danger font-mono">> DENY</button>
                                            </form>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="list-group-item bg-transparent text-secondary px-0 border-0">No active upgrade requests.</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Active Coordinators -->
                    <div class="col-md-6 mb-4">
                        <div class="card card-hacker p-4 rounded-0 border-danger h-100">
                            <h4 class="font-mono text-white mb-4">ACTIVE_SUDO_PERSONNEL</h4>
                            <ul class="list-group list-group-flush bg-transparent font-share">
                                <?php if (!empty($active_sudos)): ?>
                                    <?php foreach ($active_sudos as $sudo): ?>
                                        <li class="list-group-item bg-transparent text-white border-secondary px-0 d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong><?php echo \App\Core\Security::escape($sudo['username']); ?></strong>
                                            </div>
                                            <form action="/architect/manage-role" method="POST">
                                                <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                                <input type="hidden" name="user_id" value="<?php echo $sudo['id']; ?>">
                                                <button type="submit" name="action" value="revoke" class="btn btn-sm btn-danger font-mono" onclick="return confirm('REVOKE CLEARANCE?');">> REVOKE</button>
                                            </form>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="list-group-item bg-transparent text-secondary px-0 border-0">No active coordinators found.</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- INTEL TAB -->
            <div class="tab-pane fade animate-in slide-in-from-bottom-10" id="intel" role="tabpanel" tabindex="0">
                <div class="card card-hacker p-4 rounded-0 border-danger">
                    <h4 class="font-mono text-white mb-4">RESOURCE_VERIFICATION_QUEUE</h4>
                    <p class="text-secondary font-share small mb-4">Approve submitted resources before they are published to the Operative Intel tab.</p>
                    <div class="table-responsive">
                        <table class="table table-dark table-borderless font-share">
                            <thead class="border-bottom border-danger text-danger font-mono">
                                <tr>
                                    <th>SUBMITTED_BY</th>
                                    <th>RESOURCE_TITLE</th>
                                    <th>PAYLOAD_URL</th>
                                    <th>AUTHORIZATION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($pending_resources)): ?>
                                    <?php foreach ($pending_resources as $res): ?>
                                        <tr>
                                            <td><?php echo \App\Core\Security::escape($res['username']); ?></td>
                                            <td><?php echo \App\Core\Security::escape($res['title']); ?></td>
                                            <td><a href="<?php echo \App\Core\Security::escape($res['url']); ?>" target="_blank" class="text-secondary hover-danger"><?php echo \App\Core\Security::escape($res['url']); ?></a></td>
                                            <td>
                                                <form action="/architect/manage-resource" method="POST" class="d-inline">
                                                    <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                                    <input type="hidden" name="resource_id" value="<?php echo $res['id']; ?>">
                                                    <button type="submit" name="action" value="approve" class="btn btn-sm btn-outline-success font-mono">> PUBLISH</button>
                                                    <button type="submit" name="action" value="reject" class="btn btn-sm btn-outline-danger font-mono">> DROP</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="text-secondary text-center">No pending resources.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- CERTIFICATES TAB -->
            <div class="tab-pane fade animate-in slide-in-from-bottom-10" id="certs" role="tabpanel" tabindex="0">
                <div class="row g-4">
                    <!-- Stage 2: Initial Signature -->
                    <div class="col-md-6">
                        <div class="card card-hacker p-5 rounded-0 border-danger text-center h-100" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255, 0, 60, 0.05) 10px, rgba(255, 0, 60, 0.05) 20px);">
                            <div class="display-1 text-danger mb-3" style="opacity: 0.5;">🖋️</div>
                            <h4 class="font-mono text-white mb-3">[ STAGE_2: APPLY_SIGNATURE ]</h4>
                            <p class="text-secondary font-share small mb-4">
                                Review base certificates uploaded by Coordinators. Download, apply Faculty signature, and upload the signed payload to forward to the Director.
                            </p>
                            <form action="/cert/manage-workflow" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                <input type="hidden" name="action" value="sign_faculty">
                                
                                <div class="mb-3 text-start mx-auto w-75">
                                    <label class="form-label text-danger font-mono small">>_ PENDING_TEMPLATES</label>
                                    <select name="cert_id" id="faculty_cert_select" class="form-select bg-black text-white border-danger rounded-0 font-share shadow-none" required onchange="document.getElementById('faculty_download_btn').href=this.options[this.selectedIndex].getAttribute('data-path');">
                                        <option value="">-- Select Template --</option>
                                        <?php if (!empty($pending_faculty_certs)): ?>
                                            <?php foreach ($pending_faculty_certs as $cert): ?>
                                                <option value="<?php echo $cert['id']; ?>" data-path="<?php echo $cert['template_path']; ?>"><?php echo \App\Core\Security::escape($cert['event_title']); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <a id="faculty_download_btn" href="#" target="_blank" class="btn btn-sm btn-outline-secondary font-mono w-75 mx-auto mb-3" <?php echo empty($pending_faculty_certs) ? 'disabled' : ''; ?>>> DOWNLOAD_BASE_TEMPLATE</a>

                                <div class="mb-4 text-start mx-auto w-75">
                                    <label class="form-label text-danger font-mono small">>_ FACULTY_SIGNED_PAYLOAD (JPG/PNG)</label>
                                    <input name="signed_image" class="form-control bg-transparent text-white border-danger rounded-0 font-share shadow-none" type="file" accept=".jpg,.jpeg,.png" required>
                                </div>

                                <button type="submit" class="btn btn-outline-danger font-mono w-75 mx-auto" <?php echo empty($pending_faculty_certs) ? 'disabled' : ''; ?>>> UPLOAD_AND_FORWARD</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- PREVIEW & RE-UPLOAD PENDING TEMPLATES -->
                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <div class="card card-hacker p-5 rounded-0 border-secondary">
                            <h4 class="font-mono text-white mb-3">[ AWAITING_ROOT_SIGNATURE ]</h4>
                            <p class="text-secondary font-share small mb-4">These faculty-signed templates are awaiting the Director's final signature. If you made a mistake during signing, you can re-upload them now.</p>
                            
                            <div class="table-responsive">
                                <table class="table table-dark table-borderless font-share align-middle">
                                    <thead>
                                        <tr class="text-secondary font-mono small border-bottom border-secondary">
                                            <th>EVENT</th>
                                            <th>FACULTY_SIGNED_PREVIEW</th>
                                            <th>ACTION_OVERWRITE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($pending_root_certs)): ?>
                                            <?php foreach ($pending_root_certs as $cert): ?>
                                            <tr>
                                                <td><?php echo \App\Core\Security::escape($cert['event_title']); ?></td>
                                                <td>
                                                    <a href="<?php echo \App\Core\Security::escape($cert['template_path']); ?>" target="_blank">
                                                        <img src="<?php echo \App\Core\Security::escape($cert['template_path']); ?>" alt="preview" style="max-height: 60px; border: 1px solid #444;">
                                                    </a>
                                                </td>
                                                <td>
                                                    <form action="/cert/manage-workflow" method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
                                                        <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                                        <input type="hidden" name="action" value="reupload_faculty">
                                                        <input type="hidden" name="cert_id" value="<?php echo $cert['id']; ?>">
                                                        <input type="file" name="signed_image" class="form-control form-control-sm bg-black text-white border-secondary rounded-0 w-auto" accept="image/*" required>
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary font-mono">> OVERWRITE</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="3" class="text-secondary">No templates awaiting Root signature.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Stage 4: Final Verification -->
                    <div class="col-md-6">
                        <div class="card card-hacker p-5 rounded-0 border-success text-center h-100">
                            <div class="display-1 text-success mb-3" style="opacity: 0.5;">⚙️</div>
                            <h4 class="font-mono text-white mb-3">[ STAGE_4: VERIFY_ROOT_SIGNATURE ]</h4>
                            <p class="text-secondary font-share small mb-4">
                                The Director has finalized these templates. Verify the cryptographic signatures to release them for Sudo mass-generation.
                            </p>

                            <?php if (!empty($pending_verify_certs)): ?>
                                <div class="table-responsive mb-4 text-start mx-auto w-75">
                                    <table class="table table-dark table-borderless font-share align-middle mb-0">
                                        <thead>
                                            <tr class="text-secondary font-mono small border-bottom border-secondary">
                                                <th>EVENT</th>
                                                <th>ROOT_SIGNED_PREVIEW</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pending_verify_certs as $cert): ?>
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

                            <form action="/cert/manage-workflow" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                <input type="hidden" name="action" value="verify_generate">
                                
                                <div class="mb-3 text-start mx-auto w-75">
                                    <label class="form-label text-success font-mono small">>_ PENDING_VERIFICATIONS</label>
                                    <select name="cert_id" id="faculty_verify_select" class="form-select bg-black text-white border-success rounded-0 font-share shadow-none" required onchange="document.getElementById('faculty_verify_download_btn').href=this.options[this.selectedIndex].getAttribute('data-path');">
                                        <option value="">-- Select Template --</option>
                                        <?php if (!empty($pending_verify_certs)): ?>
                                            <?php foreach ($pending_verify_certs as $cert): ?>
                                                <option value="<?php echo $cert['id']; ?>" data-path="<?php echo $cert['template_path']; ?>"><?php echo \App\Core\Security::escape($cert['event_title']); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <a id="faculty_verify_download_btn" href="#" target="_blank" class="btn btn-sm btn-outline-secondary font-mono w-75 mx-auto mb-3" <?php echo empty($pending_verify_certs) ? 'disabled' : ''; ?>>> DOWNLOAD_ROOT_SIGNED_PAYLOAD</a>

                                <button type="submit" class="btn btn-success text-black font-mono w-75 mx-auto" <?php echo empty($pending_verify_certs) ? 'disabled' : ''; ?>>> VERIFY_AND_RELEASE</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BLOG CMS TAB -->
            <div class="tab-pane fade animate-in slide-in-from-bottom-10" id="blog" role="tabpanel" tabindex="0">
                <div class="card card-hacker p-5 rounded-0 border-danger h-100">
                    <h3 class="font-mono text-white mb-4">[ PUBLISH_KAVACH_LOG ]</h3>
                    <form action="/admin/blog/create" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                        <div class="mb-3">
                            <label class="form-label text-danger font-mono small">>_ ARTICLE_TITLE</label>
                            <input type="text" name="title" class="form-control bg-transparent text-white border-secondary rounded-0 font-share shadow-none" required placeholder="e.g. Exploiting Buffer Overflows in C">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-danger font-mono small">>_ CONTENT (Supports Basic Markdown)</label>
                            <textarea name="content" class="form-control bg-transparent text-white border-secondary rounded-0 font-share shadow-none" rows="10" required placeholder="Write your write-up here. Use **bold** and ```code blocks```."></textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-danger font-mono w-100">> DEPLOY_TO_PUBLIC_LOGS</button>
                    </form>
                </div>
            </div>

            <!-- BROADCAST TAB -->
            <div class="tab-pane fade animate-in slide-in-from-bottom-10" id="broadcast" role="tabpanel" tabindex="0">
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

        </div>
    </div>
</section>

<style>
    /* Admin UI Animations */
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
        0% { box-shadow: 0 0 0 0 rgba(255, 0, 60, 0.4); }
        70% { box-shadow: 0 0 0 6px rgba(255, 0, 60, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 0, 60, 0); }
    }
</style>
