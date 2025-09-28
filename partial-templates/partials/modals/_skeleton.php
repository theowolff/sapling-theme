<div class="modal micromodal-slide" id="<?php echo $slug; ?>" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="<?php echo $slug; ?>-title">
            <?php if($title || $close): ?>
                <header class="modal__header">
                    <?php if($title): ?>
                        <h2 class="modal__title" id="<?php echo $slug; ?>-title">
                            <?php echo $title; ?>
                        </h2>
                    <?php endif; ?>

                    <?php if($close): ?>
                        <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                    <?php endif; ?>
                </header>
            <?php endif; ?>

            <main class="modal__content" id="<?php echo $slug; ?>-content">
                <?php echo $content; ?>
            </main>
        </div>
    </div>
</div>
