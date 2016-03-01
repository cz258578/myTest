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
