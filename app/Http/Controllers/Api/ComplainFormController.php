<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Log;

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

        $columns = ['customer_name', 'customer_email','sales_order_no','problem_desc'];

        $from = $request->input('from');
        $to = $request->input('to');

        $datas = [];
        $last = strtotime($from); 
        $next = strtotime($to);

        $order = trim($request->input('orderBy')) !== '' ? $request->input('orderBy') : 'tbl_complaint_forms.created_at';
        $orderDirection = $request->input('orderDirection') == 'true' ? 'ASC' : 'DESC';

        $datas = $this->thread
                    ->join('tbl_complaint_forms','tbl_complaint_forms.id','=','tbl_threads.cf_id')
                    ->where('tbl_threads.customer_id',auth()->user()->user_id)->orderBy($order,$orderDirection);

        if(trim($request->input('q')) !== ''){

            $datas->where(function($query) use($request,$columns)
            {

                foreach($columns as $c){

                    $query->orWhere('tbl_complaint_forms.'.$c, 'LIKE', '%'.$request->input('q').'%');

                }
            
            });

        }

        if(trim($last) !== '' && trim($next) !== ''){

            $datas->where('created_at','>=',date('Y-m-d', $last))
                        ->where('created_at','<=',date('Y-m-d', $next)); 
                        
        }

        $datas = $datas->select(['tbl_complaint_forms.*','tbl_threads.*','tbl_threads.id as thread_id'])->get();

        return response()->json($datas);

    }

    public function show($id){

        $data = $this->thread->where('id',$id)->first();

        if(is_object($data)){

            $data->salesorder;
            $data->form;
            $data->problem;
            $data->customer;

        }

        return response()->json($data);

    }

    public function store(Request $request){

        $this->validate($request, [

            'customer_name' => 'required',
            'customer_email' => 'required',
            'sales_order_no' => 'required',
            'problem_desc' => 'required',

        ]);
        
        // check thread for particular so id
        if(is_object($this->thread->where('so_id',$request->input('so_id'))->where('thread_status','open')->first())){
            return response()->json(['message' => 'Data dari sales order sudah ada'], 404);
        }    

        //cf
        $cf = $this->cf->create([

            'customer_name' => $request->input('customer_name'),
            'customer_email' => $request->input('customer_email'),
            'sales_order_no' => $request->input('sales_order_no'),
            'problem_desc' => $request->input('problem_desc')

        ]);

        //assign cs
        $supports = User::role('Support')->get();

        $before = ['uid' => '','total' => 0 ];
        foreach($supports as $user){

            $total = $user->threads()->where('thread_status','open')->count();

            if($total <= $before['total']){
                $before['uid'] = $user->user_id;
                $before['total'] = $total;
            }

        }

        //if no cs availliable
        if(trim($before['uid']) == ''){
            foreach($supports as $user){
                    $before['uid'] = $user->user_id;
                    $before['total'] = $total;
                break;
            }
        }

        //thread
        $thread = $this->thread->create([

            'so_id' => $request->input('so_id'),
            'pl_id' => $request->input('pl_id'),
            'cf_id' => $cf->id,
            'customer_id' => auth()->user()->user_id,
            'cs_id' => $before['uid'],
            'thread_status' => 'open'

        ]);

        //messages
            //images
            $image1 = $this->message->create([

                'sender_id' => auth()->user()->user_id,
                'type' => 'image',
                'body' => 'bukti_transfer',
                'thread_id' => $thread->id
            ]);

            $image1->addMedia($request->file('bukti_transfer'))->toMediaCollection('message');

            $image2 = $this->message->create([

                'sender_id' => auth()->user()->user_id,
                'type' => 'image',
                'body' => 'lampiran_1',
                'thread_id' => $thread->id

            ]);

            $image2->addMedia($request->file('lampiran_1'))->toMediaCollection('message');

            //text
            $this->message->create([

                'sender_id' => auth()->user()->user_id,
                'type' => 'text',
                'body' => $request->input('problem_desc'),
                'thread_id' => $thread->id

            ]);

        return response()->json($thread);

    }

    public function changeStatus($id,Request $request){

        $this->validate($request, [

            'thread_reason' => 'required',
            'thread_status' => 'required',

        ]);

        $thread = $this->thread
                    ->where('customer_id',auth()->user()->user_id)
                    ->where('id',$id)->first();

        if(!is_object($thread)){

            return response()->json([ 'message' => 'Data not found' ]);

        }

        $thread->thread_reason = $request->input('thread_reason');
        $thread->thread_status = $request->input('thread_status');
        $thread->save();

        return response()->json($thread);

    }

}
