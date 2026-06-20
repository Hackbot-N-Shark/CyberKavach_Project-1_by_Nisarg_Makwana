<section class="py-5 bg-black" style="min-height: 80vh;">
    <div class="container">
        <h1 class="font-mono text-danger mb-2">>_ SUDO_TERMINAL</h1>
        <p class="text-secondary font-share mb-5">Welcome, Student Coordinator <span class="text-white glitch" data-text="<?php echo \App\Core\Security::escape($user['username']); ?>"><?php echo \App\Core\Security::escape($user['username']); ?></span>. Accessing logistics matrix.</p>

        <!-- Custom Tabs -->
        <ul class="nav nav-pills mb-4 border-bottom border-danger pb-2 font-mono" id="sudoTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active bg-transparent text-secondary hover-danger rounded-0 px-4" id="proposals-tab" data-bs-toggle="pill" data-bs-target="#proposals" type="button" role="tab">
                    [PROPOSALS]
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link bg-transparent text-secondary hover-danger rounded-0 px-4" id="logistics-tab" data-bs-toggle="pill" data-bs-target="#logistics" type="button" role="tab">
                    [LOGISTICS]
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link bg-transparent text-secondary hover-danger rounded-0 px-4" id="attendance-tab" data-bs-toggle="pill" data-bs-target="#attendance" type="button" role="tab">
                    [ATTENDANCE]
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link bg-transparent text-secondary hover-danger rounded-0 px-4" id="reports-tab" data-bs-toggle="pill" data-bs-target="#reports" type="button" role="tab">
                    [CERTIFICATES]
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="sudoTabsContent">
            
            <!-- PROPOSALS TAB -->
            <div class="tab-pane fade show active animate-in slide-in-from-bottom-10" id="proposals" role="tabpanel" tabindex="0">
                <div class="row">
                    <div class="col-lg-5 mb-4 mb-lg-0">
                        <div class="card card-hacker p-4 rounded-0 border-danger h-100">
                            <h4 class="font-mono text-white mb-4">DRAFT_EVENT_PROPOSAL</h4>
                            <form action="/coordinator/create-proposal" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                <div class="mb-3">
                                    <label class="form-label text-danger font-mono small">>_ EVENT_TITLE</label>
                                    <input type="text" name="title" class="form-control bg-transparent text-white border-danger rounded-0 font-share shadow-none" required placeholder="e.g. Winter Hackathon 2026">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-danger font-mono small">>_ T_MINUS_DATE</label>
                                    <input type="datetime-local" name="event_date" class="form-control bg-transparent text-white border-danger rounded-0 font-share shadow-none" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-danger font-mono small">>_ MAX_OPERATIVES_ALLOWED</label>
                                    <input type="number" name="max_participants" min="1" class="form-control bg-transparent text-white border-danger rounded-0 font-share shadow-none" placeholder="Leave empty for unlimited">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-danger font-mono small">>_ BRIEFING_DESC</label>
                                    <textarea name="description" rows="4" class="form-control bg-transparent text-white border-danger rounded-0 font-share shadow-none" required placeholder="Enter objective and details..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-outline-danger font-mono w-100 btn-hacker">> SUBMIT_TO_ARCHITECT</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="card card-hacker p-4 rounded-0 border-danger h-100">
                            <h4 class="font-mono text-white mb-4">EVENT_DATABASE</h4>
                            <div class="table-responsive">
                                <table class="table table-dark table-borderless font-share">
                                    <thead class="border-bottom border-danger text-danger font-mono">
                                        <tr>
                                            <th>TITLE</th>
                                            <th>DATE</th>
                                            <th>STATUS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($events)): ?>
                                            <?php foreach ($events as $event): ?>
                                                <tr>
                                                    <td><?php echo \App\Core\Security::escape($event['title']); ?></td>
                                                    <td><?php echo date('M j, Y', strtotime($event['event_date'])); ?></td>
                                                    <td>
                                                        <?php if ($event['status'] === 'pending_approval'): ?>
                                                            <span class="badge bg-warning text-black font-mono">PENDING_AUTHORIZATION</span>
                                                        <?php elseif ($event['status'] === 'upcoming'): ?>
                                                            <span class="badge bg-success text-black font-mono pulse-anim">LIVE</span>
                                                        <?php elseif ($event['status'] === 'completed'): ?>
                                                            <span class="badge bg-secondary text-black font-mono">CONCLUDED</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="3" class="text-secondary">No records found.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LOGISTICS TAB -->
            <div class="tab-pane fade animate-in slide-in-from-bottom-10" id="logistics" role="tabpanel" tabindex="0">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card card-hacker p-4 rounded-0 border-danger h-100">
                            <h4 class="font-mono text-white mb-4">[ VOLUNTEER_ASSIGNMENT ]</h4>
                            <p class="text-secondary font-share small mb-4">Assign operatives to active events as support staff.</p>
                            <form>
                                <div class="mb-3">
                                    <label class="form-label text-danger font-mono small">>_ SELECT_EVENT</label>
                                    <select class="form-select bg-black text-white border-danger rounded-0 font-share shadow-none">
                                        <option>Zero Day Chase Hackathon</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-danger font-mono small">>_ OPERATIVE_ID</label>
                                    <input type="text" class="form-control bg-transparent text-white border-danger rounded-0 font-share shadow-none" placeholder="Enter Operative ID">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-danger font-mono small">>_ ASSIGNMENT_ROLE</label>
                                    <input type="text" class="form-control bg-transparent text-white border-danger rounded-0 font-share shadow-none" placeholder="e.g. Registration Desk, Network Monitor">
                                </div>
                                <button type="button" class="btn btn-outline-danger font-mono w-100">> ASSIGN_STAFF</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card card-hacker p-4 rounded-0 border-danger h-100">
                            <h4 class="font-mono text-white mb-4">[ BROADCAST_COMMUNICATIONS ]</h4>
                            <p class="text-secondary font-share small mb-4">Send encrypted alerts to all enrolled operatives of an event.</p>
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

            <!-- ATTENDANCE TAB -->
            <div class="tab-pane fade <?php echo isset($_GET['attendance_event_id']) ? 'show active' : ''; ?> animate-in slide-in-from-bottom-10" id="attendance" role="tabpanel" tabindex="0">
                <div class="card card-hacker p-4 rounded-0 border-danger">
                    <h4 class="font-mono text-white mb-4">[ ATTENDANCE_MATRIX ]</h4>
                    <p class="text-secondary font-share small mb-4">Select a CONCLUDED event to log physical attendance and input rank metrics.</p>
                    
                    <div class="mb-4 w-50">
                        <label class="form-label text-danger font-mono small">>_ CONCLUDED_EVENTS</label>
                        <select class="form-select bg-black text-white border-danger rounded-0 font-share shadow-none" onchange="window.location.href='/dashboard?attendance_event_id=' + this.value + '#attendance'">
                            <option value="">-- Select Event --</option>
                            <?php if (!empty($completed_events)): ?>
                                <?php foreach ($completed_events as $ce): ?>
                                    <option value="<?php echo $ce['id']; ?>" <?php echo (isset($attendance_event_id) && $attendance_event_id == $ce['id']) ? 'selected' : ''; ?>>
                                        <?php echo \App\Core\Security::escape($ce['title']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <?php if (isset($attendance_event_id) && isset($attendance_roster)): ?>
                    <form id="attendance-form" action="/coordinator/save-attendance" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                        <input type="hidden" name="event_id" value="<?php echo $attendance_event_id; ?>">
                        <div class="table-responsive mb-4">
                            <table class="table table-dark table-borderless font-share">
                                <thead class="border-bottom border-danger text-danger font-mono">
                                    <tr>
                                        <th>ATTENDED</th>
                                        <th>OPERATIVE_ALIAS</th>
                                        <th>RANK_ASSIGNMENT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($attendance_roster)): ?>
                                        <?php foreach ($attendance_roster as $ar): ?>
                                        <tr>
                                            <td>
                                                <input class="form-check-input bg-transparent border-danger" type="checkbox" name="attendance[<?php echo $ar['user_id']; ?>][attended]" value="1" id="att<?php echo $ar['user_id']; ?>" <?php echo $ar['attended'] ? 'checked' : ''; ?>>
                                            </td>
                                            <td><label for="att<?php echo $ar['user_id']; ?>"><?php echo \App\Core\Security::escape($ar['username']); ?></label></td>
                                            <td>
                                                <select name="attendance[<?php echo $ar['user_id']; ?>][rank]" class="form-select form-select-sm bg-black text-white border-secondary rounded-0 font-share shadow-none" style="width: auto;">
                                                    <option value="Participant" <?php echo ($ar['rank'] === 'Participant') ? 'selected' : ''; ?>>Participant</option>
                                                    <option value="1st Place Winner" <?php echo ($ar['rank'] === '1st Place Winner') ? 'selected' : ''; ?>>1st Place Winner</option>
                                                    <option value="Runner Up" <?php echo ($ar['rank'] === 'Runner Up') ? 'selected' : ''; ?>>Runner Up</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="3" class="text-secondary text-center">No operatives registered for this event.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-outline-success font-mono">> COMMIT_ATTENDANCE_RECORD</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- REPORTS & GALLERY TAB -->
            <div class="tab-pane fade animate-in slide-in-from-bottom-10" id="reports" role="tabpanel" tabindex="0">
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="card card-hacker p-5 rounded-0 border-danger text-center h-100" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255, 0, 60, 0.05) 10px, rgba(255, 0, 60, 0.05) 20px);">
                            <div class="display-1 text-danger mb-3" style="opacity: 0.5;">📄</div>
                            <h4 class="font-mono text-white mb-3">[ CERTIFICATE_INITIATION ]</h4>
                            <p class="text-secondary font-share small mb-4">
                                Upload the master blank certificate template. This initiates the digital signature chain.
                            </p>
                            <form action="/cert/upload-template" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                <div class="mb-3 text-start">
                                    <label class="form-label text-danger font-mono small">>_ SELECT_EVENT</label>
                                    <select name="event_id" class="form-select bg-black text-white border-danger rounded-0 font-share shadow-none" required>
                                        <?php if (!empty($completed_events)): ?>
                                            <?php foreach ($completed_events as $ce): ?>
                                                <option value="<?php echo $ce['id']; ?>"><?php echo \App\Core\Security::escape($ce['title']); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="mb-3 text-start">
                                    <label class="form-label text-danger font-mono small">>_ BASE_TEMPLATE (JPG/PNG)</label>
                                    <input name="template_image" class="form-control bg-transparent text-white border-danger rounded-0 font-share shadow-none" type="file" accept=".jpg,.jpeg,.png" required>
                                </div>
                                <button type="submit" class="btn btn-outline-danger font-mono w-100">> INITIATE_WORKFLOW</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-hacker p-5 rounded-0 border-success text-center h-100">
                            <div class="display-1 text-success mb-3" style="opacity: 0.5;">📸</div>
                            <h4 class="font-mono text-white mb-3">[ EVENT_GALLERY_UPLOAD ]</h4>
                            <p class="text-secondary font-share small mb-4">Upload screenshots or photos from concluded events to the Operative Mission board.</p>
                            <form action="/event/upload-gallery" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                <div class="mb-3 text-start">
                                    <label class="form-label text-success font-mono small">>_ CONCLUDED_EVENTS</label>
                                    <select name="event_id" class="form-select bg-black text-white border-success rounded-0 font-share shadow-none" required>
                                        <?php if (!empty($completed_events)): ?>
                                            <?php foreach ($completed_events as $ce): ?>
                                                <option value="<?php echo $ce['id']; ?>"><?php echo \App\Core\Security::escape($ce['title']); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="mb-3 text-start">
                                    <label class="form-label text-success font-mono small">>_ MEDIA_PAYLOAD</label>
                                    <input name="gallery_image" class="form-control bg-transparent text-white border-success rounded-0 font-share shadow-none" type="file" accept="image/*" required>
                                </div>
                                <button type="submit" class="btn btn-outline-success font-mono w-100">> UPLOAD_MEDIA</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- PREVIEW & RE-UPLOAD PENDING TEMPLATES -->
                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <div class="card card-hacker p-5 rounded-0 border-secondary">
                            <h4 class="font-mono text-white mb-3">[ AWAITING_FACULTY_SIGNATURE ]</h4>
                            <p class="text-secondary font-share small mb-4">These base templates are awaiting Faculty approval. If you made a mistake, you can re-upload them now.</p>
                            
                            <div class="table-responsive">
                                <table class="table table-dark table-borderless font-share align-middle">
                                    <thead>
                                        <tr class="text-secondary font-mono small border-bottom border-secondary">
                                            <th>EVENT</th>
                                            <th>BASE_PREVIEW</th>
                                            <th>ACTION_OVERWRITE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($pending_faculty_certs)): ?>
                                            <?php foreach ($pending_faculty_certs as $cert): ?>
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
                                                        <input type="hidden" name="action" value="reupload_base">
                                                        <input type="hidden" name="cert_id" value="<?php echo $cert['id']; ?>">
                                                        <input type="file" name="base_image" class="form-control form-control-sm bg-black text-white border-secondary rounded-0 w-auto" accept="image/*" required>
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary font-mono">> OVERWRITE</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="3" class="text-secondary">No templates awaiting faculty signature.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STAGE 2: VISUAL TEMPLATE MAPPING -->
                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <div class="card card-hacker p-5 rounded-0 border-info text-center">
                            <div class="display-1 text-info mb-3" style="opacity: 0.5;">⚙️</div>
                            <h4 class="font-mono text-white mb-3">[ STAGE_2: VISUAL_MAPPING ]</h4>
                            <p class="text-secondary font-share small mb-4 w-75 mx-auto">
                                Configure where dynamically generated text (Name, Event, Rank) will be placed. Coordinates are vertical percentage offsets from the top (e.g. 50 is center).
                            </p>

                            <?php if (!empty($unmapped_certs)): ?>
                                <div class="table-responsive mb-4 text-start w-75 mx-auto">
                                    <table class="table table-dark table-borderless font-share align-middle mb-0">
                                        <thead>
                                            <tr class="text-secondary font-mono small border-bottom border-secondary">
                                                <th>EVENT</th>
                                                <th>BASE_PREVIEW</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($unmapped_certs as $cert): ?>
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

                            <form action="/cert/save-mapping" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                
                                <div class="mb-4 text-start w-75 mx-auto">
                                    <label class="form-label text-info font-mono small">>_ SELECT_UNMAPPED_TEMPLATE</label>
                                    <select name="cert_id" class="form-select bg-black text-white border-info rounded-0 font-share shadow-none" required>
                                        <option value="">-- Select Template --</option>
                                        <?php if (!empty($unmapped_certs)): ?>
                                            <?php foreach ($unmapped_certs as $cert): ?>
                                                <option value="<?php echo $cert['id']; ?>"><?php echo \App\Core\Security::escape($cert['event_title']); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="row w-75 mx-auto text-start g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label text-info font-mono small">NAME Y-OFFSET (%)</label>
                                        <input type="number" name="name_y" class="form-control bg-transparent text-white border-info rounded-0 font-share shadow-none" value="55" min="0" max="100" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-info font-mono small">NAME FONT SIZE</label>
                                        <input type="number" name="name_size" class="form-control bg-transparent text-white border-info rounded-0 font-share shadow-none" value="32" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-info font-mono small">NAME COLOR (HEX)</label>
                                        <input type="color" name="name_color" class="form-control form-control-color bg-transparent border-info w-100 rounded-0" value="#FFFFFF" title="Choose color" required>
                                    </div>
                                </div>

                                <div class="row w-75 mx-auto text-start g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label text-info font-mono small">EVENT Y-OFFSET (%)</label>
                                        <input type="number" name="event_y" class="form-control bg-transparent text-white border-info rounded-0 font-share shadow-none" value="70" min="0" max="100" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-info font-mono small">EVENT FONT SIZE</label>
                                        <input type="number" name="event_size" class="form-control bg-transparent text-white border-info rounded-0 font-share shadow-none" value="20" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-info font-mono small">EVENT COLOR (HEX)</label>
                                        <input type="color" name="event_color" class="form-control form-control-color bg-transparent border-info w-100 rounded-0" value="#FFFFFF" required>
                                    </div>
                                </div>

                                <div class="row w-75 mx-auto text-start g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label text-info font-mono small">RANK Y-OFFSET (%)</label>
                                        <input type="number" name="rank_y" class="form-control bg-transparent text-white border-info rounded-0 font-share shadow-none" value="80" min="0" max="100" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-info font-mono small">RANK FONT SIZE</label>
                                        <input type="number" name="rank_size" class="form-control bg-transparent text-white border-info rounded-0 font-share shadow-none" value="24" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-info font-mono small">RANK COLOR (HEX)</label>
                                        <input type="color" name="rank_color" class="form-control form-control-color bg-transparent border-info w-100 rounded-0" value="#FF003C" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-outline-info font-mono w-50 mx-auto" <?php echo empty($unmapped_certs) ? 'disabled' : ''; ?>>> SAVE_TEMPLATE_MAPPING</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card card-hacker p-5 rounded-0 border-warning text-center">
                            <div class="display-1 text-warning mb-3" style="opacity: 0.5;">⚡</div>
                            <h4 class="font-mono text-white mb-3">[ STAGE_5: MASS_GENERATE ]</h4>
                            <p class="text-secondary font-share small mb-4 w-50 mx-auto">
                                Root-signed and Faculty-verified templates are ready. Trigger the auto-generator to overlay details and dispatch to Vaults.
                            </p>

                            <?php if (!empty($ready_certs)): ?>
                                <div class="table-responsive mb-4 text-start w-50 mx-auto">
                                    <table class="table table-dark table-borderless font-share align-middle mb-0">
                                        <thead>
                                            <tr class="text-secondary font-mono small border-bottom border-secondary">
                                                <th>EVENT</th>
                                                <th>FINAL_PREVIEW</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($ready_certs as $cert): ?>
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
                                <input type="hidden" name="action" value="generate_all">
                                <div class="mb-4 text-start w-50 mx-auto">
                                    <label class="form-label text-warning font-mono small">>_ VERIFIED_TEMPLATES</label>
                                    <select name="cert_id" class="form-select bg-black text-white border-warning rounded-0 font-share shadow-none" required>
                                        <option value="">-- Select Template --</option>
                                        <?php if (!empty($ready_certs)): ?>
                                            <?php foreach ($ready_certs as $cert): ?>
                                                <option value="<?php echo $cert['id']; ?>"><?php echo \App\Core\Security::escape($cert['event_title']); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-warning text-black font-mono w-50 mx-auto" <?php echo empty($ready_certs) ? 'disabled' : ''; ?>>> MASS_GENERATE_CERTIFICATES</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
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
    .form-check-input:checked {
        background-color: var(--ck-danger);
        border-color: var(--ck-danger);
    }
</style>
