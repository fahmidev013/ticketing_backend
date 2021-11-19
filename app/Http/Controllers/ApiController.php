<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Priority;
use App\Models\Resolution;
use App\Models\Issue;
use App\Models\Type;
use App\Models\Status;
use App\Models\Component;
use App\Models\Project;
use App\Models\UserGroup;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{

    public function getCategory()
    {
        $res = Category::all();
        return response()->json($res, 200);
    }

    public function getNotif(Request $req)
    {
        $isuList = $req->input("isuId");
        $listInt = explode(",", $isuList);
        $data = Issue::whereIn('Issue.id', $listInt)
            ->leftjoin('systemdb.system_user as db2', 'Issue.id_user', '=', 'db2.id')
            ->leftjoin('status', 'Issue.id_status', '=', 'status.id')
            ->leftjoin('category', 'Issue.id_category', '=', 'category.id')
            ->leftjoin('priority', 'Issue.id_priority', '=', 'priority.id')
            ->leftjoin('component', 'Issue.id_component', '=', 'component.id')
            ->leftjoin('project', 'Issue.id_project', '=', 'project.id')
            ->leftjoin('resolution', 'Issue.id_resolution', '=', 'resolution.id')
            ->select([
                'Issue.id_resolution as id_resolution', 'resolution.description as resolution_desc',
                'Issue.id_project as id_project', 'project.description as project_desc',
                'Issue.id_component as id_component', 'component.description as component_desc',
                'Issue.id_priority as id_priority', 'priority.description as priority_desc',
                'Issue.id_category as id_category', 'category.description as category_desc',
                'Issue.id_status as id_status', 'status.description as status_desc',
                'Issue.id as id', 'Issue.description as desc', 'db2.id as id_user',
                'db2.name as name', 'Issue.register_date', 'Issue.close_date', 'Issue.labels', 'Issue.title', 'Issue.environment'
            ])
            ->orderBy('Issue.id', 'DESC')
            ->get();


        if (!$data) {
            $res = [];
            return response()->json($res, 401);
        }

        $res = array();
        foreach ($data as $key => $val) {
            array_push($res, [
                "index" => $key + 1,
                "issue_id" => $val->id,
                "issue_status" => $val->status_desc,
                "issue_category" => $val->category_desc,
                "issue_priority" => $val->priority_desc,
                "issue_resolution" => $val->resolution_desc,
                "issue_project" => $val->project_desc,
                "issue_component" => $val->component_desc,
                "issue_desc" => $val->desc,
                "issue_user_id" => $val->id_user,
                "issue_user_name" => $val->name,
                "issue_register_date" => $val->register_date,
                "issue_close_date" => $val->close_date,
                "issue_labels" => $val->labels,
                "issue_title" => $val->title,
                "issue_environment" => $val->environment,

            ]);
        }

        // $res = array_map('intval', explode(',', $isuList));

        // $res = Component::all();
        return response()->json($res, 200);
    }


    public function getIssueByGroup(Request $req)
    {


        $userGroup = DB::connection('mysql2')->select('select system_group_id from system_user_group where system_user_id = :iduser and system_group_id in (13,14,15,17,18)', ['iduser' => $req->userid]);

        $groups = [];
        foreach ($userGroup as $val) {
            array_push($groups, $val->system_group_id);
        }
        if (in_array(15, $groups)) {
            $data = Issue::select([
                'issue.id_resolution as id_resolution', 'resolution.description as resolution_desc',
                'issue.id_project as id_project', 'project.description as project_desc',
                'issue.id_component as id_component', 'component.description as component_desc',
                'issue.id_priority as id_priority', 'priority.description as priority_desc',
                'issue.id_category as id_category', 'category.description as category_desc',
                'issue.id_status as id_status', 'status.description as status_desc',
                'issue.id as id', 'Issue.description as desc', 'db2.id as id_user',
                'db2.name as name', 'issue.register_date', 'issue.close_date', 'issue.labels', 'issue.title', 'issue.environment'
            ])
                ->leftjoin('systemdb.system_user as db2', 'issue.id_user', '=', 'db2.id')
                ->leftjoin('status', 'issue.id_status', '=', 'status.id')
                ->leftjoin('category', 'issue.id_category', '=', 'category.id')
                ->leftjoin('priority', 'issue.id_priority', '=', 'priority.id')
                ->leftjoin('component', 'issue.id_component', '=', 'component.id')
                ->leftjoin('project', 'issue.id_project', '=', 'project.id')
                ->leftjoin('resolution', 'issue.id_resolution', '=', 'resolution.id')
                ->orderBy('issue.id', 'DESC')
                ->get();
        } else {
            $listUser = DB::connection('mysql2')->select('select DISTINCT(system_user_id) from system_user_group where system_group_id in ( ? ) order by system_user_id', [implode(',', $groups)]);
            $users = [];
            foreach ($listUser as $user) {
                array_push($users, $user->system_user_id);
            }


            $data = Issue::whereIn('id_user', $users)
                ->leftjoin('systemdb.system_user as db2', 'issue.id_user', '=', 'db2.id')
                ->leftjoin('status', 'issue.id_status', '=', 'status.id')
                ->leftjoin('category', 'issue.id_category', '=', 'category.id')
                ->leftjoin('priority', 'issue.id_priority', '=', 'priority.id')
                ->leftjoin('component', 'issue.id_component', '=', 'component.id')
                ->leftjoin('project', 'issue.id_project', '=', 'project.id')
                ->leftjoin('resolution', 'issue.id_resolution', '=', 'resolution.id')
                ->select([
                    'issue.id_resolution as id_resolution', 'resolution.description as resolution_desc',
                    'issue.id_project as id_project', 'project.description as project_desc',
                    'issue.id_component as id_component', 'component.description as component_desc',
                    'issue.id_priority as id_priority', 'priority.description as priority_desc',
                    'issue.id_category as id_category', 'category.description as category_desc',
                    'issue.id_status as id_status', 'status.description as status_desc',
                    'issue.id as id', 'Issue.description as desc', 'db2.id as id_user',
                    'db2.name as name', 'issue.register_date', 'issue.close_date', 'issue.labels', 'issue.title', 'issue.environment'
                ])
                ->orderBy('issue.id', 'DESC')
                ->get();
        }



        if (!$data) {
            $res = [];
            return response()->json($res, 401);
        }

        $res = array();
        foreach ($data as $key => $val) {
            array_push($res, [
                "index" => $key + 1,
                "issue_id" => $val->id,
                "issue_status" => $val->status_desc,
                "issue_category" => $val->category_desc,
                "issue_priority" => $val->priority_desc,
                "issue_resolution" => $val->resolution_desc,
                "issue_project" => $val->project_desc,
                "issue_component" => $val->component_desc,
                "issue_desc" => $val->desc,
                "issue_user_id" => $val->id_user,
                "issue_user_name" => $val->name,
                "issue_register_date" => $val->register_date,
                "issue_close_date" => $val->close_date,
                "issue_labels" => $val->labels,
                "issue_title" => $val->title,
                "issue_environment" => $val->environment,

            ]);
        }

        return response()->json($res, 200);
    }

    public function getIssueById(Request $req)
    {


        $userGroup = DB::connection('mysql2')->select('select system_group_id from system_user_group where system_user_id = :iduser and system_group_id in (13,14,15,17,18)', ['iduser' => $req->userid]);

        $groups = [];
        foreach ($userGroup as $val) {
            array_push($groups, $val->system_group_id);
        }
        if (in_array(15, $groups)) {
            $data = Issue::select([
                'issue.id_resolution as id_resolution', 'resolution.description as resolution_desc',
                'issue.id_project as id_project', 'project.description as project_desc',
                'issue.id_component as id_component', 'component.description as component_desc',
                'issue.id_priority as id_priority', 'priority.description as priority_desc',
                'issue.id_category as id_category', 'category.description as category_desc',
                'issue.id_status as id_status', 'status.description as status_desc',
                'issue.id as id', 'issue.description as desc', 'db2.id as id_user',
                'db2.name as name', 'issue.register_date', 'issue.close_date', 'issue.labels', 'issue.title', 'issue.environment'
            ])
                ->leftjoin('systemdb.system_user as db2', 'issue.id_user', '=', 'db2.id')
                ->leftjoin('status', 'issue.id_status', '=', 'status.id')
                ->leftjoin('category', 'issue.id_category', '=', 'category.id')
                ->leftjoin('priority', 'issue.id_priority', '=', 'priority.id')
                ->leftjoin('component', 'issue.id_component', '=', 'component.id')
                ->leftjoin('project', 'issue.id_project', '=', 'project.id')
                ->leftjoin('resolution', 'issue.id_resolution', '=', 'resolution.id')
                ->orderBy('issue.id', 'DESC')
                ->get();
        } else {


            $data = Issue::where('id_user', $req->userid)
                ->orWhere('id_member', $req->userid)
                ->leftjoin('systemdb.system_user as db2', 'issue.id_user', '=', 'db2.id')
                ->leftjoin('status', 'issue.id_status', '=', 'status.id')
                ->leftjoin('category', 'issue.id_category', '=', 'category.id')
                ->leftjoin('priority', 'issue.id_priority', '=', 'priority.id')
                ->leftjoin('component', 'issue.id_component', '=', 'component.id')
                ->leftjoin('project', 'issue.id_project', '=', 'project.id')
                ->leftjoin('resolution', 'issue.id_resolution', '=', 'resolution.id')
                ->select([
                    'issue.id_resolution as id_resolution', 'resolution.description as resolution_desc',
                    'issue.id_project as id_project', 'project.description as project_desc',
                    'issue.id_component as id_component', 'component.description as component_desc',
                    'issue.id_priority as id_priority', 'priority.description as priority_desc',
                    'issue.id_category as id_category', 'category.description as category_desc',
                    'issue.id_status as id_status', 'status.description as status_desc',
                    'issue.id as id', 'issue.description as desc', 'db2.id as id_user',
                    'db2.name as name', 'issue.register_date', 'issue.close_date', 'issue.labels', 'issue.title', 'issue.environment'
                ])
                ->orderBy('issue.id', 'DESC')
                ->get();
        }



        if (!$data) {
            $res = [];
            return response()->json($res, 401);
        }

        $res = array();
        foreach ($data as $key => $val) {
            array_push($res, [
                "index" => $key + 1,
                "issue_id" => $val->id,
                "issue_status" => $val->status_desc,
                "issue_category" => $val->category_desc,
                "issue_priority" => $val->priority_desc,
                "issue_resolution" => $val->resolution_desc,
                "issue_project" => $val->project_desc,
                "issue_component" => $val->component_desc,
                "issue_desc" => $val->desc,
                "issue_user_id" => $val->id_user,
                "issue_user_name" => $val->name,
                "issue_register_date" => $val->register_date,
                "issue_close_date" => $val->close_date,
                "issue_labels" => $val->labels,
                "issue_title" => $val->title,
                "issue_environment" => $val->environment,

            ]);
        }

        return response()->json($res, 200);
    }

    public function getPriority()
    {
        $res = Priority::all();
        return response()->json($res, 200);
    }

    public function getProject()
    {
        $res = Project::all();
        return response()->json($res, 200);
    }

    public function getResolution()
    {
        $res = Resolution::all();
        return response()->json($res, 200);
    }

    public function getStatus()
    {
        $res = Status::all();
        return response()->json($res, 200);
    }

    public function getType()
    {
        $res = Type::all();
        return response()->json($res, 200);
    }


    function generateRandomString($length = 80)
    {
        $karakkter = '012345678dssd9abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $panjang_karakter = strlen($karakkter);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $karakkter[rand(0, $panjang_karakter - 1)];
        }
        return $str;
    }
}
