<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use App\Http\Requests;
use App\Audit;
use App\AuditStore;
use App\EnrollmentType;
use App\AuditEnrollmentTypeMapping;

class AuditController extends Controller
{
    public function index(){
    	$audits = Audit::all();
    	return view('audit.index', compact('audits'));
    }


    public function create()
    {
        return view('audit.create');
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|max:100|unique:audits,description',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        $audit = new Audit;
        $audit->description = $request->description;
        $audit->start_date = date('Y-m-d',strtotime($request->start_date));
        $audit->end_date = date('Y-m-d',strtotime($request->end_date));
        $audit->active = ($request->active) ? 1 : 0;
        $audit->save();



        Session::flash('flash_message', 'Audit successfully added!');
        Session::flash('flash_class', 'alert-success');
        return redirect()->route("audits.index");
    }

    public function edit($id){
        $audit = Audit::findOrFail($id);
        return view('audit.edit',compact('audit'));
    }

   public function update(Request $request, $id){

        $audit = Audit::findOrFail($id);
        $this->validate($request, [
            'description' => 'required|max:100|unique:audits,description,'.$id,
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        $audit->description = $request->description;
        $audit->start_date = date('Y-m-d',strtotime($request->start_date));
        $audit->end_date = date('Y-m-d',strtotime($request->end_date));
        $audit->active = ($request->active) ? 1 : 0;
        $audit->update();



        Session::flash('flash_message', 'Audit successfully added!');
        Session::flash('flash_class', 'alert-success');
        return redirect()->route("audits.index");
    }
}
