/**
 * @aythor  WenzhouChan <wenzhouchan@gmail.com>
 * @link    https://NowTime.cc
 */

var $ = mdui.JQ;

$('#create').on('click', function (e) {
    var type = $("#create").attr("en_name");
    var get_input_data = $("input[name='"+type+"_value']").get();
    var subtitle = [];

    for (var i=0;i<get_input_data.length;i++) {
        if(get_input_data[i].value == '' || get_input_data[i].value == false) {
            subtitle[i] = get_input_data[i].placeholder;
        }else{
            subtitle[i] = get_input_data[i].value;
        }
    }

    $.ajax({
        method: 'POST',
        url: '/api/make',
        data: {
            type: type,
            version: 'v0.0.1',
            subtitle: subtitle
        },
        dataType: 'json',
        timeout: 12000,
        beforeSend: function (xhr) {
            $("#create").attr("disabled","");//添加禁止操作属性
            $("#create").html("正在生成中，请稍等一会儿...");
        },
        success: function (data) {
            console.log(data);
            if(data.code == 200){
                var result = '<h4>生成成功！</h4>' +
                    '<p>点击下载：' +
                    '<a class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent" href="'+data.gif_path+'" download="'+type+'.gif">下载</a>' +
                    '</p><p>点击预览：' +
                    '<a class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent" href="'+data.gif_path+'" target="_blank">预览</a>' +
                    '</p>';
            }else{
                var result = '<h4>生成失败！</h4>' +
                    '<p>错误码：'+data.code+'</p><br/>' +
                    '<p>错误原因：'+data.msg+'</p>';
            }
            mdui.alert(result);
        },
        complete: function (xhr, textStatus) {
            $("#create").removeAttr("disabled");//移除禁止操作属性
            $("#create").html("生成");
        },
        error: function (xhr, textStatus) {
            // xhr 为 XMLHttpRequest 对象
            // textStatus 为包含错误代码的字符串
            mdui.alert('出现错误('+textStatus+')<br/>1.请检查你的网络设置<br/>2.服务器暂时出现故障，请稍后再试')
        }
    });

});
