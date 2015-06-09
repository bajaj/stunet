<?php

class imagemanager{
    private $type="";
    private $uploadExtensions=array('png','gif','jpg','jpeg','PNG','JPEG','GIF','JPEG');
    private $uploadTypes=array('image/gif','image/jpg','image/jpeg','image/png','image/pjpeg');
    private $image;
    private $name;
    
    public function __construct(){}
    
    public function loadFromFile($filepath){
        $info=getimagesize($filepath);//getimagesize returns an array of seven elements (height, width, IMAGETYPE_XXX, height="YYY" width="XXX", mime (mime type), channels ( 3 for rgb and 4 for cmyk), bit (number of bits for each color));
        $this->type=$info[2];
        if($this->type==IMAGETYPE_JPEG){
            $this->image=imagecreatefromjpeg($filepath); //returns an image identifier representing the image obtained from the given filename
        }
        elseif($this->type==IMAGETYPE_PNG){
            $this->image=imagecreatefrompng($filepath);
        }
        elseif($this->type==IMAGETYPE_GIF){
            $this->image=imagecreatefromgif($filepath);
        }
    }
    
    public function getWidth(){
        return imagesx($this->image);
    }
    
    public function getHeight(){
        return imagesy($this->image);
    }
    
    public function resize($x,$y)
     {
        $new=imagecreatetruecolor($x, $y);//returns an image resource identifier representing a black image of the specified size
        imagecopyresampled($new,$this->image,0,0,0,0,$x,$y,$this->getWidth(),$this->getHeight());//imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
        //copies a rectangular portion of one image to another image
        $this->image=$new;
    }
    
    public function resizeScaleWidth($height){
        $width=$this->getWidth()*($height/$this->getHeight());
        $this->resize($width,$height);
    }
    
    public function resizeScaleHeight($width){
        $height=$this->getHeight()*($width/$this->getWidth());
        $this->resize($width,$height);
    }
    
    public function scale($percentage){
        $width=$this->getWidth()*percentage/100;
        $height=$this->getHeight()*percentage/100;
        $this->resize($width,$height);
    }
    
    public function display(){
        $type='';
        if($this->type==IMAGETYPE_GIF){
            $type='image/gif';
        }
        elseif($this->type==IMAGETYPE_PNG){
            $type='image/png';
        }
        elseif($this->type==IMAGETYPE_JPEG){
            $type='image/png';
        }
        header('Content-type: '.$type);
        if($this->type==IMAGETYPE_JPEG){
            imagejpeg($this->image);//creates a JPEG file from the given image
        }
        elseif($this->type==IMAGETYPE_PNG){
            imagepng($this->image);
        }
        elseif($this->type==IMAGETYPE_GIF){
            imagegif($this->image);
        }
    }
    
    public function loadFromPost($postfield,$moveto,$name_prefix='')
    {
        if(is_uploaded_file($_FILES[$postfield]['tmp_name'])){//$_FILES - An associative superglobal array of items uploaded to the current script via the HTTP POST method
            //is_uploaded_file(filename) -Returns TRUE if the file named by filename was uploaded via HTTP POST
            $i=strpos($_FILES[$postfield]['name'],'.');
            if(!$i){
                return false;
            }
            else{
                $l=strlen($_FILES[$postfield]['name'])-$i;
                $ext=strtolower(substr($_FILES[$postfield]['name'],$i+1,$l));
                $ext=strtolower($ext);
                if(in_array($ext,$this->uploadExtensions))
                {
                    if(in_array($_FILES[$postfield]['type'],$this->uploadTypes))
                    {
                        $name=str_replace(' ','',$_FILES[$postfield]['name']);//remove any spaces in the filename
                        $this->name=$name_prefix.$name;//set the new name
                        $path=$moveto.$this->name;//set the path
                        move_uploaded_file($_FILES[$postfield]['tmp_name'], $path);//move the uploaded file to the set path
                        $this->loadFromFile($path);//save the image in the image variable of the object
                        return true;
                    }
                    else
                    {//invalid file type
                        return false;
                    }
                }
                else{//invalid extension
                    return false;
                }
            }
                    
        }
        else{//file not uploaded;
            return false;
        }
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function save($location,$type='',$quality=100){
        $type=($type=='')?$this->type:$type;
        if($type==IMAGETYPE_JPEG){
            imagejpeg($this->image,$location,$quality);//creates a JPEG file from the image and stores it in the given location
        }
        elseif($type==IMAGETYPE_GIF){
            imagegif($this->image,$location);
        }
        elseif($type==IMAGETYPE_PNG){
            imagepng($this->image,$location);
        }
    }
}
 