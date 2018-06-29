<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require "application/libraries/vendor/autoload.php";
use Aws\S3\S3Client;
class Aws_sdk{
	public $s3Client,$ci;
	public function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->config('aws_sdk');
		$this->s3Client  = S3Client::factory([
			'credentials' => [
				'key'    => 'AKIAJKIHVAKF2KXWXM5Q',
				'secret' => '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'
			],		  
			//'region' => 'us-west-2',
			'region' => 'us-east-1',
			'version' => '2006-03-01'
		]);
		$this->bucket="s3pathways";
		
	}
	public function __call($name, $arguments=null)
   {
		if(!property_exists($this, $name)) {
			return call_user_func_array(array($this->s3Client,$name), $arguments);
  		}
   }
   /**
    * Wrapper of putObject with duplicate check.
    * If the file exists in bucket, it appends a unix timestamp to filename.
    *
    * @param  array  $params same as putObject
    * @return result
    */
   public function saveObject($params=array())
   {
		if($this->doesObjectExist($params['Bucket'],$params['Key'])){
			$path = pathinfo($params['Key']);
			$params['Key'] = $path['dirname'].'/'.$path['filename'].'-'.date('U').'.'.$path['extension'];
		}
		return $this->putObject($params);
   }
   /**
    * Wrapper for best practices in putting an object.
    * @param  array  $params: Bucket, Prefix, SourceFile, Key (filename)
    * @return string         URL of the uploaded object in s3
    */
   public function saveObjectInBucket($params = array())
   {
   		$error = null;
		// Create bucket
		try{
			$this->createBucket(array('Bucket' => $params['Bucket']));
		}catch (Exception $e){
			throw new Exception("Something went wrong creating bucket for your file.\n".$e);
		}
		// Poll the bucket until it is accessible
		try{
			$this->waitUntil('BucketExists', array('Bucket' => $params['Bucket']));
		}catch (Exception $e){
			throw new Exception("Something went wrong waiting for the bucket for your file.\n".$e);
		}
		// Upload an object
		$file_key = $params['Prefix'].'/'.$params['Key'];
		$path = pathinfo($file_key);
		$extension = $path['extension'];
		$mimes = new Guzzle\Http\Mimetypes();
		$mimetype = $mimes->fromExtension($extension);
		try{
			$aws_object=$this->saveObject(array(
			    'Bucket'      => $params['Bucket'],
			    'Key'         => $file_key,
			    'ACL'		  => 'public-read',
			    'SourceFile'  => $params['SourceFile'],
			    'ContentType' => $mimetype
			))->toArray();
		}catch (Exception $e){
			throw new Exception("Something went wrong saving your file.\n".$e);
		}

		// We can poll the object until it is accessible
		try{
			$this->waitUntil('ObjectExists', array(
			    'Bucket' => $params['Bucket'],
			    'Key'    => $file_key
			));
		}catch (Exception $e){
			throw new Exception("Something went wrong polling your file.\n".$e);
		}
		// Return result
		return $aws_object['ObjectURL'];
   }


   public function sendFile($path ,$fileName){	   
	   /*try{
		   $this->createBucket(array('Bucket' => 's3visulive'));		   
	   }catch (Exception $e){
		   throw new Exception("Something went wrong creating bucket for your file.\n".$e);
	   }*/
	   $mimetype = 'image/png';
	   $aws_object = $this->saveObject(array(
		   'Bucket'      => $this->bucket,
		   'Key'         => $path.'/'.$fileName['name'],
		   'ACL'		 => 'public-read',
		   'StorageClass' => 'STANDARD',
		   'SourceFile'  => $fileName['tmp_name'],
		   'ContentType' => $mimetype
	   ))->toArray();
	   return $aws_object['ObjectURL'];
   }
   
   public function deleteImage($object_name){
       if($this->doesObjectExist($this->bucket,$object_name)){
            $result = $this->s3Client->deleteObject([
             'Bucket' => $this->bucket,
             'Key' => $object_name
         ]);
         return $result;
           
       }else{
           return false;
       }        
    }




}
