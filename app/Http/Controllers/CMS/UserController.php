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
use Illuminate\Support\Facades\Log;
use Facebook\Facebook;

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

    public function ToFbConnect($id)
    {
        $user = $this->user->find($id);

        if(is_object($user)){

            session(['UserFB' => $id ]);
            return redirect()->route('cms.connect.fb');

        }

        return redirect()->route('users.index')
        ->with('danger', 'Data Not Found');
    }

    public function RequestToken(Facebook $facebook, Request $request){
        
        $value = $request->session()->get('UserFB');

        if(!$request->session()->has('UserFB')){

            return redirect()->route('users.index')
            ->with('danger', 'Data Not Found');

        }

        $user = $this->user->find($value);

        if(!is_object($user)){

            return redirect()->route('users.index')
            ->with('success', 'Data Not Found');

        }

        $input = array(                
            'fb_token' => $user->fb_token,
            'fb_page_id' => $user->fb_page_id,
            'ig_page_id' => $user->ig_page_id
        );

        $loginUrl = "";
        $pagesArray = [];
        $igsArray = [];
        $accessToken = "";

        // helper
        $helper = $facebook->getRedirectLoginHelper();

        $helper->getPersistentDataHandler()->set('state', $request->input('state'));

        // oauth object
        $oAuth2Client = $facebook->getOAuth2Client();

        if ( (trim($request->input('code')) != '') 
                && (trim($user->ig_page_id) == '') ) { // get access token

            try {
                
                $accessToken = $helper->getAccessToken();
                $accessToken = $oAuth2Client->getLongLivedAccessToken( $accessToken );
                $accessToken = (String) $accessToken;

                $loginUrl = "";
                $baseUrl = 'https://graph.facebook.com/v9.0/';
    
                // get pages endpoint
                $endpointFormat = $baseUrl . 'me/accounts?access_token={access-token}';
                $pagesEndpoint = $baseUrl . 'me/accounts';
    
                // endpoint params
                $pagesParams = array(
                    'access_token' => $accessToken
                );
    
                // add params to endpoint
                $pagesEndpoint .= '?' . http_build_query( $pagesParams );
    
                // setup curl
                $ch = curl_init();
                curl_setopt( $ch, CURLOPT_URL, $pagesEndpoint );
                curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
                curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    
                // make call and get response
                $response = curl_exec( $ch );
                curl_close( $ch );
                $pagesArray = json_decode( $response, true );
                unset( $pagesArray['data'][0]['access_token'] );

                // get instagram account id endpoint
                $endpointFormat = $baseUrl . $pagesArray['data'][0]['id'].'?fields=instagram_business_account&access_token={access-token}';
                $instagramAccountEndpoint = $baseUrl . $pagesArray['data'][0]['id'];
    
                // endpoint params
                $igParams = array(
                    'fields' => 'instagram_business_account',
                    'access_token' => $accessToken
                );
    
                // add params to endpoint
                $instagramAccountEndpoint .= '?' . http_build_query( $igParams );
    
                // setup curl
                $ch = curl_init();
                curl_setopt( $ch, CURLOPT_URL, $instagramAccountEndpoint );
                curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
                curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    
                // make call and get response
                $response = curl_exec( $ch );
                curl_close( $ch );
                $igsArray = json_decode( $response, true );
    
                // update fb/fb page id
                $input = array(                
                    'fb_token' => $accessToken,
                    'fb_page_id' => $pagesArray['data'][0]['id'],
                    'ig_page_id' => $igsArray['instagram_business_account']['id']
                );
    
                $user->update($input);

            } catch ( Facebook\Exceptions\FacebookSDKException $e ) {

                return redirect()->route('users.index')
                ->with('danger', 'Error getting long lived access token ' . $e->getMessage());

            }

        } else { // display login url

            $permissions = ['public_profile', 'instagram_basic', 'pages_show_list', 'instagram_manage_insights', 'instagram_manage_comments'];
            $loginUrl = $helper->getLoginUrl( route('cms.connect.fb'), $permissions );   

        }     
            
        return view('cms.users.instagram', compact('user', 'loginUrl', 'input'));

    }

    public function getPages(){

        $accessToken = "EAAFTxbrFTZB8BAFj6DIbbx37fILbZC3IIgqKbtREjCZCJE14GBPT0zmzdh7ztkYXrJVb9PEl0A54jtCXgwzufb5uXwnaZCJZCrhwSTB2lWD5buLfZCsZCLKYMw1OZCZBUmEGGTDnbLbJDg8iYKMrbH5WM33a9Oze2krXs1yhZA7C4pfAZDZD";

        $baseUrl = 'https://graph.facebook.com/v9.0/';

        // get pages endpoint
        $endpointFormat = $baseUrl . 'me/accounts?access_token={access-token}';
        $pagesEndpoint = $baseUrl . 'me/accounts';

        Log::info($accessToken);

        // endpoint params
        $pagesParams = array(
            'access_token' => $accessToken
        );

        // add params to endpoint
        $pagesEndpoint .= '?' . http_build_query( $pagesParams );

        // setup curl
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $pagesEndpoint );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        // make call and get response
        $response = curl_exec( $ch );
        curl_close( $ch );
        $pagesArray = json_decode( $response, true );

        echo $pagesArray['data'][0]['id'];
    }
}
