<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2024 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Ftp;

/**
 * FTP class
 *
 * @category   Pop
 * @package    Pop\Ftp
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2024 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.0.0
 */
class Ftp
{

    /**
     * FTP resource
     * @var mixed
     */
    protected mixed $connection = null;

    /**
     * FTP address
     * @var ?string
     */
    protected ?string $address = null;

    /**
     * FTP username
     * @var ?string
     */
    protected ?string $username = null;

    /**
     * FTP password
     * @var ?string
     */
    protected ?string $password = null;

    /**
     * FTP connection string
     * @var ?string
     */
    protected ?string $connectionString = null;

    /**
     * Constructor
     *
     * Instantiate the FTP object
     *
     * @param  string $address
     * @param  string $user
     * @param  string $pass
     * @param  bool   $ssl
     * @throws Exception
     */
    public function __construct(string $address, string $user, string $pass, bool $ssl = false)
    {
        $this->address          = $address;
        $this->username         = $user;
        $this->password         = $pass;
        $this->connectionString = 'ftp://' . $user . ':' . $pass . '@' . $address;

        if (!function_exists('ftp_connect')) {
            throw new Exception('Error: The FTP extension is not available.');
        } else if ($ssl) {
            if (!($this->connection = ftp_ssl_connect($this->address))) {
                throw new Exception('Error: There was an error connecting to the FTP server ' . $this->address);
            }
        } else {
            if (!($this->connection = ftp_connect($this->address))) {
                throw new Exception('Error: There was an error connecting to the FTP server ' . $this->address);
            }
        }

        if (!ftp_login($this->connection, $this->username, $this->password)) {
            throw new Exception(
                'Error: There was an error connecting to the FTP server ' . $this->address . ' with those credentials.'
            );
        }
    }

    /**
     * Return current working directory
     *
     * @return string
     */
    public function pwd(): string
    {
        return ftp_pwd($this->connection);
    }

    /**
     * Change directories
     *
     * @param  string $dir
     * @throws Exception
     * @return Ftp
     */
    public function chdir(string $dir): Ftp
    {
        if (!ftp_chdir($this->connection, $dir)) {
            throw new Exception('Error: There was an error changing to the directory ' . $dir);
        }
        return $this;
    }

    /**
     * Make directory
     *
     * @param  string $dir
     * @throws Exception
     * @return Ftp
     */
    public function mkdir(string $dir): Ftp
    {
        if (!ftp_mkdir($this->connection, $dir)) {
            throw new Exception('Error: There was an error making the directory ' . $dir);
        }
        return $this;
    }

    /**
     * Make nested sub-directories
     *
     * @param  string $dirs
     * @throws Exception
     * @return Ftp
     */
    public function mkdirs(string $dirs): Ftp
    {
        if (str_starts_with($dirs, '/')) {
            $dirs = substr($dirs, 1);
        }
        if (str_ends_with($dirs, '/')) {
            $dirs = substr($dirs, 0, -1);
        }

        $dirs   = explode('/', $dirs);
        $curDir = $this->connectionString;

        foreach ($dirs as $dir) {
            $curDir .= '/' . $dir;
            if (!is_dir($curDir)) {
                $this->mkdir($dir);
            }
            $this->chdir($dir);
        }

        return $this;
    }

    /**
     * Remove directory
     *
     * @param  string $dir
     * @throws Exception
     * @return Ftp
     */
    public function rmdir(string $dir): Ftp
    {
        if (!ftp_rmdir($this->connection, $dir)) {
            throw new Exception('Error: There was an error removing the directory ' . $dir);
        }
        return $this;
    }

    /**
     * Check if file exists
     *
     * @param  string $file
     * @return bool
     */
    public function fileExists(string $file): bool
    {
        return is_file($this->connectionString . $file);
    }

    /**
     * Check if directory exists
     *
     * @param  string $dir
     * @return bool
     */
    public function dirExists(string $dir): bool
    {
        return is_dir($this->connectionString . $dir);
    }

    /**
     * Get file
     *
     * @param  string $local
     * @param  string $remote
     * @param  int    $mode
     * @throws Exception
     * @return Ftp
     */
    public function get(string $local, string $remote, int $mode = FTP_BINARY): Ftp
    {
        if (!ftp_get($this->connection, $local, $remote, $mode)) {
            throw new Exception('Error: There was an error getting the file ' . $remote);
        }
        return $this;
    }

    /**
     * Put file
     *
     * @param  string $remote
     * @param  string $local
     * @param  int    $mode
     * @throws Exception
     * @return Ftp
     */
    public function put(string $remote, string $local, int $mode = FTP_BINARY): Ftp
    {
        if (!ftp_put($this->connection, $remote, $local, $mode)) {
            throw new Exception('Error: There was an error putting the file ' . $local);
        }
        return $this;
    }

    /**
     * Rename file
     *
     * @param  string $old
     * @param  string $new
     * @throws Exception
     * @return Ftp
     */
    public function rename(string $old, string $new): Ftp
    {
        if (!ftp_rename($this->connection, $old, $new)) {
            throw new Exception('Error: There was an error renaming the file ' . $old);
        }
        return $this;
    }

    /**
     * Change permissions
     *
     * @param  string $file
     * @param  string $mode
     * @throws Exception
     * @return Ftp
     */
    public function chmod(string $file, string $mode): Ftp
    {
        if (!ftp_chmod($this->connection, $mode, $file)) {
            throw new Exception('Error: There was an error changing the permission of ' . $file);
        }
        return $this;
    }

    /**
     * Delete file
     *
     * @param  string $file
     * @throws Exception
     * @return Ftp
     */
    public function delete(string $file): Ftp
    {
        if (!ftp_delete($this->connection, $file)) {
            throw new Exception('Error: There was an error removing the file ' . $file);
        }
        return $this;
    }

    /**
     * Switch the passive mode
     *
     * @param  bool $flag
     * @return Ftp
     */
    public function pasv(bool $flag = true): Ftp
    {
        ftp_pasv($this->connection, $flag);
        return $this;
    }

    /**
     * Determine whether or not connected
     *
     * @return bool
     */
    public function isConnected(): bool
    {
        return is_resource($this->connection);
    }

    /**
     * Get the connection resource
     *
     * @return mixed
     */
    public function getConnection(): mixed
    {
        return $this->connection;
    }

    /**
     * Get the connection string
     *
     * @return string
     */
    public function getConnectionString(): string
    {
        return $this->connectionString;
    }

    /**
     * Close the FTP connection.
     *
     * @return void
     */
    public function disconnect(): void
    {
        if ($this->isConnected()) {
            ftp_close($this->connection);
        }
    }

}
