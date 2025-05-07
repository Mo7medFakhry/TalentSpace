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
        Schema::create("offers", function (Blueprint $table) {
            $table->id();
            $table->text("title");
            $table->integer("amount");
            $table->text("notes")->nullable();
            $table->enum("status", [
                "pendingAdminApproval", // Initial state
                "adminAccepted",      // Admin approved, pending talent response
                "adminRejected",      // Admin rejected
                "talentAccepted",     // Talent accepted
                "talentRejected"      // Talent rejected
            ])->default("pendingAdminApproval");

            $table->foreignId("investor_id")->constrained("users")->onDelete("cascade");
            $table->foreignId("talent_id")->constrained("users")->onDelete("cascade");

            // Admin who processed the offer (nullable)
            $table->foreignId("admin_id")
                    ->nullable()
                    ->constrained("users")
                  ->onDelete("set null"); // Or cascade, depending on your logic
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("offers");
    }
};
