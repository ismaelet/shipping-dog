<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    /**
     * Display the FAQ page.
     */
    public function index(Request $request): View
    {
        $title = 'FAQ';
        $location = 'support';

        $faqGroups = [
            [
                'eyebrow' => 'Acquisti e assistenza',
                'title' => 'Ordini',
                'icon' => 'faq.orders',
                'accent' => 'text-[#ff8904]',
                'items' => [
                    [
                        'question' => 'Ho ordinato ma non mi è arrivata nessuna email di conferma',
                        'answer' => "Se non ti è arrivata nessuna email di conferma dopo l'acquisto ti consigliamo di controllare nella cartella di posta indesiderata o spam, in quanto a volte viene filtrata automaticamente per sbaglio. Qualora non si riesca comunque a trovare l'email non preoccuparti: l'ordine comunque è stato registrato e il corriere GLS ti manderà in ogni caso l'email della conferma di spedizione nella serata del giorno in cui la merce verrà spedita, normalmente entro 1-5 giorni lavorativi dall'acquisto.",
                    ],
                    [
                        'question' => 'Cambi di taglia, resi e rimborsi',
                        'answer_before' => 'È possibile eseguire un cambio taglia o un reso del capo, basta seguire la semplice ',
                        'link' => [
                            'url' => route('support.refund.search'),
                            'text' => 'procedura online',
                        ],
                        'answer_after' => ". Nel caso di un cambio, le spese di spedizione del nuovo capo sono a carico del cliente. Vi consigliamo di controllare la lista taglie prima di procedere a un cambio o ad un nuovo acquisto. L'invio del nuovo capo avverrà entro un giorno lavorativo dalla restituzione di quello vecchio.",
                    ],
                ],
            ],
            [
                'eyebrow' => 'Consegne e tracciamento',
                'title' => 'Spedizioni',
                'icon' => 'faq.shipping',
                'accent' => 'text-[#00bcff]',
                'items' => [
                    [
                        'question' => 'Tempi di spedizione',
                        'answer' => "Dal momento dell'acquisto l'ordine sarà in elaborazione per 1-5 giorni lavorativi. Successivamente verrà spedito e, se il metodo di spedizione scelto è tramite corriere, il pacco verrà consegnato in 1-2 giorni lavorativi. Se invece è stata scelta la spedizione tramite posta ordinaria i tempi saranno di 3-8 giorni lavorativi. Per le spedizioni all'estero i tempi possono variare e sono specificati al momento dell'acquisto.",
                    ],
                    [
                        'question' => 'Tracciamento ordine',
                        'answer' => 'Riceverete una email dal corriere GLS con istruzioni e codice di tracciamento intorno alle 20:00 del giorno in cui avverrà la spedizione, prevista entro 3 giorni lavorativi dal momento del pagamento.',
                    ],
                    [
                        'question' => 'Spedizioni in giacenza',
                        'answer' => "Nel caso ci sia un errore nell'indirizzo inserito, o nel caso non ci sia nessuno presente al momento della consegna per due giorni consecutivi allora il pacco andrà in giacenza. Vi contatteremo immediatamente per fissare un nuovo tentativo di consegna ma, se non riceveremo risposta entro 3 giorni, saremo costretti a chiedere il ritorno della merce.",
                    ],
                ],
            ],
            [
                'eyebrow' => 'Creator e negozi',
                'title' => 'Vendite',
                'icon' => 'faq.selling',
                'accent' => 'text-[#00d084]',
                'items' => [
                    [
                        'question' => 'Come apro un negozio?',
                        'answer_before' => 'Aprire un negozio sul nostro sito è molto semplice, basta registrarsi, andare sulla ',
                        'link' => [
                            'url' => route('open-shop'),
                            'text' => 'pagina per aprire uno store',
                        ],
                        'answer_after' => ' e seguire la procedura, facile e veloce.',
                    ],
                    [
                        'question' => 'Il mio negozio sarà attivo fin da subito?',
                        'answer_before' => 'Appena creato il negozio sarà già possibile cambiare tutti i dettagli a piacimento, come immagine di copertina, nome, descrizione, prezzi, ma sarà necessario caricare almeno un lavoro prima che il negozio possa essere visibile al pubblico. Quanto ti senti pronto/a mandanci la richiesta a ',
                        'link' => [
                            'url' => 'mailto:'.config('custom.contacts.email.url'),
                            'text' => config('custom.contacts.email.label'),
                        ],
                        'answer_after' => ' e pubblicheremo il tuo negozio entro 24 ore.',
                    ],
                    [
                        'question' => 'Guadagni proposti',
                        'answer' => 'Per ogni vendita di un capo con stampato il proprio design verrà corrisposto una percentuale del prezzo di vendita come guadagno. Dopo aver creato un negozio è possibile modificare a piacere il prezzo degli articoli, modificando così i propri guadagni.',
                    ],
                    [
                        'question' => 'Come verrò pagato?',
                        'answer' => "Appena accumulato un guadagno minimo di 25,00€ sarà possibile richiedere il pagamento. A causa delle nuove normative riguardo ai pagamenti occasionali, il pagamento avverrà entro 7-10 giorni lavorativi dal momento della richiesta. Per poter ricevere il pagamento si deve disporre di un account PayPal. Nel caso non si abbia un account PayPal crearne uno è semplice, veloce e completamente gratuito. Una volta ricevuto il pagamento su PayPal sarà possibile usare l'importo direttamente, oppure spostarlo comodamente sulla propria carta di credito o conto corrente.",
                    ],
                ],
            ],
        ];

        return view('support.faq', [
            'title' => $title,
            'location' => $location,
            'faqGroups' => $faqGroups,
        ]);
    }
}
