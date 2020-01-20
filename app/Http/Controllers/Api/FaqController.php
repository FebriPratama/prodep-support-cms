<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
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

    private $faq;

    public function __construct(Faq $faq)
    {
         $this->faq = $faq;
    }

    public function index(Request $request){
        $columns = ['id','name','description','slug'];

        $from = $request->input('from');
        $to = $request->input('to');

        $datas = [];
        $last = strtotime($from); 
        $next = strtotime($to);

        $order = trim($request->input('orderBy')) !== '' ? $request->input('orderBy') : 'tbl_faqs.created_at';
        $orderDirection = $request->input('orderDirection') == 'true' ? 'ASC' : 'DESC';

        $datas = $this->faq->orderBy($order,$orderDirection);

        if(trim($request->input('q')) !== ''){

            $datas->where(function($query) use($request,$columns)
            {

                foreach($columns as $c){

                    $query->orWhere('tbl_faqs.'.$c, 'LIKE', '%'.$request->input('q').'%');

                }
            
            });

        }

        if(trim($last) !== '' && trim($next) !== ''){

            $datas->where('created_at','>=',date('Y-m-d', $last))
                        ->where('created_at','<=',date('Y-m-d', $next)); 
                        
        }

        $datas = $datas->dontRemember()->get();

        return response()->json($datas);

    }

    public function show($id){

        $data = $this->faq->dontRemember()->where('slug',$id)->first();

        return response()->json($data);

    }
}
