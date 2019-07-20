<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <title>祥富微信文章爬取工具</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://res.layui.com/layui/dist/css/layui.css">
    <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
    <script src="{{asset('js/layui.all.js')}}"></script>
</head>
<body>
<div style="margin-top: 50px;"><a class="layui-btn layui-btn-danger" href="{{route('fail.index')}}">未处理文章</a><a class="layui-btn layui-btn-normal" style="margin-left: 20px;" href="{{url('/index')}}">爬取文章</a></div>
<h1 style="text-align: center;margin-top: 50px;">已处理文章</h1>
<table class="layui-table" lay-size="lg">
    <colgroup>
        <col width="150">
        <col width="200">
        <col>
    </colgroup>
    <thead>
    <tr>
        <th>标题</th>
        <th>来源</th>
        <th>预览图</th>
        <th>文章简述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $v)
        <tr>
            <td>{{$v->title}}</td>
            <td>{{$v->origin}}</td>
            <td><img src="{{$v->preview}}" height="50" width="50"/></td>
            <td>{{$v->description}}</td>
            <td><a class='layui-btn layui-btn-xs' onclick='article(this,{{$v->id}})'>预览</a> <a class='layui-btn layui-btn-xs' href="/article/{{$v->id}}/edit">编辑</a> <a class='layui-btn layui-btn-danger layui-btn-xs'lay-event='del' onclick='article_del(this,{{$v->id}})'>删除</a></td>
        </tr>
    @endforeach
    </tbody>
</table>

<style>

    .page-link{
        color: #000;
        margin: 0 5px 10px;
        padding: 5px 10px;
        background: #c0ddf6;
        display: inline-block;
    }
    .pagelist li{
        text-align: center;
        min-height: 0;
        display: block;
        float: left;
    }
    .pagelist span{
        color: #fff;
        margin: 0 5px 10px;
        padding: 5px 10px;
        background: #000000;
        display: inline-block;
    }
    .pagelist ul{
        margin: 50px auto;
    }
</style>
<div style="margin:0 auto">
    <div class="pagelist" >{{$data->links()}}</div>
</div>
</body>
<script>

    function article(obj,id){
        layer.open({
            type: 2,
            area: ['700px', '450px'],
            title:'预览',
            content: "article/"+id
        });
    }

    function article_del(obj,id){
        layer.confirm('确认要删除吗？',function(index){
            // alert(id);
            $.post('{{url('index/')}}/'+id,{'_token':'{{csrf_token()}}','_method':'delete' },function(res){
                if(res.code==1){
                    $(obj).parents("tr").remove();
                    layer.msg('已删除', {icon:6,time:1000});
                }
                else{
                    layer.msg('删除失败', {icon:5,time:1000});
                }
            });
        });
    }
</script>
</html>