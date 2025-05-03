<?php

namespace App\Services;

use Carbon\CarbonInterface;
use Exception;
use Yasumi\Yasumi;

/**
 * 社内運用に基づく休日定義。
 * 休日の定義は、以下のとおりです。
 * 1. 土日
 * 2. 祝日（Yasumiライブラリに準拠）
 * 3. 年末年始（12月29日〜1月3日）
 * 4. お盆（8月13日〜8月15日）
 */
class HolidayService
{
    /**
     * 休日かどうかを判定します。
     * @throws Exception
     */
    public function isHoliday(CarbonInterface $date): bool
    {
        // タイムゾーンがAsia/Tokyoであることを検査します。
//        if ($date->getTimezone()->getName() != 'Asia/Tokyo') {
//            throw new Exception('タイムゾーンがAsia/Tokyoではありません。');
//        }
        
        // 休日は、土日です。
        if ($date->dayOfWeek == 0 || $date->dayOfWeek == 6) {
            return true;
        }
        // 休日は、祝日です。
        // 祝日の判定にYasumiを使います。
        $holidaysProvider = Yasumi::create('Japan', $date->year, 'ja_JP');
        if ($holidaysProvider->isHoliday($date)) {
            return true;
        }
        // 休日は、年末年始です。
        if ($date->month == 12 && $date->day >= 29) {
            return true;
        }
        if ($date->month == 1 && $date->day <= 3) {
            return true;
        }
        // 休日は、お盆休みです。(8月13日から8月15日まで)
        if ($date->month == 8 && $date->day >= 13 && $date->day <= 15) {
            return true;
        }
        
        return false;
    }
}