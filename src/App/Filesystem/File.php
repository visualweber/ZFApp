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
class App_Filesystem_File {

    /**
     * Wrapper for the standard file_exists function
     *
     * @param   string  $file  File path
     *
     * @return  boolean  True if path is a file
     *
     * @since   11.1
     */
    public static function exists($file) {
        return is_file(App_Filesystem_Path::clean($file));
    }

    /**
     * Returns the name, without any path.
     *
     * @param   string  $file  File path
     *
     * @return  string  filename
     *
     * @since   11.1
     * @deprecated  13.3 (Platform) & 4.0 (CMS) - Use basename() instead.
     */
    public static function getName($file) {
        if (Zend_Registry::isRegistered('logger')):
            $logger = Zend_Registry::get('logger');
        endif;
        $logger->getLog('filesystem')->log(__METHOD__ . ' is deprecated. Use native basename() syntax.', Zend_Log::WARN);
        // Convert back slashes to forward slashes
        $file = str_replace('\\', '/', $file);
        $slash = strrpos($file, '/');

        if ($slash !== false) {
            return substr($file, $slash + 1);
        } else {
            return $file;
        }
    }

    /**
     * Makes file name safe to use
     *
     * @param   string  $file  The name of the file [not full path]
     *
     * @return  string  The sanitised string
     *
     * @since   11.1
     */
    public static function makeSafe($file) {
        // Remove any trailing dots, as those aren't ever valid file names.
        $file = rtrim($file, '.');

        $regex = array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#');

        return trim(preg_replace($regex, '', $file));
    }

    /**
     * Copies a file
     *
     * @param   string   $src          The path to the source file
     * @param   string   $dest         The path to the destination file
     * @param   string   $path         An optional base path to prefix to the file names
     * @param   boolean  $use_streams  True to use streams
     *
     * @return  boolean  True on success
     *
     * @since   11.1
     */
    public static function copy($src, $dest, $path = null, $use_streams = false) {
        // Prepend a base path if it exists
        if ($path) {
            $src = App_Filesystem_Path::clean($path . '/' . $src);
            $dest = App_Filesystem_Path::clean($path . '/' . $dest);
        }
        if (Zend_Registry::isRegistered('logger')):
            $logger = Zend_Registry::get('logger');
        endif;

        // If the parent folder doesn't exist we must create it
        if (!file_exists(dirname($dest))) {
            App_Filesystem_Folder::create(dirname($dest));
        }
        // Check src path
        if (!is_readable($src)) {
            $logger->getLog('filesystem')->log(sprintf(__METHOD__ . ': :copy: Cannot find or read file: $%s', $src), Zend_Log::WARN);
            return false;
        }

        if ($use_streams) {
            $stream = new App_Filesystem_Stream();
            if (!$stream->copy($src, $dest)) {
                $logger->getLog('filesystem')->log(sprintf(__METHOD__ . ': :copy(%1$s, %2$s): %3$s', $src, $dest, $stream->getError()), Zend_Log::WARN);
                return false;
            }

            return true;
        } else {
            if (!@ copy($src, $dest)) {
                $logger->getLog('filesystem')->log(sprintf(__METHOD__ . ': :copy(%1$s, %2$s): Copy failed.', $src, $dest), Zend_Log::WARN);
                return false;
            }
            return true;
        }
    }

    /**
     * Read the contents of a file
     *
     * @param   string   $filename   The full file path
     * @param   boolean  $incpath    Use include path
     * @param   integer  $amount     Amount of file to read
     * @param   integer  $chunksize  Size of chunks to read
     * @param   integer  $offset     Offset of the file
     *
     * @return  mixed  Returns file contents or boolean False if failed
     *
     * @since   11.1
     * @deprecated  13.3 (Platform) & 4.0 (CMS) - Use the native file_get_contents() instead.
     */
    public static function read($filename, $incpath = false, $amount = 0, $chunksize = 8192, $offset = 0) {

        if (Zend_Registry::isRegistered('logger')):
            $logger = Zend_Registry::get('logger');
        endif;
        $logger->getLog('filesystem')->log(__METHOD__ . ' is deprecated. Use native file_get_contents() syntax.', Zend_Log::WARN);
        $data = null;

        if ($amount && $chunksize > $amount) {
            $chunksize = $amount;
        }

        if (false === $fh = fopen($filename, 'rb', $incpath)) {
            $logger->getLog('filesystem')->log(sprintf(__METHOD__ . ': :read: Unable to open file: %s', $filename), Zend_Log::WARN);
            return false;
        }

        clearstatcache();

        if ($offset) {
            fseek($fh, $offset);
        }

        if ($fsize = @ filesize($filename)) {
            if ($amount && $fsize > $amount) {
                $data = fread($fh, $amount);
            } else {
                $data = fread($fh, $fsize);
            }
        } else {
            $data = '';

            /*
             * While it's:
             * 1: Not the end of the file AND
             * 2a: No Max Amount set OR
             * 2b: The length of the data is less than the max amount we want
             */
            while (!feof($fh) && (!$amount || strlen($data) < $amount)) {
                $data .= fread($fh, $chunksize);
            }
        }

        fclose($fh);

        return $data;
    }

    /**
     * Moves a file
     *
     * @param   string   $src          The path to the source file
     * @param   string   $dest         The path to the destination file
     * @param   string   $path         An optional base path to prefix to the file names
     * @param   boolean  $use_streams  True to use streams
     *
     * @return  boolean  True on success
     *
     * @since   11.1
     */
    public static function move($src, $dest, $path = '', $use_streams = false) {
        if ($path) {
            $src = App_Filesystem_Path::clean($path . '/' . $src);
            $dest = App_Filesystem_Path::clean($path . '/' . $dest);
        }
        if (Zend_Registry::isRegistered('logger')):
            $logger = Zend_Registry::get('logger');
        endif;

        // Check src path
        if (!is_readable($src)) {
            $logger->getLog('filesystem')->log(sprintf(__METHOD__ . ': :Cannot find source file(%s)', $src), Zend_Log::WARN);
            return false;
        }

        if ($use_streams) {
            $stream = new App_Filesystem_Stream();

            if (!$stream->move($src, $dest)) {
                $logger->getLog('filesystem')->log(sprintf(__METHOD__ . ': :move: %s', $stream->getError()), Zend_Log::WARN);
                return false;
            }

            return true;
        } else {
            if (!@ rename($src, $dest)) {
                $logger->getLog('filesystem')->log(sprintf(__METHOD__ . ': :Rename failed(%1$s, %2$s)', $src, $dest), Zend_Log::WARN);
                return false;
            }
            return true;
        }
    }

    /**
     * Delete a file or array of files
     *
     * @param   mixed  $file  The file name or an array of file names
     *
     * @return  boolean  True on success
     *
     * @since   11.1
     */
    public static function delete($file) {
        if (Zend_Registry::isRegistered('logger')):
            $logger = Zend_Registry::get('logger');
        endif;

        if (is_array($file)) {
            $files = $file;
        } else {
            $files[] = $file;
        }
        foreach ($files as $file) {
            $file = App_Filesystem_Path::clean($file);

            // Try making the file writable first. If it's read-only, it can't be deleted
            // on Windows, even if the parent folder is writable
            @chmod($file, 0777);

            // In case of restricted permissions we zap it one way or the other
            // as long as the owner is either the webserver or the ftp
            if (@unlink($file)) {
                // Do nothing
            } else {
                $filename = basename($file);
                $logger->getLog('filesystem')->log(sprintf(__METHOD__ . ' :Failed deleting %s', $filename), Zend_Log::WARN);
                return false;
            }
        }

        return true;
    }

    /**
     * Gets the extension of a file name
     *
     * @param   string  $file  The file name
     *
     * @return  string  The file extension
     *
     * @since   11.1
     */
    public static function getExt($file) {
        $dot = strrpos($file, '.') + 1;
        return substr($file, $dot);
    }

    /**
     * Strips the last extension off of a file name
     *
     * @param   string  $file  The file name
     *
     * @return  string  The file name without the extension
     *
     * @since   11.1
     */
    public static function stripExt($file) {
        return preg_replace('#\.[^.]*$#', '', $file);
    }

    /**
     * Moves an uploaded file to a destination folder
     *
     * @param   string   $src          The name of the php (temporary) uploaded file
     * @param   string   $dest         The path (including filename) to move the uploaded file to
     * @param   boolean  $use_streams  True to use streams
     *
     * @return  boolean  True on success
     *
     * @since   11.1
     */
    public static function upload($src, $dest, $use_streams = false) {
        // Ensure that the path is valid and clean
        $dest = App_Filesystem_Path::clean($dest);
        if (Zend_Registry::isRegistered('logger')):
            $logger = Zend_Registry::get('logger');
        endif;

        // Create the destination directory if it does not exist
        $baseDir = dirname($dest);

        if (!file_exists($baseDir)) {
            App_Filesystem_Folder::create($baseDir);
        }

        if ($use_streams) {
            $stream = new App_Filesystem_Stream();

            if (!$stream->upload($src, $dest)) {
                $logger->getLog('filesystem')->log(sprintf(__METHOD__ . ' :upload: %s', $stream->getError()), Zend_Log::WARN);
                return false;
            }

            return true;
        } else {
            $ret = false;
            if (is_writeable($baseDir) && move_uploaded_file($src, $dest)) {
                // Short circuit to prevent file permission errors
                if (App_Filesystem_Path::setPermissions($dest)) {
                    $ret = true;
                } else {
                    $logger->getLog('filesystem')->log(__METHOD__ . ' :Warning: Failed to change file permissions!', Zend_Log::WARN);
                }
            } else {
                $logger->getLog('filesystem')->log(__METHOD__ . ' :Warning: Failed to move file!', Zend_Log::WARN);
            }
            return $ret;
        }
    }

    /**
     * Write contents to a file
     *
     * @param   string   $file         The full file path
     * @param   string   &$buffer      The buffer to write
     * @param   boolean  $use_streams  Use streams
     *
     * @return  boolean  True on success
     *
     * @since   11.1
     */
    public static function write($file, &$buffer, $use_streams = false) {
        @set_time_limit(ini_get('max_execution_time'));
        if (Zend_Registry::isRegistered('logger')):
            $logger = Zend_Registry::get('logger');
        endif;
        // If the destination directory doesn't exist we need to create it
        if (!file_exists(dirname($file))) {
            if (App_Filesystem_Folder::create(dirname($file)) == false) {
                return false;
            }
        }

        if ($use_streams) {
            $stream = new App_Filesystem_Stream();

            // Beef up the chunk size to a meg
            $stream->set('chunksize', (1024 * 1024));

            if (!$stream->writeFile($file, $buffer)) {
                $logger->getLog('filesystem')->log(sprintf(__METHOD__ . ': :write(%1$s): %2$s', $file, $stream->getError()), Zend_Log::WARN);
                return false;
            }

            return true;
        } else {
            $file = App_Filesystem_Path::clean($file);
            return is_int(file_put_contents($file, $buffer)) ? true : false;
        }
    }

}
