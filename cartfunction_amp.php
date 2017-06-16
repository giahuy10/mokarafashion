<?php 

$article->text = '{product}605,606,607,608,609|3{/product}';
 preg_match_all('/{product}(.*?){\/product}/is', $article->text, $matches);
	echo ($matches[0][0]);
	echo "<br/>";
     $str = $matches[1][0];
	 $text = explode("|",$str);
	 $product_ids = explode(",",$text[0]);
	 $on_row = $text[1];
	 var_dump($product_ids);
	 echo "<br/>";
	 var_dump($on_row);
?>