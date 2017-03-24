(function($){
	var $teamMembers;

	function selectTeam(){
		var $selector = $(this);
		var loc = $selector.data('loc');
		var $toShow = loc ?
			$teamMembers.filter( '.mj_teamMember_' + loc ) :
			$teamMembers;
		var $toHide = $teamMembers.not( $toShow );
		$selector.addClass('s').siblings('.s').removeClass('s');
		$toShow.removeClass('hidden');
		$toHide.addClass('hidden');
	}

	function init(){
		$teamMembers = $('.w-team-member');
		$('#mj_teamLocSelector span').click( selectTeam );


	}
	$(init);
}(jQuery));