<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         * オペレーションテーブルは以下のカラムを持ちます。
         * - id
         * - timestamps
         * - 実行予定時間
         * - 内容
         * - 通知済みフラグ
         */
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('scheduled_at')
                  ->nullable(); // For MySQL 5.7
            $table->string('content');
            $table->boolean('notified')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
