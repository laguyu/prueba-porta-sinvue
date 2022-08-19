<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DailyEmailUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia un correo a los usuarios que no hay iniciado sesion en 30 días o más';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            log::info('Envio de correo- '.Carbon::now());
            $limit=Carbon::now()->subDays(30)->toDateString();

            $users = User::whereDate('last_sing_in_date','<=',$limit)->get();

            foreach ($users as $user) {
                
                $dataEmail = [
                    'm' => 'Lleva más de 30 días sin iniciar sesión en nuestra página',
                ];
    
                $email=$user->email;

                Mail::send('mail.emailLoggedIn',['data' => $dataEmail] , function ($msj) use ($email)
            {
                $msj->subject('Aviso');
                $msj->to($email);
            });

        }
        } catch (\Throwable $th) {
            Log::error('Error al enviar correo -> '.$th);
       
         
       

        }
    }
}
