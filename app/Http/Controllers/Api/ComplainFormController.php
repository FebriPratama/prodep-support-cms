<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\API\ComplainFormApi;
use App\Models\API\ThreadApi;

use App\Models\User;
use App\Models\Message;

class ComplainFormController extends Controller
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

    private $cf;
    private $thread;
    private $user;
    private $message;

    public function __construct(ComplainFormApi $cf,ThreadApi $thread,User $user,Message $message)
    {
         $this->cf = $cf;
         $this->thread = $thread;
         $this->user = $user;
         $this->message = $message;
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

    public function store(){

        $this->validate($request, [

            'name' => 'required',
            'email' => 'required',
            'sales_order_no' => 'required',
            'problem_desc' => 'required',

        ]);

        //cf
        $cf = $this->cf->insert([

            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'sales_order_no' => $request->input('sales_order_no'),
            'problem_desc' => $request->input('problem_desc')

        ]);

        //assign cs
        $supports = User::role('Support')->get();

        $before = ['uid' => '','total' => 0 ];
        foreach($supports as $user){

            $total = $user->threads()->where('thread_status','open')->count();

            if($total <= $before['total']){
                $before['uid'] => $user->user_id;
                $before['total'] => $total;
            }

        }

        //if no cs availliable
        if(trim($before['uid']) == ''){
            foreach($supports as $user){
                    $before['uid'] => $user->user_id;
                    $before['total'] => $total;
                break;
            }
        }

        //thread
        $thread = $this->thread->insert([

            'so_id' => $request->input('so_id'),
            'pl_id' => $request->input('pl_id'),
            'cf_id' => $cf->id,
            'customer_id' => $request->input('customer_id'),
            'cs_id' => $before['uid'],
            'thread_status' => 'open',

        ]);

        //messages
            //images
            $image1 = $this->message->insert([

                'sender_id' => $request->input('customer_id'),
                'type' => 'image',
                'body' => 'bukti_transfer',

            ]);

            $image1->addMedia($request->file('bukti_transfer'))->toMediaCollection('message');
            
            $image2 = $this->message->insert([

                'sender_id' => $request->input('customer_id'),
                'type' => 'image',
                'body' => 'lampiran_1',

            ]);

            $image2->addMedia($request->file('lampiran_1'))->toMediaCollection('message');

            //text
            $this->message->insert([

                'sender_id' => $request->input('customer_id'),
                'type' => 'text',
                'body' => $request->input('problem_desc'),

            ]);

    }

}
