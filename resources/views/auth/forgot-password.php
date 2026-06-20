<section class="py-5 bg-black d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="container" style="max-width: 500px;">
        <div class="card card-hacker p-5 rounded-0 border-danger" data-aos="zoom-in">
            <div class="text-center mb-4">
                <h1 class="font-mono text-danger mb-2">RECOVERY_PROTOCOL</h1>
                <p class="text-secondary font-share small">Request a passkey override directly from the Director. This will flag your alias for manual reset.</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger font-mono small rounded-0 border-danger bg-transparent text-danger">
                    [!] <?php echo \App\Core\Security::escape($error); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success font-mono small rounded-0 border-success bg-transparent text-success">
                    [OK] <?php echo \App\Core\Security::escape($success); ?>
                </div>
            <?php else: ?>
                <form action="/forgot-password" method="POST" onsubmit="return confirm('Transmit override request to Director?');">
                    <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                    
                    <div class="mb-4">
                        <label class="form-label text-danger font-mono small">>_ REGISTERED_ALIAS</label>
                        <input type="text" name="username" class="form-control bg-transparent text-white border-danger rounded-0 font-share shadow-none" required autofocus placeholder="operative_name">
                    </div>
                    
                    <button type="submit" class="btn btn-outline-danger w-100 font-mono btn-hacker mb-3">> TRANSMIT_REQUEST</button>
                </form>
            <?php endif; ?>
            
            <div class="text-center mt-4 border-top border-danger pt-3">
                <a href="/login" class="text-secondary font-mono text-decoration-none hover-danger">< ABORT_AND_RETURN</a>
            </div>
        </div>
    </div>
</section>
