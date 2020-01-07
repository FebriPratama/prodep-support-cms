<?php

namespace App\Http\Controllers\CMS;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use DataTables;
use Illuminate\Database\DatabaseManager as DB;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * @var BaseModel The primary model associated with this controller
     */
    public static $model = Role::class;

    /**
     * @var BaseModel The parent model of the model, in the case of a child rest controller
     */
    public static $parentModel = null;

    /**
     * @var null|BaseTransformer The transformer this controller should use, if overriding the model & default
     */
    public static $transformer = null;

    private $dB;
    private $role;
    private $permission;

    public function __construct(DB $dB, Role $role, Permission $permission, DataTables $datatables)
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);

         $this->dB = $dB;
         $this->role = $role;
         $this->permission = $permission;
    }

    public function index(Request $request)
    {
        $columns = ['id','name','guard_name'];

        $from = $request->input('from');
        $to = $request->input('to');

        $datas = [];
        $last = strtotime($from); 
        $next = strtotime($to);

        $order = trim($request->input('orderBy')) !== '' ? $request->input('orderBy') : 'roles.created_at';
        $orderDirection = $request->input('orderDirection') == 'true' ? 'ASC' : 'DESC';

        $datas = $this->role->orderBy($order,$orderDirection);

        if(trim($request->input('q')) !== ''){

            $datas->where(function($query) use($request)
            {

                foreach($columns as $c){

                    $query->orWhere('roles.'.$c, 'LIKE', '%'.$request->input('q').'%');

                }
            
            });

        }

        if(trim($last) !== '' && trim($next) !== ''){

            $datas->where('created_at','>=',date('Y-m-d', $last))
                        ->where('created_at','<=',date('Y-m-d', $next)); 
                        
        }

        $datas = $datas->paginate(5);

        return view('cms.roles.index',compact('datas'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {

        $permissionsApi = $this->permission->where('guard_name','api')->get();
        $permissionsWeb = $this->permission->where('guard_name','web')->get();

        return view('cms.roles.create',compact('permissionsApi','permissionsWeb'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'guard_name' => 'required',
            'permission' => 'required',
        ]);

        $role = $this->role->create(['guard_name' => $request->input('guard_name'),'name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')
                        ->with('success','Role created successfully');
    }

    public function show($id)
    {
        $role = $this->role->find($id);

        if(!is_object($role)){

            return redirect()
                        ->route('roles.index');

        }

        $rolePermissions = $this->permission->join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();


        return view('cms.roles.show',compact('role','rolePermissions'));
    }

    public function edit($id)
    {
        $role = $this->role->find($id);

        if(!is_object($role)){

            return redirect()
                        ->route('roles.index');

        }

        $permissions = $this->permission->where('guard_name',$role->guard_name)->get();

        $rolePermissions = $this->dB->table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        return view('cms.roles.edit',compact('role','rolePermissions','permissions'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = $this->role->find($id);

        if(!is_object($role)){

            return redirect()
                        ->route('roles.index');

        }

        $role->name = $request->input('name');
        $role->save();
        
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')
                        ->with('success','Role updated successfully');
    }
    
    public function destroy($id)
    {
        $this->role->whereId($id)->delete();
        return redirect()->route('roles.index')
                        ->with('success','Role deleted successfully');
    }

}

/*<?php

namespace App\Http\Controllers;

use Illuminate\Database\DatabaseManager as DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RoleController extends Controller
{
 
}
*/
