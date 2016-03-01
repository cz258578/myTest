
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Dear宝贝-后台管理</title>

<link rel="icon" href="<?= Yii::getAlias('@asset_url') ?>/images/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="<?= Yii::getAlias('@asset_url') ?>/js/easyui-1.4.4/themes/default/easyui.css" id="swicth-style2">
<link rel="stylesheet" type="text/css" href="<?= Yii::getAlias('@asset_url') ?>/js/easyui-1.4.4/themes/icon.css">
<script type="text/javascript" src="<?= Yii::getAlias('@asset_url') ?>/js/easyui-1.4.4/jquery.min.js"></script>
<script type="text/javascript" src="<?= Yii::getAlias('@asset_url') ?>/js/easyui-1.4.4/jquery.easyui.min.js"></script>
<style type="text/css">
    body {
        font-size: 13px;
        font-family: "微软雅黑", "宋体", Arial, sans-serif, Verdana, Tahoma;
        background-color: #ffffff;
        background-image:url(<?= Yii::getAlias('@asset_url') ?>/images/main_body_bg.jpg);
        background-repeat:repeat-x;
    }

    .window-mask{ opacity: 0.8;}

    a{
        color: #000000;
    }
    a:link {
        text-decoration: none;
    }
    a:visited {
        text-decoration: none;
    }
    a:hover {
        text-decoration: underline;
    }
    a:active {
        text-decoration: none;
    }
    .cs-north {
        height:48px;
        overflow:hidden;
    }
    .cs-north-bg {
        width: 100%;
        height: 100%;
        background-color: #E0ECFF;
        background: url(<?= Yii::getAlias('@asset_url') ?>/images/header_bg.gif) repeat-x;
    }
    .cs-north-logo {
        height: 40px;
        margin: 6px 0px 0px 0px;
        display: inline-block;
        color:#FFFF00;
        font-size:16px;
        text-decoration:none;
        font-family: "微软雅黑";
    }
    .cs-west {
        width:188px;
        padding:0px;
        border-radius:0px;
    }
    .cs-south {
        height:5px;background:url('<?= Yii::getAlias('@asset_url') ?>/js/easyui-1.4.4/themes/pepper-grinder/images/ui-bg_fine-grain_15_ffffff_60x60.png') repeat-x;padding:0px;text-align:center;
    }
    .cs-navi-tab {
        padding: 8px;
    }
    .cs-tab-menu {
        width:120px;
    }
    .cs-home-remark {
        padding: 10px;
    }
    .wrapper {
        float: right;
        height: 30px;
        margin-left: 10px;
    }
    .ui-skin-nav {
        float: right;
        padding: 0;
        margin-right: 10px;
        list-style: none outside none;
        height: 30px;
    }

    .ui-skin-nav .li-skinitem {
        float: left;
        font-size: 12px;
        line-height: 30px;
        margin-left: 10px;
        text-align: center;
    }
    .ui-skin-nav .li-skinitem span {
        cursor: pointer;
        width:10px;
        height:10px;
        display:inline-block;
    }
    .ui-skin-nav .li-skinitem span.cs-skin-on{
        border: 1px solid #FFFFFF;
    }

    .ui-skin-nav .li-skinitem span.gray{background-color:gray;}
    .ui-skin-nav .li-skinitem span.pepper-grinder{background-color:#BC3604;}
    .ui-skin-nav .li-skinitem span.blue{background-color:blue;}
    .ui-skin-nav .li-skinitem span.cupertino{background-color:#D7EBF9;}
    .ui-skin-nav .li-skinitem span.dark-hive{background-color:black;}
    .ui-skin-nav .li-skinitem span.sunny{background-color:#FFE57E;}


        /*修改tabs样式*/
    #mainPanle #tabs .tabs-panels .panel{

    }

    #mainPanle #tabs .tabs-first{
        margin-left: 1px;
    }

    #mainPanle #tabs .tabs-panels .panel .panel-body{
        overflow:hidden;
    }

        /*修改左侧菜单样式*/

    .easyui-accordion .panel .panel-header{
        padding-top: 8px;
        padding-bottom: 8px;
        padding-left: 8px;
    }

    .easyui-accordion .panel .panel-header .panel-title{
        font-size: 13px;
        font-family: "微软雅黑", "宋体", Arial, sans-serif, Verdana, Tahoma;
    }

    .easyui-accordion .panel p{
        display: block;
        padding-left: 5px;
    }

    .ui-skin-nav .easyui-menubutton .l-btn-text{
        color: #000000;
    }


        /*弹出窗口的边框样式*/

    .panel-noscroll>.panel{
        transition:border linear .2s,box-shadow linear .5s;
        -moz-transition:border linear .2s,-moz-box-shadow linear .5s;
        -webkit-transition:border linear .2s,-webkit-box-shadow linear .5s;
        outline:none;
        border-color:#888888;
        box-shadow:0 0 8px #888888;
        -moz-box-shadow:0 0 8px #888888;
        -webkit-box-shadow:0 0 8px #888888;
    }

    .panel-noscroll>.panel .panel-header{
        /*border: 0px;*/
        border-color: #aaaaaa;
    }

    .panel-noscroll>.panel .panel-body{
        border: 0px;

    }
    #openWindow{
        background-color:#E0ECFF;
        overflow: hidden;
    }

</style>


<script type="text/javascript">
window.closeWinIsReloadData = 0; // 关闭窗口的时候是否重新刷新页面数据, 0不重新刷新, 1刷新
window.closeDialogIsReloadData = 0; // 关闭dialog窗口的时候是否重新刷新页面数据, 0不重新刷新, 1刷新

window.passDataForHypertextTransfer = null; //定义 JS 跨域传输的 json 数据

function addTab(title, url){
    if ($('#tabs').tabs('exists', title)){
        $('#tabs').tabs('select', title);//选中并刷新
        var currTab = $('#tabs').tabs('getSelected');
        var url = $(currTab.panel('options').content).attr('src');
        if(url != undefined && currTab.panel('options').title != '首页') {
            $('#tabs').tabs('update',{
                tab:currTab,
                options:{
                    content:createFrame(url)
                }
            })
        }
    } else {
        var content = createFrame(url);
        $('#tabs').tabs('add',{
            title:title,
            content:content,
            closable:true
        });
    }
    tabClose();
}
function createFrame(url) {
    var s = '<iframe scrolling="auto" frameborder="0"  src="'+url+'" style="width:100%;height:100%;"></iframe>';
    return s;
}

function tabClose() {
    /*双击关闭TAB选项卡*/
    $(".tabs-inner").dblclick(function(){
        var subtitle = $(this).children(".tabs-closable").text();
        $('#tabs').tabs('close',subtitle);
    })
    /*为选项卡绑定右键*/
    $(".tabs-inner").bind('contextmenu',function(e){
        $('#mm').menu('show', {
            left: e.pageX,
            top: e.pageY
        });

        var subtitle =$(this).children(".tabs-closable").text();

        $('#mm').data("currtab",subtitle);
        $('#tabs').tabs('select',subtitle);
        return false;
    });
}
//绑定右键菜单事件
function tabCloseEven() {
    //刷新
    $('#mm-tabupdate').click(function(){
        var currTab = $('#tabs').tabs('getSelected');
        var url = $(currTab.panel('options').content).attr('src');
        if(url != undefined && currTab.panel('options').title != '首页') {
            $('#tabs').tabs('update',{
                tab:currTab,
                options:{
                    content:createFrame(url)
                }
            })
        }
    })
    //关闭当前
    $('#mm-tabclose').click(function(){
        var currtab_title = $('#mm').data("currtab");
        $('#tabs').tabs('close',currtab_title);
    })
    //全部关闭
    $('#mm-tabcloseall').click(function(){
        $('.tabs-inner span').each(function(i,n){
            var t = $(n).text();
            if(t != '首页') {
                $('#tabs').tabs('close',t);
            }
        });
    });
    //关闭除当前之外的TAB
    $('#mm-tabcloseother').click(function(){
        var prevall = $('.tabs-selected').prevAll();
        var nextall = $('.tabs-selected').nextAll();
        if(prevall.length>0){
            prevall.each(function(i,n){
                var t=$('a:eq(0) span',$(n)).text();
                if(t != '首页') {
                    $('#tabs').tabs('close',t);
                }
            });
        }
        if(nextall.length>0) {
            nextall.each(function(i,n){
                var t=$('a:eq(0) span',$(n)).text();
                if(t != '首页') {
                    $('#tabs').tabs('close',t);
                }
            });
        }
        return false;
    });
    //关闭当前右侧的TAB
    $('#mm-tabcloseright').click(function(){
        var nextall = $('.tabs-selected').nextAll();
        if(nextall.length==0){
            //msgShow('系统提示','后边没有啦~~','error');
            alert('后边没有啦~~');
            return false;
        }
        nextall.each(function(i,n){
            var t=$('a:eq(0) span',$(n)).text();
            $('#tabs').tabs('close',t);
        });
        return false;
    });
    //关闭当前左侧的TAB
    $('#mm-tabcloseleft').click(function(){
        var prevall = $('.tabs-selected').prevAll();
        if(prevall.length==0){
            alert('到头了，前边没有啦~~');
            return false;
        }
        prevall.each(function(i,n){
            var t=$('a:eq(0) span',$(n)).text();
            $('#tabs').tabs('close',t);
        });
        return false;
    });

    //退出
    $("#mm-exit").click(function(){
        $('#mm').menu('hide');
    })
}

$(function() {
    tabCloseEven();

    $('.cs-navi-tab').click(function() {
        var $this = $(this);
        var href = $this.attr('src');
        var title = $this.text();
        addTab(title, href);
    });

    var themes = {
        'gray' : '<?= Yii::getAlias('@asset_url') ?>/js/easyui-1.4.4/themes/gray/easyui.css',
        'pepper-grinder' : '<?= Yii::getAlias('@asset_url') ?>/js/easyui-1.4.4/themes/pepper-grinder/easyui.css',
        'blue' : '<?= Yii::getAlias('@asset_url') ?>/js/easyui-1.4.4/themes/default/easyui.css',
        'cupertino' : '<?= Yii::getAlias('@asset_url') ?>/js/easyui-1.4.4/themes/cupertino/easyui.css',
        'dark-hive' : '<?= Yii::getAlias('@asset_url') ?>/js/easyui-1.4.4/themes/dark-hive/easyui.css',
        'sunny' : '<?= Yii::getAlias('@asset_url') ?>/js/easyui-1.4.4/themes/sunny/easyui.css'
    };

    var skins = $('.li-skinitem span').click(function() {
        var $this = $(this);
        if($this.hasClass('cs-skin-on')) return;
        skins.removeClass('cs-skin-on');
        $this.addClass('cs-skin-on');
        var skin = $this.attr('rel');
        $('#swicth-style').attr('href', themes[skin]);
        setCookie('cs-skin', skin);
        skin == 'dark-hive' ? $('.cs-north-logo').css('color', '#FFFFFF') : $('.cs-north-logo').css('color', '#000000');
    });

    if(getCookie('cs-skin')) {
        var skin = getCookie('cs-skin');
        $('#swicth-style').attr('href', themes[skin]);
        $this = $('.li-skinitem span[rel='+skin+']');
        $this.addClass('cs-skin-on');
        skin == 'dark-hive' ? $('.cs-north-logo').css('color', '#FFFFFF') : $('.cs-north-logo').css('color', '#000000');
    }
});

/* 显示错误信息 */
function showMessage(title, errorMsg) {
    $.messager.show({
        title: title,
        msg: errorMsg
    });
}

function setCookie(name,value) {//两个参数，一个是cookie的名子，一个是值
    var Days = 30; //此 cookie 将被保存 30 天
    var exp = new Date();    //new Date("December 31, 9998");
    exp.setTime(exp.getTime() + Days*24*60*60*1000);
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}

function getCookie(name) {//取cookies函数        
    var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
    if(arr != null) return unescape(arr[2]); return null;
}

$(function(){
    $(".panel-tool").on("click",function(){
        parent.$('#openWindow').window('close',true);
    })
})
</script>
</head>

<body>

<div class="easyui-layout" fit="true" style="padding:0px; margin:0px; margin-left:8px;">
    <div region="north" border="false" class="cs-north">
        <div class="cs-north-bg">
            <div class="cs-north-logo"><span  style="color:#FFFFCC;display:block; float:left;"><img src="<?= Yii::getAlias('@asset_url') ?>/images/logo.gif" height="28" align="absmiddle" /></span>
                <span style="color:#FFFFFF; display:block; margin-top:5px; float:left; font-size:14px;">&nbsp;&nbsp;<span style=" padding:3px; border:1px solid #FFFFFF; background-color:#FFFFFF; color:#0099FF;border-radius:6px; padding-right:6px; padding-left:5px; padding-top:2px; font-size:14px;">公司后台管理</span>
                    &nbsp;&nbsp;&nbsp;&nbsp;你好，<?= yii::$app->user->identity->name?>（<?= yii::$app->user->identity->username?>-<?= $this->context->userRoleInfo->name?>）</span></div>

            <div class="ui-skin-nav" style="margin-right:16px; margin-top:8px;">




                <a id="btn" href="<?php echo \Yii::$app->urlManager->createUrl(['site/logout']);?>" class="easyui-linkbutton" data-options="iconCls:'icon-no'">安全退出</a>
            </div>


        </div>
    </div>
    <div region="west" border="true" split="false" title="" class="cs-west">
        <div class="easyui-accordion" fit="false" multiple="true" border="false">

            <!-- 左部菜单  -->
            <?php $userModulesDatas = isset($this->context->adminUserRoleModulesIds) && is_array($this->context->adminUserRoleModulesIds)? $this->context->adminUserRoleModulesIds: [];?>
            <?php $userModulesDataLength = count($userModulesDatas);?>
            <?php foreach ($userModulesDatas as $userModulesDataKey => $userModulesData) :?>

                <?php if($userModulesData['parent_id']==0){?>
                <div title="<?= $userModulesData['name']?>" <?= $userModulesDataKey == $userModulesDataLength - 1? 'style="border-bottom:0px;padding-bottom:28px;"': ''; ?>>

                    <?php foreach ($userModulesDatas as $userModulesDataItem) :?>

                        <?php if($userModulesDataItem['parent_id']==$userModulesData['id'] && $userModulesDataItem['id']!=2){?> <!--首页 不显示-->
                        <p><a href="javascript:void(0);" src="<?= yii::$app->urlManager->createUrl([$userModulesDataItem['module_addr'].'/'.$userModulesDataItem['action_addr']])?>"
                              class="cs-navi-tab"><?= $userModulesDataItem['name'];?></a></p>
                        <?php }?>

                    <?php endforeach;?>

                </div>
                <?php }?>

            <?php endforeach;?>

            <span style="position:fixed;left:10px;bottom:11px; width:178px; color:#6799DC;padding-left:6px; padding-top:6px; padding-bottom:6px;background-color:#ffffff;">Copyright © DearBaobei.com</span>

        </div>

    </div>

    <div id="mainPanle" region="center" border="false" border="false">
        <div id="tabs" class="easyui-tabs"  fit="true" border="false" tabHeight="32">
            <div title="首页">
                <div class="cs-home-remark">
                    <h1>深圳市小唐有为科技有限公司</h1> <br>

                </div>
            </div>
        </div>
    </div>

    <div region="east" border="false" style="width:8px; background-color:#ffffff; background-image:url(<?= Yii::getAlias('@asset_url') ?>/images/main_east_layout_bg.gif);background-repeat:repeat-x;"></div>
    <div region="south" border="false" style="height:8px;"></div>


</div>

<div id="mm" class="easyui-menu cs-tab-menu">
    <div id="mm-tabupdate">刷新</div>
    <div class="menu-sep"></div>
    <div id="mm-tabclose">关闭</div>
    <div id="mm-tabcloseother">关闭其他</div>
    <div id="mm-tabcloseall">关闭全部</div>
</div>
<!--用于弹出窗口-->
<div id="openWindow"></div>
<div id="openDialog"></div>
<script type="text/javascript">
    if (window!=top) // 判断当前的window对象是否是top对象
        top.location.href =window.location.href; // 如果不是，将top对象的网址自动导向被嵌入网页的网址
</script>

</body>
</html>