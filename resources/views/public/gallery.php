<section class="py-5 bg-black" style="min-height: 80vh;">
    <div class="container">
        <h1 class="font-mono text-danger mb-2">>_ GLOBAL_GALLERY</h1>
        <p class="text-secondary font-share mb-5">Visual intelligence gathered from past CyberKavach operations and deployments.</p>

        <div class="row g-4" data-masonry='{"percentPosition": true }'>
            <?php if (empty($gallery_images)): ?>
                <div class="col-12">
                    <p class="text-secondary font-mono">> NO_VISUAL_DATA_AVAILABLE.</p>
                </div>
            <?php else: ?>
                <?php foreach ($gallery_images as $img): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card bg-black border-secondary rounded-0 overflow-hidden hover-scale position-relative">
                            <img src="<?php echo htmlspecialchars($img['image_path']); ?>" class="card-img-top rounded-0" alt="Operation Visual">
                            <div class="card-img-overlay d-flex flex-column justify-content-end bg-dark bg-opacity-75" style="opacity: 0; transition: opacity 0.3s; cursor: pointer;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">
                                <h5 class="font-mono text-danger mb-1"><?php echo \App\Core\Security::escape($img['event_title']); ?></h5>
                                <p class="text-white font-share small mb-0"><i class="fa fa-clock text-danger me-2"></i><?php echo date('M j, Y', strtotime($img['uploaded_at'])); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Include Masonry for dynamic grid layout -->
<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async></script>
