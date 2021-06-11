<?php

namespace App\Http\Controllers\Admin;

use App\Hacker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendWaitingEmails;
use App\Jobs\SendAcceptedEmails;
use App\Jobs\SendRejectedEmails;
use App\Mail\WelcomeMail;
use App\Notifications\InvoicePaid;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;
class MailingController extends Controller
{

    /**
     * Mailing view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $hackers = Hacker::all()->where('decision','=','not_yet');
        $number_of_not_viewed_hackers = count($hackers);

        $hackers = Hacker::all()->where('decision','=','accepted');
        $number_of_accepted_hackers = count($hackers);

        $hackers = Hacker::all()->where('decision','=','rejected');
        $number_of_rejected_hackers = count($hackers);

        $hackers = Hacker::all()->where('decision','=','waiting_list');
        $number_of_waiting_hackers = count($hackers);

        return view('dashboard.mailing' , ['accepted' => $number_of_accepted_hackers , 'rejected' => $number_of_rejected_hackers , 'waiting' => $number_of_waiting_hackers , 'not_yet' => $number_of_not_viewed_hackers]);
    }

    /**
     * @param Request $request
     * Handle the request sent from mail view and send the appropriate emails category
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {
        $mailType = json_decode($request->getContent())->MailType;
       
        switch ($mailType) {
            case 'accepted_mail':
                $this->sendEmailsAccepted();
                break;
            case 'rejected_mail':
                $this->sendEmailsRejected();
                break;
            case 'waiting_mail':
                $this->sendEmailsWaiting();
                break;

            default:

                break;
        }
        return response()->json([
            'response' => '200',
        ]);
    }

    /**
     *Send emails to all accepted hackers that haven't received an accepted email yet
     */
    public function mailWelcome (Request $request, $id) {
        // return $id;
        $mail = Hacker::where('id',$id)->first();
        // return $mail->email;
        Mail::to($mail->email)->send(new WelcomeMail());
        return redirect()->back()->with('message', 'Mail has been sent bro!');

    }
    public function mWelcome (Request $request, $id) {
        $mail = Hacker::where('id',$id)->first();

        $basic  = new \Vonage\Client\Credentials\Basic("2e6032b9", "n35m1JCgDaDJvyds");
        $client = new \Vonage\Client($basic);
        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS( $mail->phone_number , BRAND_NAME, 'Hello'.$mail->first_name)
        );
        
        $message = $response->current();
        
        if ($message->getStatus() == 0) {
            echo "The message was sent successfully\n";
        } else {
            echo "The message failed with status: " . $message->getStatus() . "\n";
        }

        // $invoice = 400;
        // $mail = Hacker::where('id',$id)->get();
        // Notification::route('wMail' ,$mail[0]['phone_number'])->notify(new InvoicePaid($invoice));
        // return redirect()->back()->with('message', 'Message has been sent bro!');

    }
    protected function sendEmailsAccepted()
    {
        $acceptedHackers = Hacker::all()
            ->where('decision','=','accepted')
            ->where('accepted_email_received_at' , '=' , null);

        $token = '';

        foreach ($acceptedHackers as $hacker){
            $hacker->accepted_email_received_at = Carbon::now() ;
            $hacker->save();
            $token = Crypt::encrypt($hacker->id.$hacker->first_name);
            $link = route('confirm',['token'=>$token]);
            $this->dispatch(new SendAcceptedEmails($hacker,$link));


            /** if you have a problem with laravel queues , just use this way to send emails :
             *
             *  Mail::to($hacker)->send(new Accepted($link,$hacker));
             *
             */
        }

    }


    /**
     *Send email to all rejected hackers
     */
    protected function sendEmailsRejected()
    {
        $rejectedHackers = DB::table('hackers')
            ->where('decision','=','rejected')
            ->select('email as email','first_name as name')
            ->get();

        foreach ($rejectedHackers as $hacker){
            $this->dispatch(new SendRejectedEmails($hacker));
        }
    }

    /**
     *Send email to all wainting list hackers
     */
    protected function sendEmailsWaiting()
    {
        $waitingHackers = DB::table('hackers')
            ->where('decision','=','waiting_list')
            ->select('email as email','first_name as name')
            ->get();

        foreach ($waitingHackers as $hacker){
            $this->dispatch(new SendWaitingEmails($hacker));
        }
    }
}
