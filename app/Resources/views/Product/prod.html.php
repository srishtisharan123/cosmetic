<?php
/**
 * @var \Pimcore\Templating\PhpEngine $this
 * @var \Pimcore\Templating\PhpEngine $view
 * @var \Pimcore\Templating\GlobalVariables $app
 */

 $this->extend('layout.html.php');
?>

<head>
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

.pagination a:hover:not(.active) {background-color: #ddd;}

</style>
</head>
<h2>Our Products</h2>
<br>

<?= $this->wysiwyg("specialContent"); ?>

<div class="product-info">
    <?php 
    if($this->editmode):
       
    else: ?>

    <div id="product">
  
      <?php
      $pageLimit = 3;

      if (isset($_GET["page"])) {
        $page  = $_GET["page"];    
      }    
      else {
        $page = 1;    
      } 
      $offset = ($page-1) * $pageLimit; 

    $prod = new \Pimcore\Model\DataObject\Product\Listing();

    $products = count($prod);
    $prod->setLimit($pageLimit);
    if($offset > 0){
      $prod->setOffset($offset);
    }
    else{
      $prod->setOffset(0);
    }
    ?>

<input type="text" class="form-control" id="myInput" onkeyup="searchProductByName()" placeholder="Search for names...">

        <table class="d">
            <tr>
                <th >Name</th>
                <th >Brand</th>
                <th >Price</th>
                <th >Rating</th>
                <th >Image</th>
                
                
            </tr>            
        </table>
        
       <table class="d" id="myTable">
       <?php
        foreach($prod as $product) 
        {
            ?>
            
            <tr >
            
            <td ><?=$product->getName(); ?></td> 
            <td ><?=$product->getBrand(); ?></td>
            <td ><?=$product->getPrice(); ?></td> 
            <td ><?=$product->getRating(); ?></td>

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
        </table>
        
    </div>
    <?php endif; ?>
    <center>
      <div class="pagination" >    
      <?php
      if(!$this->editmode) {
        echo "</br>";     
        $pageCount = ceil($products / $pageLimit);     
        $gotoPage = "";       
      
        if($page >= 2){   
            echo "<a href='http://project1.local/Product?page=".($page-1)."'>  Prev </a>";   
        }       
                    
        for ($i = 1; $i <= $pageCount; $i++) {
              
          if ($i == $page) {   
              $gotoPage .= "<a class = 'active' href='http://project1.local/Product?page=".$i."'>".$i." </a>";   
          }         
          else  {   
              $gotoPage .= "<a href='http://project1.local/Product?page=".$i."'>".$i." </a>";     
          }   
        };     
        echo $gotoPage;
            
        if($page<$pageCount){   
            echo "<a href='http://project1.local/Product?page=".($page+1)."'>  Next </a>";   
        }
    }
      ?>    
      </div>
        </center>

</div>
<br>
<script>
function searchProductByName() {
  // Declare variables
  var input, searchKey, table, rows, tableData, i, txtValue;
  input = document.getElementById("myInput");
  searchKey = input.value.trim().toUpperCase();
  table = document.getElementById("myTable");
  rows = table.getElementsByTagName("tr");
  
  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < rows.length; i++) {
    tableData = rows[i].getElementsByTagName("td")[0];
    if (tableData) {
      txtValue = tableData.textContent || tableData.innerText;
      if (txtValue.toUpperCase().indexOf(searchKey) > -1) {
        rows[i].style.display = "";
      } else {
        rows[i].style.display = "none";
      }
    }
  }
}
</script>
