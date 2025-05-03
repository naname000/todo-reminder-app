<?php

namespace App\Console\Commands;

use App\Models\Operation;
use Illuminate\Console\Command;
use Notification;

class NotifyTenMinutesFromNowOperationToSlack extends Command
{
    private const MAX_MESSAGE_LENGTH = 10;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-ten-minutes-from-now-operation-to-slack';

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
        //今から10分後の予定を取得
        $operationsBuilder = Operation::where('scheduled_at', '>=', now())
                               ->where('scheduled_at', '<=', now()->addMinutes(10))
                               ->where('notified', false);
        $operations = $operationsBuilder->get();
        
        if ($operations->count() === 0) {
            // 画面になにもないことを表示
            $this->info('予定はありません。');
            return;
        }
        // 通知の内容
        $message = "今から10分以内の予定は以下の通りです。\n\n";
        foreach ($operations as $operation) {
            $link = route('operations.show', ['operation' => $operation->id]);
            $japanTime = $operation->scheduled_at->setTimezone('Asia/Tokyo')->format('H時i分');
            $desc = preg_replace("/\r\n|\r|\n/", '',mb_substr($operation->content, 0, self::MAX_MESSAGE_LENGTH));
            $message .= "<{$link}|[{$japanTime}] {$desc}>\n";
        }
        $notification = new \App\Notifications\OperationToSlackNotification($message);
        // オンデマンド通知
        Notification::route('slack', config('services.slack.webhook_url'))->notify($notification);
        // 通知フラグを立てる
        $operationsBuilder->update(['notified' => true]);
    }
}
