<?php

namespace App\Http\Controllers\CMS;

use Illuminate\Http\Request;
use App\Models\ProblemTopic;
use DataTables;
use Illuminate\Database\DatabaseManager as DB;
use App\Http\Controllers\Controller;

class ProblemController extends Controller
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
    private $topic;

    public function __construct(DB $dB, ProblemTopic $topic, DataTables $datatables)
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);

         $this->dB = $dB;
         $this->topic = $topic;
    }

    public function index(Request $request)
    {
        $columns = ['id','name'];

        $from = $request->input('from');
        $to = $request->input('to');

        $datas = [];
        $last = strtotime($from); 
        $next = strtotime($to);

        $order = trim($request->input('orderBy')) !== '' ? $request->input('orderBy') : 'tbl_problem_topics.created_at';
        $orderDirection = $request->input('orderDirection') == 'true' ? 'ASC' : 'DESC';

        $datas = $this->topic->orderBy($order,$orderDirection);

        if(trim($request->input('q')) !== ''){

            $datas->where(function($query) use($request)
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

        $datas = $datas->paginate(5);

        return view('cms.topics.index',compact('datas'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {

        return view('cms.topics.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:tbl_problem_topics,name',
        ]);

        $topic = $this->topic->create([
            'name' => $request->input('name')]);

        return redirect()->route('topics.index')
                        ->with('success','Topic created successfully');
    }

    public function show($id)
    {

        return redirect()
                        ->route('topics.index');
    }

    public function edit($id)
    {
        $data = $this->topic->find($id);

        if(!is_object($data)){

            return redirect()
                        ->route('topics.index');

        }

        return view('cms.topics.edit',compact('data'));
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
            'name' => 'required|:tbl_problem_topics,name,'.$id
        ]);

        $topic = $this->topic->find($id);

        if(!is_object($topic)){

            return redirect()
                        ->route('roles.index');

        }

        $topic->name = $request->input('name');
        $topic->save();
        
        return redirect()->route('topics.index')
                        ->with('success','Topic updated successfully');
    }
    
    public function destroy($id)
    {
        $this->topic->whereId($id)->delete();
        return redirect()->route('topics.index')
                        ->with('success','Topic deleted successfully');
    }
}