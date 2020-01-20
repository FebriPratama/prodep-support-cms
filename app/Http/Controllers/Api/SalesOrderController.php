<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\SalesOrderApi;

class SalesOrderController extends Controller
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

    private $so;

    public function __construct(SalesOrderApi $so)
    {
         $this->so = $so;
    }

    public function index(Request $request){

        $columns = ['so_type', 'so_product_name','so_total','user_id','so_status'];

        $from = $request->input('from');
        $to = $request->input('to');

        $datas = [];
        $last = strtotime($from); 
        $next = strtotime($to);

        $order = trim($request->input('orderBy')) !== '' ? $request->input('orderBy') : 'tbl_salesorders.created_at';
        $orderDirection = $request->input('orderDirection') == 'true' ? 'ASC' : 'DESC';

        $datas = $this->so->where('user_id',auth()->user()->user_id)->orderBy($order,$orderDirection);

        if(trim($request->input('q')) !== ''){

            $datas->where(function($query) use($request,$columns)
            {

                foreach($columns as $c){

                    $query->orWhere('tbl_salesorders.'.$c, 'LIKE', '%'.$request->input('q').'%');

                }
            
            });

        }

        if(trim($last) !== '' && trim($next) !== ''){

            $datas->where('created_at','>=',date('Y-m-d', $last))
                        ->where('created_at','<=',date('Y-m-d', $next)); 
                        
        }

        $datas = $datas->get();

        return response()->json($datas);

    }

    public function show($id){

        $data = $this->so->where('user_id',auth()->user()->user_id)
                ->where('id',$id)->first();

        return response()->json($data);

    }

}
