<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\API\ThreadApi;
use App\Events\MessageAdded;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
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

    public function index($id,Request $request){

        $datas = [];

        $order = trim($request->input('orderBy')) !== '' ? $request->input('orderBy') : 'tbl_messages.created_at';
        $orderDirection = $request->input('orderDirection') == 'true' ? 'ASC' : 'DESC';

        $datas = $this->message->where('thread_id',$id)->orderBy($order,'ASC');

        $datas = $datas->get();
        
        return response()->json($datas);

    }

    public function store($id,Request $request){

        $thread = $this->thread->find($id);

        if(!is_object($thread)){
        
            return response()->json([ 'message' => 'Data not found' ]);

        }

        $datas = [];

        if($request->hasFile('attachments')){
            
            foreach($request->file('attachments') as $file){

                $image = $this->message->create([

                    'sender_id' => auth()->user()->user_id,
                    'type' => 'image',
                    'body' => 'attachment',
                    'thread_id' => $id

                ]);

                $image->addMedia($file)->toMediaCollection('message');

                $datas[] = $image;

            }

        }

        if(trim($request->input('body')) != ''){

            //text
            $datas[] = $this->message->create([

                'sender_id' => auth()->user()->user_id,
                'type' => 'text',
                'body' => $request->input('body'),
                'thread_id' => $id

            ]);

        }

        event(new MessageAdded($datas,$id,$thread));

        return response()->json($datas);

    }

}
