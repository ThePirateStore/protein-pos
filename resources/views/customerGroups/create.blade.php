@extends('layouts.app')

@section('title')
    - Create New Customer Group
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Create New Customer Group
                </div>
                <div class="panel-body">
                    <form method="post" action="{{ route('customer-groups.store') }}" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-5">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Eg: VIP Group" required value="{{ old('name') }}" />
                                @foreach($errors->get('name') as $error)
                                    <span class="label label-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('discount') ? 'has-error' : '' }}">
                            <label for="discount" class="col-sm-2 control-label">Discount</label>
                            <div class="col-sm-5">
                                <input type="text" id="discount" name="discount" class="form-control" placeholder="Between 1 - 100" required value="{{ old('discount') }}" />
                                @foreach($errors->get('discount') as $error)
                                    <span class="label label-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-3">
                                <button type="submit" class="btn btn-success btn-block">Save</button>
                            </div>
                            <div class="col-sm-2">
                                <a href="{{ URL::previous() }}" class="btn btn-danger btn-block">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection