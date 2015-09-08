<?php

/**
 * @package    App.Platform
 * @subpackage  FileSystem
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * A Path handling class
 *
 * @package    App.Platform
 * @subpackage  FileSystem
 * @since       11.1
 */
class App_Filesystem_Folder {

    /**
     * Wrapper for the standard file_exists function
     *
     * @param   string  $path  Folder name relative to installation dir
     *
     * @return  boolean  True if path is a folder
     *
     * @since   11.1
     */
    public static function exists($path) {
        return is_dir(App_Filesystem_Path::clean($path));
    }

    /**
     * Checks if a path's permissions can be changed
     *
     * @param	string	$path	Path to check
     * @return	boolean	True if path can have mode changed
     */
    public static function canChmod($path) {
        $perms = fileperms($path);
        if ($perms !== false) {
            if (@ chmod($path, $perms ^ 0001)) {
                @chmod($path, $perms);
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
    public static function setPermissions($path, $filemode = '0644', $foldermode = '0755') {

        // Initialize return value
        $ret = true;

        if (is_dir($path)) {
            $dh = opendir($path);
            while ($file = readdir($dh)) {
                if ($file != '.' && $file != '..') {
                    $fullpath = $path . '/' . $file;
                    if (is_dir($fullpath)) {
                        if (!App_Filesystem_Folder::setPermissions($fullpath, $filemode, $foldermode)) {
                            $ret = false;
                        }
                    } else {
                        if (isset($filemode)) {
                            if (!@ chmod($fullpath, octdec($filemode))) {
                                $ret = false;
                            }
                        }
                    } // if
                } // if
            } // while
            closedir($dh);
            if (isset($foldermode)) {
                if (!@ chmod($path, octdec($foldermode))) {
                    $ret = false;
                }
            }
        } else {
            if (isset($filemode)) {
                $ret = @ chmod($path, octdec($filemode));
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
    public static function getPermissions($path) {
        $path = App_Filesystem_Folder::clean($path);
        $mode = @ decoct(@ fileperms($path) & 0777);

        if (strlen($mode) < 3) {
            return '---------';
        }
        $parsed_mode = '';
        for ($i = 0; $i < 3; $i ++) {
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
    public static function clean($path, $ds = DS) {
        $path = trim($path);

        if (empty($path)) {
            $path = PATH_PROJECT;
        } else {
            // Remove double slashes and backslahses and convert all slashes and backslashes to DS
            $path = preg_replace('#[/\\\\]+#', $ds, $path);
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
    public static function getDirectories($path, $appendPath = false) {
        if (is_dir($path)) {
            $contents = scandir($path); //open directory and get contents
            if (is_array($contents)) { //it found files
                $returnDirs = false;
                foreach ($contents as $dir) {
                    //validate that this is a directory
                    if (is_dir($path . '/' . $dir) && $dir != '.' && $dir != '..' && $dir != '.svn') {
                        $returnDirs [] = $appendPath . $dir;
                    }
                }
                if ($returnDirs) {
                    return $returnDirs;
                }
            }
        }
    }

    /**
     * Create a folder -- and all necessary parent folders.
     *
     * @param   string   $path  A path to create from the base path.
     * @param   integer  $mode  Directory permissions to set for folders created. 0755 by default.
     *
     * @return  boolean  True if successful.
     *
     * @since   11.1
     */
    public static function create($path = '', $mode = 0755) {
        static $nested = 0;
        //if (Zend_Registry::isRegistered('logger')):
         //   $logger = Zend_Registry::get('logger');
        //endif;

        // Check to make sure the path valid and clean
        $path = App_Filesystem_Path::clean($path);

        // Check if parent dir exists
        $parent = dirname($path);

        if (!self::exists($parent)) {
            // Prevent infinite loops!
            $nested++;

            if (($nested > 20) || ($parent == $path)) {
                //$logger->getLog('genmodel')->log(__METHOD__ . ': Infinite loop detected', Zend_Log::WARN);
                $nested--;

                return false;
            }

            // Create the parent directory
            if (self::create($parent, $mode) !== true) {
                // JFolder::create throws an error
                $nested--;

                return false;
            }

            // OK, parent directory has been created
            $nested--;
        }

        // Check if dir already exists
        if (self::exists($path)) {
            return true;
        }

        // Check for safe mode
        // We need to get and explode the open_basedir paths
        $obd = ini_get('open_basedir');

        // If open_basedir is set we need to get the open_basedir that the path is in
        if ($obd != null) {
            if (IS_WIN) {
                $obdSeparator = ";";
            } else {
                $obdSeparator = ":";
            }

            // Create the array of open_basedir paths
            $obdArray = explode($obdSeparator, $obd);
            $inBaseDir = false;

            // Iterate through open_basedir paths looking for a match
            foreach ($obdArray as $test) {
                $test = App_Filesystem_Path::clean($test);

                if (strpos($path, $test) === 0) {
                    $inBaseDir = true;
                    break;
                }
            }
            if ($inBaseDir == false) {
                // Return false for JFolder::create because the path to be created is not in open_basedir
                //$logger->getLog('genmodel')->log(__METHOD__ . ': Path not in open_basedir paths', Zend_Log::WARN);

                return false;
            }
        }

        // First set umask
        $origmask = @umask(0);

        // Create the path
        if (!$ret = @mkdir($path, $mode)) {
            @umask($origmask);
           // $logger->getLog('genmodel')->log(__METHOD__ . ': Could not create directory ' . 'Path: ' . $path, Zend_Log::WARN);

            return false;
        }

        // Reset umask
        @umask($origmask);

        return $ret;
    }

    /**
     * Copy a folder.
     *
     * @param   string   $src          The path to the source folder.
     * @param   string   $dest         The path to the destination folder.
     * @param   string   $path         An optional base path to prefix to the file names.
     * @param   boolean  $force        Force copy.
     * @param   boolean  $use_streams  Optionally force folder/file overwrites.
     *
     * @return  boolean  True on success.
     *
     * @since   11.1
     * @throws  RuntimeException
     */
    public static function copy($src, $dest, $path = '', $force = false, $use_streams = false) {
        @set_time_limit(ini_get('max_execution_time'));
        //if (Zend_Registry::isRegistered('logger')):
          //  $logger = Zend_Registry::get('logger');
        //endif;

        if ($path) {
            $src = JPath::clean($path . '/' . $src);
            $dest = JPath::clean($path . '/' . $dest);
        }

        // Eliminate trailing directory separators, if any
        $src = rtrim($src, DIRECTORY_SEPARATOR);
        $dest = rtrim($dest, DIRECTORY_SEPARATOR);

        if (!self::exists($src)) {
            throw new RuntimeException('Source folder not found', -1);
        }
        if (self::exists($dest) && !$force) {
            throw new RuntimeException('Destination folder not found', -1);
        }

        // Make sure the destination exists
        if (!self::create($dest)) {
            throw new RuntimeException('Cannot create destination folder', -1);
        }

        if (!($dh = @opendir($src))) {
            throw new RuntimeException('Cannot open source folder', -1);
        }
        // Walk through the directory copying files and recursing into folders.
        while (($file = readdir($dh)) !== false) {
            $sfid = $src . '/' . $file;
            $dfid = $dest . '/' . $file;

            switch (filetype($sfid)) {
                case 'dir':
                    if ($file != '.' && $file != '..') {
                        $ret = self::copy($sfid, $dfid, null, $force, $use_streams);

                        if ($ret !== true) {
                            return $ret;
                        }
                    }
                    break;

                case 'file':
                    if ($use_streams) {
                        $stream = JFactory::getStream();

                        if (!$stream->copy($sfid, $dfid)) {
                            throw new RuntimeException('Cannot copy file: ' . $stream->getError(), -1);
                        }
                    } else {
                        if (!@copy($sfid, $dfid)) {
                            throw new RuntimeException('Copy file failed', -1);
                        }
                    }
                    break;
            }
        }
        return true;
    }

    /**
     * renames a directory
     *
     * @param string $source
     * @param string $newName
     */
    public static function rename($source, $newName) {
        if (is_dir($source)) {
            return rename($source, $newName);
        }
    }

    /**
     * deletes a directory recursively
     *
     * @param string $target
     * @param bool $verbose
     * @return bool
     */
    public static function deleteRecursive($target, $verbose = false) {
        $exceptions = array(
            '.',
            '..');
        if (!$sourcedir = @opendir($target)):
            if ($verbose):
                die('Couldn&#146;t open ' . $target);
            endif;
            return false;
        endif;

        while (false !== ($sibling = readdir($sourcedir))):
            if (!in_array($sibling, $exceptions)) :
                $object = str_replace('//', '/', $target . '/' . $sibling);
                if ($verbose):
                    echo 'Processing: <strong>' . $object . "</strong><br />\n";
                endif;

                if (is_dir($object)):
                    App_Filesystem_Folder::deleteRecursive($object);
                endif;

                if (is_file($object)):
                    $result = @unlink($object);
                    if ($verbose && $result):
                        echo "File has been removed<br />\n";
                    endif;

                    if ($verbose && (!$result)):
                        die('Couldn&#146;t remove file');
                    endif;
                endif;
            endif;
        endwhile;
        closedir($sourcedir);

        if ($result = @rmdir($target)):
            if ($verbose):
                echo "Target directory has been removed<br />\n";
                return true;
            endif;
        else:
            if ($verbose):
                die('Couldn&#146;t remove target directory');
                return false;
            endif;
        endif;
    }

    /**
     * Searches the directory paths for a given file.
     *
     * @access	protected
     * @param	array|string	$path	An path or array of path to search in
     * @param	string	$file	The file name to look for.
     * @return	mixed	The full path and file name for the target file, or boolean false if the file is not found in any of the paths.
     */
    public static function find($paths, $file) {
        settype($paths, 'array'); //force to array
        // start looping through the path set
        foreach ($paths as $path) {
            // get the path to the file
            $fullname = $path . DS . $file;

            // is the path based on a stream?
            if (strpos($path, '://') === false) {
                // not a stream, so do a realpath() to avoid directory
                // traversal attempts on the local file system.
                $path = realpath($path); // needed for substr() later
                $fullname = realpath($fullname);
            }

            // the substr() check added to make sure that the realpath()
            // results in a directory registered so that
            // non-registered directores are not accessible via directory
            // traversal attempts.
            if (file_exists($fullname) && substr($fullname, 0, strlen($path)) == $path) {
                return $fullname;
            }
        }

        // could not find the file in the set of paths
        return false;
    }

}
