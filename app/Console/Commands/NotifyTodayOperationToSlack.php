<?php

namespace App\Console\Commands;

use App\Models\Operation;
use Illuminate\Console\Command;
use Notification;

class NotifyTodayOperationToSlack extends Command
{
    private const MAX_MESSAGE_LENGTH = 10;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-today-operation-to-slack';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //今日(日本時間)の予定を取得
        $operations = Operation::getTodayOperations();
        
        /** 今日の予定が何も無ければステータスコード1で終了 */
        if ($operations->count() === 0) return 1;
        
        // 通知の内容
        $message = "今日の予定は以下の通りです。\n\n";
        foreach ($operations as $operation) {
            $link = route('operations.show', ['operation' => $operation->id]);
            $japanTime = $operation->scheduled_at->setTimezone('Asia/Tokyo')->format('H時i分');
            $desc = preg_replace("/\r\n|\r|\n/", '',mb_substr($operation->content, 0, self::MAX_MESSAGE_LENGTH));
            $message .= "<{$link}|[{$japanTime}] {$desc}...>\n";
        }
        $notification = new \App\Notifications\OperationToSlackNotification($message);
        // オンデマンド通知
        Notification::route('slack', config('services.slack.webhook_url'))->notify($notification);
    }
}
