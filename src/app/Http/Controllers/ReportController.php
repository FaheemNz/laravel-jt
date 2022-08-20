<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Report;
use DataTables;
use Validation;
use Illuminate\Validation\ValidationException;
use Validator;

class ReportController extends BaseController
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $data = Report::withTrashed()->latest();
            return DataTables::of($data)
                    ->filter(function ($instance) use ($request) {
                        if($request->get('status') == '0') {
                            $instance->onlyTrashed();
                        }
                        if($request->get('is_resolved') == '1') {
                            $instance->where(function($w) use($request){
                                $w->where('is_resolved',true);
                            });
                        } else {
                            $instance->where(function($w) use($request){
                                $w->where('is_resolved',false);
                            });
                        }
                        if($request->get('is_reviewed') == '1') {
                            $instance->where(function($w) use($request){
                                $w->where('is_reviewed',true);
                            });
                        } else {
                            $instance->where(function($w) use($request){
                                $w->where('is_reviewed',false);
                            });
                        }
                    })
                    ->setRowAttr([
                        'style' => function($row)  use ($request){
                            if($request->get('status') != '0') {
                                return $row->trashed() ? 'background-color: #ff6666;' : '';
                            }
                        }
                    ])
                    ->addIndexColumn()
                    ->addColumn('reported_by', function($row){
                        $order_by = null;
                        if ($row->createdBy) {
                            $order_by = $row->createdBy->first_name.$row->createdBy->last_name;
                        }
                        return $order_by;
                    })
                    ->addColumn('entity', function($row){
                        $data = null;
                        if($row->type == 'order') {
                            $data =  '<a href="'.url('orders').'/'.$row->entity_id.'">Order No : ' .$row->entity_id. '</a>';
                        } else {
                            $data =  '<a href="'.url('offers').'/'.$row->entity_id.'">Offer No : ' .$row->entity_id. '</a>';
                        }
                        return $data;
                    })
                    ->addColumn('reason', function($row){
                        $reason = null;
                        if($row->reason){
                            $reason = '<span class="badge badge-warning">'.$row->reason.'</span>';
                        }
                        return $reason;
                    })
                    ->addColumn('is_reviewed', function($row){
                        if ($row->is_reviewed == true) {
                            $status = '<span class="badge badge-success">Yes</span>';
                        } else {
                            $status = '<span class="badge badge-warning">No</span>';
                        }
                        return $status;
                    })
                    ->addColumn('is_resolved', function($row){
                        if ($row->is_resolved == true) {
                            $status = '<span class="badge badge-success">Yes</span>';
                        } else {
                            $status = '<span class="badge badge-danger">No</span>';
                        }
                        return $status;
                    })
                    ->addColumn('created_at', function($row){
                        $created_at = null;
                        if ($row->created_at) {
                            $created_at = date('Y-m-d H:i',strtotime($row->created_at));
                        }
                        return $created_at;
                    })
                    ->addColumn('action', function($row){
                        $btn = '';
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editReport">Update</a>';
                        $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteReport">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['reported_by','entity','reason','is_reviewed','is_resolved','created_at','action'])
                    ->make(true);
        }
        $reports = Report::latest()->get();
        return view('report.index',compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->report_id == '' || $request->report_id == null) {
            return response()->json(null);
        } else {
            $validator = Validator::make(
                $request->all(),
                [
                    'admin_review'            => 'nullable',
                    'is_reviewed'             => 'required|boolean',
                    'is_resolved'             => 'required|boolean',
                ]
            );
        }
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $report = Report::find($request->report_id);
        $report->is_reviewed = ($request->is_reviewed == "1") ? true : false;
        $report->is_resolved = ($request->is_resolved == "1") ? true : false;
        $report->admin_review = $request->admin_review;
        $report->save();
        return response()->json(['success' => ['Report updated successfully']], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $report = Report::with('createdBy')->where('id',$id)->first();
        return response()->json($report);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $report = Report::find($id);
        if($report) {
            $report->delete();
        }
        return response()->json(['success'=>'Report deleted successfully.']);
    }
}
