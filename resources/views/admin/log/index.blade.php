@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="logSearch layui-form">
                <span>操作用户</span>
                <div class="layui-inline">
                    <input class="layui-input" name="user_name" autocomplete="off" placeholder="操作用户">
                </div>

                <span>主菜单</span>
                <div class="layui-inline">
                    <input class="layui-input" name="menu_name" autocomplete="off" placeholder="主菜单">
                </div>

                <span>子菜单</span>
                <div class="layui-inline">
                    <input class="layui-input" name="sub_menu_name"  autocomplete="off" placeholder="子菜单">
                </div>

                <button class="layui-btn search-btn" data-type="reload">搜索</button>

                <button class="layui-btn layui-btn-warm export-btn">下载(有数据中json字串不要使用)</button>
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
            var table = layui.table;

            // 用户表格初始化
            var dataTable = table.render({
                elem: '#dataTable'
                ,id: 'dataTable'
                ,toolbar: 'default'
                ,totalRow: true // 开启合计行
                ,defaultToolbar: ['filter', 'print']
                ,url: "{{ route('admin.log.data') }}" // 数据接口
                ,page: true // 开启分页
                ,cols: [[ // 表头
                    {checkbox: true, fixed: true}
                    ,{field: 'id', title: 'ID', sort: true, width:80}
                    ,{field: 'user_name', title: '用户名'}
                    ,{field: 'menu_name', title: '主菜单'}
                    ,{field: 'sub_menu_name', title: '子菜单'}
                    ,{field: 'ip', title: 'IP'}
                    ,{field: 'operate_name', title: '操作'}
                    ,{field: 'input', title: '操作信息'}
                    ,{field: 'created_at', title: '创建时间'}
                    ,{fixed: 'right', width: 320, align:'center', title: '操作',toolbar: '#options'}
                ]]
            });

            // 监听工具条
            table.on('tool(dataTable)', function(obj) {
                var data = obj.data;      // 获得当前行数据
                var layEvent = obj.event; // 获得 lay-event 对应的值
                if (layEvent === 'del') {
                    delRows([data.id]);
                } else if (layEvent === 'edit') {
                    editRow(data.id);
                }
            });

            // 监听排序
            table.on('sort(dataTable)', function(obj) {
                table.reload('dataTable', {
                    initSort: obj
                    ,where: {
                        field: obj.field
                        ,order: obj.type
                    }
                });
            });


            // 监听头工具栏事件
            table.on('toolbar(dataTable)', function (obj) {
                var checkStatus = table.checkStatus(obj.config.id);
                var data = checkStatus.data; // 获取选中的数据
                switch (obj.event) {
                    case 'add':
                        createRow();
                        break;
                    case 'update':
                        if (data.length === 0) {
                            layer.msg('请选择一行');
                        } else if (data.length > 1) {
                            layer.msg('只能同时编辑一个');
                        } else {
                            editRow(checkStatus.data[0].id);
                        }
                        break;
                    case 'delete':
                        if (data.length === 0) {
                            layer.msg('请选择一行');
                        } else {
                            delRows(getIds(data));
                        }
                        break;
                }
            });

            var $ = layui.$, active = {
                reload: function() {
                    // 执行重载
                    table.reload('dataTable', {
                        page: {
                            curr: 1, // 重新从第 1 页开始
                        },
                        where: {
                            menu_name: $('input[name=menu_name]').val(),
                            sub_menu_name: $('input[name=sub_menu_name]').val(),
                            user_name: $('input[name=user_name]').val(),
                        }
                    });
                }
            };

            // 触发搜索
            $('.logSearch .search-btn').on('click', function() {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });

            // 触发下载
            $('.logSearch .export-btn').on('click', function() {
                $.ajax({
                    type: 'get',
                    url: "{{route('admin.log.data')}}",
                    data: {
                        menu_name: $('input[name=menu_name]').val(),
                        sub_menu_name: $('input[name=sub_menu_name]').val(),
                        user_name: $('input[name=user_name]').val(),
                    },
                    success: function (res) {
                        table.exportFile(dataTable.config.id, res.data, 'xls');
                    },
                    error: function() {
                        layer.msg('导出失败');
                    }
                });
            });

            function createRow() {
                layer.open({
                    type: 2
                    ,title: '创建(demo)'
                    ,content: "{{route('admin.log.create')}}"
                    ,area: ['500px', '400px']
                    ,shade: 0
                    ,maxmin: true
                    ,scrollbar: false
                });
            }

            function delRows(ids) {
                layer.confirm('确认删除吗？', function (index) {
                    $.post("{{ route('admin.log.destroy') }}", {
                        _method: 'delete',
                        ids: ids
                    }, function (result) {
                        if (result.code == 0) {
                            dataTable.reload();
                        }
                        layer.close(index);
                        layer.msg(result.msg, {icon: 6})
                    });
                });
            }

            function editRow(id) {
                layer.open({
                    type: 2
                    ,title: '编 辑 【id:'+ id +'】(demo)'
                    ,content: '/admin/log/' + id + '/edit'
                    ,area: ['500px', '400px']
                    ,shade: 0
                    ,maxmin: true
                    ,scrollbar: false
                });
            }

            function getIds(data) {
                var ids = [];
                $.each(data, function(index, element) {
                    ids.push(element.id);
                });
                return ids;
            }
        })
    </script>
    @endcan
@endsection
