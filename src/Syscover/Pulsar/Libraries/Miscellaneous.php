<?php namespace Syscover\Pulsar\Libraries;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Syscover\Pulsar\Models\AttachmentMime;

class Miscellaneous
{
    /**
     *  Funtion to set ObjectTrans variable
     *
     * @access  public
     * @param   array   $parameters
     * @param   string  $objectTrans
     * @return  string
     */
    public static function getObjectTransValue($parameters, $objectTrans)
    {
        if(Lang::has($parameters['package'] . '::pulsar.' . $objectTrans))
        {
            return $parameters['package'] . '::pulsar.' . $objectTrans;
        }
        elseif(Lang::has('pulsar::pulsar.' . $objectTrans))
        {
            return 'pulsar::pulsar.' . $objectTrans;
        }
    }

    /**
     *  Funtion to set data atrributes to html tags
     *
     * @access  public
     * @param   array   $data
     * @return  string
     */
    public static function setDataAttributes($data)
    {
        if(is_array($data) && count($data) > 0)
        {
            $keys = array_keys($data);

            $response = '';
            foreach($keys as $key)
            {
                $response .= ' data-' . $key . '="' . $data[$key] . '"';
            }

            return $response;
        }

        return null;
    }

    /**
     *  Funtion to set data atrributes to option html tag
     *
     * @access  public
     * @param   array   $data
     * @param   object  $object
     * @return  string
     */
    public static function setDataOptionAttributes($data, $object)
    {
        if(is_array($data) && count($data) > 0)
        {
            $keys = array_keys($data);

            $response = '';
            foreach($keys as $key)
            {
                $response .= ' data-' . $key . '="' . $object->{$data[$key]} . '"';
            }

            return $response;
        }

        return null;
    }

    /**
     *  Funtion to check if option from select is seleted
     *
     * @access  public
     * @param   mixed   $value
     * @param   mixed   $valueObject
     * @return  boolean
     */
    public static function isSelected($value, $valueObject)
    {
        if(is_array($value) && count($value) > 0)
        {
            return in_array($valueObject, $value);
        }

        if($value instanceof Collection)
        {
            foreach($value as $v)
            {
                if($v->getKey() === $valueObject)
                {
                    return true;
                }
            }
            return false;
        }
        elseif($value === $valueObject)
        {
            return true;
        }
        return false;
    }

    /**
     *  Función que instancia varibles de sesión en caso de realizar búsquedas múltiples rápida desde la vista de tablas
     *
     * @access  public
     * @param   array   $data
     * @return  array
     */
    public static function sessionParamterSetSearchParams($data = [])
    {
        if (Request::input('search_params'))
        {
            session(['search_params' => Request::all()]);
            $data['search_params'] = Request::all();
            session(['cadena' => null]);
        }
        elseif (session('search_params') && Request::input('accion') != 'query')
        {
            //comprobamos que al recoger la variable de la session no se está realizando una busqueda rápida, si es así se estará lanzando la variable Request::input('accion') == 'query
            //y pasaremos de largo.
            $data['search_params'] = session('search_params');
        }
        else
        {
            $data['search_params'] = null;
        }

        return $data;
    }

    /**
     *  Function to upload base64 image
     *
     * @access  public
     * @param   $inputName
     * @param   $path
     * @param   bool $encryption
     * @param   bool $newFilename
     * @return  bool
     */
    public static function base64ImageUpload($inputName, $path, $encryption = false, $newFilename = false)
    {
        $json	    = json_decode(Request::input($inputName));
        $imageData 	= base64_decode(explode(',', $json->data)[1]);
        $nameArray  = explode('.', $json->name);

        $extension	= strtolower($nameArray[1]);
        $basename	= strtolower($nameArray[0]);

        if ($encryption)
        {
            mt_srand();
            $filename = md5(uniqid(mt_rand())) . "." . $extension;
        }
        elseif ($newFilename)
        {
            $filename = $newFilename . "." . $extension;
        }
        else
        {
            $filename = $json->name;
        }

        $i = 0;
        while(file_exists($path . '/' . $filename))
        {
            $i++;
            $filename = $basename . '-' . $i . '.' . $extension;
        }

        $handle	= fopen($path . '/' . $filename, 'w');
        fwrite($handle, $imageData);
        fclose($handle);

        return $filename;
    }

    /**
     *  Función para subir ficheros
     *
     * @access  public
     * @param   $inputName
     * @param   $path
     * @param   bool        $encryption
     * @param   bool        $newFilename
     * @return  bool
     */
    public static function uploadFiles($inputName, $path, $encryption = false, $newFilename = false)
    {
        $file       = Request::file($inputName);
        $extension  = $file->getClientOriginalExtension();
        $basename   = basename($file->getClientOriginalName(), '.' . $extension);

        if ($encryption)
        {
            mt_srand();
            $filename = md5(uniqid(mt_rand())) . "." . $extension;
        }
        elseif ($newFilename)
        {
            $filename = $newFilename . "." . $extension;
        }
        else
        {
            $filename = $file->getClientOriginalName();
        }

        $i = 0;
        while (file_exists($path . '/' . $filename))
        {
            $i++;
            $filename = $basename . '-' . $i . '.' . $extension;
        }

        $file->move($path, $filename);

        return $filename;
    }

    /**
     *  Función para mover ficheros
     *
     * @access  public
     * @param   $path
     * @param   $target
     * @param   $fileName
     * @param   bool    $encryption
     * @param   bool    $newFilename
     * @return  bool
     */
    public static function move($path, $target, $fileName, $encryption = false, $newFilename = false)
    {
        $fileNameOld    = $fileName;
        $extension      = File::extension($fileName);
        $baseName       = basename($fileName, '.' . $extension);

        if ($encryption)
        {
            mt_srand();
            $fileName = md5(uniqid(mt_rand())) . "." . $extension;
        }
        elseif ($newFilename)
        {
            $fileName = $newFilename . "." . $extension;
        }

        $i = 0;
        while (File::exists($target . '/' . $fileName))
        {
            $i++;
            $fileName = $baseName . '-' . $i . '.' . $extension;
        }
        
        File::move($path . '/' . $fileNameOld, $target . '/' . $fileName);

        return $fileName;
    }

    /**
     *  Función para listar directorios
     *
     * @access    public
     * @param $path
     * @return bool
     */
    public static function listDirectory($path)
    {
        if (is_dir($path))
        {
            $directories = array();

            if ($rsc = opendir($path))
            {
                while (($file = readdir($rsc)) !== false)
                {
                    if (is_dir($path . $file) && $file != "." && $file != "..")
                    {
                        array_push($directories, $file);
                    }
                }
                closedir($rsc);
            }
            return $directories;
        }
        return false;
    }

    /**
     * get string with mimes to getfile plugin
     *
     * @param $resource
     * @return null|string
     */
    public static function getMimesAccept($resource)
    {
        $attachmentMimes = AttachmentMime::builder()->where('resource_id_019', $resource)->get();

        $index = 1;
        $mimes = null;
        foreach($attachmentMimes as $attachmentMime)
        {
            $mimes .= "'" . $attachmentMime->mime_019 . "'";

            if($index < count($attachmentMimes))
                $mimes .= ", ";

            $index ++;
        }

        return $mimes;
    }

    /**
     *  Función para rellenar con ceros a la izquierda un número
     *
     * @access    public
     * @param $num
     * @param $zerofill
     * @return bool
     */
    public static function zerofill($num, $zerofill)
    {
        return str_pad($num, $zerofill, '0', STR_PAD_LEFT);
    }

    /**
     *  Función que formatea un texto, pensada para inputs
     *
     * @access  public
     * @param   string    $text
     * @param   bool      $ucwords
     * @param   bool      $strtolower
     * @param   bool      $trim
     * @return  string
     */
    public static function formatText($text, $ucwords= true, $strtolower = true, $trim = true)
    {
        if($strtolower)     $text = mb_strtolower($text, 'UTF-8'); //pone todas en minusculas
        if($ucwords)        $text = mb_convert_case($text, MB_CASE_TITLE, 'UTF-8'); //pone la primera letra en mayusculas
        if($trim)           $text = trim($text); //quita espacios
        
        return $text;
    }

    /**
     *  Function to set config arguments datatable
     *
     * @access  public
     * @param   array $parameters
     * @return  array
     */
    public static function paginateDataTable($parameters = [])
    {
        // Datatable paginate
        $parameters['sStart']   = null;
        $parameters['sLength']  = null;
	    if(Request::input('iDisplayStart') != null && Request::input('iDisplayLength') != '-1')
        {
            $parameters['sStart']   = Request::input('iDisplayStart');
            $parameters['sLength']  = Request::input('iDisplayLength');
        }
        
        return $parameters;
    }

    /**
     *  Function to set config arguments to sorting datatable
     *
     * @access  public
     * @param   array   $args
     * @param   array   $aColumns
     * @return  array
     */
    public static function dataTableSorting($args, $aColumns)
    {
	    $args['sOrder'] = null;
        $args['sTypeOrder'] = null;
        
        if(Request::input('iSortCol_0') != null)
        {
            for($i=0; $i < intval(Request::input('iSortingCols')); $i++)
            {
                if (Request::input('bSortable_' . intval(Request::input('iSortCol_'.$i))) == "true")
                {
                    $args['sOrder']       = is_array($aColumns[intval(Request::input('iSortCol_'.$i))])?  $aColumns[intval(Request::input('iSortCol_'.$i))]['data'] : $aColumns[intval(Request::input('iSortCol_'.$i))];
                    $args['sTypeOrder']   = Request::input('sSortDir_'.$i);
                }
            }
        }
        return $args;
    }

    /**
     *  Function to set arguments to search on datatable
     *
     * @access  public
     * @param   array   $args
     * @return  array
     */
    public static function filteringDataTable($args)
    {
        //filtrado de la tabla
        $args['sWhere'] = null;
        if(Request::input('sSearch') != null && Request::input('sSearch') != "")
        {
                $args['sWhere'] = Request::input('sSearch');
        }
        return $args;
    }

    /**
     *  Function to set arguments to search on datatable
     *
     * @access  public
     * @param   array   $args
     * @param   array   $aColumns
     * @return  array
     */
    public static function individualFilteringDataTable($args, $aColumns)
    {
        $args['sWhereColumns'] = null;
        for($i=0; $i < count($aColumns); $i++)
        {
            if(Request::input('bSearchable_'.$i) != null && Request::input('bSearchable_'.$i) == "true" && Request::input('sSearch_'.$i) != '')
            {
                $sWhereColumn['sWhere']     = Request::input('sSearch_'.$i);
                $sWhereColumn['aColumn']    = is_array($aColumns[$i])? $aColumns[$i]['name'] : $aColumns[$i];

                if(!is_array($args['sWhereColumns']))$args['sWhereColumns'] = array();

                array_push($args['sWhereColumns'], $sWhereColumn);
            }
        }
        return $args;
    }

    /**
     *  Function to set arguments to SQL query
     *
     * @access  public
     * @param   array   $query
     * @param   array   $aColumns
     * @param   array   $sWhere
     * @param   array   $sWhereColumns
     * @return  Illuminate/Database/Eloquent/Builder
     */
    public static function getQueryWhere($query, $aColumns, $sWhere, $sWhereColumns)
    {
        if($sWhere != null)
        {
            $query->where(function($query) use ($aColumns, $sWhere){
                $i=0;
                foreach($aColumns as $aColumn)
                {
                    // check if column if is searchable
                    if(Request::input('bSearchable_'.$i) === 'true')
                    {
                        if(is_array($aColumn)) $aColumn = $aColumn['data'];
                        $query->orWhere($aColumn, 'LIKE', '%'.$sWhere.'%');
                    }
                    $i++;
                }
            });
        }
        
        if($sWhereColumns != null)
        {
            $query->where(function($query) use ($sWhereColumns){
                foreach($sWhereColumns as $sWhereColumn)
                {
                     $query->where($sWhereColumn['aColumn'], 'LIKE', '%'.$sWhereColumn['sWhere'].'%');
                }
           });
        }
        
        return $query;
    }

    /**
     *  Function to format phone
     *
     * @access  public
     * @param   int $numero
     * @return  string
     */
    public static function phoneNumberFormat($numero)
    {
        $numero = str_replace(array(" ", "-"), array(""), $numero);
        $comienzo = strlen($numero);
        $resultado = '';
        while($comienzo>=0)
        {
            $resultado = substr($numero, $comienzo, 3) . " " . $resultado;
            $comienzo -= 3;
        }
        return $resultado;
    }

    /**
     *  Function to etract email from text
     *
     * @access	public
     * @param   string  $text
     * @return	array
     */
    public static function extractEmail($text)
    {
        $matches    = array();
        $pattern	= "/(?:[a-z0-9!#$%&'*+=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/";

        // Devuelve un array de dos dimensiones
        preg_match_all($pattern, $text, $matches); //find matching pattern

        if(count($matches) > 0)
            $matches = $matches[0];

        return $matches;
    }

    /**
     *  Function to search in array
     *
     * @access  public
     * @param   mixed   $needle
     * @param   array   $haystack
     * @param   string  $index
     * @return  boolean
     */
    public static function inArray($needle, $haystack, $index)
    {
        foreach($haystack as $element)
        {
            if($element[$index] == $needle) return true;
        }
        return false;
    }

    /**
     *  Function to convert php date format to moment.js format
     *
     * @access  public
     * @param   string  $format
     * @return  string
     */
    public static function convertFormatDate($format)
    {
        $replacements = [
            'd' => 'DD',
            'D' => 'ddd',
            'j' => 'D',
            'l' => 'dddd',
            'N' => 'E',
            'S' => 'o',
            'w' => 'e',
            'z' => 'DDD',
            'W' => 'W',
            'F' => 'MMMM',
            'm' => 'MM',
            'M' => 'MMM',
            'n' => 'M',
            't' => '', // no equivalent
            'L' => '', // no equivalent
            'o' => 'YYYY',
            'Y' => 'YYYY',
            'y' => 'YY',
            'a' => 'a',
            'A' => 'A',
            'B' => '', // no equivalent
            'g' => 'h',
            'G' => 'H',
            'h' => 'hh',
            'H' => 'HH',
            'i' => 'mm',
            's' => 'ss',
            'u' => 'SSS',
            'e' => 'zz', // deprecated since version 1.6.0 of moment.js
            'I' => '', // no equivalent
            'O' => '', // no equivalent
            'P' => '', // no equivalent
            'T' => '', // no equivalent
            'Z' => '', // no equivalent
            'c' => '', // no equivalent
            'r' => '', // no equivalent
            'U' => 'X',
        ];

        $momentFormat = strtr($format, $replacements);

        return $momentFormat;
    }


    public static function  generateRandomString($length = 10, $type = null)
    {
        switch ($type) {
            case 'uppercase':
                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'lowercase':
                $characters = 'abcdefghijklmnopqrstuvwxyz';
                break;
            case 'uppercase-number':
                $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'lowercase-number':
                $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
                break;
            default:
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
        }

        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * convert a array to object
     *
     * @param $array
     * @return stdClass
     */
    public static function arrayToObject($array)
    {
        $obj = new \stdClass();
        foreach($array as $k => $v)
        {
            if(strlen($k))
            {
                if(is_array($v) && count($v) > 0)
                {
                    // recursive
                    $instance = new static;
                    $obj->{$k} = $instance->arrayToObject($v);
                }
                else
                {
                    $obj->{$k} = $v;
                }
            }
        }
        return $obj;
    }
}