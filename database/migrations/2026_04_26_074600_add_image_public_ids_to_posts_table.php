<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            if (!\Schema::hasColumn('posts', 'image_public_ids')) {
                $table->json('image_public_ids')->nullable()->comment('Cloudinary public IDs for image deletion');
            }
            if (!\Schema::hasColumn('posts', 'images')) {
                // Modify images column to be nullable for caption-only posts
                $table->json('images')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('image_public_ids');
        });
    }
};
