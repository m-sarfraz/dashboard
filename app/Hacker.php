<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Hacker extends Model
{

    use Notifiable;

    /**
     * Route notifications for the Nexmo channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForNexmo($notification)
    {
        return $this->phone_number;
    }


    protected $fillable=[
        "id",
        "first_name",
        "last_name",
        "email",
        "birthday",
        "sex",
        "phone_number",
        "motivation",
        "github",
        "linked_in",
        "skills",
        "size",
        "team_id",
        "decision",
    ];
      /**
     * Route notifications for the Nexmo channel.
     * @param mixed $notifiable
    * @return $array
 */
    public function via($notifiable) {
        return ['mail', 'database' , 'nexmo'];

    }

    public function toNexmo($notifiable)

    {
        return (new NexmoMessage)
                    ->content('Hello test')
                    ->from('923431077718');
    }

    
    public $dates = ['accepted_email_received_at'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function hasTeam()
    {
        return (bool) ! is_null($this->team);
    }

    public function confirmAttendance(){
        $this->confirmed = true;

    }
    public function reject(){
        $this->decision = 'rejected';
    }
}
