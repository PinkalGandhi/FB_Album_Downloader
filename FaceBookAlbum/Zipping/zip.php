<?php

//creating class for zip
class zip
{
   private $zip;
   public function __construct( $file_name, $zip_directory)
   {
        $this->zip = new ZipArchive();
        $this->path = dirname( __FILE__ ) . $zip_directory . $file_name . '.zip';
        $this->zip->open( $this->path, ZipArchive::CREATE | ZipArchive::OVERWRITE );
    }
      
    //path to create a zip
    public function get_zip_path()
    {
        return $this->path;
    }   
       
    //directory for zip
    public function add_directory( $directory )
    {
        if( is_dir( $directory ) && $handle = opendir( $directory ) )
        {
            $this->zip->addEmptyDir( $directory );
            while( ( $file = readdir( $handle ) ) !== false )
            {
                if (!is_file($directory . '/' . $file))
                {
                    if (!in_array($file, array('.', '..')))
                    {
                        $this->add_directory($directory . '/' . $file );
                    }
                }
                else
                {
                    $this->add_file($directory . '/' . $file);                }
            }
        }
    }

    public function add_file( $path )
    {
        $this->zip->addFile( $path, $path);
    }
   
    //save zip and close it
    public function save()
    {
        $this->zip->close();
    }

    /**
     * remove the directory recursively
     */
    public function removeRecursive($dir)
    {
        // Remove . and .. firectories from the directory list
        $files = array_diff(scandir($dir), array('.','..'));

        // Delete all files one by one
        foreach ($files as $file) {
            // If current file is directory then recurse it
            (is_dir("$dir/$file")) ? $this->removeRecursive("$dir/$file") : unlink("$dir/$file");
        }

        // Remove blank directory after deleting all files
        return rmdir($dir);
    }
}

?>