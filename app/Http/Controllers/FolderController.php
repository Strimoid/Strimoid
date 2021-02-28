<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Strimoid\Models\Folder;
use Strimoid\Models\Group;

class FolderController extends BaseController
{
    public function __construct(private \Illuminate\Contracts\Routing\ResponseFactory $responseFactory, private \Illuminate\Auth\AuthManager $authManager, private \Illuminate\Foundation\Application $application, private \Illuminate\Validation\Factory $validationFactory, private \Illuminate\Routing\Redirector $redirector)
    {
    }
    public function displayFolder(): void
    {
    }

    public function createFolder(Request $request)
    {
        $this->validate($request, Folder::validationRules());

        $folder = user()->folders()->create([
            'name' => $request->get('name'),
        ]);

        if ($request->has('groupname')) {
            $group = Group::findOrFail($request->get('groupname'));
            $folder->groups()->attach($group);
        }

        return $this->responseFactory->json(['status' => 'ok', 'id' => $folder->getKey()]);
    }

    public function editFolder(Request $request)
    {
        $folder = Folder::findOrFail($request->get('folder'));

        $this->validate($request, [
            'name' => 'min:1|max:64|regex:/^[a-z0-9\pL ]+$/u',
        ]);

        if ($request->has('public')) {
            $folder->public = $request->get('public') === 'true';
        }

        $folder->save();

        return $this->responseFactory->json(['status' => 'ok']);
    }

    public function copyFolder(Request $request)
    {
        $folder = Folder::findUserFolderOrFail($request->get('user'), $request->get('folder'));

        if (!$folder->public && $folder->user->getKey() !== $this->authManager->id()) {
            $this->application->abort(404);
        }

        $validator = $this->validationFactory->make($request->all(), [
            'name' => 'required|min:1|max:64|regex:/^[a-z0-9\pL ]+$/u'
        ]);

        if ($validator->fails()) {
            return $this->redirector->route('user_folder_contents', [$request->get('user'), $request->get('folder')])
                ->with('danger_msg', $validator->messages()->first());
        }

        $id = Str::slug($request->get('name'));

        if (Folder::find($id)) {
            return $this->redirector->route('user_folder_contents', [$request->get('user'), $request->get('folder')])
                ->with('danger_msg', 'Folder z podaną nazwą już istnieje.');
        }

        $folder->exists = false;
        $folder->name = $request->get('name');

        $this->authManager->user()->folders()->save($folder);

        return $this->redirector->route('folder_contents', $id)->with('info_msg', 'Folder został skopiowany.');
    }

    public function removeFolder(Request $request)
    {
        $folder = Folder::findOrFail($request->get('folder'));
        $folder->delete();

        return $this->responseFactory->json(['status' => 'ok']);
    }

    public function addToFolder(Request $request)
    {
        $group = Group::findOrFail($request->get('group'));
        $folder = Folder::findOrFail($request->get('folder'));

        $folder->groups()->attach($group);

        return $this->responseFactory->json(['status' => 'ok']);
    }

    public function removeFromFolder(Request $request)
    {
        $group = Group::findOrFail($request->get('group'));
        $folder = Folder::findOrFail($request->get('folder'));

        $folder->groups()->detach($group);

        return $this->responseFactory->json(['status' => 'ok']);
    }
}
