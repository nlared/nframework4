
<?

class sidebar{
	public $class;
	public $id;
	public $items;
	
	function __construct($contenido=''){
		$this->content=$contenido;
		
	}
	
	function __toString(){
		foreach($this->items as $key=>$item){
			
			$results.='<li'.($key==$this->active? ' class="active"':'' ) .'>
			<a href="'.$item['link'].'">
				<span class="'.$item['icon'].' icon"></span>
				<span class="title">'.$item['title'].'</span>
				'.(isset($item['counter'])?'<span class="counter">'.$item['counter'].'</span>':'').'
			</a>
			</li>';
			
		}
		return '
		<div class="maincont">
			<div class="sidebarleft">
				<ul class="sidebar bg-lightBlue" id="sidebarleft" style="height:100%">'.$results.'</ul>
			</div>
			<div class="maincont1">
			<div style="line-height: 10px">&nbsp</div>
			'.$this->content.'
			</div>
		</div>
		';
		
	}
}
?>
<script>$(function(){
            $('.sidebar').on('click', 'li', function(){
                if (!$(this).hasClass('active')) {
                    $('.sidebar li').removeClass('active');
                    $(this).addClass('active');
                }
            })
        })
</script>
<style>	
	.maincont{
		 display: flex;
		 height: calc(100% - 60px);
		 width:100%;
	}
	
	.sidebarleft{
		float: left;
		max-width: 200px;
		height:100%;
		
		
	}
	.maincont1{
		flex-grow: 1;
		overflow-y:scroll;
		height:100%;
		max-width:auto;
	}
	
	.top_space{
		height: auto;
	}
</style>
