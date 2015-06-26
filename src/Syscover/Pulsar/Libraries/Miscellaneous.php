<?php namespace Syscover\Pulsar\Libraries;

/**
 * @package		Pulsar
 * @author		Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2014, SYSCOVER, SL.
 * @license		
 * @link		http://www.syscover.com
 * @since		Version 1.0
 * @filesource  Librarie that instance helper functions
 */

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;

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
                if($v->getKey() == $valueObject)
                {
                    return true;
                }
            }
            return false;
        }
        elseif($value == $valueObject)
        {
            return true;
        }
        return false;
    }

    /**
     *  Función que instancia la vista que se verá para acciones que nos interese, como menus, o reset de variable de sesión de cadena, etc.
     *
     * @access    public
     * @param $page
     */
    public static function setParameterSessionPage($page)
    {
        if (session('page') != $page)
        {
            session(['page' => $page]);
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
        if (in_array(session('page'), $pages))
            return ' style="display: block;"';
    }
    
    public static function setOpenPage($pages)
    {
        if (in_array(session('page'), $pages))
            return ' class="open"';
    }
    
    public static function setOpenDefaultPage($pages)
    {
        if (in_array(session('page'), $pages))
            return ' class="open"';
    }
    
    public static function setCurrentOpenPage($pages)
    {
        if (in_array(session('page'), $pages))
           return ' class="current open"';
    }
    
    public static function setCurrentPage($page)
    {
        if(is_array($page))
        {
            if (in_array(session('page'), $page))
            {
                return ' class="current"';
            }
        }
        else
        {
            if (session('page') == $page)
            {
                return ' class="current"';
            }
        }
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
        $parameters['sStart'] = null;
        $parameters['sLength'] = null;
	    if(Request::input('iDisplayStart') != null && Request::input('iDisplayLength') != '-1' )
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
        if(Request::input('iSortCol_0')!=null)
        {
            for($i=0; $i < intval(Request::input('iSortingCols')); $i++)
            {
                if (Request::input('bSortable_'.intval(Request::input('iSortCol_'.$i))) == "true")
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
        {
            $matches = $matches[0];
        }

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
}