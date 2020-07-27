<?php

namespace App\Http\Controllers;

use App\Instance;
use Illuminate\Http\Request;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

use Auth;

class InstanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Instance  $instance
     * @return \Illuminate\Http\Response
     */
    public function show(Instance $instance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Instance  $instance
     * @return \Illuminate\Http\Response
     */
    public function edit(Instance $instance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Instance  $instance
     * @return \Illuminate\Http\Response
     */
    public function update($action, Request $request)
    {
        $user = Auth::user();
        $instances = $user->instances()->pluck('instance_id')->toArray();

        if(!in_array($request->id, $instances)){
            return redirect()->back()->withErrors('Wrong ID');
        }

        $key = Auth::user()->alibaba_key;
        $secret = Auth::user()->alibaba_secret;
        $region = Auth::user()->region ?? 'ap-southeast-5';

        AlibabaCloud::accessKeyClient($key,$secret)
            ->regionId($region)
            ->asDefaultClient();

        $action = $action == 'stop'
                    ? 'StopInstances'
                    : ( $action == 'start'
                                ? 'StartInstances'
                                : 'RebootInstances');


        $result = AlibabaCloud::rpc()
                          ->product('Ecs')
                          // ->scheme('https') // https | http
                          ->version('2014-05-26')
                          ->action($action)
                          ->method('POST')
                          ->host('ecs.ap-southeast-5.aliyuncs.com')
                          ->options([
                                        'query' => [
                                          'RegionId' => "ap-southeast-5",
                                          'InstanceId.1' => $request->id,
                                          'DryRun' => "false",
                                        ],
                                    ])
                          ->request();

        return redirect()->back()->withInfo('VPS '. $request->action.' '.$result->Code.' '.$result->Message );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Instance  $instance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Instance $instance)
    {
        //
    }
}
