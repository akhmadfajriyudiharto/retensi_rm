<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageSetting = [
            'title'         => 'Log Activity',
            'routeName'     => 'admin.activity-log'
        ];
        if (request()->ajax()) {
            $query = Activity::query()->orderBy('created_at','desc');

            return datatables()
                ->eloquent($query)
                ->addIndexColumn()
                ->rawColumns(['description', 'properties'])
                ->editColumn('id', function (Activity $model) {
                    return $model->id;
                })
                ->editColumn('subject_id', function (Activity $model) {
                    if (!isset($model->subject)) {
                        return '';
                    }

                    if (isset($model->subject->name)) {
                        return $model->subject->name;
                    }

                    return $model->subject->id;
                })
                ->editColumn('causer_id', function (Activity $model) {
                    return $model->causer ? $model->causer->name : __('System');
                })
                ->editColumn('properties', function (Activity $model) {
                    $content = $model->properties;

                    return view('admin.activity-log._details', compact('content'));
                })
                ->editColumn('created_at', function (Activity $model) {
                    return $model->created_at->format('d M, Y H:i:s');
                })
                ->make(true);
        }
        return view('admin.activity-log.index', compact('pageSetting'));
    }
}
