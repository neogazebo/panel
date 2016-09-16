<div class="row">

	<?php if($parent_comp_name == 'Menus'): ?>
	    <label class="col-lg-3 control-label" for="company-com_currency">Menu Items</label>

	    <div class="col-lg-8">
	        <select class="form-control menu-item-selector">
	            <option value="">Select item</option>
				<?php foreach($data as $component): ?>
				    <option value="<?= $component; ?>"><?= $component; ?></option>
				<?php endforeach; ?>
	        </select>
	    </div>
	<?php else: ?>
		<label class="col-lg-3 control-label" for="company-com_currency">Page Names</label>

	    <div class="col-lg-8">
	        <select class="form-control page-selector">
	            <option value="">Select page</option>
				<?php foreach($data as $component): ?>
				    <option value="<?= $component; ?>"><?= $component; ?></option>
				<?php endforeach; ?>
	        </select>
	    </div>
	<?php endif; ?>
</div>