<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Payments;
use App\Models\Invoices;

class SubscriptionCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get all active users
        $customers = User::where('status', 1)->where('role_id', 2)->get();
        if(!empty($customers)) {
            foreach($customers as $customer) {
                // Get Customer Payment info
                $payments = Payments::where('user_id', $customer->id)->where('status', 'active')->latest('created_at')->first();
                
                if(!empty($payments)) {
                    $current_recur_date = $payments->created_at;
                    if(!empty($payments->recurring_date)) {
                        $current_recur_date = $payments->recurring_date;
                    }

                    $recurring_date = date('Y-m-d', strtotime($current_recur_date));
                    $current_recurring_date = strtotime($recurring_date);
                    $datetoday = date('Y-m-d');
                    $current_date = strtotime($datetoday);

                    if($current_date >= $current_recurring_date && $payments->plan_status == 0) {
                        // Increase 1 month
                        $next_recurring_date = date("Y-m-d", strtotime("+1 month", $current_recurring_date));
                        Payments::whereId($payments->id)->update(['recurring_date' => $next_recurring_date]);

                        $last_invoice = Invoices::where('user_id', $customer->id)->latest('created_at')->first();

                        $invoice_number = 100000000;
                        if(!empty($last_invoice)) {
                            $invoice_number = intval($last_invoice->number) + 1;
                        }

                        // Create invoice
                        Invoices::create([
                            'user_id' => $customer->id,
                            'payment_id' => $payments->id,
                            'number' => $invoice_number,
                            'date_invoice' => $datetoday,
                            'plan' => $payments->plan,
                            'amount' => $payments->price
                        ]);

                    } elseif($current_recurring_date >= $current_date && $payments->plan_status == 1) {
                        Payments::whereId($payments->id)->update(['status' => 'cancelled']);
                        // Cancelled customer
                        \Log::info("customer ". $customer->first_name ." is cancelled!");
                    }
                }
            }
        }

        $this->info('SubscriptionCron Cummand Run successfully!');
    }
}
