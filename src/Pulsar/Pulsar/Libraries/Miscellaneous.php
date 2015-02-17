<?php namespace Pulsar\Pulsar\Libraries;

/**
 * @package		Pulsar
 * @author		Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2014, SYSCOVER, SL.
 * @license		
 * @link		http://www.syscover.com
 * @since		Version 1.0
 * @filesource  Librarie that instance helper functions
 */

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;

class Miscellaneous
{
    /**
     *  Función que instancia la vista que se verá para acciones que nos interese, como menus, o reset de variable de sesión de cadena, etc.
     *
     * @access    public
     * @param $page
     */
    public static function setParameterSessionPage($page)
    {
        if (Session::get('page') != $page)
        {
            Session::put('page', $page);
        }
    }

    /**
     *  Funciónes para manejar el menu.
     *  En cada controller establecemos en que página estamos con la función Miscellaneous::sessionParamterSetPage(), en el menu simplemente dependiendo en que
     *  página nos envíe el cotroller, configura el menú de la forma apropiada
     *
     * @access    public
     * @param $pages
     * @return string
     */
    public static function setDisplayPage($pages)
    {
        if (in_array(Session::get('page'), $pages))
            return ' style="display: block;"';
    }
    
    public static function setOpenPage($pages)
    {
        if (in_array(Session::get('page'), $pages))
            return ' class="open"';
    }
    
    public static function setOpenDefaultPage($pages)
    {
        if (in_array(Session::get('page'), $pages))
            return ' class="open"';
    }
    
    public static function setCurrentOpenPage($pages)
    {
        if (in_array(Session::get('page'), $pages))
           return ' class="current open"';
    }
    
    public static function setCurrentPage($page)
    {
        if(is_array($page))
        {
            if (in_array(Session::get('page'), $page))
            {
                return ' class="current"';
            }
        }
        else
        {
            if (Session::get('page') == $page)
            {
                return ' class="current"';
            }
        }
    }

    /**
     *  Función que instancia varibles de sesión en caso de realizar búsquedas múltiples rápida desde la vista de tablas
     *
     * @access    public
     * @param array $data
     * @return array
     */
    public static function sessionParamterSetSearchParams($data = [])
    {
        if (Input::get('search_params'))
        {
            Session::put('search_params', Input::all());
            $data['search_params'] = Input::all();
            Session::put('cadena', null);
        }
        elseif (Session::get('search_params') && Input::get('accion') != 'query')
        {
            //comprobamos que al recoger la variable de la session no se está realizando una busqueda rápida, si es así se estará lanzando la variable Input::get('accion') == 'query
            //y pasaremos de largo.
            $data['search_params'] = Session::get('search_params');
        }
        else
        {
            $data['search_params'] = null;
        }

        return $data;
    }

    /**
     *  Función para identificar si un registro está creado en un idioma en concreto
     *
     * @access    public
     * @param $id
     * @param $idioma
     * @param $resultados
     * @param $parametroIdioma
     * @param $parametroId
     * @return array
     */
    public static function isCreateLanguage($id, $idioma, $resultados, $parametroIdioma, $parametroId)
    {
        $arrayResultados = $resultados->toArray();

        foreach ($arrayResultados as $resultado)
        {
            if ($resultado[$parametroId] == $id && $resultado[$parametroIdioma] == $idioma)
            {
                return false;
            }
        }
        return true;
    }

    /**
     *  Función que devuelve en arrays los parametros de los objetos que le pasemos
     *
     * @access    public
     * @param $collection
     * @param $idName
     * @return array
     */
     public static function getIdsCollection($collection, $idName)
     {
        $ids = array();
        $arrayCollection = $collection->toArray();
        foreach ($arrayCollection as $object)
        {
            array_push($ids, $object[$idName]);
        }
        return $ids;
    }

    /**
     *  Función para subir ficheros
     *
     * @access    public
     * @param $inputName
     * @param $path
     * @param bool $encryption
     * @param bool $newFilename
     * @return bool
     */
    public static function uploadFiles($inputName, $path, $encryption = false, $newFilename = false)
    {
        $file       = Input::file($inputName);
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
     * @access    public
     * @param $path
     * @param $target
     * @param $fileName
     * @param bool $encryption
     * @param bool $newFilename
     * @return bool
     */
    public static function move($path, $target, $fileName, $encryption = false, $newFilename = false)
    {
        $fileNameOld    = $fileName;
        $extension      = File::extension($fileName);
        $baseName       = basename($fileName,'.'.$extension);

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
     *  Función para obtener la IP real de un cliente
     *
     * @access	public
     * @return	string
     */
    public static function getRealIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     *  Función para obtener el pais del cliente según su ip
     *
     * @access    public
     * @param $ip
     * @return string
     */
    public static function getCountryIp($ip)
    {
        $apiCountry = 'http://api.hostip.info/?ip=' . $ip;
        //$apiCountry = 'http://api.hostip.info/?ip=87.106.139.27';
        
        $xml = new \SimpleXMLElement($apiCountry, LIBXML_NOCDATA, true);
        
        $countries = $xml->xpath('//countryAbbrev');
        return strtolower($countries[0]);
    }

    /*
      determine which language out of an available set the user prefers most

      $available_languages        array with language-tag-strings (must be lowercase) that are available
      $http_accept_language    a HTTP_ACCEPT_LANGUAGE string (read from $_SERVER['HTTP_ACCEPT_LANGUAGE'] if left out)
    */
    /**
     *  Determine which language out of an available set the user prefers most
     *  $available_languages, array with language-tag-strings (must be lowercase) that are available
     *  $http_accept_language, a HTTP_ACCEPT_LANGUAGE string (read from $_SERVER['HTTP_ACCEPT_LANGUAGE'] if left out)
     *
     * @access    public
     * @param   array $available_languages
     * @param array|string $http_accept_language
     * @return string
     */
    public static function preferedLanguage ($available_languages, $http_accept_language = "auto")
    {
        // if $http_accept_language was left out, read it from the HTTP-Header
        if ($http_accept_language == "auto") $http_accept_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';

        // standard  for HTTP_ACCEPT_LANGUAGE is defined under
        // http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4
        // pattern to find is therefore something like this:
        //    1#( language-range [ ";" "q" "=" qvalue ] )
        // where:
        //    language-range  = ( ( 1*8ALPHA *( "-" 1*8ALPHA ) ) | "*" )
        //    qvalue         = ( "0" [ "." 0*3DIGIT ] )
        //            | ( "1" [ "." 0*3("0") ] )
        preg_match_all("/([[:alpha:]]{1,8})(-([[:alpha:]|-]{1,8}))?" .
            "(\s*;\s*q\s*=\s*(1\.0{0,3}|0\.\d{0,3}))?\s*(,|$)/i",
            $http_accept_language, $hits, PREG_SET_ORDER);

        // default language (in case of no hits) is the first in the array
        $bestlang = $available_languages[0];
        $bestqval = 0;

        foreach ($hits as $arr) {
            // read data from the array of this hit
            $langprefix = strtolower ($arr[1]);
            if (!empty($arr[3])) {
                $langrange = strtolower ($arr[3]);
                $language = $langprefix . "-" . $langrange;
            }
            else $language = $langprefix;
            $qvalue = 1.0;
            if (!empty($arr[5])) $qvalue = floatval($arr[5]);

            // find q-maximal language
            if (in_array($language,$available_languages) && ($qvalue > $bestqval)) {
                $bestlang = $language;
                $bestqval = $qvalue;
            }
            // if no direct hit, try the prefix only but decrease q-value by 10% (as http_negotiate_language does)
            else if (in_array($langprefix,$available_languages) && (($qvalue*0.9) > $bestqval)) {
                $bestlang = $langprefix;
                $bestqval = $qvalue*0.9;
            }
        }
        return $bestlang;
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
     * @param   array $args
     * @return  array
     */
    public static function paginateDataTable($args = [])
    {
        // Datatable paginate
        $args['sStart'] = null;
        $args['sLength'] = null;
	    if(Input::get('iDisplayStart') != null && Input::get('iDisplayLength') != '-1' )
        {
            $args['sStart']   = Input::get('iDisplayStart');
            $args['sLength']  = Input::get('iDisplayLength');
        }
        
        return $args;
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
        if(Input::get('iSortCol_0')!=null)
        {
            for($i=0; $i < intval(Input::get('iSortingCols')); $i++)
            {
                if (Input::get('bSortable_'.intval(Input::get('iSortCol_'.$i))) == "true")
                {
                    $args['sOrder']       = is_array($aColumns[intval(Input::get('iSortCol_'.$i))])?  $aColumns[intval(Input::get('iSortCol_'.$i))]['name'] : $aColumns[intval(Input::get('iSortCol_'.$i))];
                    $args['sTypeOrder']   = Input::get('sSortDir_'.$i);
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
        if(Input::get('sSearch') != null && Input::get('sSearch') != "")
        {
                $args['sWhere'] = Input::get('sSearch');
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
            if(Input::get('bSearchable_'.$i) != null && Input::get('bSearchable_'.$i) == "true" && Input::get('sSearch_'.$i) != '')
            {
                $sWhereColumn['sWhere']     = Input::get('sSearch_'.$i);
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
                foreach($aColumns as $aColumn)
                {
                    if(is_array($aColumn)) $aColumn = $aColumn['name'];
                    $query->orWhere($aColumn, 'LIKE', '%'.$sWhere.'%');
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
        {
            $matches = $matches[0];
        }

        return $matches;
    }
}