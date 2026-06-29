<?php

use App\Helpers\FileHelper;
use App\Helpers\StringHelper;
use App\Legacy\System\DB;
use App\Legacy\System\Query_Result;
use App\Models\Ecommerce\Design;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Vite;

function client_url(string $parcialUrl = '') {
    return $parcialUrl;
}

function client_path(string $file = '') {
    $url = asset('' . file_time(generate_base_url($file)));
    return $url;
}

function file_time($file) {
    if (!strpos($file, '?=') && file_exists($url = base_path() . '/legacy' . $file)) $file .= '?=' . filemtime($url);

    return $file;
}

function info_box($text) { ?>

    <div class="tip-box inlb clickable">

        <div class="icon-box">
            <?= embed_icon('question') ?>
        </div>

        <p class="tip-icon-txt"><?= $text ?></p>

    </div>

    <?php }

function try_include(string $filePath) {
    if (file_exists($filePath)) {
        include($filePath);
    }
}

function sort_subvalue(&$array, $key) {
    usort($array, function ($a, $b) use ($key) {
        return $a[$key] <=> $b[$key];
    });
}

function new_url(string $parcialUrl = '') {
    return $parcialUrl;
}

function explode_last_legacy($separator, $string) {
    $array = explode($separator, $string);
    return end($array);
}

function redirect_old(string|false $path = false) {
    if (empty($path)) {
        $url = $_SERVER['REQUEST_URI'];
    } else if ($path[0] == '?') {
        $url = explode('?', $_SERVER['REQUEST_URI'])[0] . $path;
    } else if ($path[0] == '#') {
        $url = explode('#', $_SERVER['REQUEST_URI'])[0] . $path;
    } else if ($path[0] == '&') {
        $url = $_SERVER['REQUEST_URI'] . $path;
    } else if ($path[0] == '/') {
        $url = client_url($path);
    } else {
        $url = $path;
    }

    header('Location: ' . $url);
    exit();
}

function ecommerce_config($paramenter) {
    global $ecommerceConfig;

    if (empty($ecommerceConfig)) include_once(base_path() . '/legacy/app/config/ecommerce-config.php');

    return $ecommerceConfig[$paramenter];
}

function rows_to_objects(?Query_Result $rows = null, string $className) {
    $objects = [];

    while ($row = $rows->fetch_assoc()) {
        $objects[] = new $className($row[$className::INDEX]);
    }

    return $objects;
}

function extract_markup(string $markup, string $content): string|null {
    if (strpos($content, '<' . $markup . '>') !== false) {
        return explode('</' . $markup . '>', explode('<' . $markup . '>', $content, 2)[1], 2)[0];
    } else {
        return null;
    }
}


function generate_base_url(string $file = '') {
    if (!strpos($file, '.')) {
        $file .= '.webp';
    }

    if ($file[0] == '/') {
        $url = $file;
    } else {
        $url = 'images/' . $file;
    }

    return $url;
}

function server_path(string $file = '') {
    $url = base_path() . "/legacy/" . generate_base_url($file);

    return $url;
}

function public_server_path(string $file = '') {
    $url = server_path($file);

    return $url;
}

function public_client_url(string $parcialUrl = '') {
    $url = client_url($parcialUrl);

    return $url;
}

function background_image($img) {
    return 'style="background-image: url(' . Vite::image($img) . ');"';
}

function assets_directory($page_url): string|null {
    if ($page_url) {
        $url = substr($page_url, strpos($page_url, 'pages') - 1);
        $url = str_replace(['/views/', '.php'], ['/assets/', '/'], $url);
    } else {
        $url = null;
    }

    return $url;
}

function des_img_gen(int $design_id): void {

    $design = Design::find($design_id);

    //design path
    $des_path = public_storage('designs/' . $design->id . '.png');

    //outputs
    $dst_image_path = public_storage('designs/previews/' . $design->id . '.webp');
    $dst_image_path_m = public_storage('designs/previews/' . $design->id . '_m.webp');

    if (file_exists($des_path)) {

        //make directory if doesn't exist
        if (!file_exists($dir = public_storage('designs/previews'))) {
            mkdir($dir, 0777, true);
        }

        //SAVE HD
        $w = $h = 1024;
        $w_m = $h_m = 400;

        $dst_image = imagecreatetruecolor($w, $h);

        //bgr
        $src_image = imagecreatetruecolor($w, $h);

        if (isset($design->color)) $design->color = DB::find('color FROM designs WHERE id = ?', [$design->id]);

        $rgba = parse_rgba(DB::find('color FROM designs WHERE id = ?', [$design->id]));
        $color = imagecolorallocate($src_image, $rgba[0], $rgba[1], $rgba[2]);

        imagefilledrectangle($src_image, 0, 0, $w - 1, $h - 1, $color);

        imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $w, $h, $w, $h);

        //design
        $src_image = imagecreatefrompng($des_path);

        $old_size = getimagesize($des_path);
        $src_w = $old_size[0];
        $src_h = $old_size[1];
        $ratio = $src_w / $src_h;

        if ($ratio > 1) {
            $dst_w = $w;
            $dst_h = $h / $ratio;
            $dst_x = 0;
            $dst_y = ($h - $dst_h) / 2;
        } else {
            $dst_w = $w * $ratio;
            $dst_h = $h;
            $dst_x = ($w - $dst_w) / 2;
            $dst_y = 0;
        }

        imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        imagewebp($dst_image, $dst_image_path, 90);

        imagedestroy($src_image);
        imagedestroy($dst_image);

        //SAVE LD
        $src_image = imagecreatefromwebp($dst_image_path);
        $dst_image = imagecreatetruecolor($w_m, $h_m);

        imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $w_m, $h_m, $w, $h);
        imagewebp($dst_image, $dst_image_path_m, 80);

        imagedestroy($src_image);
        imagedestroy($dst_image);
    }
}

// parse colors
function parse_rgba($hex, $alpha = 1) {
    $rgba = [];
    $hex = str_replace('#', '', $hex);

    for ($i = 0; $i < 3; $i++) $rgba[] = hexdec(substr($hex, $i * 2, 2));

    $rgba[] = $alpha;

    return $rgba;
}

function random_string($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';

    $charactersLength = strlen($characters);

    $randomString = '';

    for ($i = 0; $i < $length; $i++) $randomString .= $characters[rand(0, $charactersLength - 1)];

    return $randomString;
}

function temp_bg($img) {
    $img = str_replace(asset('/storage'), '', $img);

    return 'style="background-image: url(' . public_asset($img) . ');"';
}

function format($n) {
    $n = (float) $n;
    return number_format($n, 2, '.', '');
}

function formatc($n) {
    $n = (float) $n;
    return number_format($n, 2, ',', '');
}

function generate_form($fields, $data = false, $error = false, $done = false) {

    foreach ($fields as $field) {

        echo '<div class="form-field">';

        if (isset($field[0]))
            foreach ($field as $input) generate_input($input, $data, $error, $done);

        else
            generate_input($field, $data, $error, $done);

        echo '</div>';
    }
}

function generate_input($input, $data, $error, $done) {

    // basic data
    $type = $input['type'] ?? 'text';
    $name = $input['name'] ?? false;
    $styling = $input['styling'] ?? false;

    // calculate value
    $value = false;

    // if value is explicit
    if (isset($input['value'])) $value = $input['value'];

    // else get from POST, if set
    else if ($name && isset($_POST[$name])) $value = $_POST[$name];

    // else get from MySQL result, if set
    else if ($name && isset($data[$name]) && $type != 'cbx') $value = $data[$name];

    // else if it's a checkbox
    else if ($type == 'cbx') $value = 1;

    // styling
    if ($styling == 'fancy') { ?>

        <div class="fancy-input <?= isset($input['size']) ? 'fancy-input_size' . $input['size'] : '' ?>">

            <?php }

        // start to generate
        // label
        switch ($type) {

            case 'lbl': ?>

                <p class="label"><?= $input['text'] ?>:</p>

            <?php break;

            case 'info': ?>

                <p class="form-text"><?= $input['text'] ?></p>

                <?php break;

            case 'done':

                if ($type == 'done' && $done) { ?>

                    <p class="confirmation"><?= $input['text'] ?></p>

                <?php }
                break;

            case 'error':

                if ($input['error'] == $error) { ?>

                    <p class="error"><?= $input['text'] ?></p>

                <?php }
                break;

            case 'search':

                input_search($input);

                break;

            case 'file': ?>

                <input type="file" class="input file" name="<?= $name ?>" <?= isset($input['multiple']) ? 'multiple' : '' ?> <?= isset($input['required']) ? 'required' : '' ?> <?= isset($input['accept']) ? 'accept="' . $input['accept'] . '"' : '' ?>>

            <?php break;

            case 'select':

                if (isset($input['query'])) {
                    $selectOptions = DB::select($input['query'])->res;
                    $fieldId = $input['field-name'];
                    $fieldShow = $input['field-show'];
                } else if (isset($input['array'])) {
                    $selectOptions = $input['array'];
                    $fieldId = 0;
                    $fieldShow = 1;
                }
            ?>

                <select class="select <?= isset($input['affect']) ? 'affect' : '' ?>" <?= isset($input['affect']) ? 'data-affect="' . $input['affect'] . '"' : '' ?> name="<?= $name ?>">

                    <?php if (isset($input['empty'])) { ?>

                        <option value="0" selected>- <?= ('None') ?> -</option>

                    <?php }
                    foreach ($selectOptions as $option) { ?>

                        <option value="<?= $option[$fieldId] ?>" <?php if ($option[$fieldId] == $value) echo 'selected' ?>>
                            <?= $option[$fieldShow] ?>
                        </option>

                    <?php } ?>

                </select>

            <?php break;

            case 'select-dependent': ?>

                <select class="select" data-tbl="<?= $input['query-tbl'] ?>" data-filter="<?= $input['query-filter'] ?>" data-id="<?= $input['field-name'] ?>" data-show="<?= $input['field-show'] ?>" data-root="<?= CLIENT_ROOT ?>" name="<?= $name ?>" disabled>
                    <option>-</option>
                </select>

            <?php break;

            case 'textarea': ?>

                <textarea class="textarea" name="<?= $name ?>" placeholder="<?= $input['text'] ?? '' ?>" <?= isset($input['maxlength']) ? 'maxlength="' . $input['maxlength'] . '"' : '' ?>><?=
                                                                                                                                                                                            $value ?: ''
                                                                                                                                                                                            ?></textarea>

            <?php break;

            case 'cbx': ?>

                <label class="
				checkbox
				<?= $input['custom-class'] ?? '' ?>
				<?= isset($input['size']) ? 'inp-size' . $input['size'] : '' ?>
			">

                    <input type="checkbox" name="<?= $name ?>" value="<?= $value ?>" <?= !empty($input['checked']) || ($name && isset($_POST[$name])) || ($name && isset($data[$name]) && $data[$name] == $value) ? 'checked' : '' ?> <?= isset($input['required']) ? 'required' : '' ?>>

                    <span></span>

                    <p><?= $input['text'] ?></p>

                </label>

            <?php break;

            case 'img': ?>

                <img class="image-fit" src="<?= $input['src'] ?>" alt="">

            <?php break;

            case 'btn': ?>

                <button type="<?= !empty($input['submit']) ? 'submit' : 'button' ?>" class="<?= $input['btn-type'] ?? 'pbtn' ?> <?= isset($input['load']) ? 'file-submit' : '' ?> <?= $input['custom-class'] ?? '' ?>" name="<?= $name ?: 'submit' ?>" data-size="<?= $input['size'] ?? 4 ?>">
                    <?= $input['text'] ?? '' ?>
                </button>

                <?php if (isset($input['load'])) { ?>

                    <div class="file-load"><?php embed_icon('loading') ?></div>

                <?php } ?>

            <?php break;

            default: ?>

                <input type="<?= $type ?>" class="input
				<?= $input['custom-class'] ?? '' ?>
			" name="<?= $name ?>" value="<?= $value ?>" placeholder="<?= $styling ? '' : ($input['text'] ?? '') ?>" <?= isset($input['minlength']) ? 'minlength="' . $input['minlength'] . '"' : '' ?> <?= isset($input['maxlength']) ? 'maxlength="' . $input['maxlength'] . '"' : '' ?> <?= isset($input['min']) ? 'min="' . $input['min'] . '"' : '' ?> <?= isset($input['max']) ? 'max="' . $input['max'] . '"' : '' ?> <?= isset($input['step']) ? 'step="' . $input['step'] . '"' : '' ?> <?= isset($input['required']) ? 'required' : '' ?> <?= isset($input['readonly']) ? 'readonly' : '' ?> <?= isset($input['required']) ? 'required' : '' ?> data-size="<?= $input['size'] ?? 4 ?>" <?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                if (isset($input['data']))
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    foreach ($input['data'] as $dataName => $dataValue) echo 'data-' . $dataName . '="' . $dataValue . '" '
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ?>>

            <?php break;
        }

        // styling closing tag
        if ($styling == 'fancy') { ?>

            <p class="fancy-input-lbl"><?= $input['text'] ?></p>

        </div>

    <?php }
    }

    /*
* (string) search: the parcial name of file that fill be fetched (the full name will be search-*name*)
* (string) name: the input's name
* (string) text: the placeholder text
* ? (string) name-show: the name of the visible field, used in custom situation, for ex. to add a new entry based on the typed name
* ? (mixed) value: the actual value of the input
* ? (mixed) value-show: the shown value of the input
* ? (string) custom-classes: custom classes that the whole block will have
* ? (bool) required: set if the input is required
* ? (bool) title: if set, it will show the shown input content on hover
*/

    function input_search($input) {
        $showValue = $input['value-show'] ?? '';
        $showValue = str_replace('"', "&quot;", $showValue);
        $customClasses = isset($input['custom-classes']) ? implode(' ', $input['custom-classes']) : '';
    ?>

    <div class="ajax-search relative <?= $customClasses ?>" data-endpoint="<?= $input['search'] ?>">

        <input type="hidden" class="ajax-search-id" name="<?= $input['name'] ?>" value="<?= $input['value'] ?? '' ?>" <?= isset($input['required']) ? 'required' : '' ?>>

        <input type="text" class="input ajax-search-text" <?= isset($input['name-show']) ? 'name="' . $input['name-show'] . '"' : '' ?> value="<?= $showValue ?>" <?= !empty($input['title']) ? 'title="' . $showValue . '"' : '' ?> placeholder="<?= $input['text'] ?? '' ?>" <?= isset($input['required']) ? 'required' : '' ?> autocomplete="off">

        <div class="ajax-search-ddown">
            <div class="ajax-search-box"></div>
        </div>
    </div>

<?php }

    function legacyEmbedSvg(string $fileName, array $customClasses = [], bool $isIcon = false): string {
        // Replace dots with slashes
        $fileName = str_replace('.', '/', $fileName);

        // Add the default icon directories
        if ($isIcon) {
        }

        // Add the default class and any custom classes
        $classes = implode(' ', ['svg-icon', ...$customClasses]);

        // Get the icon file
        $fileDirectory = $isIcon ? '/resources/svg' : '/icons';
        $filePath = $isIcon ? resource_path('svg/icons/' . $fileName . '.svg') : base_path() . $fileDirectory . '/' . $fileName . '.svg';

        // Get the icon content
        try {
            $file = file_get_contents($filePath);

            // Add the classes to the icon and return it
            return str_replace('<svg', '<svg class="' . $classes . '"', $file);
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                dd('Icon not found: ' . $fileName);
            }

            Log::error('Icon not found: ' . $fileName . ')');
        }

        return '';
    }

    /**
     * Import an icon and embed it in the HTML
     */
    function embed_icon(string $fileName, array $customClasses = []): ?string {
        return legacyEmbedSvg($fileName, $customClasses, true);
    }

    /**
     * Explode a string by the last occurrence of a separator
     */
    function explode_last($separator, $string): array {
        return StringHelper::explodeLast($separator, $string);
    }


    /**
     * Debug an array
     * 
     * @param array $array The array to debug
     * @return void
     */
    function dd_array(array $array): void {
        dd('<pre>' . print_r($array, true) . '</pre>');
    }

    function legacyPublicAsset(string $path): string {
        $publicFilePath = asset('storage/' . $path);
        $storedFilePath = base_path() . '/public/storage/' . $path;
        $fileVersion = file_exists($storedFilePath) ? filemtime($storedFilePath) : 0;

        return $publicFilePath . '?v=' . $fileVersion;
    }

    /**
     * Get the path of a file in the public storage directory
     */
    function public_asset(string $path): string {
        $filePath = legacyPublicAsset($path);

        if (!$filePath) {
            Log::error('File not found: ' . $path);
        }

        return $filePath;
    }

    /**
     * Get the path of a file in the public storage directory
     */
    function public_storage(?string $path = null): string {
        return storage_path('app/public/' . $path);
    }
