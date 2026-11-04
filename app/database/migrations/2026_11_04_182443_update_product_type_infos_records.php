<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('product_type_infos')
            ->where('product_type_id', 1)
            ->update([
                'description' => 'Tessuto: 100% cotone per capi a tinta unita; da 90/10% a 70/30% cotone/poliestere, per capi melange, a seconda del colore.
Densità: 145 g/m²
Stampa: Molto resistente ai lavaggi
Vestibilità: Normale',
            ]);

        DB::table('product_type_infos')
            ->where('product_type_id', 2)
            ->update([
                'description' => 'Tessuto: 100% cotone per capi a tinta unita; da 90/10% a 70/30% cotone/poliestere, per capi melange, a seconda del colore.
Densità: 145 g/m²
Stampa: Molto resistente ai lavaggi
Vestibilità: Normale',
            ]);

        DB::table('product_type_infos')
            ->where('product_type_id', 3)
            ->update([
                'description' => 'Tessuto: 100% cotone per capi a tinta unita; da 90/10% a 70/30% cotone/poliestere, per capi melange, a seconda del colore.
Densità: 145 g/m²
Stampa: Molto resistente ai lavaggi
Vestibilità: Normale',
            ]);

        DB::table('product_type_infos')
            ->where('product_type_id', 4)
            ->update([
                'description' => 'Tessuto: 100% cotone jersey, eccetto grigio melage (90% Cotone / 10% Poliestere)
Densità: 144 g/m² bianco e 153 g/m² colorati
Stampa: Molto resistente ai lavaggi
Vestibilità: Normale, spalline larghe',
            ]);

        DB::table('product_type_infos')
            ->where('product_type_id', 5)
            ->update([
                'description' => 'Tessuto: 100% cotone jersey, eccetto grigio melage (90% Cotone, 10% Poliestere)
Densità: 144 g/m² bianco e 153 g/m² colorati
Stampa: Molto resistente ai lavaggi
Vestibilità: Normale, spalline larghe',
            ]);

        DB::table('product_type_infos')
            ->where('product_type_id', 6)
            ->update([
                'description' => 'Tessuto: 50% cotone, 50% poliestere
Densità: 257 g/m² bianco e 271 g/m² colorati
Stampa: Molto resistente ai lavaggi
Vestibilità: Normale, tubolare',
            ]);

        DB::table('product_type_infos')
            ->where('product_type_id', 7)
            ->update([
                'description' => 'Tessuto: 50% cotone, 50% poliestere
Densità: 257 g/m² bianco e 271 g/m² colorati
Stampa: Molto resistente ai lavaggi
Vestibilità: Normale, tubolare',
            ]);

        DB::table('product_type_infos')
            ->where('product_type_id', 8)
            ->update([
                'description' => 'Tessuto: 50% cotone, 50% poliestere
Densità: 257 g/m² bianco e 271 g/m² colorati
Stampa: Molto resistente ai lavaggi
Vestibilità: Normale, tubolare',
            ]);

        DB::table('product_type_infos')
            ->where('product_type_id', 9)
            ->update([
                'description' => 'Tessuto: 50% cotone, 50% poliestere
Densità: 257 g/m² bianco e 271 g/m² colorati
Stampa: Molto resistente ai lavaggi
Vestibilità: Normale, tubolare',
            ]);

        DB::table('product_type_infos')
            ->where('product_type_id', 10)
            ->update([
                'description' => 'Tessuto: 50% cotone, 50% poliestere
Densità: 271 g/m²
Stampa: Molto resistente ai lavaggi
Vestibilità: Normale, tubolare',
            ]);

        DB::table('product_type_infos')
            ->where('product_type_id', 17)
            ->update([
                'description' => 'Tessuto: 50% cotone, 50% poliestere
Densità: 271 g/m²
Stampa: Molto resistente ai lavaggi
Vestibilità: Normale, tubolare',
            ]);

        DB::table('product_type_infos')
            ->where('product_type_id', 113)
            ->update([
                'description' => 'Tessuto: 100% cotone jersey, eccetto grigio melage (90% Cotone, 10% Poliestere)
Densità: 144 bianco g/m² - 153 colorati g/m²
Stampa: Molto resistente ai lavaggi
Vestibilità: Normale, tubolare',
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
