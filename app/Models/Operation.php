<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Yasumi\Holiday;
use Yasumi\Yasumi;

class Operation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'scheduled_at',
        'content',
        'notified',
    ];
    
    protected $casts = [
        'scheduled_at' => 'datetime',
        'notified' => 'boolean',
    ];
    
    /**
     * 今日の予定を取得
     * @return Collection
     */
    public static function getTodayOperations(): Collection
    {
        return Operation::where('scheduled_at', '>=', now('Asia/Tokyo')->startOfDay()->setTimezone('UTC'))
                        ->where('scheduled_at', '<=', now('Asia/Tokyo')->endOfDay()->setTimezone('UTC'))
                        ->get();
    }
    
    /**
     * 明日の予定を取得
     * @return Collection
     */
    public static function getTomorrowOperations(): Collection
    {
        return Operation::where('scheduled_at', '>=', now('Asia/Tokyo')->addDay()->startOfDay()->setTimezone('UTC'))
                        ->where('scheduled_at', '<=', now('Asia/Tokyo')->addDay()->endOfDay()->setTimezone('UTC'))
                        ->get();
    }
    
    /**
     * 10分後の予定を取得
      * @return Collection|array|\Illuminate\Support\Collection
     */
    public static function getTenMinutesFromNowOperation(): Collection|array|\Illuminate\Support\Collection
    {
        $MINUTES = 10;
        return Operation::where('scheduled_at', '>=', now('Asia/Tokyo')->setTimezone('UTC'))
                        ->where('scheduled_at', '<=', now('Asia/Tokyo')->addMinutes($MINUTES)->setTimezone('UTC'))
                        ->where('notified', false)
                        ->get();
    }
    
    /**
     * 次の営業日の予定を取得
     * @return Collection
     */
    public static function getNextBusinessDayOperations(): Collection
    {
        // 次の営業日を計算
        $nextBusinessDay = self::calculateNextBusinessDay();
        
        // 次の営業日の予定を取得
        return Operation::where('scheduled_at', '>=', $nextBusinessDay->startOfDay())
                        ->where('scheduled_at', '<=', $nextBusinessDay->endOfDay())
                        ->get();
    }
    
    /**
     * 今日以降の次の営業日を計算する
     * 営業日とは、土日および祝日を除いた日とする
     * 営業日とは、年末年始を除いた日とする
     * 営業日とは、「行政機関の休日に関する法律」が定める十二月二十九日から翌年の一月三日までの日を除いた日とする
     * TODO: お盆休みを営業日から除外する
     *
     * @return CarbonImmutable
     */
    public static function calculateNextBusinessDay(): CarbonImmutable
    {
        // Yasumiライブラリを使用して日本の祝日を取得
        $holidaysProvider = Yasumi::create('Japan', now()->year, 'ja_JP');
        // 現在の日付
        $today = now('Asia/Tokyo');
        // 1日ずつ進みながら、土日祝日でない営業日を探す
        while (true) {
            // 現在の日付を1日進める
            $today->modify('+1 day');
            // 進めた日が土日祝日でない場合、これを次の営業日として返す
            if ( $holidaysProvider->isHoliday($today) === false && $today->format('N') < 6) {
                return $today->toImmutable();
            }
        }
    }
}
