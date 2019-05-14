<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OperationLog;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.log.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.log.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionCreateRequest $request)
    {
        $data = $request->all();
        if (OperationLog::create($data)) {
            return redirect()->to(route('admin.permission'))->with(['status' => '添加成功']);
        }
        return redirect()->to(route('admin.permission'))->withErrors('系统错误');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission  = OperationLog::findOrFail($id);
        $permissions = $this->tree();
        return view('admin.permission.edit', compact('permission', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionUpdateRequest $request, $id)
    {
        $permission = OperationLog::findOrFail($id);
        $data       = array_filter($request->all());
        if ($permission->update($data)) {
            return redirect()->to(route('admin.permission'))->with(['status' => '更新权限成功']);
        }
        return redirect()->to(route('admin.permission'))->withErrors('系统错误');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (empty($ids)) {
            return response()->json(['code' => 1, 'msg' => '请选择删除项']);
        }
        $permission = OperationLog::find($ids[0]);
        if (!$permission) {
            return response()->json(['code' => -1, 'msg' => '权限不存在']);
        }
        // 如果有子权限，则禁止删除
        if (OperationLog::where('parent_id', $ids[0])->first()) {
            return response()->json(['code' => 2, 'msg' => '存在子权限禁止删除']);
        }

        if ($permission->delete()) {
            return response()->json(['code' => 0, 'msg' => '删除成功']);
        }
        return response()->json(['code' => 1, 'msg' => '删除失败']);
    }

     /**
     * 数据表格接口
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function data(Request $request)
    {
        $query = new OperationLog();

        // 查询
        $menu_name = $request->get('menu_name');
        if (!empty($menu_name)) {
            $query = $query->where('menu_name', $menu_name);
        }
        $sub_menu_name = $request->get('sub_menu_name');
        if (!empty($sub_menu_name)) {
            $query = $query->where('sub_menu_name', $sub_menu_name);
        }
        $user_name = $request->get('user_name');
        if (!empty($user_name)) {
            $query = $query->where('user_name', $user_name);
        }
        // 排序
        $field = $request->get('field');
        if (empty($field)) {
            $query = $query->orderby('id', 'desc');
        } else {
            $query = $query->orderby($field, $request->get('order'));
        }
        $res  = $query->paginate($request->get('limit', 30))->toArray();
        $data = [
            'code'  => 0,
            'msg'   => '正在请求中...',
            'count' => $res['total'],
            'data'  => $res['data']
        ];
        return response()->json($data);
    }

}
