<?php
use Carbon\Carbon;
use App\Models\Cat;
use App\Models\Client;
use App\Models\Office;
use App\Models\SocialNetwork;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

function fecha($fecha = null, string $formato = 'd/m/Y')
{
    if ($fecha != null) {
		$date = Carbon::parse($fecha)->locale('es_MX');
		//echo $date->translatedFormat($formato);
		$d = $date->translatedFormat($formato);
        return str_replace('.', '', $d);//optional(new Carbon($fecha))->format($formato);
    } else {
        return null;
    }
}

function hora($fecha = null, string $formato = 'h:i')
{
    return optional($fecha)->format($formato);
}

function setMessage($mensaje, $alerta = 'success')
{
    return  flash($mensaje)->{$alerta}()->important();
}

//Registra las variables de sesion para el usuario
function setSession($attribute = '', $value = '')
{
    session([config('app.name').'_'.$attribute => $value]);
}

function delSession($attribute = '')
{
    session()->forget(config('app.name').'_'.$attribute);
}


//Retorna las variables de sesion para el usuario
function getSession($attribute = '', $value = '')
{
    return session(config('app.name').'_'.$attribute);
}


function iconoArchivo($ext)
{
    switch ($ext) {
        case 'pdf':
            $icon = '<i class="align-middle text-danger fad fa-file-pdf fs-1 me-1" aria-hidden="true"></i>';
            break;
        case 'xls':
        case 'xlsx':
            $icon = '<i class="align-middle text-success fas fa-file-excel fs-1 me-1" aria-hidden="true"></i>';
            break;
        case 'doc':
        case 'docx':
            $icon = '<i class="align-middle text-primary fas fa-file-word fs-1 me-1" aria-hidden="true"></i>';
            break;
        case 'jpg':
        case 'jpeg':
        case 'png':
            $icon = '<i class="align-middle fas fa-file-image fs-1 me-1" aria-hidden="true"></i>';
            break;
        case 'a.folder':
            $icon = '<i class="align-middle text-gray-800 far fa-folder fs-1 me-1" aria-hidden="true"></i>';
            break;
        case 'comment':
            $icon = '<i class="align-middle text-bg-gray-400 bi bi-chat-square-dots fs-1 me-1" aria-hidden="true"></i>';
            break;
        case 'link':
            $icon = '<i class="align-middle bi bi-link-45deg fs-1 me-1" aria-hidden="true"></i>';
            break;
        default:
            $icon = '<i class="align-middle text-gray fas fa-file-alt fs-1 me-1" aria-hidden="true"></i>';
    }

    return $icon;
}

function iconoArchivoGrid($ext)
{
    switch ($ext) {
        case 'pdf':
            $icon = '<i class="align-middle text-danger fal fa-file-pdf fa-8x me-1" aria-hidden="true"></i>';
            break;
        case 'xls':
        case 'xlsx':
            $icon = '<i class="align-middle text-success fal fa-file-excel fa-8x me-1" aria-hidden="true"></i>';
            break;
        case 'doc':
        case 'docx':
            $icon = '<i class="align-middle text-primary fal fa-file-word fa-8x me-1" aria-hidden="true"></i>';
            break;
        case 'jpg':
        case 'jpeg':
        case 'png':
            $icon = '<i class="align-middle fal fa-file-image fa-8x me-1" aria-hidden="true"></i>';
            break;
        case 'a.folder':
            $icon = '<i class="align-middle text-gray-800 fal fa-folder fa-8x me-1" aria-hidden="true"></i>';
            break;
        case 'comment':
            $icon = '<i class="align-middle text-bg-gray-400 bi bi-chat-square-dots fa-8x me-1" aria-hidden="true"></i>';
            break;
        case 'link':
            $icon = '<i class="align-middle bi bi-link-45deg fa-8x me-1" aria-hidden="true"></i>';
            break;
        default:
            $icon = '<i class="align-middle text-gray fal fa-file-alt fa-8x me-1" aria-hidden="true"></i>';
    }

    return $icon;
}

function obtIconArchivo($ext)
{
    $icon = "fa fa-file";
    switch ($ext) {
        case 'pdf':
            $icon = " fs-1s text-danger fad fa-file-pdf align-middle";
            break;
        case 'mp3':
            $icon = " fs-1s text-primary bi bi-file-audio align-middle";
            break;
        case 'doc':
        case 'docx':
            $icon = " fs-1s text-primary bi bi-file-earmark-word align-middle";
            break;
        case 'xls':
        case 'xlsx':
            $icon = " fs-1s text-success bi bi-file-earmark-spreadsheet align-middle";
            break;
        case 'txt':
            $icon = " fs-1s text-secondary bi bi-file-text align-middle";
            break;
        case 'ppt':
        case 'pptx':
            $icon = " fs-1s text-warning bi bi-file-powerpoint align-middle";
            break;
        case 'jpg':
        case 'jpeg':
        case 'png':
            $icon = " fs-1s bi bi-file-image align-middle";
            break;
        default:
            $icon = " fs-1s bi bi-file-text align-middle";
            break;
    }
    return $icon;
}

function reporte($name, $params, $type = 'pdf')
{
    try {
        $client = new Client(env('JASPER_SERVER_URL'), env('JASPER_SERVER_USERNAME'), env('JASPER_SERVER_PASSWORD'));

        $report = $client->reportService()->runReport('/reports/'.$name, $type, null, null, $params);
        $nombre_temporal = Str::random(32).'.'.$type;
        Storage::disk('public')->put('temporales/'.$nombre_temporal, $report);

        return $nombre_temporal;
    } catch (Throwable $error) {
        return 'error';
    }
}

function redireccionar($page = 'dashboard', $message = 'Hola mundo', $tipo = 'info')
{
    if (is_string($page)) {
        $location = $page;
    } else {
        $location = $_SERVER['SCRIPT_NAME'];
    }
    setSession($tipo, $message);
    //header('Location: '.$location);
    return redirect()->to($location)->send();
    //return redirect()->route('/');
    //exit;
}

function displayNotify()
{
    if (getSession('success') != '') {
        $message = getSession('success');
        echo '<script language="javascript">notifyMsg("'.$message.'", "#", "success", "");</script>';
        delSession('success');
    }
    if (getSession('danger') != '') {
        $message = getSession('danger');
        echo '<script language="javascript">notifyMsg("'.$message.'", "#", "danger", "");</script>';
        delSession('danger');
    }
    if (getSession('warning') != '') {
        $message = getSession('warning');
        echo '<script language="javascript">notifyMsg("'.$message.'", "#", "warning", "");</script>';
        delSession('warning');
    }
    if (getSession('info') != '') {
        $message = getSession('info');
        echo '<script language="javascript">notifyMsg("'.$message.'", "#", "info", "");</script>';
        delSession('info');
    }
}

function getModules()
{
    $user['show_mod'] = ['sidebar', 'panel', 'all'];
    $user['tipo_mod'] = ['module'];
    $modules = DB::table('modules as sec')->select('sec.id', 'sec.desc', 'sec.nom', 'sec.icon')
                                                ->join('modules as sub', 'sub.module_id', '=', 'sec.id')
                                                ->leftJoin('permits as per', 'per.sub_module_id', '=', 'sub.id')
                                                ->where('per.status', 1)
                                                ->where('per.user_id', auth()->user()->id)
                                                ->where('sec.status', 1)
                                                ->where('sub.status', 1)
                                                ->where('sec.module_id', 0)
                                                ->where('sub.module_id', '>', 0)
                                                ->whereIn('sub.show_on', $user['show_mod'])
                                                ->groupBy('sec.id', 'sec.desc', 'sec.nom', 'sec.icon')
                                                ->orderBy('sec.desc', 'asc')->get();

    return $modules;
}

function estadoReg($edo = 1)
{
    $estado = '';
    switch ($edo) {
        case 1:
            $estado = '<span class="fs-9 align-middle badge bg-success">Activo</span>';
            break;
        case 2:
            $estado = '<span class="fs-9 align-middle badge bg-danger">Bloqueado</span>';
            break;
        case 3:
            $estado = '<span class="fs-9 align-middle badge bg-warning">Baneado</span>';
            break;
        default:
            $estado = '<span class="fs-9 align-middle badge bg-secondary">Eliminado</span>';
            break;
    }

    return $estado;
}

function activeReg($edo = 1)
{
    $estado = '';
    switch ($edo) {
        case 1:
            $estado = '<span class="fs-9 align-middle badge bg-success">Activo</span>';
            break;
        case 2:
            $estado = '<span class="fs-9 align-middle badge bg-danger">Inactivo</span>';
            break;
        case 3:
            $estado = '<span class="fs-9 align-middle badge bg-warning">Baneado</span>';
            break;
        default:
            $estado = '<span class="fs-9 align-middle badge bg-secondary">Eliminado</span>';
            break;
    }

    return $estado;
}

function getSubModules($data)
{
    $modules = DB::table('modules as sub')
                ->join('permits as per', 'per.sub_module_id', '=', 'sub.id')
                ->where('sub.status', 1)
                ->where('per.status', 1)
                ->where('sub.module_id', $data['mod_id'])
                ->where('per.user_id', auth()->user()->id)
                ->whereIn('sub.show_on', $data['show_on'])
                ->orderBy('sub.desc', 'asc')
                ->get();

    return $modules;
}

function getRealIpAddr()
{
    if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('HTTP_X_FORWARDED')) {
        $ip = getenv('HTTP_X_FORWARDED');
    } elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ip = getenv('HTTP_FORWARDED_FOR');
    } elseif (getenv('HTTP_FORWARDED')) {
        $ip = getenv('HTTP_FORWARDED');
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}

function splitWordSearch($str)
{
    $str = trim($str);
    $wds = [];
    $words_split = explode(' ', strtolower($str));
    $words = explode(' ', strtolower($str));
    $temp = strtolower($str);
    while (count($words) > 0) {
        $words = explode(' ', $temp);
        array_splice($words, -1);
        $temp = implode(' ', $words);
        if ($temp != '') {
            $wds[] = $temp;
        }
    }
    foreach ($words_split as $word) {
        if ($word != 'el' && $word != 'lo' && $word != 'los' && $word != 'las' && $word != 'con' && $word != 'más' && $word != 'mas' && $word != 'el' && $word != 'de' && $word != 'del' && $word != 'otro' && $word != 'y' && $word != 'o' && $word != 'un' && $word != 'una' && $word != 'a' && $word != 'otros' && $word != 'mejor' && $word != 'solo' && $word != 'unico') {
            $wds[] = $word;
        }
    }

    return $wds;
}

function get_words($sentence, $count = 10)
{
    preg_match("/(?:[^\s,\.;\?\!]+(?:[\s,\.;\?\!]+|$)){0,$count}/", $sentence, $matches);

    return $matches[0];
}

function paginate($page, $tpages, $adjacents, $fuc_load, $from_id = 0, $from_table = '', $div = '')
{
    $prevlabel = '<i class="fas fa-chevron-left"></i>';
    $nextlabel = '<i class="fas fa-chevron-right"></i>';
    $out = '<nav aria-label="..."><ul class="pagination pagination-large justify-content-end mt-1">';

    // previous label
    if ($page == 1) {
        $out .= '<li class="page-item disabled"><a class="page-link">'.$prevlabel.'</a></li>';
    } elseif ($page == 2) {
        $out .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="' . $fuc_load . '(1' . ($from_id > 0 ? (',' . $from_id . ',\'' . $from_table . '\',\'' . $div . '\'') : '') . ');">' . $prevlabel . '</a></li>';
    } else {
        $out .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="' . $fuc_load . '(' . ($page - 1) . ($from_id > 0 ? (',' . $from_id . ',\'' . $from_table . '\',\'' . $div . '\'') : '') . ');">' . $prevlabel . '</a></li>';
    }

    // first label
    if ($page > ($adjacents + 1)) {
        $out .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="' . $fuc_load . '(1' . ($from_id > 0 ? (',' . $from_id . ',\'' . $from_table . '\',\'' . $div . '\'') : '') . ');">1</a></li>';
    }

    // interval
    if ($page > ($adjacents + 2)) {
        $out .= '<li class="page-item disabled"><a class="page-link">...</a></li>';
    }

    // pages
    $pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
    $pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
    for ($i = $pmin; $i <= $pmax; $i++) {
        if ($i == $page) {
            $out .= '<li class="page-item active"><a class="page-link">'.$i.'</a></li>';
        } elseif ($i == 1) {
            $out .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="' . $fuc_load . '(1' . ($from_id > 0 ? (',' . $from_id . ',\'' . $from_table . '\',\'' . $div . '\'') : '') . ');">' . $i . '</a></li>';
        } else {
            $out .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="' . $fuc_load . '(' . $i . ($from_id > 0 ? (',' . $from_id . ',\'' . $from_table . '\',\'' . $div . '\'') : '') . ');">' . $i . '</a></li>';
        }
    }

    // interval
    if ($page < ($tpages - $adjacents - 1)) {
        $out .= '<li class="page-item disabled"><a class="page-link">...</a></li>';
    }

    // last
    if ($page < ($tpages - $adjacents)) {
        $out .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="' . $fuc_load . '(' . $tpages . ($from_id > 0 ? (',' . $from_id . ',\'' . $from_table . '\',\'' . $div . '\'') : '') . ');">' . $tpages . '</a></li>';
    }

    // next
    if ($page < $tpages) {
        $out .= '<li class="page-item"><a class="page-link" href="javascript:void(0);"" onclick="' . $fuc_load . '(' . ($page + 1) . ($from_id > 0 ? (',' . $from_id . ',\'' . $from_table . '\',\'' . $div . '\'') : '') . ');">' . $nextlabel . '</a></li>';
    } else {
        $out .= '<li class="page-item disabled"><a class="page-link">'.$nextlabel.'</a></li>';
    }

    $out .= '</ul></nav>';

    return $out;
}

function paginateFiles($page, $tpages, $adjacents, $fuc_load, $from_id, $from_table, $div)
{
    $prevlabel = '<i class="fas fa-chevron-left"></i>';
    $nextlabel = '<i class="fas fa-chevron-right"></i>';
    $out = '<nav aria-label="..."><ul class="pagination pagination-large justify-content-end mt-1">';

    // previous label
    if ($page == 1) {
        $out .= '<li class="page-item disabled"><a class="page-link">' . $prevlabel . '</a></li>';
    } elseif ($page == 2) {
        $out .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="' . $fuc_load . '(1,' . $from_id . ',\'' . $from_table . '\',\'' . $div . '\');">' . $prevlabel . '</a></li>';
    } else {
        $out .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="' . $fuc_load . '(' . ($page - 1) . ',' . $from_id . ',\'' . $from_table . '\',\'' . $div . '\');">' . $prevlabel . '</a></li>';
    }

    // first label
    if ($page > ($adjacents + 1)) {
        $out .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="' . $fuc_load . '(1,' . $from_id . ',\'' . $from_table . '\',\'' . $div . '\');">1</a></li>';
    }

    // interval
    if ($page > ($adjacents + 2)) {
        $out .= '<li class="page-item disabled"><a class="page-link">...</a></li>';
    }

    // pages
    $pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
    $pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
    for ($i = $pmin; $i <= $pmax; $i++) {
        if ($i == $page) {
            $out .= '<li class="page-item active"><a class="page-link">' . $i . '</a></li>';
        } elseif ($i == 1) {
            $out .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="' . $fuc_load . '(1,' . $from_id . ',\'' . $from_table . '\',\'' . $div . '\');">' . $i . '</a></li>';
        } else {
            $out .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="' . $fuc_load . '(' . $i . ',' . $from_id . ',\'' . $from_table . '\',\'' . $div . '\');">' . $i . '</a></li>';
        }
    }

    // interval
    if ($page < ($tpages - $adjacents - 1)) {
        $out .= '<li class="page-item disabled"><a class="page-link">...</a></li>';
    }

    // last
    if ($page < ($tpages - $adjacents)) {
        $out .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="' . $fuc_load . '(' . $tpages . ',' . $from_id . ',\'' . $from_table . '\',\'' . $div . '\');">' . $tpages . '</a></li>';
    }

    // next
    if ($page < $tpages) {
        $out .= '<li class="page-item"><a class="page-link" href="javascript:void(0);"" onclick="' . $fuc_load . '(' . ($page + 1) . ',' . $from_id . ',\'' . $from_table . '\',\'' . $div . '\');">' . $nextlabel . '</a></li>';
    } else {
        $out .= '<li class="page-item disabled"><a class="page-link">' . $nextlabel . '</a></li>';
    }

    $out .= '</ul></nav>';

    return $out;
}


function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2).' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2).' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2).' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes.' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes.' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}
function nicetime($date)
{
    if (empty($date)) {
        return 'No date.';
    }

    $periods = ['seg', 'min', 'hora', 'día', 'semana', 'mes', 'año', 'década'];
    $lengths = ['60', '60', '24', '7', '4.35', '12', '10'];
    $now = time();
    $unix_date = strtotime($date);

    // check validity of date
    if (empty($unix_date)) {
        return 'Error en la fecha';
    }

    // is it future date or past date
    if ($now > $unix_date) {
        $difference = $now - $unix_date;
        $tense = 'hace';
    } else {
        $difference = $unix_date - $now;
        $tense = 'hace ';
    }

    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    if ($difference != 1) {
        if ($periods[$j] == 'mes') {
            $periods[$j] .= 'es';
        } else {
            $periods[$j] .= 's';
        }
    }

    return "{$tense} $difference $periods[$j]";
}

function getFieldOptions(array $options = [], $name = null)
{
    $options['class'] = trim(' '.getFieldOptionsClass($options));

    // If we've been provided the input name and the ID has not been set in the options,
    // we'll use the name as the ID to hook it up with the label.
    if ($name && ! array_key_exists('id', $options)) {
        $options['id'] = $name;
    }

    return $options;
}

function getFieldOptionsClass(array $options = [])
{
    return Arr::get($options, 'class');
}

function attributes($attributes)
{
    $html = [];

    foreach ((array) $attributes as $key => $value) {
        $element = attributeElement($key, $value);

        if (! is_null($element)) {
            $html[] = $element;
        }
    }

    return count($html) > 0 ? ' '.implode(' ', $html) : '';
}

function attributeElement($key, $value)
{
    // For numeric keys we will assume that the value is a boolean attribute
    // where the presence of the attribute represents a true value and the
    // absence represents a false value.
    // This will convert HTML attributes such as "required" to a correct
    // form instead of using incorrect numerics.
    if (is_numeric($key)) {
        return $value;
    }

    // Treat boolean attributes as HTML properties
    if (is_bool($value) && $key !== 'value') {
        return $value ? $key : '';
    }

    if (is_array($value) && $key === 'class') {
        return 'class="'.implode(' ', $value).'"';
    }

    if (! is_null($value)) {
        return $key.'="'.e($value, false).'"';
    }
}

function todayMasD($mas = 0)
{
    $hoy = date('Y-m-d', time());
    $dia = date('Y-m-d', strtotime($hoy.' + '.$mas.' days'));

    return $dia;
}

function inputEmail($field, $label = false, $value = '', $icon = 'bi bi-at', $options = [])
{
    $optionsField = getFieldOptions(Arr::except($options, ['suffix', 'prefix']), $field);
    $optionsField['class'] = $optionsField['class'].' form-control';
    $component = '<div class="input-group">
                    <span class="has-float-label">
                        <input type="email" '.attributes($optionsField).' name="'.$field.'" autocomplete="off" value="'.$value.'">
                        <label for="'.$field.'">'.($label != false ? $label : '').'</label>
                        <i class="'.$icon.' form-control-icon"></i>
                    </span>
                </div>';

    return $component;
}

function inputDate($field, $label = false, $value = '', $options = [])
{
    $optionsField = getFieldOptions(Arr::except($options, ['suffix', 'prefix']), $field);
    $optionsField['class'] = $optionsField['class'].' form-control';
    $component = '<div class="form-group">
                        <span class="has-float-label">
                            <input type="date" '.attributes($optionsField).' name="'.$field.'" autocomplete="off" value="'.$value.'">
                            <label for="'.$field.'">'.($label != false ? $label : '').'</label>
                        </span>
                    </div>';

    return $component;
}

function inputTextArea($field, $label = false, $value = '', $icon = 'bi bi-card-text', $options = [])
{
    $optionsField = getFieldOptions(Arr::except($options, ['suffix', 'prefix']), $field);
    $optionsField['class'] = $optionsField['class'].' form-control';
    $component = '<label for="' . $field . '">' . ($label != false ? $label : '') .
        '</label>
                <textarea name="' . $field . '" rows="2" ' . attributes($optionsField) . '>' . $value . '</textarea>';

    return $component;
}

function inputText($field, $label = false, $value = null, $icon = 'bi bi-card-text', $options = [])
{
    $optionsField = getFieldOptions(Arr::except($options, ['suffix', 'prefix']), $field);
    $optionsField['class'] = $optionsField['class'].' form-control';
    $component = '<div class="input-group">
                    <span class="has-float-label">
                        <input type="text" ' . attributes($optionsField) . ' name="' . $field . '" autocomplete="off" value="' . $value . '">
                        <label id="lbl-' . $field . '" for="' . $field . '">' . ($label != false ? $label : '') . '</label>
                        <i class="'.$icon.' form-icon"></i>
                    </span>
                </div>';

    return $component;
}

function inputSelect($field, $label = false, $val = null, $values = [], $options = [])
{
    $optionsField = getFieldOptions(Arr::except($options, ['suffix', 'prefix']), $field);
    $optionsField['class'] = $optionsField['class'].' form-control form-select';
    $component = '<div class="input-group">
        <span class="has-float-label">
            <select '.attributes($optionsField).' name="'.$field.'">
                <option disabled="" value="" '.($val == null ? 'selected' : '').'> --Seleccionar-- </option>';
    foreach ($values as $k => $value) {
        $component .= '<option value="' . $k . '" ' . ($val != null && $val == $k ? 'selected' : '') . '>' . $value . '</option>';
    }
    $component .= '</select>
                    <label for="'.$field.'">'.($label != false ? $label : '').'</label>
                    </span>
                </div>';

    return $component;
}

function inputPassword($field, $label = false, $value = '', $icon = 'bi bi-lock', $options = [])
{
    $optionsField = getFieldOptions(Arr::except($options, ['suffix', 'prefix']), $field);
    $optionsField['class'] = $optionsField['class'].' form-control ';
    $component = '<div class="input-group">
                    <div class="has-float-label">
                        <i class="'.$icon.' form-icon-passwd btn-show-passwd" data-passwd="'.$field.'"></i>
                        <input type="password" '.attributes($optionsField).' name="'.$field.'" autocomplete="off" value="'.$value.'">
                        <label for="'.$field.'">'.($label != false ? $label : '').'</label>
                    </div>
                </div>';

    return $component;
}