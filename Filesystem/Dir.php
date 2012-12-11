<?php
/**
 * Here're your description about this file and its function
 *
 * @version			$Id: Dir.php Jul 27, 2010 2:58:56 PM$
 * @category		Kernel
 * @package			Kernel Package
 * @subpackage		App_Filesystem_Dir
 * @license			http://xgoon.com
 * @copyright		Copyright (c) 2005-2011 XGOON MEDIA
 * @author			toan@xgoon.com (toan)
 * @implements		Toan LE
 * @file			Dir.php
 *
 */


class App_Filesystem_Dir
{

    /**
     * Checks if a path's permissions can be changed
     *
     * @param	string	$path	Path to check
     * @return	boolean	True if path can have mode changed
     */
    public static function canChmod($path)
    {
        $perms = fileperms ( $path );
        if ($perms !== false)
        {
            if (@ chmod ( $path, $perms ^ 0001 ))
            {
                @chmod ( $path, $perms );
                return true;
            }
        }
        return false;
    }
    /**
     * Chmods files and directories recursivly to given permissions
     *
     * @param	string	$path		Root path to begin changing mode [without trailing slash]
     * @param	string	$filemode	Octal representation of the value to change file mode to [null = no change]
     * @param	string	$foldermode	Octal representation of the value to change folder mode to [null = no change]
     * @return	boolean	True if successful [one fail means the whole operation failed]
     */
    public static function setPermissions($path, $filemode = '0644', $foldermode = '0755')
    {

        // Initialize return value
        $ret = true;

        if (is_dir ( $path ))
        {
            $dh = opendir ( $path );
            while ( $file = readdir ( $dh ) )
            {
                if ($file != '.' && $file != '..')
                {
                    $fullpath = $path . '/' . $file;
                    if (is_dir ( $fullpath ))
                    {
                        if (! App_Filesystem_Dir::setPermissions ( $fullpath, $filemode, $foldermode ))
                        {
                            $ret = false;
                        }
                    }
                    else
                    {
                        if (isset ( $filemode ))
                        {
                            if (! @ chmod ( $fullpath, octdec ( $filemode ) ))
                            {
                                $ret = false;
                            }
                        }
                    } // if
                } // if
            } // while
            closedir ( $dh );
            if (isset ( $foldermode ))
            {
                if (! @ chmod ( $path, octdec ( $foldermode ) ))
                {
                    $ret = false;
                }
            }
        }
        else
        {
            if (isset ( $filemode ))
            {
                $ret = @ chmod ( $path, octdec ( $filemode ) );
            }
        } // if
        return $ret;
    }

    /**
     * Get the permissions of the file/folder at a give path
     *
     * @param	string	$path	The path of a file/folder
     * @return	string	Filesystem permissions
     */
    public static function getPermissions($path)
    {
        $path = App_Filesystem_Dir::clean ( $path );
        $mode = @ decoct ( @ fileperms ( $path ) & 0777 );

        if (strlen ( $mode ) < 3)
        {
            return '---------';
        }
        $parsed_mode = '';
        for($i = 0; $i < 3; $i ++)
        {
            // read
            $parsed_mode .= ($mode {$i} & 04) ? "r" : "-";
            // write
            $parsed_mode .= ($mode {$i} & 02) ? "w" : "-";
            // execute
            $parsed_mode .= ($mode {$i} & 01) ? "x" : "-";
        }
        return $parsed_mode;
    }
    /**
     * Function to strip additional / or \ in a path name
     *
     * @static
     * @param	string	$path	The path to clean
     * @param	string	$ds		Directory separator (optional)
     * @return	string	The cleaned path
     */
    public static function clean($path, $ds = DS)
    {
        $path = trim ( $path );

        if (empty ( $path ))
        {
            $path = BASE_PATH;
        }
        else
        {
            // Remove double slashes and backslahses and convert all slashes and backslashes to DS
            $path = preg_replace ( '#[/\\\\]+#', $ds, $path );
        }

        return $path;
    }

    /**
     * returns the directories in the path
     * if append path is set then this path will appended to the results
     *
     * @param string $path
     * @param string $appendPath
     * @return array
     */
    public static function getDirectories($path, $appendPath = false)
    {
        if (is_dir ( $path ))
        {
            $contents = scandir ( $path ); //open directory and get contents
            if (is_array ( $contents ))
            { //it found files
                $returnDirs = false;
                foreach ( $contents as $dir )
                {
                    //validate that this is a directory
                    if (is_dir ( $path . '/' . $dir ) && $dir != '.' && $dir != '..' && $dir != '.svn')
                    {
                        $returnDirs [] = $appendPath . $dir;
                    }
                }
                if ($returnDirs)
                {
                    return $returnDirs;
                }
            }
        }
    }

    /**
     * this is getting a little extreme i know
     * but it will help out later when we want to keep updated indexes
     * for right now, not much
     *
     * @param unknown_type $path
     */
    public static function make($path)
    {
        return @mkdir ( $path, 0755 );
    }

    /**
     * adds a complete directory path
     * eg: /my/own/path
     * will create
     * >my
     * >>own
     * >>>path
     *
     * @param string $base
     * @param string $path
     */
    public static function makeRecursive($base, $path)
    {
        $pathArray = explode ( '/', $path );
        if (is_array ( $pathArray ))
        {
            $strPath = null;
            foreach ( $pathArray as $path )
            {
                if (! empty ( $path ))
                {
                    $strPath .= '/' . $path;
                    if (! is_dir ( $base . $strPath ))
                    {
                        if (! self::make ( $base . $strPath ))
                        {
                            return false;
                        }
                    }
                }
            }
            return true;
        }
    }

    /**
     * renames a directory
     *
     * @param string $source
     * @param string $newName
     */
    public static function rename($source, $newName)
    {
        if (is_dir ( $source ))
        {
            return rename ( $source, $newName );
        }
    }

    /**
     * copies a directory recursively
     * if you want to move the directory then follow this with deleteRecursive()...
     * @param string $source
     * @param string $target
     */
    public static function copyRecursive($source, $target)
    {
        if (is_dir ( $source ))
        {
            @mkdir ( $target );

            $d = dir ( $source );

            while ( false !== ($entry = $d->read ()) )
            {
                if ($entry == '.' || $entry == '..')
                {
                    continue;
                }

                $Entry = $source . '/' . $entry;
                if (is_dir ( $Entry ))
                {
                    App_Filesystem_Directory_Writer::copyRecursive ( $Entry, $target . '/' . $entry );
                    continue;
                }
                copy ( $Entry, $target . '/' . $entry );
            }

            $d->close ();
        }
        else
        {
            copy ( $source, $target );
        }
    }

    /**
     * deletes a directory recursively
     *
     * @param string $target
     * @param bool $verbose
     * @return bool
     */
    public static function deleteRecursive($target, $verbose = false)
    {
        $exceptions = array (
            '.', 
            '..' );
        if (! $sourcedir = @opendir ( $target ))
        {
            if ($verbose)
            {
                echo '<strong>Couldn&#146;t open ' . $target . "</strong><br />\n";
            }
            return false;
        }
        while ( false !== ($sibling = readdir ( $sourcedir )) )
        {
            if (! in_array ( $sibling, $exceptions ))
            {
                $object = str_replace ( '//', '/', $target . '/' . $sibling );
                if ($verbose)
                echo 'Processing: <strong>' . $object . "</strong><br />\n";
                if (is_dir ( $object ))
                App_Filesystem_Dir::deleteRecursive ( $object );
                if (is_file ( $object ))
                {
                    $result = @unlink ( $object );
                    if ($verbose && $result)
                    echo "File has been removed<br />\n";
                    if ($verbose && (! $result))
                    echo "<strong>Couldn&#146;t remove file</strong>";
                }
            }
        }
        closedir ( $sourcedir );

        if ($result = @rmdir ( $target ))
        {
            if ($verbose)
            {
                echo "Target directory has been removed<br />\n";
                return true;
            }
        }
        else
        {
            if ($verbose)
            {
                echo "<strong>Couldn&#146;t remove target directory</strong>";
                return false;
            }
        }
    }

    /**
     * Searches the directory paths for a given file.
     *
     * @access	protected
     * @param	array|string	$path	An path or array of path to search in
     * @param	string	$file	The file name to look for.
     * @return	mixed	The full path and file name for the target file, or boolean false if the file is not found in any of the paths.
     */
    public static function find($paths, $file)
    {
        settype ( $paths, 'array' ); //force to array


        // start looping through the path set
        foreach ( $paths as $path )
        {
            // get the path to the file
            $fullname = $path . DS . $file;

            // is the path based on a stream?
            if (strpos ( $path, '://' ) === false)
            {
                // not a stream, so do a realpath() to avoid directory
                // traversal attempts on the local file system.
                $path = realpath ( $path ); // needed for substr() later
                $fullname = realpath ( $fullname );
            }

            // the substr() check added to make sure that the realpath()
            // results in a directory registered so that
            // non-registered directores are not accessible via directory
            // traversal attempts.
            if (file_exists ( $fullname ) && substr ( $fullname, 0, strlen ( $path ) ) == $path)
            {
                return $fullname;
            }
        }

        // could not find the file in the set of paths
        return false;
    }
}