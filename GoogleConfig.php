<?php
    
    //getting google drive configuration
    require_once 'GoogleAPI/vendor/autoload.php';

    class GoogleDrive
    {
        //function to get client
        private function getClient()
        {
            $gclient = new Google_Client();
            $gclient->setApplicationName('Upload File To Google Drive');
            $gclient->setScopes(Google_Service_Drive::DRIVE);
            $gclient->setAuthConfig('client_secret.json'); 
            $gclient->setAccessType('offline'); 

            $credentialPath = 'token.json';
            if(file_exists($credentialPath))
            {
                $accessToken = json_decode(file_get_contents($credentialPath),true);
            }
            else{
                $authUrl = $gclient->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n",$authUrl);
                print("Enter Verification Code ");
                $authCode = trim(fgets(STDIN));

                //authorization code for an access Token
                $accessToken = $gclient->fetchAccessTokenWithAuthCode($authCode);

                if(!file_exists(dirname($credentialsPath)))
                {
                    mkdir(dirname($credentialsPath),0700,true);
                }
                file_put_contents($credentialsPath,json_encode($accessToken));
                printf("Credentials Saved : :",$credentialsPath);
            }
            $gclient->setAccessToken($accessToken);
            
            //referesh the access Token
            if($gclient->isAccessTokenExpired())
            {
                $gclient->fetchAccessTokenWithRefreshToken($gclient->getRefereshToken());
                file_put_contents($credentialsPath,json_encode($gclient->getAccessToken()));
            }
            return $gclient;
        }
        
        
        //upload album id wise
        public function UploadAlbum($album_ID)
        {
            $gclient =$this->getClient();
            $services = new Google_Service_Drive($gclient);
            $fileMetaData = new Google_Services_Drive_DriveFile(array('name' => 'photo.jpg'));
            $contents = file_get_contents($albumname/photo.jpg);
            $file=$services->files->create($fileMetaData,array(
                'data' => $contents,
                'mimeType' => mime_content_type($albumname.$album_ID),
                'uploadType' => 'multipart', 
                'fields' => 'id'
            ));
            return $file;
        }
    }

     $drive = new GoogleDrive();
    $file = $drive->upload('files/','$photos'.'.jpg');
    if(!empty($file->id))
    {
        printf("File Uploaded Successfully");
    }
?>