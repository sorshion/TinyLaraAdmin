{{csrf_field()}}
<div class="layui-form-item">
    <label class="layui-form-label">用户名</label>
    <div class="layui-input-block">
        <input type="text" name="user_name" value="{{$operateLog->user_name ?? ''}}" required lay-verify="required" placeholder="用户名" autocomplete="off" class="layui-input">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">主菜单</label>
    <div class="layui-input-block">
        <input type="text" name="menu_name" value="{{$operateLog->menu_name ?? ''}}" required
               lay-verify="required" placeholder="主菜单" autocomplete="off" class="layui-input">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">子菜单</label>
    <div class="layui-input-block">
        <input type="text" name="sub_menu_name" value="{{$operateLog->sub_menu_name ?? ''}}" required
               lay-verify="required" placeholder="子菜单" autocomplete="off" class="layui-input">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">操作信息</label>
    <div class="layui-input-block">
        <textarea name="input" required lay-verify="required" placeholder="操作信息" class="layui-textarea">{{$operateLog->input ?? ''}}</textarea>
    </div>
</div>
<div class="layui-form-item">
    <div class="layui-input-block">
        <button class="layui-btn" lay-submit lay-filter="logDemo">提 交</button>
        @if (empty($operateLog))
            <button type="reset" class="layui-btn layui-btn-primary">重 置</button>
        @endif
    </div>
</div>
