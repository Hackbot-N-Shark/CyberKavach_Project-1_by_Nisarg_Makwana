<section class="py-5 bg-black" style="min-height: 80vh;">
    <div class="container">
        <h1 class="font-mono text-danger mb-2">>_ KAVACH_LOGS</h1>
        <p class="text-secondary font-share mb-5">Public vulnerability write-ups, community updates, and hackathon debriefs.</p>

        <div class="row g-4">
            <?php if (empty($blogs)): ?>
                <div class="col-12">
                    <p class="text-secondary font-mono">> NO_LOGS_FOUND. THE_ARCHIVE_IS_EMPTY.</p>
                </div>
            <?php else: ?>
                <?php foreach ($blogs as $blog): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card bg-black border border-secondary p-4 h-100 rounded-0 hover-lift">
                            <h4 class="font-mono text-danger mb-3"><?php echo \App\Core\Security::escape($blog['title']); ?></h4>
                            <p class="text-secondary font-share small mb-4">
                                <i class="fa fa-terminal me-2"></i>Author: <?php echo \App\Core\Security::escape($blog['author_name']); ?><br>
                                <i class="fa fa-clock me-2"></i>Date: <?php echo date('M j, Y', strtotime($blog['created_at'])); ?>
                            </p>
                            <!-- Truncate content for preview -->
                            <p class="text-gray-300 font-share mb-4" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                <?php echo htmlspecialchars(strip_tags($blog['content'])); ?>
                            </p>
                            <div class="mt-auto">
                                <a href="/blog/view?slug=<?php echo urlencode($blog['slug']); ?>" class="btn btn-outline-danger font-mono w-100">> READ_MORE</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
