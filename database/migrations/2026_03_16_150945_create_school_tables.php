<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_kelas', function (Blueprint $table) {
            $table->id('Nid_kelas'); 
            $table->string('Vnama_kelas', 100);
            $table->timestamps();
        });

        Schema::create('tbl_siswa', function (Blueprint $table) {
            $table->id('Nid_siswa'); 
            $table->bigInteger('Nnis')->unique();
            $table->string('Vnama', 100);
            
            $table->unsignedBigInteger('Nid_kelas');
            $table->foreign('Nid_kelas')->references('Nid_kelas')->on('tbl_kelas')->onDelete('cascade');
            
            $table->timestamps();
        });

        Schema::create('tbl_mapel', function (Blueprint $table) {
            $table->id('Nid_mapel'); 
            $table->string('Vnama_mapel', 100);
            $table->timestamps();
        });

        Schema::create('tbl_nilai', function (Blueprint $table) {
            $table->id('Nid_nilai'); 
            $table->unsignedBigInteger('Nid_siswa');
            $table->foreign('Nid_siswa')->references('Nid_siswa')->on('tbl_siswa')->onDelete('cascade');
            $table->unsignedBigInteger('Nid_mapel');
            $table->foreign('Nid_mapel')->references('Nid_mapel')->on('tbl_mapel')->onDelete('cascade');
            
            $table->string('Vtahun_ajaran', 20)->default('2025/2026');
            $table->string('Vsemester', 10)->default('Ganjil');
            
            $table->float('Nuh');
            $table->float('Nuts');
            $table->float('Nuas');
            $table->timestamps();
        });

        DB::unprepared('DROP TRIGGER IF EXISTS trg_cek_nilai');
        DB::unprepared('
            CREATE TRIGGER trg_cek_nilai BEFORE INSERT ON tbl_nilai
            FOR EACH ROW
            BEGIN
                IF NEW.Nuh < 0 OR NEW.Nuh > 100 OR NEW.Nuts < 0 OR NEW.Nuts > 100 OR NEW.Nuas < 0 OR NEW.Nuas > 100 THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Nilai tidak boleh kurang dari 0 dan lebih dari 100";
                END IF;
            END
        ');

        
        DB::unprepared('DROP TRIGGER IF EXISTS trg_cek_nilai_update');
        DB::unprepared('
            CREATE TRIGGER trg_cek_nilai_update BEFORE UPDATE ON tbl_nilai
            FOR EACH ROW
            BEGIN
                IF NEW.Nuh < 0 OR NEW.Nuh > 100 OR NEW.Nuts < 0 OR NEW.Nuts > 100 OR NEW.Nuas < 0 OR NEW.Nuas > 100 THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Nilai tidak boleh kurang dari 0 dan lebih dari 100";
                END IF;
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_hitung_rata_rata_siswa');
        DB::unprepared('
            CREATE PROCEDURE sp_hitung_rata_rata_siswa(IN p_Nid_siswa INT)
            BEGIN
                SELECT 
                    rata_rata,
                    total_nilai,
                    CASE 
                        WHEN rata_rata >= 85 THEN "A"
                        WHEN rata_rata >= 70 THEN "B"
                        WHEN rata_rata >= 55 THEN "C"
                        ELSE "D"
                    END AS predikat
                FROM (
                    SELECT 
                        IFNULL(AVG((Nuh + Nuts + Nuas) / 3), 0) AS rata_rata,
                        IFNULL(SUM(Nuh + Nuts + Nuas), 0) AS total_nilai
                    FROM tbl_nilai
                    WHERE Nid_siswa = p_Nid_siswa
                ) AS sub;
            END
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_hitung_rata_rata_siswa');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_cek_nilai');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_cek_nilai_update');
        
        Schema::dropIfExists('tbl_nilai');
        Schema::dropIfExists('tbl_siswa');
        Schema::dropIfExists('tbl_mapel');
        Schema::dropIfExists('tbl_kelas');
    }
};