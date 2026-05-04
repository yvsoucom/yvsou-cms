// database/migrations/xxxx_create_api_tokens_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('api_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('name')->nullable();
            $table->string('type'); // admin | user | api
            $table->string('token_hash', 64)->unique();

            $table->json('scopes')->nullable();

            $table->string('ip', 45)->nullable();
            $table->string('user_agent', 255)->nullable();

            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->boolean('is_revoked')->default(false);

            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_tokens');
    }
};