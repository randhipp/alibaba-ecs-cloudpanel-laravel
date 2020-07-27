@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="table-responsive">

                    </div>
                    <table class="table table-sm table-stripped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID/Name</th>
                                <th>IP</th>
                                <th>Status</th>
                                <th>Specification</th>
                                <th>Billing</th>
                                <th></th>
                                <th>Action</th>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($result as $key => $instance)
                            @php
                                $ip = (object)$instance->EipAddress;
                            @endphp
                            @if(in_array($instance->InstanceId, \Auth::user()->instances()->pluck('instance_id')->toArray()))
                            <tr>
                                <td>
                                    {{ $key+1 }}
                                </td>
                                <td>
                                    <b>{{ $instance->InstanceName }}</b><br>
                                    instance-id: <code>{{ $instance->InstanceId }}</code>
                                </td>
                                <td>
                                    {{ $ip->IpAddress }}<br>
                                    Bandwidth: {{ $ip->Bandwidth ?? '-' }} M
                                </td>
                                @php
                                    if($instance->Status == 'Running'){
                                        $badge = "success";
                                    } elseif ($instance->Status == 'Stopped'){
                                        $badge = "danger";
                                    } else {
                                        $badge = "warning";
                                    }
                                @endphp
                                <td>
                                    <span class="badge badge-pill badge-{{$badge}}">{{ $instance->Status }}</span>
                                </td>
                                <td>
                                    RAM: {{ number_format($instance->Memory/1000,0) }} GB<br>
                                    CPU: {{ $instance->Cpu }} vCPU<br>
                                </td>
                                <td>
                                    Billing Type: {{ $instance->InstanceChargeType }}<br>
                                    Created Date: {{ Carbon\Carbon::parse($instance->CreationTime)->format('d/m/y H:i') }}
                                </td>
                                <td>
                                    <form action="/instance/start" method="POST">
                                        @csrf
                                        <input type="hidden" value="{{ $instance->InstanceId }}" name="id">
                                        <button type="submit" class="btn btn-primary" {{$instance->Status == 'Running' ? 'disabled' : ''}}>
                                            <i class="fa fa-play" aria-hidden="true"></i> Start
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form action="/instance/restart" method="POST">
                                        @csrf
                                        <input type="hidden" value="{{ $instance->InstanceId }}" name="id">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-refresh" aria-hidden="true"></i> Restart
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form action="/instance/stop" method="POST">
                                        @csrf
                                        <input type="hidden" value="{{ $instance->InstanceId }}" name="id">
                                        <button type="submit" class="btn btn-danger" {{$instance->Status !== 'Running' ? 'disabled' : ''}}>
                                            <i class="fa fa-stop" aria-hidden="true"></i> Stop
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @else
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
