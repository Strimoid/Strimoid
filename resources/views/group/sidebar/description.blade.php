<?php

$subscribed = false;
$blocked = false;

if (auth()->check()) {
    $subscribed = user()->isSubscriber($group);
    $blocked = user()->isBlocking($group);
}

?>

<div class="well group_description_widget">

    <div class="row">
        <div class="col-lg-4">
            <img src="{!! $group->getAvatarPath() !!}" alt="{{ $group->name }}" style="width: 100%; height: 100%;">
        </div>
        <div class="col-lg-8 group_info">
            <h4 class="group_name">
                {{ $group->name }}
                <small class="group_urlname">[g/{{ $group->urlname }}]</small>
            </h4>
        </div>
    </div>

    @auth
        <div class="btn-group btn-group-justified group_buttons" data-name="{{ $group->urlname }}">
            <button type="button" class="btn btn-sm group_subscribe_btn @if ($subscribed) btn-success @else btn-secondary @endif">
                @lang('common.subscribe')
            </button>
            <button type="button" class="btn btn-sm group_block_btn @if ($blocked) btn-danger @else btn-secondary @endif" title="@lang('common.block')">
                <i class="fa fa-ban"></i>
            </button>
            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" title="@lang('common.folders')">
                <span class="fa fa-folder-open"></span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu folder-menu" role="menu" data-group="{{{ $group->getKey() }}}">
                @foreach (user()->folders as $folder)
                    <li>
                        <label>
                            <input type="checkbox" class="modify_folder" data-id="{!! $folder->id !!}" {{ $folder->groups->pluck('id')->contains($group->getKey()) ? 'checked' : '' }}>
                            {{{ $folder->name }}}
                        </label>
                    </li>
                @endforeach
                <li class="divider"></li>
                <li>
                    <input type="text" class="form-control create_folder" placeholder="Nowy folder">
                </li>
            </ul>
        </div>
    @endauth

    <p class="group_desc">{!! $group->sidebar !!}</p>
</div>
