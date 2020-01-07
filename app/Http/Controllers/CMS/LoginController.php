<?php

namespace App\Http\Controllers\CMS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class LoginController extends Controller
{

    /**
     * @var BaseModel The primary model associated with this controller
     */
    public static $model = BaseModel::class;

    /**
     * @var BaseModel The parent model of the model, in the case of a child rest controller
     */
    public static $parentModel = null;

    /**
     * @var null|BaseTransformer The transformer this controller should use, if overriding the model & default
     */
    public static $transformer = null;

    public function index()
    {
        return view('cms.auth.login');
    }

    public function doLogin(Request $request)
    {

        $this->validate($request, [

          'email' => 'required|email',
          'password' => 'required'

        ]);

        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {

            return redirect()->route('cms.dashboard');

        }

        return redirect()->route('cms.auth.login')
                        ->with('success','Login failed, please check Email/Password');
    }
    public function logout()
    {
        if (Auth::guard('web')->check()) {
          Auth::guard('web')->logout();
        }
        
        return redirect()->route('cms.auth.login');
    }
}
