// External dependencies
import jQuery from 'jquery';

class Berleridij {
  constructor() {
    this.setupTooltip();
  }

  setupTooltip() {
    const $slider = jQuery('#berletidij input[name="berletidij-val"]');
    let el, newPoint, newPlace;

    $slider.on('input change', function() {
      el = jQuery(this);

      // Figure out placement percentage between left and right of input
      newPoint = ( el.val() - el.attr('min') ) / ( el.attr('max') - el.attr('min') );

      // Corrections (for absolute position, left property)
      const minus = (1 - newPoint) * (-6);
      const plus = newPoint * 6;

      // Measure width of range slider
      const width = jQuery('.berletidij-slider-container').width() - 45;

      // Prevent bubble from going beyond left or right (unsupported browsers)
      if ( newPoint < 0 ) {
        newPlace = 0;
      } else if ( newPoint > 1 ) {
        newPlace = width;
      } else {
        newPlace = width * newPoint;
      }

      // Move bubble
      el.next('output').css({
        left: newPlace + minus + plus,
      }).text(
        el.val() + '%',
      );
    }).trigger('change'); // Trigger positioning on page load
  }
}

export default Berleridij;
