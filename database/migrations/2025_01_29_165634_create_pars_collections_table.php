<?php

use App\Models\Collection;
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
        Schema::create('pars_collections', function (Blueprint $table) {
            $table->id();
            $table->integer('start_page')->default(0);
            $table->integer('count_page')->default(0);
            $table->timestamp('last_period')->nullable();
            $table->timestamps();

            $table->foreignIdFor(Collection::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pars_collections');
    }
};
