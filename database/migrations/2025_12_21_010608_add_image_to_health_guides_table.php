// ... database/migrations/YYYY_MM_DD_HHMMSS_add_image_to_health_guides_table.php
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
        Schema::table('health_guides', function (Blueprint $table) {
            // 💡 إضافة حقل image جديد من نوع string، ويمكن أن يكون null (اختياري) بعد حقل 'content'
            $table->string('image')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('health_guides', function (Blueprint $table) {
            // 💡 حذف حقل image في حال التراجع عن الهجرة
            $table->dropColumn('image');
        });
    }
};
