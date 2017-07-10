<div class="form-group">
    <label><?= $label ?></label>
    <select name="<?= $name; ?>" class="<?= $class; ?>" style="width: 100%;">
    	<?php foreach($selects as $option): ?>
    		<option value="<?= $option['value']; ?>" <?= $option['selected'](); ?>><?= $option['text']; ?></option>
    	<?php endforeach; ?>
    </select>
</div>