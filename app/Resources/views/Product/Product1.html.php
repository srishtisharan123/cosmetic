<?php
echo $this->ta;
/**
 * @var \Pimcore\Templating\PhpEngine $this
 * @var \Pimcore\Templating\PhpEngine $view
 * @var \Pimcore\Templating\GlobalVariables $app
 */

$this->extend('layout.html.php');

?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="author" content="Srishti Sharan">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Bootstrap Datatable </title>
       <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide|Sofia|Trirong">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<style>
* {
  box-sizing: border-box;

}
table {
  border-collapse: collapse;
  width: 100%;
} 

th,td {
  border: 1px solid blue;
  text-align: center;
  padding: 10px;
}

th {
  background-color: #ff6699;
  color: black;
}


table.d {
  table-layout: fixed;
  width: 100%;  
}
tr:nth-child(even) {background-color: #f2f2f2;}
.form-control {
  font-family: "FontAwesome"
}



#myInput {
  background-image: url('/css/searchicon.png'); /* Add a search icon to input */
  background-position: 10px 12px; /* Position the search icon */
  background-repeat: no-repeat; /* Do not repeat the icon image */
  width: 200px; /* width */
  font-size: 16px; /* Increase font-size */
  padding: 12px 12px 20px 20px; /* Add some padding */
  border: 1px solid #ddd; /* Add a grey border */
  margin-bottom: 12px; /* Add some space below the input */
}

.pagination {
  display: inline-block;
}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
  transition: background-color .3s;
}

.pagination a.active {
  background-color: #4CAF50;
  color: white;
}

.pagination a:hover:not(.active) {background-color: #0000ff;}
</style>
</head>
<br>

<?= $this->wysiwyg("specialContent"); ?>
<div class="screen">
   
<div class="product-info">
    <?php
    if($this->editmode):
       
    else: ?>

   <div class="container">
  <div class="row  justify-content-center">
  <div class="col-lg-10 bg-light rounded my-2 py-2">
  <h4 class="text-center text-dark pt-2"><b>Our Products</b></h4><hr>
  
           <table class="table table-bordered table-striped table-hover">
     
           <thead>   <tr>
           
            <th>SKU</th>
                <th>Name</th>
                <th  >Brand</th>
                <th  >Price</th>
                <th  >Quantity</th>
                <th  >For</th>
                <th  >Star</th>
                <th  >Expiry Date</th>
                <th  >Made In</th>
                
               
                <th  >Image</th>
               
            </tr>    </thead>          
   
       
 <div class="screen-body">
   
      <tbody>
  <?php $prod = new \Pimcore\Model\DataObject\Product\Listing(); ?>
       <?php
        foreach($prod as $product)
        {
            ?>
           
            <tr >
            <td ><?=$product->getSku(); ?></td>
            <td ><?=$product->getName(); ?></td>
             <td ><?=$product->getBrand(); ?></td>
             <td ><?=$product->getPrice(); ?></td>
             <td ><?=$product->getQuantity(); ?></td>
              <td><?=$product->getGender(); ?></td>
              <td><?=$product->getRating(); ?></td>
              <td><?=$product->getExpiry(); ?></td>
              <td><?=$product->getCountry(); ?></td>
           
           
            <?php


            $picture = $product->getImage();
              if($picture instanceof \Pimcore\Model\Asset\Image):

            /** @var \Pimcore\Model\Asset\Image $Image */
            ?>

         <td><?= $picture->getThumbnail()->getHtml(["width" => 100,"height" => 100])?> </td>
           
           
            <?php endif;
           
            ?>
           
            </tr>
        <?php
     }
     ?>  
       </tbody>        
        </table></div></div>
    </div> </div>  </div>
    </div>
    </div>
   <!-- <?php endif; ?> -->
 
</div>
</div>
<script type="text/javascript">



$(document).ready(function(){
$('table').DataTable();


} );

</script>
  </body>
 
  </html>
