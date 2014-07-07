@extends('global.master')

@section('content')
<div class="row content-row">
    <div class="media">
        <div class="voting">
            <button type="button" class="btn btn-default btn-sm pull-left vote-btn-up">
                <span class="glyphicon glyphicon-arrow-up vote-up"></span> 63
            </button>

            <button type="button" class="btn btn-default btn-sm pull-left vote-btn-down">
                <span class="glyphicon glyphicon-arrow-down vote-down"></span> 11
            </button>
        </div>

        <a class="pull-left" href="#">
            <img class="media-object img-thumbnail" src="/assets/img/cat.png" alt="cat">
        </a>
        <div class="media-body">
            <h4 class="media-heading"><a href="/">Those eyes...</a></h4>
            Opis dodanej treści i inne informacje na jej temat pojawią się tutaj.
        </div>
    </div>
</div>

<div class="row content-row">
    <div class="media">
        <div class="voting">
            <button type="button" class="btn btn-default btn-sm pull-left vote-btn-up">
                <span class="glyphicon glyphicon-arrow-up vote-up"></span> 63
            </button>

            <button type="button" class="btn btn-default btn-sm pull-left vote-btn-down">
                <span class="glyphicon glyphicon-arrow-down vote-down"></span> 11
            </button>
        </div>

        <a class="pull-left" href="#">
            <img class="media-object img-thumbnail" src="/assets/img/cat.png" alt="cat">
        </a>
        <div class="media-body">
            <h4 class="media-heading"><a href="/">Those eyes...</a></h4>
            Opis dodanej treści i inne informacje na jej temat pojawią się tutaj.
        </div>
    </div>
</div>

<div class="row content-row">
    <div class="media">
        <div class="voting">
            <button type="button" class="btn btn-default btn-sm pull-left vote-btn-up">
                <span class="glyphicon glyphicon-arrow-up vote-up"></span> 63
            </button>

            <button type="button" class="btn btn-default btn-sm pull-left vote-btn-down">
                <span class="glyphicon glyphicon-arrow-down vote-down"></span> 11
            </button>
        </div>

        <a class="pull-left" href="#">
            <img class="media-object img-thumbnail" src="/assets/img/cat.png" alt="cat">
        </a>
        <div class="media-body">
            <h4 class="media-heading"><a href="/">Those eyes...</a></h4>
            Opis dodanej treści i inne informacje na jej temat pojawią się tutaj.
        </div>
    </div>
</div>

<div class="row content-row">
    <div class="media">
        <div class="voting">
            <button type="button" class="btn btn-default btn-sm pull-left vote-btn-up">
                <span class="glyphicon glyphicon-arrow-up vote-up"></span> 63
            </button>

            <button type="button" class="btn btn-default btn-sm pull-left vote-btn-down">
                <span class="glyphicon glyphicon-arrow-down vote-down"></span> 11
            </button>
        </div>

        <a class="pull-left" href="#">
            <img class="media-object img-thumbnail" src="/assets/img/cat.png" alt="cat">
        </a>
        <div class="media-body">
            <h4 class="media-heading"><a href="/">Those eyes...</a></h4>
            Opis dodanej treści i inne informacje na jej temat pojawią się tutaj.
        </div>
    </div>
</div>

<div class="row content-row">
    <div class="media">
        <div class="voting">
            <button type="button" class="btn btn-default btn-sm pull-left vote-btn-up">
                <span class="glyphicon glyphicon-arrow-up vote-up"></span> 63
            </button>

            <button type="button" class="btn btn-default btn-sm pull-left vote-btn-down">
                <span class="glyphicon glyphicon-arrow-down vote-down"></span> 11
            </button>
        </div>

        <a class="pull-left" href="#">
            <img class="media-object img-thumbnail" src="/assets/img/cat.png" alt="cat">
        </a>
        <div class="media-body">
            <h4 class="media-heading"><a href="/">Those eyes...</a></h4>
            Opis dodanej treści i inne informacje na jej temat pojawią się tutaj.
        </div>
    </div>
</div>
@stop

@section('sidebar')
<div class="well">
    <div class="input-group">
        <input type="text" class="form-control">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
                    </span>
    </div><!-- /input-group -->
</div><!-- /well -->

<div class="well">
    <div class="row">
        <div class="col-lg-4">
            <button type="button" class="btn btn-default">Dodaj treść</button>
        </div>
        <div class="col-lg-8">
            <p>Dodaj nowy link lub treść własną do tej grupy.</p>
        </div>
    </div>


</div>

<div class="well">
    <h4>r/devblog</h4>
    <p>Blog developera projektu lorem ipsum. Tutaj można wstawić kilka zdań dot. grupy czy jakkolwiek to nazwiemy</p>
    <p>Regulamin grupy:</p>
</div>

<div class="well">
    <h4>Popularne tagi</h4>
    <div class="row">
        <div class="col-lg-6">
            <ul class="list-unstyled">
                <li><a href="#dinosaurs">#Dinosaurs</a></li>
                <li><a href="#spaceships">#Spaceships</a></li>
                <li><a href="#fried-foods">#Fried Foods</a></li>
                <li><a href="#wild-animals">#Wild Animals</a></li>
            </ul>
        </div>
        <div class="col-lg-6">
            <ul class="list-unstyled">
                <li><a href="#alien-abductions">#Alien Abductions</a></li>
                <li><a href="#business-casual">#Business Casual</a></li>
                <li><a href="#robots">#Robots</a></li>
                <li><a href="#fireworks">#Fireworks</a></li>
            </ul>
        </div>
    </div>
</div><!-- /well -->
@stop