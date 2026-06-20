<section class="py-5 bg-black d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="container" style="max-width: 500px;">
        <div class="card card-hacker p-5 rounded-0 border-warning" data-aos="zoom-in">
            <div class="text-center mb-4">
                <h1 class="font-mono text-warning mb-2 blink">MANDATORY_OVERRIDE</h1>
                <p class="text-secondary font-share small">Your clearance was forcefully overridden by the Director. You MUST establish a new secure passkey before continuing.</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger font-mono small rounded-0 border-danger bg-transparent text-danger">
                    [!] <?php echo \App\Core\Security::escape($error); ?>
                </div>
            <?php endif; ?>

            <form action="/force-change-password" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                
                <div class="mb-4">
                    <label class="form-label text-warning font-mono small">>_ NEW_SECURE_PASSKEY</label>
                    <input type="password" name="password" class="form-control bg-transparent text-white border-warning rounded-0 font-share shadow-none" required autofocus minlength="8" placeholder="Minimum 8 characters">
                </div>
                
                <button type="submit" class="btn btn-outline-warning w-100 font-mono btn-hacker mb-3">> SET_NEW_PASSKEY</button>
            </form>
        </div>
    </div>
</section>
