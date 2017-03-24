var Sections = function() {

  this.ids = [
    'services', 
    'about-us', 
    'testimonials', 
    'cases', 
    'partnership', 
    'team', 
    'contact-us', 
    'contact-form'];

  this.sections = {};

	this.doCalculate = true;

  this.isInViewport = function(id){
    return verge.inViewport(document.getElementById(id));
  }

  this.getPctInViewport = function( id ) {
    var ret;
    var height = jQuery('#' + id ).height();
    var vph = verge.viewportH();
    var top = verge.rectangle(document.getElementById( id ) ).top;


    if ( top > 0 )
    {
      if ( (height + top) < vph )
      {
        return height/vph;
      }
      else {

        return (vph - top ) / vph
      }
    }
    else {
    
      return (height - Math.abs(top)) / vph
    }

  }

  this.getCurrentSection = function() {
    var current = null;

    for ( var i = 0; i < this.ids.length; i++ )
    {
      var id = this.ids [ i ];

      if ( current != null )
      {
        if ( this.sections[  id ].pctInViewport > 
            this.sections[ current ].pctInViewport )
        {
          current = id;
        }
      } else { current = id }

    }

    return current == null ? 'about-us': current;
  }

  this.calculateAll = function() {

    if ( ! this.doCalculate ) return;

    for (var i = 0; i < this.ids.length; i++ )
    {
      var id = this.ids[ i ];

      this.sections[ id ] = this.sections[ id ] || {};

      this.sections[ id ].inViewport = this.isInViewport( id );
      this.sections[ id ].pctInViewport = this.getPctInViewport( 
          id );
    }
  
  }
}


jQuery(document).ready(function() {

    var s = new Sections();

    jQuery(window).on('scroll', function(event) {
      s.calculateAll();
    });

		jQuery(window).bind('orientationchange', function(event) {
        s.doCalculate = false;

        var current = s.getCurrentSection();

        if ( current == 'cases' ||
             current == 'partnership' ||
             current == 'team' ||
             current == 'contact-us' ||
             current == 'contact-form' 
           )
        {
          setTimeout(function(){
            jQuery(window).scrollTo( '#' + current, {
              duration: 500
            });
            s.doCalculate = true;
          }, 1000);
        }

		});

});
