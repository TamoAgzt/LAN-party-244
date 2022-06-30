<?php

function CreateBrackets($matches) {
  	echo('<main id="tournament">');
	
  	$aantal = count($matches);
  	$id = 1;
  	echo('<ul class="round round-1">');
	
    foreach($matches as $match){
    	echo('
    	<li class="spacer">&nbsp;</li>
        
    	<li class="game game-top winner">'.$match->PlayerA->username.'<span></span></li>
    	<li class="game game-spacer">&nbsp;</li>
    	<li class="game game-bottom ">'.$match->PlayerB->username.'<span></span></li>
    
    	<li class="spacer">&nbsp;</li>');
    }
	
    echo('</ul>');
	echo('<ul class="round round-2">');
	
  	$aantal = $aantal / 2;
    for($i = 0; $i < $aantal; $i++) {
    	echo('
    	<li class="spacer">&nbsp;</li>
      
      	<li class="game game-top winner"> <span></span></li>
      	<li class="game game-spacer">&nbsp;</li>
      	<li class="game game-bottom "> <span></span></li>
  
      	<li class="spacer">&nbsp;</li>');
    }
	
    echo('</ul>');
    echo('<ul class="round round-3">');
	
    $aantal = $aantal /2 ;
    for($i = 0; $i < $aantal; $i++) {
      	echo('<ul class="round round-3">
      	<li class="spacer">&nbsp;</li>
    
    	<li class="game game-top winner"> <span></span></li>
    	<li class="game game-spacer">&nbsp;</li>
    	<li class="game game-bottom "> <span></span></li>'
		);
    }
	
  	echo('</ul>');
  	echo('<ul class="round round-4">');
	
    $aantal = $aantal /2 ;
    for($i = 0; $i < $aantal; $i++) {
    	echo('<ul class="round round-4>
      	<li class="spacer">&nbsp;</li>
    
    	<li class="game game-top winner"> <span></span></li>
    	<li class="game game-spacer">&nbsp;</li>
    	<li class="game game-bottom "> <span></span></li>');
    }
	
  	echo('</ul>');
  	echo('</div> </main>');
}
