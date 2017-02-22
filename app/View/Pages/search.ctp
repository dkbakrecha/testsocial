<?php echo $this->Html->css('search'); ?>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.5/angular.min.js"></script>
<!--<li class="cat-item cat-item-1"><a href="">{{ libr }}</a></li>-->
<div class="container" style="min-height: 500px;" ng-app="myApp">

	<div class="main-search" ng-controller="SearchController">
		<ul class="main-navigation">
			<li class="cd-library">
				<a href="">Library...</a>
				<ul class="category-list">
					<li class="cat-item" ng-repeat="x in libraries"> 
						<a href="">
						{{ x.Library.title }} 
						</a>
					</li>
				</ul>
			</li>
			<li class="search">
				<form method="get" id="search-form" action="">
					<input type="search" placeholder="Search..." name="s" ng-model="mainsearch" ng-keyup="search()" required="required">
					<input type="submit" class="btn btn-primary" value="submit">
				</form>				
			</li>
			<div>
				Search Result
				
				<ul>
					<li ng-repeat="r in libsearchs" style="display: block;">
						<a href="">
						{{ r.lib_title }} titled "<b> {{ mainsearch }} </b>" <span class="badge pull-right">{{ r.search_ount }}</span>
						</a>
					</li>
				</ul>
			</div>
			
		</ul>
	</div>

</div>

<?php 
	/*-- Modules --*/
	echo $this->Html->script('angular/app');
	
	/*-- Controllers --*/
	echo $this->Html->script('angular/controllers/SearchController');
?>