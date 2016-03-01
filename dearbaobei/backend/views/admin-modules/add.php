<style type="text/css">
    .fitem{ line-height: 30px; }
    .lad_text{ font-size: 12px; width: 70px; display: inline-block;}
    .span_text{ font-size: 12px; color: #999;}
    .span_text2{ font-size: 12px;}
</style>

<body style="padding:0px;margin:0px;">
<form id="ff" method="post" style="height:398px;" data-href="<?php echo \Yii::$app->urlManager->createUrl(['admin-modules/save'])?>">
    <div class="easyui-layout" fit="true" border="true">

        <div region="north" border="true" style="overflow:auto; padding:8px;height:328px; ">
            <input type="hidden" name="id" value="" class="easyui-validatebox">
            <table width="100%" border="0" cellspacing="0" cellpadding="8">
                <tr>
                    <td width="23%">目录：</td>
                    <td><input id="parent_id" name="parent_id" value="" class="easyui-combotree" data-options="novalidate:true"></td>
                    <td width="36%">&nbsp;</td>
                </tr>

              <tr>
                <td width="23%">名称：</td>
                <td><input id="name" name="name" value="" class="easyui-textbox" required="true" data-options="novalidate:true"></td>
                <td width="36%">&nbsp;</td>
              </tr>

              <tr>
                <td>控制器：</td>
                <td><input id="module_addr" name="module_addr" value="" class="easyui-textbox" required="true" data-options="novalidate:true"></td>
                <td><span style="color:red;font-size:12px;" id="subject_password_text"></span></td>
              </tr>

              <tr>
                  <td>方法：</td>
                  <td><input id="action_addr" name="action_addr" class="easyui-textbox"  required="true" data-options="novalidate:true"></td>
                  <td valign="top"></td>
              </tr>

                <tr>
                    <td>排序：</td>
                    <td><input id="sort" name="sort" value="0" class="easyui-textbox"></td>
                    <td valign="top"></td>
                </tr>

                <tr>
                    <td>是否显示：</td>
                    <td>
                        <label>
                        <input name="is_show" checked type="radio" value="1" > 显示
                        <input name="is_show" type="radio" value="0" > 隐藏
                        </label>
                    </td>
                    <td valign="top"></td>
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
        $('#parent_id').combotree({
            url:'<?= yii::$app->urlManager->createUrl(['admin-modules/get-parent-list'])?>',
            valueField:'id',
            textField:'text',
            editable:false,
            required:true,
            formatter:function(node){
                node.text = node.name;
                return node.text;
            },
            onLoadSuccess:function(){
                $('#parent_id').combotree('setValue', <?= isset($_GET['p_id']) ? (int)$_GET['p_id'] : 0?>)
            }
        })
    })

</script>

