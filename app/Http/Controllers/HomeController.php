<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use Illuminate\Support\Arr;

use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $key = Auth::user()->alibaba_key;
        $secret = Auth::user()->alibaba_secret;
        $region = Auth::user()->region ?? 'ap-southeast-5';

        AlibabaCloud::accessKeyClient($key,$secret)
            ->regionId($region)
            ->asDefaultClient();

        $result = AlibabaCloud::rpc()
                        ->product('Ecs')
                        //->scheme('https') // https | http
                        ->version('2014-05-26')
                        //->action('DescribeInstanceAttribute')
                        ->action('DescribeInstances')
                        ->method('POST')
                        ->host('ecs.ap-southeast-5.aliyuncs.com')
                        ->options([
                                        'query' => [
                                          'RegionId' => "ap-southeast-5",
                                        //   'InstanceId' => "i-k1adjteelv4u6olbwhxa",
                                        ],
                                    ])
                          ->request();

        $result = $result->get('Instances');

        // dd($result['Instance']);
        $collection = collect([]);
        foreach($result['Instance'] as $key2 => $arrayDot){
            $array = [];
            foreach ((object)$arrayDot as $key => $value) {
                Arr::set($array, $key, $value);

            }
            // dd((object)$array);
            $collection->put($key2, (object)$array);
        }
        // dd($collection);
        // // $result = collect($array)->recursive();

        // // dd($result[0]);
        $result = $collection;
        // dd($result);

        return view('home', compact('result'));
    }
}
