<div class="form-group">
    <label><?= $label ?></label><br>
    <div class="input-group">
        <div class="input-group-addon" for="reservation">
            <i class="fa fa-calendar"></i>
        </div>
        <input type="text" name="<?= $name; ?>" class="<?= $class; ?>" id="<?= $id; ?>" value="<?= $value(); ?>">
    </div>
</div>