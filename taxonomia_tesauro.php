<?php
#Connecting to Wordpress
$_SERVER['SERVER_PROTOCOL'] = "HTTP/1.1";
$_SERVER['REQUEST_METHOD'] = "GET";

define( 'WP_USE_THEMES', false );a
define( 'SHORTINIT', false );
require( 'C:\wamp\www\wordpress\wp-blog-header.php');

#Generating object instances for Collection, Metadata, Items, and Item_Metadata
$taxonomyRepo = \Tainacan\Repositories\Taxonomies::get_instance();
$termsRepo = \Tainacan\Repositories\Terms::get_instance();


#Creating Taxonomy
$thesaurus = new \Tainacan\Entities\Taxonomy();
$thesaurus->set_name('Classe Tesauro');
$thesaurus->set_status('publish');
$thesaurus->set_description('Taxonomia com o tesauro padrão de utilização entre museus do IBRAM.');
$thesaurus->set_allow_insert('yes');

$parent_array = array();

if($thesaurus->validate()){
	
	echo "Taxonomia ", $thesaurus->get_name(), " Valdiada.\n";
	
	$inserted_taxonomy = $taxonomyRepo->insert($thesaurus);
		
	if (($handle = fopen("tesauro_taxonomia.csv", "r")) == TRUE) {
		
		while (($data = fgetcsv($handle, 0, ",")) == TRUE){
			
			$term = new \Tainacan\Entities\Term();
			$term->set_name(trim($data[0]));
			$term->set_status('Publish');
			$term->set_taxonomy($inserted_taxonomy->get_db_identifier());
			
			#$term-set_description(data[3]);
			
			if($data[1] != "X"){
				
				echo "Setando Parent \n";
				
				$term->set_parent($taxonomy_parent[$data[1]]);
			}
			
			if($term->validate()){
				
				$inserted_term = $termsRepo->insert($term);
				
				echo "Termo ", $inserted_term->get_name(), " Validado.\n";
			
			}else{
				
				var_dump($term);
			}
			
			if($data[1] == "X"){
				
				$term_parent[] = $data[0];
				$term_parent_id[] = $inserted_term->get_term_id();
			}
			
			$taxonomy_parent = array_combine($term_parent, $term_parent_id);
		}
	}
	
}else{
	var_dump($thesaurus);	
}

?>
