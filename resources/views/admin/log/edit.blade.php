@extends('admin.base')

@section('content')
    <form class="layui-form" action="">
        <input type="hidden" name="id" value="{{$operateLog->id}}">
        {{method_field('put')}}
        @include('admin.log._form')
    </form>
@endsection

@section('script')
    <script>
        layui.use('form', function () {
            var form = layui.form;

            //监听提交
            form.on('submit(logDemo)', function(data) {
                $.ajax({
                    type: 'post',
                    {{--url:'/admin/log/' + {{$operateLog->id}}+ '/update',--}}
                    url: "{{route('admin.log.update',['operateLog' => $operateLog])}}",
                    data: data.field,
                    success: function () {
                        layer.msg('修改成功', {icon: 6});
                        parent.location.reload();
                    },
                    error: function() {
                        layer.msg('修改失败');
                    }
                });
                return false;
            });
        });
    </script>
@endsection
