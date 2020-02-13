<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\API\ThreadApi;
use Illuminate\Support\Facades\Log;

class ThreadController extends Controller
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

    private $message;
    private $thread;

    public function __construct(Message $message,ThreadApi $thread)
    {
         $this->message = $message;
         $this->thread = $thread;
    }

    public function index(Request $request){

        $columns = ['customer_name', 'customer_email','sales_order_no','problem_desc'];

        $datas = [];

        $order = trim($request->input('orderBy')) !== '' ? $request->input('orderBy') : 'tbl_threads.created_at';
        $orderDirection = $request->input('orderDirection') == 'true' ? 'ASC' : 'DESC';

        $datas = $this->thread
                        ->join('tbl_complaint_forms','tbl_complaint_forms.id','=','tbl_threads.cf_id')
                        ->where('tbl_threads.cs_id',auth()->user()->user_id)->orderBy($order,'ASC');

        if(trim($request->input('q')) !== ''){

            $datas->where(function($query) use($request,$columns)
            {

                foreach($columns as $c){

                    $query->orWhere('tbl_complaint_forms.'.$c, 'LIKE', '%'.$request->input('q').'%');

                }
            
            });

        }

        $datas = $datas->select(['tbl_complaint_forms.*','tbl_threads.*','tbl_threads.id as thread_id'])->get();
        
        return response()->json($datas);

    }

}
