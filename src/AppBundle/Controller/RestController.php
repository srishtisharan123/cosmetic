<?php

namespace AppBundle\Controller;

use Pimcore\Bundle\AdminBundle\Controller\Rest\AbstractRestController;
use Pimcore\Bundle\AdminBundle\HttpFoundation\JsonResponse;
use Pimcore\Bundle\AdminBundle\Security\BruteforceProtectionHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Pimcore\Model\DataObject\Product;


/**
 * Class RestController
 * @package AppBundle\Controller
 */

 class RestController extends AbstractRestController
 {
     CONST BASE_API_SERVICE = 'base_api_service';

     /**
      * @Route("/webservice/accessProductList")
      * @Method({"GET"})
      * @param Request $request
      * @return \Symfony\Component\HttpFoundation\JsonResponse
      * @throws \Pimcore\Http\Exception\ResponseException
      * @throws \Exception
      */
    public function getProductList(Request $request, BruteforceProtectionHandler $bruteforceProtectionHandler)
    {
        $data = [];
        $products = new \Pimcore\Model\DataObject\Product\Listing();                
        $products->getObjects();
        foreach ($products as $product)
        {
            $data[] = $this->getProductJson($product);
        }
        if (!empty($data)) {
            return $this->createSuccessResponse($data, true);
        }
        return $this->createErrorResponse("No product found!", Response::HTTP_NOT_FOUND);
    }

    /**
      * @Route("/filterProductList")
      * @Method({"GET"})
      * @param Request $request
      * @return \Symfony\Component\HttpFoundation\JsonResponse
      * @throws \Pimcore\Http\Exception\ResponseException
      * @throws \Exception
      */
      public function getFilteredProductList(Request $request, BruteforceProtectionHandler $bruteforceProtectionHandler)
      {
          $brand = $request->query->get('brand');
          $category = $request->query->get('category');
          $data = [];
          $products = new \Pimcore\Model\DataObject\Product\Listing();                  
          $products->getObjects();
          foreach ($products as $product)
          {
              if ($brand) {
                  if ($category) {
                      if (strcasecmp($category, $product->getCategory()->getName()) == 0 && strcasecmp($brand, $product->getBrand()->getName()) == 0) {
                        $data[] = $this->getProductJson($product);
                      }
                  }
                  else {
                      if (strcasecmp($brand, $product->getBrand()) == 0) {
                        $data[] = $this->getProductJson($product);
                      }

                  }
              }
              elseif ($category) {
                  if (strcasecmp($category, $product->getCategory()->getName()) == 0) {
                    $data[] = $this->getProductJson($product);
                  }

              }
              else {
                $data[] = $this->getProductJson($product);
              }
            
          }
          if (!empty($data)) {
            return $this->createSuccessResponse($data, true);
        }
        return $this->createErrorResponse("No product found with given filter(s)!", Response::HTTP_NOT_FOUND);
          
      }

      function getProductJson(Product $product) {
        if($product->getName()=="Lipstick" || $product->getName()=="Nail Paint")
        {
          return [
            //'id' => $product->getId(),
            'sku' => $product->getSku(),
            'productName' => $product->getName(),
            'description' => $product->getDescription(),
            'brandName' => $product->getBrand(),
             'size' => $product->getSize(),
             'color' => $product->getColor()->getHex(),
             'price' => $product->getPrice(),
             'discount' => $product->getDiscount(),
             'texture' => $product->getTexture(),
             'category' => $product->getCategory()->getName(),
             'gender' => $product->getGender(),
             'classification'=>$product->getClassification()->getCosmetics()->getTiptype(),
             'applicationArea' => $product->getApplicationarea(),
             'image' => $product->getImage()->getRelativeFileSystemPath(),
             'rating' => $product->getRating(),
             'MFD' => $product->getManufactured()->toDateString(),
             'Expiry'=> $product->getExpiry()->toDateString(),
             'country' => $product->getCountry(),
             'quantity' => $product->getQuantity(),
             'ingredients' => $product->getIngredients(),
             'features'=> $product->getFeatures()
          ];
        }
    elseif($product->getName()=="Perfume")
        {
          return [
            //'id' => $product->getId(),
            'sku' => $product->getSku(),
            'productName' => $product->getName(),
            'description' => $product->getDescription(),
            'brandName' => $product->getBrand(),
             'size' => $product->getSize(),
             'color' => $product->getColor()->getHex(),
             'price' => $product->getPrice()->__toString(),
             'texture' => $product->getTexture(),
             'category' => $product->getCategory()->getName(),
             'gender' => $product->getGender(),
             'classification'=>$product->getClassification()->getFragrance()->getStrength(),
             'applicationArea' => $product->getApplicationarea(),
             'image' => $product->getImage()->getRelativeFileSystemPath(),
             'rating' => $product->getRating(),
             'MFD' => $product->getManufactured()->toDateString(),
             'Expiry'=> $product->getExpiry()->toDateString(),
             'country' => $product->getCountry(),
             'quantity' => $product->getQuantity()->__toString(),
             'ingredients' => $product->getIngredients()
          ];
        }
        elseif($product->getName()=="Foundation")
        {
          return [
            //'id' => $product->getId(),
            'sku' => $product->getSku(),
            'productName' => $product->getName(),
            'description' => $product->getDescription(),
            'brandName' => $product->getBrand(),
             'size' => $product->getSize(),
             'color' => $product->getColor()->getHex(),
             'price' => $product->getPrice()->__toString(),
             'texture' => $product->getTexture(),
             'category' => $product->getCategory()->getName(),
             'gender' => $product->getGender(),
             'classification'=>$product->getClassification()->getSkin()->getSpf(),
             'applicationArea' => $product->getApplicationarea(),
             'image' => $product->getImage()->getRelativeFileSystemPath(),
             'rating' => $product->getRating(),
             'MFD' => $product->getManufactured()->toDateString(),
             'Expiry'=> $product->getExpiry()->toDateString(),
             'country' => $product->getCountry(),
             'quantity' => $product->getQuantity()->__toString(),
             'ingredients' => $product->getIngredients()
          ];
        }
        else
        {
          return [
            //'id' => $product->getId(),
            'sku' => $product->getSku(),
            'productName' => $product->getName(),
            'description' => $product->getDescription(),
            'brandName' => $product->getBrand(),
             'size' => $product->getSize(),
             'color' => $product->getColor()->getHex(),
             'price' => $product->getPrice()->__toString(),
             'texture' => $product->getTexture(),
             'category' => $product->getCategory()->getName(),
             'gender' => $product->getGender(),
             'applicationArea' => $product->getApplicationarea(),
             'image' => $product->getImage()->getRelativeFileSystemPath(),
             'rating' => $product->getRating(),
             'MFD' => $product->getManufactured()->toDateString(),
             'Expiry'=> $product->getExpiry()->toDateString(),
             'country' => $product->getCountry(),
             'quantity' => $product->getQuantity()->__toString(),
             'ingredients' => $product->getIngredients()
          ];
        }
      }
 }
 ?>