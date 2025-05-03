<?php

namespace App\Console\Commands;

use App\Models\Operation;
use App\Services\HolidayService;
use Illuminate\Console\Command;
use Notification;

class NotifyNextBusinessDayOperationToSlack extends Command
{
    private const MAX_MESSAGE_LENGTH = 10;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-next-business-day-operation-to-slack';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(HolidayService $holiday_service)
    {
        /** 今日と明日が休日(ex:今日が土曜日で明日が日曜日)であればステータスコード1で終了 */
        $today = now('Asia/Tokyo');
        $tomorrow = now('Asia/Tokyo')->modify('+1 day');
        $isTodayHoliday = $holiday_service->isHoliday($today);
        $isTomorrowHoliday = $holiday_service->isHoliday($tomorrow);
        if ($isTodayHoliday && $isTomorrowHoliday) return 1;
        
        /** 今日もしくは明日が平日であれば次の営業日の予定を通知する */
        //次営業日を取得
        $nextBusinessDay = Operation::calculateNextBusinessDay();
        //次営業日の予定を取得
        $operations = Operation::getNextBusinessDayOperations();
        
        /** 次の営業日の予定が何も無ければステータスコード1で終了 */
        if ($operations->count() === 0) return 1;
        
        // 通知の内容
        $week = array( "日", "月", "火", "水", "木", "金", "土" );
        $nextBusinessWeekDay = $nextBusinessDay->weekday();
        $dateString = $nextBusinessDay->format('n月j日') . "({$week[$nextBusinessWeekDay]})";
        $message = $operations->count() ? "次営業日({$dateString})の予定は以下の通りです。\n\n" : "次営業日({$dateString})の予定はありません。\n\n";
        foreach ($operations as $operation) {
            $link = route('operations.show', ['operation' => $operation->id]);
            $japanTime = $operation->scheduled_at->setTimezone('Asia/Tokyo')->format('H時i分');
            $desc = preg_replace("/\r\n|\r|\n/", '',mb_substr($operation->content, 0, self::MAX_MESSAGE_LENGTH));
            $message .= "<{$link}|[{$japanTime}] {$desc}...>\n";
        }
        $notification = new \App\Notifications\OperationToSlackNotification($message);
        // オンデマンド通知
        Notification::route('slack', config('services.slack.webhook_url'))->notify($notification);
        return 0;
    }
}
