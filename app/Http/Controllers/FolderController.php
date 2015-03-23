<?php namespace Strimoid\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Input;
use Response;
use Str;
use Strimoid\Models\Folder;
use Strimoid\Models\Group;

class FolderController extends BaseController
{
    public function displayFolder()
    {
    }

    public function createFolder(Request $request)
    {
        $this->validate($request, Folder::rules());

        if (Folder::find($id)) {
            return Response::json(['status' => 'error']);
        }

        $folder = new Folder([
            'name' => Input::get('name'),
        ]);

        if (Input::has('groupname')) {
            $group = Group::findOrFail(Input::get('groupname'));
            $folder->groups = [$group->getKey()];
        }

        if (Auth::user()->folders()->save($folder)) {
            return Response::json(['status' => 'ok', 'id' => $folder->getKey()]);
        }

        return Response::json(['status' => 'error']);
    }

    public function editFolder()
    {
        $folder = Folder::findOrFail(Input::get('folder'));

        $validator = Validator::make(Input::all(), ['name' => 'min:1|max:64|regex:/^[a-z0-9\pL ]+$/u']);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'error', 'error' => $validator->messages()->first(),
            ]);
        }

        if (Input::has('public')) {
            $folder->public = Input::get('public') == 'true';
        }

        if (Auth::user()->folders()->save($folder)) {
            return Response::json(['status' => 'ok']);
        }

        return Response::json(['status' => 'error']);
    }

    public function copyFolder()
    {
        $folder = Folder::findUserFolderOrFail(Input::get('user'), Input::get('folder'));

        if (!$folder->public && $folder->user->getKey() != Auth::id()) {
            App::abort(404);
        }

        $validator = Validator::make(Input::all(), ['name' => 'required|min:1|max:64|regex:/^[a-z0-9\pL ]+$/u']);

        if ($validator->fails()) {
            return Redirect::route('user_folder_contents', [Input::get('user'), Input::get('folder')])
                ->with('danger_msg', $validator->messages()->first());
        }

        $id = Str::slug(Input::get('name'));

        if (Folder::find($id)) {
            return Redirect::route('user_folder_contents', [Input::get('user'), Input::get('folder')])
                ->with('danger_msg', 'Folder z podaną nazwą już istnieje.');
        }

        $folder->exists = false;
        $folder->getKey() = $id;
        $folder->name = Input::get('name');

        Auth::user()->folders()->save($folder);

        return Redirect::route('folder_contents', $id)->with('info_msg', 'Folder został skopiowany.');
    }

    public function removeFolder()
    {
        $folder = Folder::findOrFail(Input::get('folder'));

        if ($folder->user->folders()->destroy($folder)) {
            return Response::json(['status' => 'ok']);
        }

        return Response::json(['status' => 'error']);
    }

    public function addToFolder()
    {
        $group = Group::findOrFail(Input::get('group'));
        $folder = Folder::findOrFail(Input::get('folder'));

        if (in_array($group, $folder->groups)) {
            Response::json(['status' => 'error']);
        }

        if ($folder->mpush('groups', $group->getKey())) {
            return Response::json(['status' => 'ok']);
        }

        return Response::json(['status' => 'error']);
    }

    public function removeFromFolder()
    {
        $group = Group::findOrFail(Input::get('group'));
        $folder = Folder::findOrFail(Input::get('folder'));

        if ($folder->mpull('groups', $group->getKey())) {
            return Response::json(['status' => 'ok']);
        }

        return Response::json(['status' => 'error']);
    }
}
