<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Dear宝贝-公司后台管理登录';
$this->params['breadcrumbs'][] = $this->title;
?>



        <div style="width:393px; margin-left:auto; margin-right:auto; margin-top:80px; border:1px solid #0099FF;border-radius:8px;">
			<div style="margin-left:auto; margin-right:auto; padding-top:8px; padding-bottom:8px; padding-left:108px; background-color:#0099FF;border-radius:6px 6px 0px 0px;"><img height="40" src="<?= Yii::getAlias('@asset_url') ?>/images/logo.gif"></div>
            <div style="padding:10px 60px 20px 60px">
                <?php
                $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'options' => ['class' => 'form-horizontal'],
                ]) ?>
                <table cellpadding="8">
                    <tr>
                        
                        <td><input class="easyui-textbox" type="text" name="AdminUserLogin[username]" value="hackjiyi" required="true"
                                   data-options="prompt:'输入用户名',novalidate:true" style="padding:6px; padding-left:8px; height:38px; width:258px;font-size:16px; font-family:'微软雅黑';">
						</td>
                    </tr>
                    <tr>
                        
                        <td><input class="easyui-textbox" type="password" name="AdminUserLogin[password]" value="123456" required="true"
                                   data-options="prompt:'请输入密码',novalidate:true" style="padding:6px; padding-left:8px; height:38px; width:258px;">
						</td>
                    </tr>
                </table>
                <?php ActiveForm::end();?>
                <div style="text-align:center;padding:5px; padding-left:10px; padding-bottom:8px;">
				
				<a href="javascript:void(0)" style=" display:block; line-height:38px;height:38px; width:258px; cursor:pointer; border:1px solid #0099FF; font-family:'微软雅黑'; font-size:18px;border-radius: 5px 5px 5px 5px; color:#FFFFFF; background-color:#0099FF; text-decoration:none; " onClick="submitForm()">公司管理登录</a></div>
				
				<div id="login_error_info" style="text-align:center; font-size:16px; font-family:'微软雅黑'; color:#FF0000;"></div>
                
                
            
            </div>
        </div>
        <script>
            function submitForm(){
                $('#login_error_info').html('');
                $('#login-form').form('submit',{
                    url:$(this).attr('action'),
                    onSubmit:function(){
                        return $(this).form('enableValidation').form('validate');
                    },
                    success:function(result){
                        var result = eval('('+result+')');
                        if (result.status==1){
                            location.href = result.info;
                        }else{
                            $('#login_error_info').html(result.info);
                            //$.messager.alert('提示',result.info,'error')
                        }
                    }
                });
            }
            function clearForm(){
                $('#login-form').form('clear');
            }
        </script>
