<?php

use App\Models\Shipping\Region;
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
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        $regions = [
            ['tag' => 'AG', 'name' => 'Agrigento', 'region' => 'Sicilia'],
            ['tag' => 'AL', 'name' => 'Alessandria', 'region' => 'Piemonte'],
            ['tag' => 'AN', 'name' => 'Ancona', 'region' => 'Marche'],
            ['tag' => 'AO', 'name' => 'Aosta', 'region' => 'Valle d’Aosta'],
            ['tag' => 'AP', 'name' => 'Ascoli-Piceno', 'region' => 'Marche'],
            ['tag' => 'AQ', 'name' => 'L’Aquila', 'region' => 'Abruzzo'],
            ['tag' => 'AR', 'name' => 'Arezzo', 'region' => 'Toscana'],
            ['tag' => 'AT', 'name' => 'Asti', 'region' => 'Piemonte'],
            ['tag' => 'AV', 'name' => 'Avellino', 'region' => 'Campania'],
            ['tag' => 'BA', 'name' => 'Bari', 'region' => 'Puglia'],
            ['tag' => 'BG', 'name' => 'Bergamo', 'region' => 'Lombardia'],
            ['tag' => 'BI', 'name' => 'Biella', 'region' => 'Piemonte'],
            ['tag' => 'BL', 'name' => 'Belluno', 'region' => 'Veneto'],
            ['tag' => 'BN', 'name' => 'Benevento', 'region' => 'Campania'],
            ['tag' => 'BO', 'name' => 'Bologna', 'region' => 'Emilia Romagna'],
            ['tag' => 'BR', 'name' => 'Brindisi', 'region' => 'Puglia'],
            ['tag' => 'BS', 'name' => 'Brescia', 'region' => 'Lombardia'],
            ['tag' => 'BT', 'name' => 'Barletta-Andria-Trani', 'region' => 'Puglia'],
            ['tag' => 'BZ', 'name' => 'Bolzano', 'region' => 'Trentino Alto Adige'],
            ['tag' => 'CA', 'name' => 'Cagliari', 'region' => 'Sardegna'],
            ['tag' => 'CB', 'name' => 'Campobasso', 'region' => 'Molise'],
            ['tag' => 'CE', 'name' => 'Caserta', 'region' => 'Campania'],
            ['tag' => 'CH', 'name' => 'Chieti', 'region' => 'Abruzzo'],
            ['tag' => 'CI', 'name' => 'Carbonia Iglesias', 'region' => 'Sardegna'],
            ['tag' => 'CL', 'name' => 'Caltanissetta', 'region' => 'Sicilia'],
            ['tag' => 'CN', 'name' => 'Cuneo', 'region' => 'Piemonte'],
            ['tag' => 'CO', 'name' => 'Como', 'region' => 'Lombardia'],
            ['tag' => 'CR', 'name' => 'Cremona', 'region' => 'Lombardia'],
            ['tag' => 'CS', 'name' => 'Cosenza', 'region' => 'Calabria'],
            ['tag' => 'CT', 'name' => 'Catania', 'region' => 'Sicilia'],
            ['tag' => 'CZ', 'name' => 'Catanzaro', 'region' => 'Calabria'],
            ['tag' => 'EN', 'name' => 'Enna', 'region' => 'Sicilia'],
            ['tag' => 'FC', 'name' => 'Forli-Cesena', 'region' => 'Emilia Romagna'],
            ['tag' => 'FE', 'name' => 'Ferrara', 'region' => 'Emilia Romagna'],
            ['tag' => 'FG', 'name' => 'Foggia', 'region' => 'Puglia'],
            ['tag' => 'FI', 'name' => 'Firenze', 'region' => 'Toscana'],
            ['tag' => 'FM', 'name' => 'Fermo', 'region' => 'Marche'],
            ['tag' => 'FR', 'name' => 'Frosinone', 'region' => 'Lazio'],
            ['tag' => 'GE', 'name' => 'Genova', 'region' => 'Liguria'],
            ['tag' => 'GO', 'name' => 'Gorizia', 'region' => 'Friuli Venezia Giulia'],
            ['tag' => 'GR', 'name' => 'Grosseto', 'region' => 'Toscana'],
            ['tag' => 'IM', 'name' => 'Imperia', 'region' => 'Liguria'],
            ['tag' => 'IS', 'name' => 'Isernia', 'region' => 'Molise'],
            ['tag' => 'KR', 'name' => 'Crotone', 'region' => 'Calabria'],
            ['tag' => 'LC', 'name' => 'Lecco', 'region' => 'Lombardia'],
            ['tag' => 'LE', 'name' => 'Lecce', 'region' => 'Puglia'],
            ['tag' => 'LI', 'name' => 'Livorno', 'region' => 'Toscana'],
            ['tag' => 'LO', 'name' => 'Lodi', 'region' => 'Lombardia'],
            ['tag' => 'LT', 'name' => 'Latina', 'region' => 'Lazio'],
            ['tag' => 'LU', 'name' => 'Lucca', 'region' => 'Toscana'],
            ['tag' => 'MB', 'name' => 'Monza-Brianza', 'region' => 'Lombardia'],
            ['tag' => 'MC', 'name' => 'Macerata', 'region' => 'Marche'],
            ['tag' => 'ME', 'name' => 'Messina', 'region' => 'Sicilia'],
            ['tag' => 'MI', 'name' => 'Milano', 'region' => 'Lombardia'],
            ['tag' => 'MN', 'name' => 'Mantova', 'region' => 'Lombardia'],
            ['tag' => 'MO', 'name' => 'Modena', 'region' => 'Emilia Romagna'],
            ['tag' => 'MS', 'name' => 'Massa-Carrara', 'region' => 'Toscana'],
            ['tag' => 'MT', 'name' => 'Matera', 'region' => 'Basilicata'],
            ['tag' => 'NA', 'name' => 'Napoli', 'region' => 'Campania'],
            ['tag' => 'NO', 'name' => 'Novara', 'region' => 'Piemonte'],
            ['tag' => 'NU', 'name' => 'Nuoro', 'region' => 'Sardegna'],
            ['tag' => 'OG', 'name' => 'Ogliastra', 'region' => 'Sardegna'],
            ['tag' => 'OR', 'name' => 'Oristano', 'region' => 'Sardegna'],
            ['tag' => 'OT', 'name' => 'Olbia Tempio', 'region' => 'Sardegna'],
            ['tag' => 'PA', 'name' => 'Palermo', 'region' => 'Sicilia'],
            ['tag' => 'PC', 'name' => 'Piacenza', 'region' => 'Emilia Romagna'],
            ['tag' => 'PD', 'name' => 'Padova', 'region' => 'Veneto'],
            ['tag' => 'PE', 'name' => 'Pescara', 'region' => 'Abruzzo'],
            ['tag' => 'PG', 'name' => 'Perugia', 'region' => 'Umbria'],
            ['tag' => 'PI', 'name' => 'Pisa', 'region' => 'Toscana'],
            ['tag' => 'PN', 'name' => 'Pordenone', 'region' => 'Friuli Venezia Giulia'],
            ['tag' => 'PO', 'name' => 'Prato', 'region' => 'Toscana'],
            ['tag' => 'PR', 'name' => 'Parma', 'region' => 'Emilia Romagna'],
            ['tag' => 'PT', 'name' => 'Pistoia', 'region' => 'Toscana'],
            ['tag' => 'PU', 'name' => 'Pesaro-Urbino', 'region' => 'Marche'],
            ['tag' => 'PV', 'name' => 'Pavia', 'region' => 'Lombardia'],
            ['tag' => 'PZ', 'name' => 'Potenza', 'region' => 'Basilicata'],
            ['tag' => 'RA', 'name' => 'Ravenna', 'region' => 'Emilia Romagna'],
            ['tag' => 'RC', 'name' => 'Reggio-Calabria', 'region' => 'Calabria'],
            ['tag' => 'RE', 'name' => 'Reggio-Emilia', 'region' => 'Emilia Romagna'],
            ['tag' => 'RG', 'name' => 'Ragusa', 'region' => 'Sicilia'],
            ['tag' => 'RI', 'name' => 'Rieti', 'region' => 'Lazio'],
            ['tag' => 'RN', 'name' => 'Rimini', 'region' => 'Emilia Romagna'],
            ['tag' => 'RO', 'name' => 'Rovigo', 'region' => 'Veneto'],
            ['tag' => 'Roma', 'name' => 'Roma', 'region' => 'Lazio'],
            ['tag' => 'SA', 'name' => 'Salerno', 'region' => 'Campania'],
            ['tag' => 'SI', 'name' => 'Siena', 'region' => 'Toscana'],
            ['tag' => 'SO', 'name' => 'Sondrio', 'region' => 'Lombardia'],
            ['tag' => 'SP', 'name' => 'La-Spezia', 'region' => 'Liguria'],
            ['tag' => 'SR', 'name' => 'Siracusa', 'region' => 'Sicilia'],
            ['tag' => 'SS', 'name' => 'Sassari', 'region' => 'Sardegna'],
            ['tag' => 'SV', 'name' => 'Savona', 'region' => 'Liguria'],
            ['tag' => 'TA', 'name' => 'Taranto', 'region' => 'Puglia'],
            ['tag' => 'TE', 'name' => 'Teramo', 'region' => 'Abruzzo'],
            ['tag' => 'TN', 'name' => 'Trento', 'region' => 'Trentino Alto Adige'],
            ['tag' => 'TO', 'name' => 'Torino', 'region' => 'Piemonte'],
            ['tag' => 'TP', 'name' => 'Trapani', 'region' => 'Sicilia'],
            ['tag' => 'TR', 'name' => 'Terni', 'region' => 'Umbria'],
            ['tag' => 'TS', 'name' => 'Trieste', 'region' => 'Friuli Venezia Giulia'],
            ['tag' => 'TV', 'name' => 'Treviso', 'region' => 'Veneto'],
            ['tag' => 'UD', 'name' => 'Udine', 'region' => 'Friuli Venezia Giulia'],
            ['tag' => 'VA', 'name' => 'Varese', 'region' => 'Lombardia'],
            ['tag' => 'VB', 'name' => 'Verbania', 'region' => 'Piemonte'],
            ['tag' => 'VC', 'name' => 'Vercelli', 'region' => 'Piemonte'],
            ['tag' => 'VE', 'name' => 'Venezia', 'region' => 'Veneto'],
            ['tag' => 'VI', 'name' => 'Vicenza', 'region' => 'Veneto'],
            ['tag' => 'VR', 'name' => 'Verona', 'region' => 'Veneto'],
            ['tag' => 'VS', 'name' => 'Medio Campidano', 'region' => 'Sardegna'],
            ['tag' => 'VT', 'name' => 'Viterbo', 'region' => 'Lazio'],
            ['tag' => 'VV', 'name' => 'Vibo-Valentia', 'region' => 'Calabria'],
        ];

        foreach ($regions as $region) {
            Region::create([
                'name' => $region['region'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
