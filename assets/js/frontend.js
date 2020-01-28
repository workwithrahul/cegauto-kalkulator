// Inputs
import Vetelar from './components/input_handlers/Vetelar';
import Loero from './components/input_handlers/Loero';
import Futamido from './components/input_handlers/Futamido';
import Berletidij from './components/input_handlers/Berleridij';

// Calculator
import Calculator from './components/Calculator';

// External dependencies
import jQuery from 'jquery';
import 'selectric';

class CAK_Frontend {
  constructor() {
    //input handlers
    this.vetelar = new Vetelar();
    this.loero = new Loero();
    this.futamido = new Futamido();
    this.berletidij = new Berletidij();

    //form helpers
    this.dirty = false;
    this.container = jQuery('.cegauto-kalkulator');

    //API
    this.api = window.cakApi;

    //create calculator and run initial calculation
    this.calculator = new Calculator();
    this.calculator.calculate();

    //setup calculator to watch for triggers (change in input fields)
    this.setUpCalculateTriggers();

    //disable submit button unless form checkboxes are checked
    jQuery('.required-checkbox').click(() => {
      jQuery('#ajanlatkeres').prop(
        'disabled',
        jQuery('input.required-checkbox:checked').length < 1
      );
    });

    //handle submit
    jQuery('#ajanlatkeres').on('click', e => {
      e.preventDefault();
      jQuery('.loader', this.container).addClass('show');
      jQuery('.error', this.container).removeClass('show');
      let google = window.grecaptcha.getResponse();

      if (!google) {
        jQuery('.error', this.container).addClass('show');
        jQuery('.error', this.container).text('Robot vagy?');
        jQuery('.loader', this.container).removeClass('show');
        return false;
      }

      let formResult = {
        milyen_gyartmany_erdekli: jQuery('#gyartmany #gyartmany-val').val(),
        mssys_fullname: jQuery('#nev').val(),
        email: jQuery('#email').val(),
        mssys_phone: jQuery('#telefonszam').val(),
        mssys_company: jQuery('#cegnevadoszam').val(),
        min1lezart_ev: jQuery('#min1lezart_ev').val(),
        ker_visszahivast: jQuery('#ker_visszahivast').val(),
        keszletes_auto_erdekel: jQuery('#keszletesauto').prop('checked'),
        feliratkozom_hirlevelre: jQuery('#hirlevel').prop('checked'),
        elfogadom_az_adatkezelesi_szabalyzatot: jQuery('#szabalyzat').prop(
          'checked'
        )
      };

      if (this.dirty) {
        formResult = {
          ...formResult,
          kalkulalt_onero: jQuery('#onero-price').text(),
          kalkulalt_havidij: jQuery('#havidij-price').text(),
          brutto_vetelar:
            parseFloat(jQuery('#vetelar .inc-val-value').text()) * 1000000,
          gepjarmu_jellege: jQuery(
            '#jarmujelleg input[name="jarmujelleg-val"]:checked'
          ).val(),
          uzemanyag: jQuery(
            '#uzemanyag input[name="uzemanyag-val"]:checked'
          ).val(),
          motor_teljesitmeny: parseInt(jQuery('#loero .inc-val-value').text()),
          futamido: parseInt(
            jQuery('#futamido input[name="futamido-val"]:checked').val()
          ),
          futasteljesitmeny: parseInt(
            jQuery('#futasteljesitmeny #futasteljesitmeny-val').val()
          ),
          onero: parseInt(
            jQuery('#berletidij input[name="berletidij-val"]').val()
          )
        };
      }
      	
      jQuery.ajax({
	          url: 'wp-admin/admin-ajax.php',
	          type: 'POST',
	          dataType: 'json',
	          data: {action: 'send_data_to_minicrm','data':formResult},
	          success: function( data ) {
	          	window.location.href = data.return_url;
	          }
			});
    });
  }

  setUpCalculateTriggers() {
    //Bruttó vételár
    jQuery('#vetelar .inc-neg').click(() => {
      this.calculator.calculate();
      this.dirty = true;
    });

    jQuery('#vetelar .inc-pos').click(() => {
      this.calculator.calculate();
      this.dirty = true;
    });

    //Üzemanyag
    jQuery('#uzemanyag input[name="uzemanyag-val"]').change(() => {
      this.calculator.calculate();
      this.dirty = true;
    });

    //Motor teljesítménye
    jQuery('#loero .inc-neg').click(() => {
      this.calculator.calculate();
      this.dirty = true;
    });
    jQuery('#loero .inc-pos').click(() => {
      this.calculator.calculate();
      this.dirty = true;
    });

    //Futamidő
    jQuery('#futamido input[name="futamido-val"]').change(() => {
      this.calculator.calculate();
      this.dirty = true;
    });

    //Futásteljesítmény
    jQuery('#futasteljesitmeny #futasteljesitmeny-val').change(() => {
      this.calculator.calculate();
      this.dirty = true;
    });

    //Induló tartós bérleti díj
    // Chrome, Firefox, Safari
    const $berletiInput = jQuery('#berletidij input[name="berletidij-val"]');
    $berletiInput.on('input', () => {
      this.calculator.calculate();
      this.dirty = true;
    });
    // IE
    $berletiInput.on('change', () => {
      this.calculator.calculate();
      this.dirty = true;
    });
  }
}

// Main
jQuery(document).ready(function() {
  // Initialize the Front End
  new CAK_Frontend();
  // Initialize jQuery Selectric
  jQuery('.selectric').selectric();
});
