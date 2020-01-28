// External dependencies
import jQuery from 'jquery';

class Futamido {
  constructor() {
    this.$radioButtons = jQuery('#futamido input[name="futamido-val"]');
    this.$radioButtonsCheckedVal = jQuery(
      '#futamido input[name="futamido-val"]:checked'
    ).val();
    jQuery('#futamido-display-curr-val').text(this.$radioButtonsCheckedVal);

    this.$radioButtons.change(function() {
      jQuery('#futamido-display-curr-val').text(this.value);
    });
  }
}

export default Futamido;
