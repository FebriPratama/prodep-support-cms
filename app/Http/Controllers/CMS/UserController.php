<?php

namespace App\Http\Controllers\CMS;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use DataTables;
use Illuminate\Database\DatabaseManager as DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    public static $model = User::class;

    private $dB;
    private $role;
    private $user;
    private $permission;
    private $datatables;

    public function __construct(DB $dB, Role $role, Permission $permission, DataTables $datatables, User $user)
    {
/*         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);*/

         $this->dB = $dB;
         $this->role = $role;
         $this->user = $user;
         $this->permission = $permission;
         $this->datatables = $datatables;
    }

    public function index(Request $request)
    {
        $columns = ['id','name','email'];

        $from = $request->input('from');
        $to = $request->input('to');

        $datas = [];
        $last = strtotime($from); 
        $next = strtotime($to);

        $order = trim($request->input('orderBy')) !== '' ? $request->input('orderBy') : 'users.created_at';
        $orderDirection = $request->input('orderDirection') == 'true' ? 'ASC' : 'DESC';

        $datas = $this->user->orderBy($order,$orderDirection);

        if(trim($request->input('q')) !== ''){

            $datas->where(function($query) use($request)
            {

                foreach($columns as $c){

                    $query->orWhere('users.'.$c, 'LIKE', '%'.$request->input('q').'%');

                }
            
            });

        }

        if(trim($last) !== '' && trim($next) !== ''){

            $datas->where('created_at','>=',date('Y-m-d', $last))
                        ->where('created_at','<=',date('Y-m-d', $next)); 
                        
        }

        $datas = $datas->paginate(5);

        return view('cms.users.index',compact('datas'))
            ->with('i', ($request->input('page', 1) - 1) * 5);           
    }

    public function create()
    {
        $roles = $this->role->pluck('name', 'name')->all();

        return view('cms.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();

        $input['password'] = bcrypt($input['password']);

        $user = $this->user->create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    public function show($id)
    {
        $user = $this->user->find($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = $this->user->find($id);
        $roles = $this->role->pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('cms.users.edit', compact('user', 'roles', 'userRole'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id.',user_id',
            'roles' => 'required'
        ]);


        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = $this->user->find($id);
        $user->update($input);
        $this->dB->table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole(Role::findByName($request->input('roles')));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $this->user->find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }

    public function register(Request $request)
    {
        $input = $request->all();
        $input['is_active'] = 1;
        $input['avatar'] = 'https://previews.123rf.com/images/jpgon/jpgon1409/jpgon140900205/31765909-illustration-of-a-cartoon-bird-avatar.jpg';
        $input['password'] = Hash::make($request->get('password'));

        return User::create($input);
    }

}
