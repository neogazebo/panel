<?php if ($use_container): ?>
	<div class="form-group">
	    <label><?= $label ? $label : '&nbsp;'; ?></label><br>
	    <button name="<?= $name; ?>" value="<?= $value(); ?>" type="<?= $type; ?>" class="<?= $class; ?>"><i class="<?= $icon; ?>"></i> <?= $text; ?></button>
	</div>
<?php else: ?>
	<button name="<?= $name; ?>" value="<?= $value(); ?>" type="<?= $type; ?>" class="<?= $class; ?>"><i class="<?= $icon; ?>"></i> <?= $text; ?></button>
<?php endif; ?>