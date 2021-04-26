<?php
    namespace AppBundle\Command;

    use Pimcore\Console\AbstractCommand;
    use Pimcore\Console\Dumper;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Pimcore\Model\DataObject\Product;
    use Pimcore\Model\DataObject\Category;
    use Symfony\Component\Console\Input\InputOption;
    use Pimcore\Model\Asset;
    use Pimcore\Model\DataObject\Log1;
    use Pimcore\Model\DataObject;

    class ImportData extends AbstractCommand {
        protected function configure() {
            $this->setName('data:save')->setDescription('import new data');
            //->addOption('file', 'f', InputOption::VALUE_REQUIRED, 'xyz');
        }
        protected function execute(InputInterface $input, OutputInterface $output) {
            $files= new \Pimcore\Model\DataObject\ImportData\Listing();
            //$files->setCondition('class_name= ?','Product');
            foreach($files as $path)
            {
                $file=$path->getFile();
                $class=$path->getClass_name();
                $stat=$path->getStatus();
                $file=(PIMCORE_PROJECT_ROOT . '/web/var/assets' .$file->getPath().$file->getFilename());
            }
            if(($h=fopen($file,"r"))!==FALSE)
            {
                while(($data=fgetcsv($h,1000,","))!==FALSE)
                {
                    $num=count($data);
                    $a[]=$data;
                    
                }
                if($class=="Product")
                {
                $count=1;
                foreach($a as $entry)
                {
                    $c=0;
                    $flag=0;
                    $msg="";
                    try{
                    $prod=new \Pimcore\Model\DataObject\Product();
                    $key=$entry[$c];
                    $prod->setPublished(true);
                    $prod->setParentId(97);
                    $prod->setKey($entry[$c]);
                    $prod->setName($entry[$c++]);
                    $prod->setSku($entry[$c++]);
                    $prod->setDescription($entry[$c++]);
                    $t= new \Pimcore\Model\DataObject\Data\RgbaColor();
                    $t->setHex($entry[$c++]);
                    $prod->setColor($t);
                    $category= new \Pimcore\Model\DataObject\Category\Listing();
                    $category->setCondition('name = ?',$entry[$c++]);
                    foreach($category as $cat)
                    {
                        $prod->setCategory($cat);
                    }
                    $prod->setBrand($entry[$c++]);
                    $prod->setRating($entry[$c++]);
                    $prod->setSize($entry[$c++]);
                    $unit=DataObject\QuantityValue\Unit::getByAbbreviation("gm");
                    $prod->setQuantity(new DataObject\Data\QuantityValue($entry[$c++],$unit->getId()));
                    $unit=DataObject\QuantityValue\Unit::getByAbbreviation("rupees");
                    $prod->setPrice(new DataObject\Data\QuantityValue($entry[$c++],$unit->getId()));
                    $prod->setTexture($entry[$c++]);
                    $prod->setApplicationarea($entry[$c++]);
                    $prod->setGender($entry[$c++]);
                    $mat=explode("|",$entry[$c++]);
                    $prod->setIngredients($mat);
                    $feat=explode("|",$entry[$c++]);
                    $prod->setFeatures($feat);
                    $prod->setCountry($entry[$c++]);
                    $img= \Pimcore\Model\Asset\Image::getByPath($entry[$c++]);
                    $prod->setImage($img);
                    $startDate= \Carbon\Carbon::parse($entry[$c++]);
                    $prod->setManufactured($startDate);
                    $mfd=strtotime($startDate);
                    $endDate= \Carbon\Carbon::parse($entry[$c++]);
                    $prod->setExpiry($endDate);
                    $exp=strtotime($endDate);
                    if($mfd>$exp)
                    {
                        $msg.="Expiry date should be greater than mfd\n";
                        $flag++;

                    }
                    else
                    {
                    $temp=$entry[$c++];
                    if ($temp=='cosmetics') 
                    {
                        $objBrick=new DataObject\Objectbrick\Data\Cosmetics($prod);
                        $objBrick->setSmudgeproof($entry[$c++]);
                        $objBrick->setWaterproof($entry[$c++]);
                        $objBrick->setTiptype($entry[$c++]);
                        $unit=DataObject\QuantityValue\Unit::getByAbbreviation("hrs");
                        $objBrick->setDuration(new DataObject\Data\QuantityValue($entry[$c++],$unit->getId()));
                        $prod->getClassification()->setCosmetics($objBrick);
                    } 
                    if($temp=="fragrance")
                    {
                        $objBrick=new DataObject\Objectbrick\Data\Fragrance($prod);
                        $objBrick->setStrength($entry[$c++]);
                        $objBrick->setSmell($entry[$c++]);
                        $prod->getClassification()->setFragrance($objBrick);
                    }
                    if($temp=="hair")
                    {   $objBrick=new DataObject\Objectbrick\Data\Hair($prod);
                        $objBrick->setConcern($entry[$c++]);
                        $objBrick->setHairtype($entry[$c++]);
                        $objBrick->setSulfate($entry[$c++]);
                        $prod->getClassification()->setHair($objBrick);
                    }
                    if($temp=="skin")
                    {   $objBrick=new DataObject\Objectbrick\Data\Skin($prod);
                        $objBrick->setSpf($entry[$c++]);
                        $objBrick->setSkintone($entry[$c++]);
                        $objBrick->setSkintype($entry[$c++]);
                        $prod->getClassification()->setSkin($objBrick);
                    }
                    $prod->save();
                    $emailSender = new \AppBundle\MailNotification();
                    $emailSender->sendImportMail();
                }
                    
                }
               //for loop }
                catch(\Exception $e)
                {
                    $c=0;
                    if($entry[$c++]==NULL)
                    {
                        $msg.="Invalid value in name feild\n";
                        $flag++;
                    }
                    //p_r($entry[$c]);
                    //die();
                    if($entry[$c++]==NULL)
                    {
                        $msg.="Invalid value in SKU feild\n";
                        $flag++;
                    }
                    //p_r($entry[$c]);
                    //die();
                    if($entry[$c++]==NULL)
                    {
                        $msg.="Invalid value in description feild\n";
                        $flag++;
                    }
                    if($entry[$c++]==NULL)
                    {
                        $msg.="Invalid value in color feild\n";
                        $flag++;
                    }
                    if($entry[$c++]==NULL)
                    {
                        $msg.="Invalid value in category feild\n";
                        $flag++;
                    }
                
                    if($entry[$c++]==NULL)
                    {
                        $msg.="Invalid value in brand feild\n";
                        $flag++;
                    }
               
                    if($entry[$c++]==NULL)
                    {
                        $msg.="Invalid value in rating feild\n";
                        $flag++;
                    }
                    if($entry[$c++]==NULL)
                    {
                        $msg.="Invalid value in size feild\n";
                        $flag++;
                    }
                    $check=is_numeric($entry[$c++]);
                    if($check==false)
                    {
                        $msg.="Invalid value in quantity feild\n";
                        $flag++;
                    }
                    $check=is_numeric($entry[$c]);
                    if($check==false)
                    {
                        $msg.="Invalid value in price feild\n";
                        $flag++;
                    }
                    if($entry[$c++]==NULL)
                    {
                        $msg.="Invalid value in texture feild\n";
                        $flag++;
                    }
                    if($entry[$c++]==NULL)
                    {
                        $msg.="Invalid value in application area feild\n";
                        $flag++;
                    }
                    if($entry[$c++]==NULL)
                    {
                        $msg.="Invalid value in gender feild\n";
                        $flag++;
                    }
                    if($entry[$c++]==NULL)
                    {
                        $msg.="Invalid value in ingredients feild\n";
                        $flag++;
                    }
                    if($entry[$c++]==NULL)
                    {
                        $msg.="Invalid value in features feild\n";
                        $flag++;
                    }
                    if($entry[$c++]==NULL)
                    {
                        $msg.="Invalid value in country feild\n";
                        $flag++;
                    }
                    if($entry[$c++]==NULL)
                    {
                        $msg.="Invalid value in image feild\n";
                        $flag++;
                    }
                    if($entry[$c++]==NULL)
                    {
                        $msg.="Invalid value in manufactured feild\n";
                        $flag++;
                    }
                    if($entry[$c++]==NULL)
                    {
                        $msg.="Invalid value in expiry feild\n";
                        $flag++;
                    }
                    if($entry[$c]==NULL)
                    {
                        $msg.="Invalid value in classification feild\n";
                        $flag++;
                    }
                    // if($entry[$c]=="skin")
                    // {
                    //     if($entry[$c++]==NULL)
                    //     {
                    //         $msg.="Invalid value in classification spf feild\n";
                    //         $flag++;  
                    //     }
                    //     if($entry[$c++]==NULL)
                    //     {
                    //         $msg.="Invalid value in classification skintone feild\n";
                    //         $flag++;  
                    //     }
                    //     if($entry[$c++]==NULL)
                    //     {
                    //         $msg.="Invalid value in classification skintype feild\n";
                    //         $flag++;  
                    //     }
                    // }
                    // if($entry[$c]=="fragrance")
                    // {
                    //     if($entry[$c++]==NULL)
                    //     {
                    //         $msg.="Invalid value in classification strength feild\n";
                    //         $flag++;  
                    //     }
                    //     if($entry[$c++]==NULL)
                    //     {
                    //         $msg.="Invalid value in classification smell feild\n";
                    //         $flag++;  
                    //     }
                    // }
                }
                    if($flag>0)
                    {
                        $this->dump('failed');
                    }
                    if($flag==0)
                    {
                        $msg.="Row ".$count." of product table is imported\n";
                        $this->dump("saved");
                        
                    }
                
                $logmsg= new \Pimcore\Model\DataObject\Log1();
                $logmsg->setKey($key);
                $logmsg->setParentId(80);
                $logmsg->setPublished(true);
                $logmsg->setName($msg);
                $logmsg->save();
                }
            }
            else if($class=="Category")
           {
                $count=1; 
                foreach($a as $entry)
                {
                    $c=0;
                    $flag=0;
                    $msg="";
                    try{
                    $prod=new \Pimcore\Model\DataObject\Category();
                    $key=$entry[$c];
                    $prod->setKey($entry[$c]);
                    $prod->setPublished(true);
                    $prod->setParentId(5);
                    $prod->setName($entry[$c++]);
                    $prod->setDescription($entry[$c++]);
                    $prod->save();
                    $emailSender = new \AppBundle\MailNotification();
                    $emailSender->sendImportMail();
                    }
                    catch(\Exception $e)
                    {
                        $c=0;
                        if($entry[$c++]==NULL)
                    {
                        $msg.="Invalid value in name feild\n";
                        $flag++;
                    }
                    if($entry[$c++]==NULL)
                    {
                        $msg.="Invalid value in description feild\n";
                        $flag++;
                    }
                    }
                    if($flag>0)
                    {
                        $this->dump('failed');
                    }
                    if($flag==0)
                    {
                        $msg.="Row ".$count." of category table is imported\n";
                        $this->dump("saved");
                        
                    }
                
                $logmsg= new \Pimcore\Model\DataObject\Log1();
                $logmsg->setKey($key);
                $logmsg->setParentId(80);
                $logmsg->setPublished(true);
                $logmsg->setName($msg);
                $logmsg->save();
                }
            }
                $count++;
                $entries=new \Pimcore\Model\DataObject\ImportData\Listing();
                foreach($entries as $entry)
                {
                    $entry->setStatus(true);
                    
                    $entry->save();
                }
                


            }
      fclose($h);
        }
    }



?>