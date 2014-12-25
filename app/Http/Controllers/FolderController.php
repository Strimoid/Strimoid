<?php namespace Strimoid\Http\Controllers;

class FolderController extends BaseController
{

    public function displayFolder() {
    }

    public function createFolder() {
        $validator = Validator::make(Input::all(), array('name' => 'required|min:1|max:64|regex:/^[a-z0-9\pL ]+$/u'));

        if ($validator->fails())
            return Response::json(array('status' => 'error'));

        $id = Str::slug(Input::get('name'));

        if (Folder::find($id))
            return Response::json(array('status' => 'error'));

        $folder = new Folder();
        $folder->_id = $id;
        $folder->name = Input::get('name');
        $folder->public = false;
        $folder->groups = [];

        if (Input::has('groupname')) {
            $group = Group::findOrFail(Input::get('groupname'));
            $folder->groups = [$group->_id];
        }

        if (Auth::user()->folders()->save($folder))
            return Response::json(['status' => 'ok', 'id' => $folder->_id]);

        return Response::json(['status' => 'error']);
    }

    public function editFolder()
    {
        $folder = Folder::findOrFail(Input::get('folder'));

        $validator = Validator::make(Input::all(), ['name' => 'min:1|max:64|regex:/^[a-z0-9\pL ]+$/u']);

        if ($validator->fails())
            return Response::json(array('status' => 'error', 'error' => $validator->messages()->first()));

        if (Input::has('name'))
        {

        }

        if (Input::has('public'))
            $folder->public = Input::get('public') == 'true' ? true : false;

        if (Auth::user()->folders()->save($folder))
            return Response::json(['status' => 'ok']);

        return Response::json(['status' => 'error']);
    }

    public function copyFolder() {
        $folder = Folder::findUserFolderOrFail(Input::get('user'), Input::get('folder'));

        if (!$folder->public && $folder->user->_id != Auth::user()->_id)
            App::abort(404);

        $validator = Validator::make(Input::all(), ['name' => 'required|min:1|max:64|regex:/^[a-z0-9\pL ]+$/u']);

        if ($validator->fails())
            return Redirect::route('user_folder_contents', [Input::get('user'), Input::get('folder')])
                ->with('danger_msg', $validator->messages()->first());

        $id = Str::slug(Input::get('name'));

        if (Folder::find($id))
            return Redirect::route('user_folder_contents', [Input::get('user'), Input::get('folder')])
                ->with('danger_msg', 'Folder z podaną nazwą już istnieje.');

        $folder->exists = false;
        $folder->_id = $id;
        $folder->name = Input::get('name');

        Auth::user()->folders()->save($folder);

        return Redirect::route('folder_contents', $id)->with('info_msg', 'Folder został skopiowany.');
    }

    public function removeFolder() {
        $folder = Folder::findOrFail(Input::get('folder'));

        if ($folder->user->folders()->destroy($folder))
            return Response::json(['status' => 'ok']);

        return Response::json(['status' => 'error']);
    }

    public function addToFolder() {
        $group = Group::findOrFail(Input::get('group'));
        $folder = Folder::findOrFail(Input::get('folder'));

        if (in_array($group, $folder->groups))
            Response::json(['status' => 'error']);

        if ($folder->mpush('groups', $group->_id))
            return Response::json(['status' => 'ok']);

        return Response::json(['status' => 'error']);
    }

    public function removeFromFolder() {
        $group = Group::findOrFail(Input::get('group'));
        $folder = Folder::findOrFail(Input::get('folder'));

        if ($folder->mpull('groups', $group->_id))
            return Response::json(['status' => 'ok']);

        return Response::json(['status' => 'error']);
    }

}