<div id="edit-hq-modal-<?= $id; ?>" class="modal fade edit-hq-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">"<?= $com_name; ?>" Edit</h4>
      </div>
      <form class="manage-hq" data-op="edit">
        <div class="modal-body">
            
            <p><span class="red"><strong>*</strong></span> required</p>

            <div class="form-group">
                <label class="control-label" for="com_name">Name <span class="red"><strong>*</strong></span></label>
                <input class="form-control com_name" type="text" name="com_name" placeholder="Merchant HQ Name" value="<?= $com_name; ?>" />
                <input type="hidden" class="com_name_temp" value="<?= $com_name ?>">
                <div class="help-block com-name-error"></div>
            </div>
            
            <!--
            <div class="form-group">
                <label class="control-label" for="com_email">Email</label>
                <input id="com_email" class="form-control" type="text" name="com_email" placeholder="Merchant Email">
                <div class="help-block com-email-error"></div>
            </div>
            -->

            <div class="form-group">
              <label>Category <span class="red"><strong>*</strong></span></label>
              <select class="form-control" style="width: 100%;" name="com_subcategory_id">
                <option value="">Please select merchant category</option>
                <?php foreach($categories as $key => $category): ?>
                  <optgroup label="<?= $key; ?>">
                    <?php foreach($category as $child_id => $child_value): ?>
                      <option value="<?= $child_id; ?>" <?php if($com_subcategory_id == $child_id) { echo 'selected'; } ?>><?= $child_value; ?></option>
                    <?php endforeach; ?>
                  </optgroup>
                <?php endforeach; ?>
              </select>
              <div class="help-block com-category-error"></div>
            </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->getCsrfToken(); ?>">
          <input type="hidden" name="com_id" value="<?= $id ?>">
          <button class="pull-left btn btn-warning" data-dismiss="modal" type="reset"><i class="fa fa-times"></i>&nbsp;Cancel</button>
          <button class="btn btn-info pull-right" type="submit"><i class="fa fa-check"></i>&nbsp;Update</button>
        </div>
      </form>
    </div>
  </div>
</div>