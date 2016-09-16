<div class="row">
    <label class="col-lg-3 control-label" for="company-com_currency">Components</label>

    <div class="col-lg-8">
        <select class="form-control main-component-selector">
            <option value="">Select Components</option>
			<?php foreach($data as $component): ?>
			    <option value="<?= $component; ?>"><?= $component; ?></option>
			<?php endforeach; ?>
        </select>
    </div>
</div>