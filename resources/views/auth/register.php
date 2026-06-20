<section class="py-5 bg-black d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="container" style="max-width: 500px;">
        <div class="card card-hacker p-5 rounded-0 border-danger" data-aos="zoom-in">
            <div class="text-center mb-4">
                <h1 class="font-mono text-danger glitch mb-2" data-text="INIT_PROTOCOL">INIT_PROTOCOL</h1>
                <p class="text-secondary font-share small">Register your alias to join the collective.</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger font-mono small rounded-0 border-danger bg-transparent text-danger">
                    [!] <?php echo \App\Core\Security::escape($error); ?>
                </div>
            <?php endif; ?>

            <form action="/register" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                
                <div class="mb-3">
                    <label class="form-label text-danger font-mono small">>_ ALIAS</label>
                    <input type="text" name="username" class="form-control bg-transparent text-white border-danger rounded-0 font-share shadow-none" required autofocus autocomplete="off" placeholder="Choose your operative alias">
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-danger font-mono small">>_ COM_LINK</label>
                    <input type="email" name="email" class="form-control bg-transparent text-white border-danger rounded-0 font-share shadow-none" required autocomplete="off" placeholder="Enter your email address">
                </div>

                <div class="mb-3">
                    <label class="form-label text-danger font-mono small">>_ CLEARANCE_LEVEL</label>
                    <select name="role" class="form-select bg-black text-white border-danger rounded-0 font-share shadow-none" required>
                        <option value="operative">Operative (User)</option>
                        <option value="sudo">Student Coordinator (Sudo)</option>
                        <option value="architect">Core Faculty (Architect)</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label text-danger font-mono small">>_ PASSKEY</label>
                    <input type="password" name="password" class="form-control bg-transparent text-white border-danger rounded-0 font-share shadow-none" required placeholder="Create a secure passkey">
                </div>
                
                <button type="submit" class="btn btn-outline-danger w-100 font-mono btn-hacker mb-3">ENROLL_IN_MAINFRAME</button>
            </form>
            
            <div class="text-center mt-4 border-top border-danger pt-3">
                <p class="text-secondary font-share small mb-0">Already have clearance?</p>
                <a href="/login" class="text-danger font-mono text-decoration-none hover-danger">ACCESS_SYS</a>
            </div>
        </div>
    </div>
</section>
