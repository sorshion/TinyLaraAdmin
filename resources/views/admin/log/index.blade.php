@extends('admin.base')

@section('content')
    <div class="layui-card">

        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="logSearch">
                <span>主菜单</span>
                <div class="layui-inline">
                    <input class="layui-input" name="menu_name" autocomplete="off" placeholder="主菜单">
                </div>

                <span>子菜单</span>
                <div class="layui-inline">
                    <input class="layui-input" name="sub_menu_name"  autocomplete="off" placeholder="子菜单">
                </div>

                <span>操作用户</span>
                <div class="layui-inline">
                    <input class="layui-input" name="user_name" autocomplete="off" placeholder="操作用户">
                </div>
                <button class="layui-btn" data-type="reload">搜索</button>
            </div>
        </div>

        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                @can('system.log.edit')
                    <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                @endcan
                @can('system.log.destroy')
                    <a class="layui-btn layui-btn-danger layui-btn-sm " lay-event="del">删除</a>
                @endcan
            </script>
        </div>
    </div>
@endsection

@section('script')
    @can('system.log')
    <script>
        layui.use(['layer','table','form'], function() {
            var layer = layui.layer;
            var form  = layui.form;
            var table = layui.table;

            // 用户表格初始化
            var dataTable = table.render({
                elem: '#dataTable'
                ,id: 'dataTable'
                ,toolbar: true
                ,url: "{{ route('admin.user.data') }}" //数据接口
                ,page: true //开启分页
                ,cols: [[ //表头
                    {checkbox: true, fixed: true}
                    ,{field: 'id', title: 'ID', sort: true, width:80}
                    ,{field: 'user_name', title: '用户名'}
                    ,{field: 'menu_name', title: '主菜单'}
                    ,{field: 'sub_menu_name', title: '子菜单'}
                    ,{field: 'operate_name', title: '操作'}
                    ,{field: 'ip', title: 'IP'}
                    ,{field: 'input', title: '操作信息'}
                    ,{field: 'created_at', title: '创建时间'}
                    ,{fixed: 'right', width: 320, align:'center', toolbar: '#options'}
                ]]
            });

            // 监听工具条
            table.on('tool(dataTable)', function(obj) {
                var data = obj.data //获得当前行数据
                    ,layEvent = obj.event; //获得 lay-event 对应的值
                if(layEvent === 'del'){
                    layer.confirm('确认删除吗？', function(index){
                        $.post("{{ route('admin.user.destroy') }}",{_method:'delete',ids:[data.id]},function (result) {
                            if (result.code==0){
                                obj.del(); //删除对应行（tr）的DOM结构
                            }
                            layer.close(index);
                            layer.msg(result.msg,{icon:6})
                        });
                    });
                } else if(layEvent === 'edit'){
                    location.href = '/admin/user/'+data.id+'/edit';
                } else if (layEvent === 'role'){
                    location.href = '/admin/user/'+data.id+'/role';
                } else if (layEvent === 'permission'){
                    location.href = '/admin/user/'+data.id+'/permission';
                }
            });

            // 监听排序
            table.on('sort(dataTable)', function(obj) {
                console.log(obj);
                table.reload('dataTable', {
                    initSort: obj
                    ,where: {
                        field: obj.field
                        ,order: obj.type
                    }
                });
            });

            var $ = layui.$, active = {
                reload: function() {
                    var menu_name = $('input[name=menu_name]');
                    var sub_menu_name = $('input[name=sub_menu_name]');
                    var user_name = $('input[name=user_name]');
                    // 执行重载
                    table.reload('dataTable', {
                        page: {
                            curr: 1, // 重新从第 1 页开始
                        },
                        where: {
                            menu_name: menu_name.val(),
                            sub_menu_name: sub_menu_name.val(),
                            user_name: user_name.val(),
                        }
                    });
                }
            };

            $('.logSearch .layui-btn').on('click', function() {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });

            // //按钮批量删除
            // $("#listDelete").click(function () {
            //     var ids = []
            //     var hasCheck = table.checkStatus('dataTable')
            //     var hasCheckData = hasCheck.data
            //     if (hasCheckData.length>0){
            //         $.each(hasCheckData,function (index,element) {
            //             ids.push(element.id)
            //         })
            //     }
            //     if (ids.length>0){
            //         layer.confirm('确认删除吗？', function(index){
            //             $.post("{{ route('admin.user.destroy') }}",{_method:'delete',ids:ids},function (result) {
            //                 if (result.code==0){
            //                     dataTable.reload()
            //                 }
            //                 layer.close(index);
            //                 layer.msg(result.msg,{icon:6})
            //             });
            //         })
            //     }else {
            //         layer.msg('请选择删除项',{icon:5})
            //     }
            // })
        })
    </script>
    @endcan
@endsection
