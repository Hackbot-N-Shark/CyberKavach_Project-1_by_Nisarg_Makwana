<?php include __DIR__ . '/../layouts/header.php'; ?>

<section class="py-5 bg-black" style="min-height: 80vh;">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="font-mono text-danger mb-3">>_ COMMAND_STRUCTURE</h1>
            <p class="text-secondary font-share">The synchronized personnel matrix of CyberKavach operatives.</p>
        </div>

        <?php 
        $roles = [
            'root' => 'DIRECTOR (ROOT)',
            'architect' => 'CORE FACULTY (ARCHITECT)',
            'sudo' => 'STUDENT COORDINATORS (SUDO)'
        ];
        ?>

        <?php foreach ($roles as $roleKey => $roleTitle): ?>
            <?php if (!empty($team[$roleKey])): ?>
                <div class="mb-5">
                    <h3 class="font-mono text-white border-bottom border-danger pb-2 mb-4">[ <?php echo $roleTitle; ?> ]</h3>
                    <div class="row g-4">
                        <?php foreach ($team[$roleKey] as $member): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card bg-surface border-secondary rounded-0 p-4 h-100 text-center hover-scale">
                                    <div class="rounded-circle bg-dark border border-danger d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                        <i class="fa fa-user fs-3 text-danger"></i>
                                    </div>
                                    <h5 class="font-mono text-white mb-1"><?php echo \App\Core\Security::escape($member['username']); ?></h5>
                                    <p class="text-secondary font-share small mb-0">> STATUS: ACTIVE_DUTY</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if (empty($team['root']) && empty($team['architect']) && empty($team['sudo'])): ?>
            <div class="text-center">
                <p class="text-secondary font-mono">> ERROR: NO_PERSONNEL_FOUND.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
