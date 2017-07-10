<form id="save-permission">
	<table class="table table-striped">
		<thead>
		    <tr>
		        <th>Resouces</th>
		        <th>Type</th>
		        <th>Sub Type</th>
		        <th>Description</th>
		        <th>Permission Name</th>
		    </tr>
		</thead>

		<tbody>
			<?php if(isset($module_components['Menus'])): ?>
				<?php if($module_components['Menus']): ?>
					<?php foreach($module_components['Menus'] as $item): ?>
					<tr>
						<td><?= $item['Name']; ?></td>
						<td><?= $item['Type']; ?></td>
						<td>-</td>
						<td><?= $item['Description']; ?></td>
						<td>
							<div class="form-group">
				                <input class="form-control" type="text" name="<?= $module_name; ?>[Menus]<?= "[" . $item['Name'] . "]"; ?>" placeholder="<?= $item['Name']; ?> permission name" value="<?= $item['Permission']; ?>" />
				            </div>
						</td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endif; ?>

			<?php if(isset($module_components['Pages'])): ?>
				<?php if($module_components['Pages']): ?>
					<?php foreach($module_components['Pages'] as $item): ?>
						<tr>
							<td><?= $item['Name']; ?></td>
							<td><?= $item['Type']; ?></td>
							<td>-</td>
							<td><?= $item['Description']; ?></td>
							<td>
								<div class="form-group">
					                <input class="form-control" type="text" name="<?= $module_name; ?>[Pages]<?= "[" . $item['Name'] . "]"; ?>" placeholder="<?= $item['Name']; ?> permission name" value="<?= $item['Permission']; ?>" />
					            </div>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endif; ?>

			<?php if(isset($module_components['Page_Components'])): ?>
				<?php if($module_components['Page_Components']): ?>
					<?php foreach($module_components['Page_Components'] as $item): ?>
						<tr>
							<td><?= $item['Name']; ?></td>
							<td><?= $item['Type']; ?></td>
							<td><?= $item['Sub_Type']; ?></td>
							<td><?= $item['Description']; ?></td>
							<td>
								<div class="form-group">
					                <input class="form-control" type="text" name="<?= $module_name; ?>[Page_Components]<?= "[" . $item['Name'] . "]"; ?>" placeholder="<?= $item['Name']; ?> permission name" value="<?= $item['Permission']; ?>" />
					            </div>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endif; ?>

		</tbody>
	</table>

	<br>

	<input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken(); ?>">
	<input type="hidden" name="module" value="<?= $module_name; ?>">

	<div class="form-group pull-right">
		<button class="btn btn-primary btn-flat" type="submit" value="view" name="output_type">
			<i class="fa fa-refresh"></i>
			Save
		</button>
	</div>

</form>

<script type="text/javascript">

	$(document).ready(function() {

		$('#save-permission').submit(function(e) {

	        e.preventDefault();
	        
	        var form_data = new FormData(this);
	        
	        $.ajax({
	            type: 'POST',
	            dataType: 'json',
	            url: '/rbac/permission/save-module-permissions',
	            data: form_data,
	            processData: false,
	            contentType: false,
	            beforeSend: function() {
	                $('.box-body').waitMe({
	                    effect : 'stretch',
	                    text : 'Saving...',
	                    bg : 'rgba(255,255,255,0.7)',
	                    color : '#000',
	                    sizeW : '',
	                    sizeH : ''
	                });
	            },
	            complete: function(){
	                $('.box-body').waitMe('hide');
	            },
	            success: function(data) {

	                if(!data.error) {
		                swal({
		                    title: 'Success',   
		                    html: true,
		                    text: 'Permission is successfully saved',
		                    type: "success",
		                });
		            } else {
		                swal({
	                        title: 'System Error',   
	                        html: true,
	                        text: data.message,
	                        type: "error",
	                    });
		            }

	            }
	        });
	    });

	});

</script>