<?php

namespace Strimoid\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Response;
use Str;
use Strimoid\Models\Folder;
use Strimoid\Models\Group;

class FolderController extends BaseController
{
    public function displayFolder(): void
    {
    }

    public function createFolder(Request $request)
    {
        $this->validate($request, Folder::validationRules());

        $folder = Auth::user()->folders()->create([
            'name' => $request->get('name'),
        ]);

        if ($request->has('groupname')) {
            $group = Group::findOrFail($request->get('groupname'));
            $folder->groups()->attach($group);
        }

        return Response::json(['status' => 'ok', 'id' => $folder->getKey()]);
    }

    public function editFolder(Request $request)
    {
        $folder = Folder::findOrFail($request->get('folder'));

        $this->validate($request, [
            'name' => 'min:1|max:64|regex:/^[a-z0-9\pL ]+$/u',
        ]);

        if ($request->has('public')) {
            $folder->public = $request->get('public') == 'true';
        }

        $folder->save();

        return Response::json(['status' => 'ok']);
    }

    public function copyFolder(Request $request)
    {
        $folder = Folder::findUserFolderOrFail($request->get('user'), $request->get('folder'));

        if (!$folder->public && $folder->user->getKey() != Auth::id()) {
            App::abort(404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:1|max:64|regex:/^[a-z0-9\pL ]+$/u'
        ]);

        if ($validator->fails()) {
            return Redirect::route('user_folder_contents', [$request->get('user'), $request->get('folder')])
                ->with('danger_msg', $validator->messages()->first());
        }

        $id = Str::slug($request->get('name'));

        if (Folder::find($id)) {
            return Redirect::route('user_folder_contents', [$request->get('user'), $request->get('folder')])
                ->with('danger_msg', 'Folder z podaną nazwą już istnieje.');
        }

        $folder->exists = false;
        $folder->name = $request->get('name');

        Auth::user()->folders()->save($folder);

        return Redirect::route('folder_contents', $id)->with('info_msg', 'Folder został skopiowany.');
    }

    public function removeFolder(Request $request)
    {
        $folder = Folder::findOrFail($request->get('folder'));
        $folder->delete();

        return Response::json(['status' => 'ok']);
    }

    public function addToFolder(Request $request)
    {
        $group = Group::findOrFail($request->get('group'));
        $folder = Folder::findOrFail($request->get('folder'));

        $folder->groups()->attach($group);

        return Response::json(['status' => 'ok']);
    }

    public function removeFromFolder(Request $request)
    {
        $group = Group::findOrFail($request->get('group'));
        $folder = Folder::findOrFail($request->get('folder'));

        $folder->groups()->detach($group);

        return Response::json(['status' => 'ok']);
    }
}
