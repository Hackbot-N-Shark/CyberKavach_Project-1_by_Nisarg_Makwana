<!-- We'll use Parsedown or a simple nl2br for now since we don't have a Markdown parser loaded. 
     To keep it simple, we use nl2br to preserve formatting. -->
<section class="py-5 bg-black" style="min-height: 80vh;">
    <div class="container max-w-4xl mx-auto">
        <a href="/blog" class="text-secondary font-mono text-decoration-none mb-4 d-inline-block">< BACK_TO_LOGS</a>
        
        <h1 class="font-mono text-danger mb-3"><?php echo \App\Core\Security::escape($blog['title']); ?></h1>
        <div class="d-flex gap-4 mb-5 border-bottom border-secondary pb-3">
            <span class="text-secondary font-share small">
                <i class="fa fa-user text-danger me-2"></i><?php echo \App\Core\Security::escape($blog['author_name']); ?>
            </span>
            <span class="text-secondary font-share small">
                <i class="fa fa-calendar text-danger me-2"></i><?php echo date('F j, Y', strtotime($blog['created_at'])); ?>
            </span>
        </div>

        <div class="blog-content font-share text-gray-300" style="line-height: 1.8; font-size: 1.1rem;">
            <?php 
                // Very basic markdown simulation for code blocks and bold text. 
                // A true parser would be better, but this fits the "plain text + simple markdown" constraint.
                $content = \App\Core\Security::escape($blog['content']);
                $content = preg_replace('/```(.*?)```/s', '<pre class="bg-dark p-3 border border-secondary text-success font-mono"><code>$1</code></pre>', $content);
                $content = preg_replace('/\*\*(.*?)\*\*/', '<strong class="text-white">$1</strong>', $content);
                echo nl2br($content);
            ?>
        </div>
    </div>
</section>

<style>
    .blog-content p { margin-bottom: 1.5rem; }
</style>
