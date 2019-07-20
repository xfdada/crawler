<!DOCTYPE html>
<html lang="en" >
<head>
<meta charset="UTF-8">
<title>祥富微信文章爬取工具</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://res.layui.com/layui/dist/css/layui.css">
<link rel="stylesheet" href="{{asset('css/style.css')}}">
<script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
<script src="{{asset('js/layui.all.js')}}"></script>
</head>
<body>
  <h1>{{$data->title}}</h1>
  <p>{{$data->origin}}</p>
  <div>
    {!!$data->content!!}}
  </div>
</body>
</html>
