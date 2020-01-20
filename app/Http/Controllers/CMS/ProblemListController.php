<?php

namespace App\Http\Controllers\CMS;

use Illuminate\Http\Request;
use App\Models\ProblemList;
use App\Models\ProblemTopic;
use DataTables;
use Illuminate\Database\DatabaseManager as DB;
use App\Http\Controllers\Controller;

class ProblemListController extends Controller
{
    /**
     * @var BaseModel The primary model associated with this controller
     */
    public static $model = ProblemTopic::class;

    /**
     * @var BaseModel The parent model of the model, in the case of a child rest controller
     */
    public static $parentModel = null;

    /**
     * @var null|BaseTransformer The transformer this controller should use, if overriding the model & default
     */
    public static $transformer = null;

    private $dB;
    private $list;
    private $topic;

    public function __construct(DB $dB, ProblemList $list, ProblemTopic $topic, DataTables $datatables)
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);

         $this->dB = $dB;
         $this->list = $list;
         $this->topic = $topic;
    }

    public function index(Request $request)
    {
        $columns = ['id','title','description'];

        $from = $request->input('from');
        $to = $request->input('to');

        $datas = [];
        $last = strtotime($from); 
        $next = strtotime($to);

        $order = trim($request->input('orderBy')) !== '' ? $request->input('orderBy') : 'tbl_problem_lists.created_at';
        $orderDirection = $request->input('orderDirection') == 'true' ? 'ASC' : 'DESC';

        $datas = $this->list->orderBy($order,$orderDirection);

        if(trim($request->input('q')) !== ''){

            $datas->where(function($query) use($request)
            {

                foreach($columns as $c){

                    $query->orWhere('tbl_problem_lists.'.$c, 'LIKE', '%'.$request->input('q').'%');

                }
            
            });

        }

        if(trim($last) !== '' && trim($next) !== ''){

            $datas->where('created_at','>=',date('Y-m-d', $last))
                        ->where('created_at','<=',date('Y-m-d', $next)); 
                        
        }

        $datas = $datas->paginate(5);

        return view('cms.problemlist.index',compact('datas'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $topics = $this->topic::all();

        return view('cms.problemlist.create',compact('topics'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'topic_id' => 'required',
        ]);

        $data = $this->list->create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'topic_id' => $request->input('topic_id')]);

        return redirect()->route('list.index')
                        ->with('success','Problem List created successfully');
    }

    public function show($id)
    {
        return redirect()
                    ->route('list.index');
    }

    public function edit($id)
    {
        $data = $this->list->find($id);

        if(!is_object($data)){

            return redirect()
                        ->route('list.index');

        }

        $topics = $this->topic::all();

        return view('cms.problemlist.edit',compact('data','topics'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'topic_id' => 'required',
        ]);

        $data = $this->list->find($id);

        if(!is_object($data)){

            return redirect()
                        ->route('list.index');

        }

        $data->title = $request->input('title');
        $data->description = $request->input('description');
        $data->topic_id = $request->input('topic_id');
        $data->save();
        
        return redirect()->route('list.index')
                        ->with('success','Problem List updated successfully');
    }
    
    public function destroy($id)
    {
        $this->topic->whereId($id)->delete();
        return redirect()->route('list.index')
                        ->with('success','Topic deleted successfully');
    }
}