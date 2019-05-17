@extends('admin.base')

@section('content')
    <form class="layui-form" action="">
        @include('admin.log._form')
    </form>
@endsection

@section('script')
<script>
    layui.use('form', function() {
        var form = layui.form;

        // 监听提交
        form.on('submit(logDemo)', function(data) {
            $.ajax({
                type: 'post',
                url: "{{route('admin.log.store')}}",
                data: data.field,
                success: function () {
                    layer.msg('添加成功', {icon: 6});
                    parent.location.reload();
                },
                error: function() {
                    layer.msg('添加失败');
                }
            });
            return false;
        });
    });
</script>
@endsection
