<?php

namespace App\Http\Controllers;

use Edujugon\PushNotification\PushNotification;
use Exception;
use Illuminate\Http\Request;

class FirebaseController extends Controller
{
    //
    public function index()
    {
        // dd('sflcsm');
        # code...
        try {
            // $push = new PushNotification('fcm');

            // $push->setMessage([
            //     'notification' => [
            //             'title'=>'This is the title',
            //             'body'=>'This is the message',
            //             'sound' => 'default'
            //             ],
            //     'data' => [
            //             'extraPayLoad1' => 'value1',
            //             'extraPayLoad2' => 'value2'
            //             ]
            //     ])
            //     ->setApiKey('AAAAfj4UCPM:APA91bHr3iA8VUAQcT8KrFTe0T4oHZmh1DoEp235onDNx71DtUr6r50tjpWEUPEnu7Cr8ZxwCgmVOt4qizwV-knsjKPnj-7WJ5H03icaOGdeVjvQQNHzqu7oNbYyiPwlm4Q7ZtOSzBmP')
            //     ->setDevicesToken('dJEQcYT2fK4pifuCZ02PLD:APA91bH5BcHZq8YHs3sBIh1hgU6alyRnSeEuyakrhXkRvz2gsiBL1p1W9Q2edVAOvyQxpk3OKxK9TvPEE_haK8IcBvhT62bJje5_hOmw6VhqL6wbzZn16E5OXFt4tFWYI2a_cOLuYGoJ')
            //     ->send();
            $push = new PushNotification('fcm');

            $push->setMessage([
                'notification' => [
                    'title' => 'This is the title',
                    'body' => 'This is the message',
                    'sound' => 'default'
                ],
                'data' => [
                    'extraPayLoad1' => 'value1',
                    'extraPayLoad2' => 'value2'
                ]
            ])
                ->setApiKey('AAAAfj4UCPM:APA91bHr3iA8VUAQcT8KrFTe0T4oHZmh1DoEp235onDNx71DtUr6r50tjpWEUPEnu7Cr8ZxwCgmVOt4qizwV-knsjKPnj-7WJ5H03icaOGdeVjvQQNHzqu7oNbYyiPwlm4Q7ZtOSzBmP')
                ->setDevicesToken(['fQ_VusuihDQ5qPZEkPja7Z:APA91bGZmzsTdXUzW-lJxQBS4sQHoVha_x6liubdsutintcTn6RW22DAfVyJ-Pr2rP-Df7FBsW_SszyhuMC8KVnQ7x0_6WimolxxWcutlHntYU_6z2_k8yObcBoKQaD-3Nwc5cQ7wJFk'])
                ->send()
                ->getFeedback();
            dd('ajsbfija');
        } catch (Exception $e) {
            dd($e);
        }
    }
}
