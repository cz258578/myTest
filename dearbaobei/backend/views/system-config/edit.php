<style type="text/css">
    .fitem{ line-height: 30px; }
    .lad_text{ font-size: 12px; width: 70px; display: inline-block;}
    .span_text{ font-size: 12px; color: #999;}
    .span_text2{ font-size: 12px;}
</style>

<body style="padding:0px;margin:0px;">
<form id="ff" method="post" style="height:468px;" data-href="<?php echo \Yii::$app->urlManager->createUrl(['system-config/save'])?>">
    <div class="easyui-layout" fit="true" border="true">

        <div region="north" border="true" style="overflow:auto; padding:8px;height:398px; ">
            <input type="hidden" name="id" value="<?= $_GET['id']?>" class="easyui-validatebox">
            <table width="100%" border="0" cellspacing="0" cellpadding="8">
              <tr>
                <td width="23%">字段名：</td>
                <td><input id="name" name="name" value="<?= $models->name?>" class="easyui-textbox" required="true" data-options="novalidate:true"></td>
                <td width="36%">例： statusType</td>
              </tr>

              <tr>
                <td>描述：</td>
                <td><input id="describe" name="describe" value="<?= $models->describe?>" class="easyui-textbox" required="true" data-options="novalidate:true"></td>
                <td><span>例： 状态类型</span></td>
              </tr>

              <tr>
                  <td>分组：</td>
                  <td><input id="group" name="group" value="" class="easyui-textbox"></td>
                  <td>例： 系统后台</td>
              </tr>

              <tr>
                <td>类型：</td>
                <td><select id="type" name="type" class="easyui-combobox" data-options="panelHeight:'auto'">
                        <option value="array" <?= $models->type=='array'?'selected=true':'' ?>>array</option>
                        <option value="string" <?= $models->type=='string'?'selected=true':'' ?>>string</option>
                </select></td>
                <td>例： array</td>
              </tr>

                <tr>
                <td valign="top">值：</td>
                <td><textarea id="value" name="value" class="easyui-textbox" style="height: 162px;"  data-options="multiline:true"><?= $value?></textarea></td>
                <td valign="top">例： 0=>禁用,1=>正常 </td>
                </tr>
            </table>

        </div>

        <div region="center" border="false" style="overflow:hidden;background-color:#E0ECFF; ">
            <div id="dlg-buttons" style="background-color:#E0ECFF; padding-top:8px; padding-bottom:0px; float:right;">
                <a href="#" id="save-btn" class="easyui-linkbutton" iconCls="icon-save" onclick="saveFrom()">保存</a>
                <a href="#" class="easyui-linkbutton panel-tool-close" iconCls="icon-no" onclick="javascript:parent.$('#openWindow').window('close',true);">取消</a>
            </div>
        </div>

    </div>
</form>
</body>

<script type="text/javascript">
    function saveFrom(){
        if ($('#save-btn').hasClass('mylinkbtn-load')) {
            return false;
        }
        $('#save-btn').addClass('mylinkbtn-load');

        var url = $("#ff").attr('data-href');
        $('#ff').form('submit',{
            url:url,
            onSubmit:function(){
                var result = $(this).form('enableValidation').form('validate');
                if(!result){
                    $('#save-btn').removeClass('mylinkbtn-load');
                };
                return result;
            },
            success:function(result){
                var result = eval('('+result+')');
                if (result.status==1){
                    parent.window.closeWinIsReloadData=1;
                    parent.$('#openWindow').window('close');
                }else{
                    $('#save-btn').removeClass('mylinkbtn-load');
                    $.messager.alert('提示',result.info,'error')
                }
            }
        });
    }

    $(function(){
        var group_data = [
            <?php
                foreach(yii::$app->params['configGroup'] as $k=>$v){
                    if($k==$models['group']){
                        echo ($k>1?',':'').'{id:'.$k.',name:"'.$v.'",selected:true}';
                    }else{
                        echo ($k>1?',':'').'{id:'.$k.',name:"'.$v.'"}';
                    }
                }
            ?>
        ];
        $('#group').combobox({
            data:group_data,
            valueField:'id',
            textField:'name',
            required:true,
            editable:false,
            onLoadSuccess:function(){
                //$('#group').combobox("setValue","请选择");
            }

        });
    })

</script>

