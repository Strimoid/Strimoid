<?php
    $isOwner = auth()->check() && user()->getKey() === $folder->user->getKey();
?>

<div class="well">
    <h4>Folder {{ $folder->name }}</h4>

    @if (auth()->check())
    <div class="btn-group" data-id="{{ $folder->hashId() }}">
        @if ($isOwner)
        <button type="button" class="btn btn-sm @if ($folder->public) btn-success @else btn-secondary @endif folder_publish"><span class="glyphicon glyphicon-lock"></span> Opublikuj</button>
        @endif

        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-file"></span> Skopiuj</button>

        <ul class="dropdown-menu" role="menu">
            <li style="padding: 5px">
                {!! Form::open(['action' => 'FolderController@copyFolder', 'class' => 'form-horizontal']) !!}
                <div class="input-group">
                    <input type="hidden" name="user" value="{!! $folder->user->name !!}">
                    <input type="hidden" name="folder" value="{!! $folder->hashId() !!}">

                    <input type="text" class="form-control" name="name" placeholder="Nazwa folderu...">

                    <span class="input-group-append">
                        <button class="btn btn-primary" type="submit">Skopiuj</button>
                    </span>
                </div>
                {!! Form::close() !!}
            </li>
        </ul>

        @if ($isOwner)
        <button type="button" class="btn btn-sm btn-danger folder_remove"><span class="glyphicon glyphicon-trash"></span> Usuń</button>
        @endif
    </div>
    @endif

    <ul class="list-group folder_groups" data-folder="{{ $folder->hashId() }}" style="margin-top: 20px">
        @foreach ($folder->groups->sortBy('name') as $group)
        <li class="list-group-item" style="padding: 5px 15px" >
            <a href="{!! route('group_contents', $group) !!}">{{ $group->name }}</a>

            @if ($isOwner)
            <button type="button" class="btn btn-xs btn-danger folder_remove_group pull-right" data-group="{{ $group }}">
                <span class="glyphicon glyphicon-trash"></span>
            </button>
            @endif
        </li>
        @endforeach

        @if ($isOwner)
        <li class="list-group-item" style="padding: 5px 15px" >
            <form class="folder_add_group">
                <div class="input-group">
                    <input type="text" class="form-control group_typeahead" name="group" placeholder="Dodaj grupę...">

                    <span class="input-group-append">
                        <button class="btn btn-primary" type="submit">Dodaj</button>
                    </span>
                </div>
            </form>
        </li>
        @endif
    </ul>
</div>
