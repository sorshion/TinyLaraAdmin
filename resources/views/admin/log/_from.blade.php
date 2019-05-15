{{csrf_field()}}
    <div class="layui-form-item">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-block">
            <input type="text" name="user_name" required lay-verify="required" placeholder="用户名" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">主菜单</label>
        <div class="layui-input-block">
            <input type="text" name="menu_name" required lay-verify="required" placeholder="主菜单" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">子菜单</label>
        <div class="layui-input-block">
            <input type="text" name="sub_menu_name" required lay-verify="required" placeholder="子菜单" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">操作信息</label>
        <div class="layui-input-block">
            <textarea name="input" required lay-verify="required" placeholder="操作信息" class="layui-textarea"></textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="createDemo">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
