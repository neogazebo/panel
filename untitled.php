<div class="form-group">
                                    <label for="ddd">Member</label>
                                    <div>
                                    <?php
                                        $data = Account::find()
                                        ->select(['acc_screen_name as value','acc_id as id'])
                                        ->asArray()
                                        ->all();
                                        $member_value = (!empty($_GET['member'])) ? $_GET['member'] : '';
                                        echo AutoComplete::widget([
                                            'name' => 'member',
                                            'id' => 'sna_member_name',
                                            'value' => $member_value,
                                            'options' => [
                                                'class' => 'form-control',
                                                'placeholder' => 'Member Name'
                                            ],
                                            'clientOptions' => [
                                                'source' => $data,
                                                'autoFill'=>true,
                                                'minLength'=>'3',
                                                'select' => new JsExpression("function( event, ui ) {
                                                    $('#sna_member').val(ui.item.id);
                                                 }")
                                            ],
                                         ]);
                                    ?>
                                        <input name="sna_member" class="form-control" id="sna_member" placeholder="Enter name" type="hidden" value="<?= (!empty($_GET['sna_member'])) ? $_GET['sna_member'] : '' ?>">
                                    </div>
                                </div>