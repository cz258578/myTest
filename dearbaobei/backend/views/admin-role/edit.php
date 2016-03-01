<style type="text/css">
    .fitem{ line-height: 30px; }
    .lad_text{ font-size: 12px; width: 70px; display: inline-block;}
    .span_text{ font-size: 12px; color: #999;}
    .span_text2{ font-size: 12px;}
</style>

<body style="padding:0px;margin:0px;">
<form id="ff" method="post" style="height:298px;" data-href="<?php echo \Yii::$app->urlManager->createUrl(['admin-role/save'])?>">
    <div class="easyui-layout" fit="true" border="true">

        <div region="north" border="true" style="overflow:auto; padding:8px;height:228px; ">
            <input type="hidden" name="id" value="" class="easyui-validatebox">
            <table width="100%" border="0" cellspacing="0" cellpadding="8">
              <tr>
                <td width="23%">角色名称：</td>
                <td><input id="name" name="name" value="<?= $models->name?>" class="easyui-textbox" required="true" data-options="novalidate:true,
                prompt:'请输入角色名称'"></td>
                <td width="36%">&nbsp;</td>
              </tr>

              </tr>
            </table>

            <input type="hidden" name="id" value="<?= $models->id?>">
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
        if ($('#save-btn').hasClass('mylinkbtn-load')){
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

</script>

