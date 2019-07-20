<!DOCTYPE html>
<html lang="en" >
<head>
<meta charset="UTF-8">
<title>祥富微信文章爬取工具</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="{{asset('css/style.css')}}">
<script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
<script src="{{asset('js/layui.all.js')}}"></script>
</head>
<body>
<section>
  <h1>搜狗微信文章爬取框架</h1>
  <form id="basic-form">
    <div class="form-group">
      <input type="text" id="url" placeholder="搜狗微信文章URL"/>
      <label for="basic-form-first-name">搜狗微信文章URL</label>
    </div>
    <input class="button" id="basic-form-submit" onclick="sumbit()" type="button" value="爬取"/>
  </form>
</section>
</body>
<script>
  function sumbit(){

    let url = $('#url').val();
    if(url==''){
      layer.msg('请输入网址',{icon: 5,time: 1500});return;
    }
    layer.msg('爬取中请稍后', {
      icon: 16
      ,shade: 0.1
      ,time:2000
    });
    $.post('{{route('index.store')}}',{_method:'post',_token:'{{csrf_token()}}',url:url},function(res){
      if (res.code==1){
        layer.confirm('爬取数据成功是否前往编辑', {
          btn: ['前往编辑','继续爬取'] //按钮
        }, function(){
          $(location).attr('href', "index/"+res.data.id+"/edit");
        }, function(){
          $('#url').val('')
        });
      }else{
        layer.msg(res.msg,{icon:5,time: 1500});return;
      }

    })
  }
</script>
</html>
