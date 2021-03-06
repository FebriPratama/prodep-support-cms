<?php

namespace App\Http\Controllers\CMS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CMSController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        return view('cms.dashboard.index');
    }
}
