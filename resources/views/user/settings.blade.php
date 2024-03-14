@extends('global.master')

@section('content')
<div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="#profile" data-bs-toggle="tab">
                <span class="fa fa-user"></span>
                {{ strans('common.profile')->upperCaseFirst() }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#settings" data-bs-toggle="tab">
                <i class="fa fa-wrench"></i>
                {{ strans('common.settings')->upperCaseFirst() }}
            </a>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                <i class="fa fa-lock"></i>
                {{ strans('common.account')->upperCaseFirst() }}
                <span class="caret"></span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#password" data-bs-toggle="tab">Zmiana hasła</a>
                <a class="dropdown-item" href="#email" data-bs-toggle="tab">Zmiana adresu email</a>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                {{ strans('common.domains')->upperCaseFirst() }} <span class="caret"></span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#domains" data-bs-toggle="tab">Zablokowane</a>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                {{ strans('common.groups')->upperCaseFirst() }} <span class="caret"></span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#subscribed" data-bs-toggle="tab">
                    {{ strans('groups.subscribed')->upperCaseFirst() }}
                </a>
                <a class="dropdown-item" href="#moderated" data-bs-toggle="tab">
                    {{ strans('groups.moderated')->upperCaseFirst() }}
                </a>
                <a class="dropdown-item" href="#blocked" data-bs-toggle="tab">
                    {{ strans('groups.blocked')->upperCaseFirst() }}
                </a>
                <a class="dropdown-item" href="#bans" data-bs-toggle="tab">
                    {{ strans('groups.banned')->upperCaseFirst() }}
                </a>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                {{ strans('common.users')->upperCaseFirst() }} <span class="caret"></span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#blockedusers" data-bs-toggle="tab">Zablokowani użytownicy</a>
            </div>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="profile">
            @include('user.settings.profile')
        </div>

        <div class="tab-pane" id="password">
            @include('user.settings.change_password')
        </div>

        <div class="tab-pane" id="email">
            @include('user.settings.change_email')
        </div>

        <div class="tab-pane" id="settings">
            @include('user.settings.settings')
        </div>

        <div class="tab-pane" id="subscribed">
            @include('user.settings.subscribed_groups')
        </div>

        <div class="tab-pane" id="moderated">
            @include('user.settings.moderated_groups')
        </div>

        <div class="tab-pane" id="blocked">
            @include('user.settings.blocked_groups')
        </div>

        <div class="tab-pane" id="bans">
            @include('user.settings.bans')
        </div>

        <div class="tab-pane" id="blockedusers">
            @include('user.settings.blocked_users')
        </div>

        <div class="tab-pane" id="domains">
            @include('user.settings.blocked_domains')
        </div>
    </div>
</div>
@stop

