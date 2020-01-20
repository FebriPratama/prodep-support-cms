<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\API\ProblemTopicApi;
use App\Models\API\ProblemListApi;

class ProblemController extends Controller
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

    private $topic;
    private $list;

    public function __construct(ProblemTopicApi $topic,ProblemListApi $list)
    {
         $this->topic = $topic;
         $this->list = $list;
    }

    public function index(Request $request){

        $columns = ['name'];

        $from = $request->input('from');
        $to = $request->input('to');

        $datas = [];
        $last = strtotime($from); 
        $next = strtotime($to);

        $order = trim($request->input('orderBy')) !== '' ? $request->input('orderBy') : 'tbl_problem_topics.created_at';
        $orderDirection = $request->input('orderDirection') == 'true' ? 'ASC' : 'DESC';

        $datas = $this->topic->orderBy($order,$orderDirection);

        if(trim($request->input('q')) !== ''){

            $datas->where(function($query) use($request,$columns)
            {

                foreach($columns as $c){

                    $query->orWhere('tbl_problem_topics.'.$c, 'LIKE', '%'.$request->input('q').'%');

                }
            
            });

        }

        if(trim($last) !== '' && trim($next) !== ''){

            $datas->where('created_at','>=',date('Y-m-d', $last))
                        ->where('created_at','<=',date('Y-m-d', $next)); 
                        
        }

        $datas = $datas->get();

        foreach($datas as $data){
            $data->list;
        }

        return response()->json($datas);

    }

    public function show($id){

        $data = $this->topic->where('id',$id)->first();

        return response()->json($data);

    }

    public function showList($id){

        $data = $this->list->where('id',$id)->first();

        return response()->json($data);

    }

}
