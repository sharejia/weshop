<!DOCTYPE html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.2</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="stylesheet" href="/adminStatic/css/font.css">
    <link rel="stylesheet" href="/adminStatic/css/xadmin.css">
    <script src="/adminStatic/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/adminStatic/js/xadmin.js"></script>
    <script type="text/javascript" src="/adminStatic/js/jquery-1.9.1.min.js"></script>
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">首页</a>
            <a href="">演示</a>
            <a>
              <cite>导航元素</cite></a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5" action="/attribute/catAttr" method="get">
                        @csrf
                        <input type="hidden" name="cat_id" id="cat_id" value="{{$attrInfo->cat_id}}">
                        <div class="layui-inline layui-show-xs-block">
                            <input type="text" name="attr_name"  placeholder="请输入属性名" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-header">
                    <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                </div>
                <div class="layui-card-body layui-table-body layui-table-main">
                    <h3>分类名称:{{$attrInfo->cat_name}}</h3>
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            <th>
                                <input type="checkbox" lay-filter="checkall" name="" lay-skin="primary">
                            </th>
                            <th>ID</th>
                            <th>属性名称</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($attrList as $k=>$v)
                            <tr>
                                <td>
                                    <input type="checkbox" name="id" value="{{$v->id}}"   lay-skin="primary">
                                </td>
                                <td>{{$v->id}}</td>
                                <td>{{$v->name}}</td>
                                <td class="td-manage">
                                    <a title="编辑" onclick="member_upd(this,'{{$v->id}}')"  href="javascript:;">
                                        <i class="layui-icon">&#xe642;</i>
                                    </a>
                                    <a title="删除" onclick="member_del(this,'{{$v->id}}')" href="javascript:;">
                                        <i class="layui-icon">&#xe640;</i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="layui-card-body ">
                    <div class="page">
                        <div>
                            {{--<a class="prev" href="">&lt;&lt;</a>--}}
                            {{--<a class="num" href="">1</a>--}}
                            {{--<span class="current">2</span>--}}
                            {{--<a class="num" href="">3</a>--}}
                            {{--<a class="num" href="">489</a>--}}
                            {{--<a class="next" href="">&gt;&gt;</a>--}}
                            {{$attrList->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    layui.use(['laydate','form'], function(){
        var laydate = layui.laydate;
        var  form = layui.form;


        // 监听全选
        form.on('checkbox(checkall)', function(data){

            if(data.elem.checked){
                $('tbody input').prop('checked',true);
            }else{
                $('tbody input').prop('checked',false);
            }
            form.render('checkbox');
        });

        //执行一个laydate实例
        laydate.render({
            elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
            elem: '#end' //指定元素
        });


    });

    /*用户-删除*/
    function member_del(obj,id){
        layer.confirm('确认要删除吗？',function(index){
            $.ajax({
                url:'attrDelOne',
                type:'get',
                data:{id:id},
                dataType: 'json',
                success:function (msg) {
                    if( msg.code == 1 ){
                        $(obj).parents("tr").remove();
                        layer.msg('已删除!',{icon:1,time:1000});
                    }else{
                        layer.msg('删除失败', {icon: 2});
                    }
                },
                error:function(){
                    alert('false');
                }
            })

        });
    }

    /*用户-批删*/
    function delAll (argument) {
        var ids = [];

        // 获取选中的id
        $('tbody input').each(function(index, el) {
            if($(this).prop('checked')){
                ids.push($(this).val())
            }
        });

        layer.confirm('确认要删除吗？'+ids.toString(),function(index){
            $.ajax({
                url:'attrDelAll',
                type:'get',
                data:{ids:ids},
                dataType: 'json',
                success:function (msg) {
                    if( msg.code == 1 ){
                        layer.msg('删除成功', {icon: 1});
                        $(".layui-form-checked").not('.header').parents('tr').remove();
                    }else{
                        layer.msg('删除失败', {icon: 2});
                    }
                },
                error:function(){
                    alert('false');
                }
            })
        });
    }

//    及点及改
    function member_upd(obj,id){
            var name = $(obj).parent().prev().html();
            var a = '<input type="text" id="'+id+'" value="'+name+'" onblur="member_doUpd(this,\'\'+id+\'\', \'\'+value+\'\')" autocomplete="off" class="layui-input">';
            $(obj).parent().prev().html(a);
    }

    function member_doUpd(obj, id, name){
        $.ajax({
            url:'attrUpd',
            type:'get',
            data:{id:id, name:name},
            dataType: 'json',
            success:function (msg) {
                if( msg.code == 1 ){
                    layer.msg('修改成功!',{icon:1,time:1000});
                    $(obj).parent().html(name);
                    $(obj).remove();
                }else{
                    layer.msg('修改失败', {icon: 2});
                }
            },
            error:function(){
                alert('false');
            }
        })
    }

    $('.page-link').click(function(){

        var where = $("input[name='attr_name']").val();

        var href = $(this).attr('href');

        var cat_id = $('#cat_id').val();

        var newHref = href+'&keyword='+where+'&cat_id='+cat_id;

        $(this).attr('href',newHref);

    })
</script>
</html>