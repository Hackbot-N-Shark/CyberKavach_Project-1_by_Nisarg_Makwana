<section class="py-5 bg-black d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="container" style="max-width: 500px;">
        <div class="card card-hacker p-5 rounded-0 border-danger" data-aos="zoom-in">
            <div class="text-center mb-4">
                <h1 class="font-mono text-danger glitch mb-2" data-text="ACCESS_SYS">ACCESS_SYS</h1>
                <p class="text-secondary font-share small">Please authenticate to access the mainframe.</p>
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
            <?php endif; ?>

            <form action="/login" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                
                <div class="mb-4">
                    <label class="form-label text-danger font-mono small">>_ ALIAS</label>
                    <input type="text" name="username" class="form-control bg-transparent text-white border-danger rounded-0 font-share shadow-none" required autofocus autocomplete="off" placeholder="Enter your operative alias">
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <label class="form-label text-danger font-mono small mb-0">>_ PASSKEY</label>
                    <a href="/forgot-password" class="text-secondary font-share small text-decoration-none hover-danger">[ FORGOT_PASSKEY? ]</a>
                </div>
                <input type="password" name="password" class="form-control bg-transparent text-white border-danger rounded-0 font-share shadow-none mb-5" required placeholder="Enter your passkey" style="margin-top:-2rem;">
                
                <button type="submit" class="btn btn-outline-danger w-100 font-mono btn-hacker mb-3">AUTHENTICATE</button>
            </form>
            
            <div class="text-center mt-4 border-top border-danger pt-3">
                <p class="text-secondary font-share small mb-0">Don't have clearance?</p>
                <a href="/register" class="text-danger font-mono text-decoration-none hover-danger">INITIATE_PROTOCOL</a>
            </div>
        </div>
    </div>
</section>
