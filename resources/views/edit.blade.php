<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0">
    <meta name="format-detection" content="telephone=no">
    <link rel="dns-prefetch" href="//rchres.hfkktt.com">
    <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('layui/css/layui.css')}}">
    <title>祥富搜狗微信文章爬取工具</title>
</head>
<body>
<h1 style="text-align: center;margin-top: 50px">祥富搜狗微信文章爬取工具编辑页面</h1>
<article class="page-container" style="margin: 100px">
    <form class="layui-form" id="atic"  method="post">
        {{csrf_field()}}
        <input name="_method" value="put" hidden/>
        <div class="layui-form-item">
            <label class="form-label col-xs-4 col-sm-2">文章标题</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" name="title" value="{{$data->title}}" lay-verify="title" autocomplete="off" placeholder="请输入标题" class="layui-input">
                <input type="text" hidden name="preview" value="{{$data->preview}}" id="testss"  autocomplete="off">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="form-label col-xs-4 col-sm-2">来源</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" name="origin" value="{{$data->origin}}" lay-verify="required" placeholder="请输入" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="form-label col-xs-4 col-sm-2">文章简述</label>
            <div class="formControls col-xs-8 col-sm-9">
                <textarea name="describe" placeholder="200个字符以内" lay-verify="describe" class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="form-label col-xs-4 col-sm-2">缩略图：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="test1">上传图片</button>
                    <div class="layui-upload-list">
                        <img class="layui-upload-img"  src="{{asset($data->preview)}}"  height="100px" width="100px" id="demo1">
                        <p id="demoText"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="form-label col-xs-4 col-sm-2">文章内容</label>
            <div class="formControls col-xs-8 col-sm-9">
                <textarea id="editor" name="content" type="text/plain" style="width:100%;">
                    {!! $data->content!!}
                </textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="demo1">立即保存</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
</article>

</body>
<script type="text/javascript" src="{{asset('/ueditor/1.4.3/ueditor.config.js')}}"></script>
<script type="text/javascript" src="{{asset('/ueditor/1.4.3/ueditor.all.min.js')}}"> </script>
<script type="text/javascript" src="{{asset('/ueditor/1.4.3/lang/zh-cn/zh-cn.js')}}"></script>
<script>
    $(function(){

        layui.use(['form', 'layedit', 'laydate'], function(){
            var form = layui.form
                ,layer = layui.layer
                ,layedit = layui.layedit
                ,laydate = layui.laydate;
            laydate.render({
                elem: '#date1'
            });
            //自定义验证规则
            form.verify({
                title: function(value){
                    if(value.length < 5||value.length>50){
                        return '标题至少得5-50个字符内';
                    }
                }
                ,describe: function(value){
                    if(value.length>200){
                        return '请输入少于200个字符';
                    }
                },
                onkeyup:true,
                focusCleanup:true,
            });
            form.on('submit(demo1)', function(data){
                $.ajax({
                    url:'/index/{{$data->id}}',
                    method:'put',
                    data:data.field,
                    dataType:'JSON',
                    success:function(res){
                        if(res.code==1){
                            layer.msg(res.msg,{ icon: 1, time: 1000 });
                            $(location).attr('href', "{{url('/article')}}");
                        }
                        else{
                            layer.msg(res.msg,{ icon: 5, time: 1000 });
                        }
                    },
                    error:function (data) {
                        console.log(data);
                    }
                })
                return false;
            });
        });
        layui.use('upload', function() {
            var $ = layui.jquery
                , upload = layui.upload;
            //多图片上传
            upload.render({
                elem: '#test2'
                ,url: '/admin/upload'
                ,multiple: true
                ,before: function(obj){
                    //预读本地文件示例，不支持ie8
                    obj.preview(function(index, file, result){
                        $('#demo2').append('<img src="'+ result +'" alt="'+ file.name +'" class="layui-upload-img">')
                    });
                }
                ,done: function(res){
                    //上传完毕
                }
            });

            //普通图片上传
            var uploadInst = upload.render({
                elem: '#test1',
                url: '/index/upload',
                before: function (obj) {
                    //预读本地文件示例，不支持ie8
                    obj.preview(function (index, file, result) {
                        $('#demo1').attr('src', result); //图片链接（base64）
                    });
                }
                , done: function (res) {
                    //如果上传失败
                    $('#testss').val(res.url);
                    if (res.status == 5) {
                        return layer.msg('上传失败');
                    }
                    //上传成功
                    $('#sssaa').val(res.data);
                }
                , error: function () {
                    //演示失败状态，并实现重传
                    var demoText = $('#demoText');
                    demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                    demoText.find('.demo-reload').on('click', function () {
                        uploadInst.upload();
                    });
                }
            });
        });


        let ue = UE.getEditor('editor');
    });

</script>
</html>