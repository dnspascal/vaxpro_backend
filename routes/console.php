<?php

use App\Jobs\VaccinationPromotionJob;
use App\Jobs\VaccinationReminderJob;
use Illuminate\Support\Facades\Schedule;



Schedule::job(VaccinationReminderJob::class)->dailyAt('08:00');

Schedule::job(VaccinationPromotionJob::class)->dailyAt('09:00');


