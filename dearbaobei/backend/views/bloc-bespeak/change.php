<style type="text/css">
    .fitem{ line-height: 30px; }
    .lad_text{ font-size: 12px; width: 70px; display: inline-block;}
    .span_text{ font-size: 12px; color: #999;}
    .span_text2{ font-size: 12px;}
</style>

<body style="padding:0px;margin:0px;">
<form id="ff" method="post" style="height:268px;" data-href="<?php echo \Yii::$app->urlManager->createUrl(['bloc-bespeak/save-change'])?>">
    <div class="easyui-layout" fit="true" border="true">

        <div region="north" border="true" style="overflow:auto; padding:8px;height:198px; ">
            <input type="hidden" name="id" value="<?= $models->id?>" class="easyui-validatebox">
            <table width="100%" border="0" cellspacing="0" cellpadding="8">
                <tr>
                    <td align="right" width="28%">用户名称：</td>
                    <td><?= $models->bloc_name?></td>
                    
                </tr>
                <tr>
                    <td align="right">更新状态：</td>
                    <td><input id="status" name="status" class="easyui-combobox"  data-options="panelHeight:'auto'"></td>
                    
                </tr>

            </table>

        </div>

        <div region="center" border="false" style="overflow:hidden;background-color:#E0ECFF; ">
            <div id="dlg-buttons" style="background-color:#E0ECFF; padding-top:8px; padding-bottom:0px; float:right;">
                <a href="#" id="save-btn" class="easyui-linkbutton" iconCls="icon-save" onClick="saveFrom()">保存</a>
                <a href="#" class="easyui-linkbutton panel-tool-close" iconCls="icon-no" onClick="javascript:parent.$('#openWindow').window('close',true);">取消</a>
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
        //status
        var statusData = [
            <?php
                foreach(yii::$app->params['blocBespeakStatus'] as $k=>$v){
                    if($k==$models->status){
                        echo ($k>1?',':'').'{id:'.$k.',name:"'.$v.'",selected:true}';
                    }else{
                        echo ($k>1?',':'').'{id:'.$k.',name:"'.$v.'"}';
                    }
                }
            ?>
        ];
        var status = $('#status').combobox({
            data:statusData,
            editable:false,
            valueField:'id',
            textField:'name',
            required: true,
            novalidate:true,
            onLoadSuccess:function(){
            }
        });

    })

</script>

