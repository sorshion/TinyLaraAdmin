<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OperationLog;
use Illuminate\Support\Facades\Auth;

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
        return view('admin.log.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['user_name', 'menu_name', 'sub_menu_name', 'input']);
        $data['method'] = $request->method();
        $data['path']   = $request->path();
        $data['ip']     = $request->ip();
        $data['user_id'] = Auth::id();
        $data['operate_name'] = '新增操作日志';
        if (OperationLog::create($data)) {
            return response()->json(['code' => 0,'msg' => '新增成功']);
            //return redirect()->to(route('admin.log'))->with(['status' => '添加成功']);
        }
        return response()->json(['code' => 1,'msg' => '新增失败']);
        //return redirect()->to(route('admin.log'))->withErrors('系统错误');
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
        $model  = OperationLog::findOrFail($id);

        return view('admin.log.edit', $model->query()->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = OperationLog::findOrFail($id);
        $data = $request->all();
        if ($model->update($data)) {
            return redirect()->to(route('admin.log'))->with(['status' => '更新权限成功']);
        }
        return redirect()->to(route('admin.log'))->withErrors('系统错误');
    }

    /**
     * 删除数据
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (empty($ids)) {
            return response()->json(['code' => 1, 'msg'=>'请选择删除项']);
        }
        if (OperationLog::destroy($ids)) {
            return response()->json(['code' => 0,'msg' => '删除成功']);
        }
        return response()->json(['code' => 1, 'msg' => '删除失败']);
    }

     /**
      * 数据表格接口
      *
      * @param Request $request
      * @return \Illuminate\Http\JsonResponse
      */
    public function data(Request $request)
    {
        $model = OperationLog::query();

        // 查询
        if ($request->get('menu_name')) {
            $model->where('menu_name', $request->get('menu_name'));
        }
        if ($request->get('sub_menu_name')) {
            $model->where('sub_menu_name', $request->get('sub_menu_name'));
        }
        if ($request->get('user_name')) {
            $model->where('user_name', $request->get('user_name'));
        }
        // 排序
        $field = $request->get('field');
        if (empty($field)) {
            $model->orderby('id', 'desc');
        } else {
            $model->orderby($field, $request->get('order'));
        }
        $res  = $model->paginate($request->get('limit', 30))->toArray();
        $data = [
            'code'  => 0,
            'msg'   => '正在请求中...',
            'count' => $res['total'],
            'data'  => $res['data']
        ];
        return response()->json($data);
    }

}
