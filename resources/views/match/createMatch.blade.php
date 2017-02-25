@extends('layouts.plain')
@section('title','Create Match')

@section('content')
    <div class="container" id="app">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="text-center">
                    Create new match
                </h3>
            </div>
            <div class="panel-body">
                <form method="post" role="form">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">Match Name:</label>
                        <input type="text" id="name" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="geoCode">
                            <button type="button" id="mapBtn" class="btn btn-primary" data-toggle="modal"
                                    data-target="#mapModal">Address
                            </button>
                        </label>
                        <input name="geoCode" id="geoCode" id="map" type="hidden">
                        <div class="modal fade" id="mapModal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <mapel></mapel>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" id="address" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="public">Public:<input id="public" class="form-control" type="checkbox" data-toggle="switch" value="true" name="public"></label>
                        <label for="recurring">Recurring:<input id="recurring" type="checkbox" data-toggle="switch" value="true" name="recurring"
                                                                class="form-control"></label>
                    </div>
                    <div class="form-group">
                        <label>Number of players</label>
                        <select name="players" id="players" class="form-control">
                            @for ($i = 8; $i<23; $i+=2)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" style="width: 100%"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection