<section class="py-5 bg-black" style="min-height: 80vh;">
    <div class="container max-w-2xl mx-auto">
        <div class="text-center mb-5">
            <div class="display-1 text-danger mb-3" style="opacity: 0.5;">📡</div>
            <h1 class="font-mono text-white">>_ ESTABLISH_CONNECTION</h1>
            <p class="text-secondary font-share">Inquiries regarding operations, sponsorships, or platform vulnerabilities.</p>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success bg-transparent border-success text-success font-mono rounded-0 mb-4 text-center">
                > TRANSMISSION_SUCCESSFUL. SECURE_CHANNEL_CLOSED.
            </div>
        <?php endif; ?>

        <div class="card card-hacker p-4 rounded-0 border-danger">
            <form action="/contact" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                <div class="mb-3">
                    <label class="form-label text-danger font-mono small">>_ OPERATIVE_ALIAS (NAME)</label>
                    <input type="text" name="name" class="form-control bg-transparent text-white border-secondary rounded-0 font-share shadow-none" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-danger font-mono small">>_ SECURE_COMM_LINK (EMAIL)</label>
                    <input type="email" name="email" class="form-control bg-transparent text-white border-secondary rounded-0 font-share shadow-none" required>
                </div>
                <div class="mb-4">
                    <label class="form-label text-danger font-mono small">>_ ENCRYPTED_PAYLOAD (MESSAGE)</label>
                    <textarea name="message" class="form-control bg-transparent text-white border-secondary rounded-0 font-share shadow-none" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-outline-danger font-mono w-100 btn-hacker">> TRANSMIT_PACKET</button>
            </form>
        </div>
    </div>
</section>
