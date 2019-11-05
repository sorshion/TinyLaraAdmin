@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>更新用户</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.user.personalUpdate',['user' => $user])}}" method="post">
                <input type="hidden" name="id" value="{{$user->id}}">
                {{method_field('put')}}
                {{csrf_field()}}
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">用户名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="username" value="{{ $user->username ?? old('username') }}" lay-verify="required" placeholder="请输入用户名" class="layui-input" >
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">昵称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" value="{{ $user->name ?? old('name') }}" lay-verify="required" placeholder="请输入昵称" class="layui-input" >
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">邮箱</label>
                    <div class="layui-input-inline">
                        <input type="email" name="email" value="{{$user->email??old('email')}}" lay-verify="email" placeholder="请输入Email" class="layui-input" >
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">手机号</label>
                    <div class="layui-input-inline">
                        <input type="text" name="phone" value="{{$user->phone??old('phone')}}" required="phone" lay-verify="phone" placeholder="请输入手机号" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">密码</label>
                    <div class="layui-input-inline">
                        <input type="password" name="password" placeholder="请输入密码" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">确认密码</label>
                    <div class="layui-input-inline">
                        <input type="password" name="password_confirmation" placeholder="请输入密码" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
