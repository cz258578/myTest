/**
 *
 * @param url
 * @param title
 * @param width
 * @param height
 * @param callfun
 * 父类 弹出窗
 */
function openTopWindow(url, title, width, height, callfun){
    parent.window.closeWinIsReloadData = 0;

    title = title == undefined ? '&nbsp;' : title;

    width = width == undefined ? 800 : width;

    height = height == undefined ? 300 : height;


    if (url != undefined) {

        var content = '<iframe name="eui-open-page" scrolling="no" frameborder="0" src="' + url + '" style="width:100%;height:100%;"></iframe>';

        parent.$('#openWindow').window({
            title: title,
            minimizable:false,
            collapsible:false,
            maximizable:false,
            top:($(window).height() - height) * 0.5,
            left:($(window).width() - width) * 0.5,
            width: width,

            height: height,

            content: content,

            modal: true,

            animate: true,

            onBeforeClose:function(){

                if (parent.window.closeWinIsReloadData == 1) {
                    if(callfun){
                        eval(callfun + '()');
                    }
                }
            }
        });
    }
}

/**
 *
 * @param url
 * @param title
 * @param width
 * @param height
 * @param callfun
 * 父类 二级弹出窗
 */
function openTopDialog(url, title, width, height, callfun){
    
    parent.window.closeDialogIsReloadData = 2;

    title = title == undefined ? '&nbsp;' : title;

    width = width == undefined ? 800 : width;

    height = height == undefined ? 300 : height;


    if (url != undefined) {

        var content = '<iframe name="eui-open-page" scrolling="no" frameborder="0" src="' + url + '" style="width:100%;height:100%;"></iframe>';

        parent.$('#openDialog').dialog({
            title: title,
            minimizable:false,
            collapsible:false,
            maximizable:false,
            width: width,

            height: height,

            content: content,

            modal: true,

            animate: true,

            onBeforeClose:function(){
                if (parent.window.closeDialogIsReloadData == 1) {
                    if(callfun){
                        eval(callfun + '()');
                    }
                }
            }
        });
    }
}

/**
 *  页面加载等待页面
 *
 * @author
 * @date
 *
 */
function loadWindow(operation){
    operation = operation == undefined || operation == 'show'? 'show' : 'close';

    var height = $(window).height();
    var width = $(window).width();

    var leftW = (width-140)/2;
    var heightW = height/2-30;

    var _html = "<div id='loading' style='position:absolute;left:0;width:100%;height:"+height+
        "px;top:0;background:#E0ECFF;opacity:0.8;filter:alpha(opacity=80);z-index:110008;'>"+
        "<div style='position:absolute;cursor1:wait;left:"+leftW+"px;"+"top:"+heightW+"px;"+
        "font-size:12px;width:auto;height:30px;padding:0px 10px 0 30px; line-height:30px;"+
        "background:#fff url(/dear_assets/js/easyui-1.4.4/themes/default/images/loading.gif) no-repeat scroll 5px center;"+
        "border:1px solid #95b8e7;color:#000;'>"+
        "正在加载，请等待..."+
        "</div></div>";

    if(operation == 'show'){
        $('body').append(_html);
    } else {
        $("#loading").remove();
    }
}