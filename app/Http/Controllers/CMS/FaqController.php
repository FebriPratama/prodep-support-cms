<?php

namespace App\Http\Controllers\CMS;

use Illuminate\Http\Request;
use App\Models\Faq;
use DataTables;
use Illuminate\Database\DatabaseManager as DB;
use App\Http\Controllers\Controller;

class FaqController extends Controller
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
    private $faq;

    public function __construct(DB $dB, Faq $faq, DataTables $datatables)
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);

         $this->dB = $dB;
         $this->faq = $faq;
    }

    public function toUrlFriendly($str){


        if($str !== mb_convert_encoding( mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
        $str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
        $str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
        $str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $str);
        $str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
        $str = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $str);
        $str = strtolower( trim($str, '-') );

        return $str;
    }

    public function index(Request $request)
    {
        $columns = ['id','name','description'];

        $from = $request->input('from');
        $to = $request->input('to');

        $datas = [];
        $last = strtotime($from); 
        $next = strtotime($to);

        $order = trim($request->input('orderBy')) !== '' ? $request->input('orderBy') : 'tbl_faqs.created_at';
        $orderDirection = $request->input('orderDirection') == 'true' ? 'ASC' : 'DESC';

        $datas = $this->faq->orderBy($order,$orderDirection);

        if(trim($request->input('q')) !== ''){

            $datas->where(function($query) use($request)
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

        $datas = $datas->paginate(5);

        return view('cms.faq.index',compact('datas'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('cms.faq.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:tbl_faqs,name',
            'description' => 'required',
        ]);

        $data = $this->faq->create([
            'name' => $request->input('name'),
            'slug' => $this->toUrlFriendly($request->input('name')),
            'description' => $request->input('description')]);

        return redirect()->route('faq.index')
                        ->with('success','Faq created successfully');
    }

    public function show($id)
    {

        return redirect()
                    ->route('faq.index');
    }

    public function edit($id)
    {
        $data = $this->faq->find($id);

        if(!is_object($data)){

            return redirect()
                        ->route('faq.index');

        }

        return view('cms.faq.edit',compact('data'));
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
            'name' => 'required:tbl_faqs,name,'.$id,
            'description' => 'required'
        ]);

        $data = $this->faq->find($id);

        if(!is_object($data)){

            return redirect()
                        ->route('faq.index');

        }

        $data->name = $request->input('name');
        $data->slug = $this->toUrlFriendly($request->input('name'));
        $data->description = $request->input('description');
        $data->save();
        
        return redirect()->route('faq.index')
                        ->with('success','Faq updated successfully');
    }
    
    public function destroy($id)
    {
        $this->topic->whereId($id)->delete();
        return redirect()->route('faq.index')
                        ->with('success','Faq deleted successfully');
    }
}