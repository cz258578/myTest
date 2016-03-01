<style type="text/css">
    .fitem{ line-height: 30px; }
    .lad_text{ font-size: 12px; width: 70px; display: inline-block;}
    .span_text{ font-size: 12px; color: #999;}
    .span_text2{ font-size: 12px;}
    .roleCheckOne{ line-height:24px;}
    .roleCheckOne .roleCheckLabelOne{font-size: 14px; font-weight: bold; display: block; background: #dedede;}
    .roleCheckTwo{padding: 0 0 0 20px;}
    .roleCheckTwo .roleCheckLabelTwo{ display: block; font-weight: bold};
    .roleCheckTwo span{color: red;}
</style>

<body style="padding:0px;margin:0px;">
<form id="ff" method="post" style="height:568px;" data-href="<?php echo \Yii::$app->urlManager->createUrl(['admin-role/save'])?>">
    <div class="easyui-layout" fit="true" border="true">

        <div region="north" border="true" style="overflow:auto; padding:8px;height:498px; ">
            <input type="hidden" name="id" value="<?= $models->id?>" class="easyui-validatebox">
            <?= $html?>
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

    $(function(){
        $('.roleCheckTwo span').css({'display':'inline-block','padding':'0 0 0 20px'});


        $('.roleCheckOne .roleCheckLabelOne .modules_ids').each(function(){
            $(this).click(function(){
                if(!$(this).prop('checked')){
                    $(this).parent().parent().find('.modules_ids').prop('checked',false);
                }
            })
        })

        $('.roleCheckLabelTwo .modules_ids').each(function(){
            $(this).click(function(){
                if(!$(this).prop('checked')){
                    $(this).parent().parent().find('.modules_ids').prop('checked',false);
                }else{
                    $(this).parent().parent().parent().find('.roleCheckLabelOne .modules_ids').prop('checked',true);
                }
            })
        })

        $('.roleCheckTwo span .modules_ids').each(function(){
            $(this).click(function(){
                if($(this).prop('checked')){
                    $(this).parent().parent().parent().find('.roleCheckLabelTwo .modules_ids').prop('checked',true);
                    $(this).parent().parent().parent().parent().find('.roleCheckLabelOne .modules_ids').prop('checked',true);
                }
            })
        })
    })

</script>

