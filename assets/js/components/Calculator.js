// Admin Settings APIs
import Altalanos from './admin_api/Altalanos';
import Regado from './admin_api/Regado';
import CegautoAdo from './admin_api/CegautoAdo';
import KotelezoBiztositas from './admin_api/KotelezoBiztositas';
import Vagyonszerzesi from './admin_api/Vagyonszerzesi';

// Helpers
import Maradvanyertek from './Maradvanyertek';

// External dependencies
import jQuery from 'jquery';

/**
 * Class that stores data and does calculations based on them.
 */
class Calculator {
  /**
   * jQuery selectors for input fields and creating new admin field handlers
   */
  constructor() {
    // Admin Settings APIs
    this.altalanosAPI = new Altalanos();
    this.regadoAPI = new Regado();
    this.cegautoAdoAPI = new CegautoAdo();
    this.kotelezoBiztositasAPI = new KotelezoBiztositas();
    this.vagyonszerzesiAPI = new Vagyonszerzesi();

    // Helpers
    this.maradvanyertekKalkulator = new Maradvanyertek();
  }

  /**
   * Gets a value and an array of ranges (object: min, max, val).
   * Maps an output value to the input value based on which range object did it fit in.
   *
   * E.g.:
   * Array of range objects: 0 => {0, 10, 5000}, 1 => {11, 20, 10000}, 2 => {21, "", 20000}
   * Input value: 2, output value: 5000
   * Input value: 15, output value: 10000
   * Input value: 50, output value: 20000
   *
   * @param value Input value.
   * @param rangeArray Array of ranges (object: min, max. val).
   * @return {number} Mapped/Output value from the Array's values.
   * (0 if no output found in range array)
   */
  mapValueToRange(value, rangeArray) {
    value = parseInt(value);

    let outputVal = 0;

    rangeArray.forEach(item => {
      let min;
      let max;
      let val;

      // check if item.min, item.max, item.value are correct, set a failsafe value if they are not
      if ( item.min === undefined || item.min === '' ) {
        min = Number.MIN_SAFE_INTEGER;
      } else {
        min = parseInt(item.min);
      }

      if ( item.max === undefined || item.max === '' ) {
        max = Number.MAX_SAFE_INTEGER;
      } else {
        max = parseInt(item.max);
      }
      
      if ( item.val === undefined || item.val === '' ) {
        val = 0;
      } else {
        val = parseInt(item.val);
      }

      // Filter
      if ( value >= min && value <= max ) {
        outputVal = val;
      }
    });

    return outputVal;
  }

  // PV
  static PV(rate, periods, payment, future, type) {
    // Initialize type
    type = typeof type === 'undefined' ? 0 : type;

    // Evaluate rate and periods (TODO: replace with secure expression evaluator)
    rate = eval(rate);
    periods = eval(periods);

    // Return present value
    if ( rate === 0 ) {
      return -payment * periods - future;
    } else {
      return (
        ( ( ( 1 - Math.pow(1 + rate, periods) ) / rate ) *
          payment *
          ( 1 + rate * type ) -
          future ) /
        Math.pow(1 + rate, periods)
      );
    }
  }

  // PMT
  static PMT(rate, nper, pv, fv, type) {
    if ( !fv ) fv = 0;
    if ( !type ) type = 0;

    if ( rate === 0 ) return -( pv + fv ) / nper;

    const pvif = Math.pow(1 + rate, nper);
    let pmt = ( rate / ( pvif - 1 ) ) * -( pv * pvif + fv );

    if ( type === 1 ) {
      pmt /= 1 + rate;
    }

    return pmt;
  }

  /**
   * Calculates the price based on the input fields from the user and admin fields.
   */
  calculate() {
    // input fields
    this.$bruttoVetelar = jQuery('#vetelar .inc-val-value');
    this.$uzemanyag = jQuery('#uzemanyag input[name="uzemanyag-val"]:checked');
    this.$motorTeljesitmeny = jQuery('#loero .inc-val-value');
    this.$futamido = jQuery('#futamido input[name="futamido-val"]:checked');
    this.$futasTeljesitmeny = jQuery(
      '#futasteljesitmeny #futasteljesitmeny-val',
    );
    this.$berletiDij = jQuery('#berletidij input[name="berletidij-val"]');

    // get input field values
    const bruttoVetelar = parseFloat(this.$bruttoVetelar.text()) * 1000000;
    const uzemanyag = this.$uzemanyag.val();
    const motorTeljesitmenyLoero = parseInt(this.$motorTeljesitmeny.text());
    const futamidoHonap = parseInt(this.$futamido.val());
    const futasTeljesitmenyEventeKw = parseInt(this.$futasTeljesitmeny.val());
    const berletiDijSzazalek = parseInt(this.$berletiDij.val());

    // altalanos admin fields
    const kamat = parseFloat(this.altalanosAPI.kamat);
    const casco = parseFloat(this.altalanosAPI.casco);
    const vizsgacimke = parseInt(this.altalanosAPI.vizsgacimke);
    const uzembehelyezes = parseInt(this.altalanosAPI.uzembehelyezes);
    const flottadij = parseInt(this.altalanosAPI.flottadij);

    // range admin fields
    const regadoRanges = this.regadoAPI.ranges;
    const cegautoAdoRanges = this.cegautoAdoAPI.ranges;
    const kotelezoBiztositasRanges = this.kotelezoBiztositasAPI.ranges;
    const vagyonszerzesiRanges = this.vagyonszerzesiAPI.ranges;

    // calculate 'Calculated Fields'
    const motorTeljesitmenyKw = motorTeljesitmenyLoero / 1.34;
    // The calculation below is commented because I found it is not used for anything
    // const osszFutasTeljesitmeny =
    //   futasTeljesitmenyEventeKw * (futamidoHonap / 12);
    const regado = this.mapValueToRange(motorTeljesitmenyKw, regadoRanges);
    const nettoVetelarPluszRegado = Math.round(
      ( bruttoVetelar - regado ) / 1.27 + regado,
    );
    const induloNetto = Math.round(
      ( berletiDijSzazalek / 100 ) * nettoVetelarPluszRegado,
    );

    const vagyonszerzesi = Math.round(
      this.mapValueToRange(motorTeljesitmenyKw, vagyonszerzesiRanges) *
      motorTeljesitmenyKw,
    );
    const berletiDijkepzesAlapjaNetto =
      nettoVetelarPluszRegado + vagyonszerzesi + vizsgacimke + uzembehelyezes;

    const maradvanyertekSzazalek = this.maradvanyertekKalkulator.calculateMaradvanyErtekSzazalek(
      futamidoHonap,
      futasTeljesitmenyEventeKw,
      bruttoVetelar,
      uzemanyag,
    );
    const maradvanyertekResult = Math.round(
      nettoVetelarPluszRegado * ( maradvanyertekSzazalek / 100 ),
    );

    const nettoMaradvanyPluszEgyHo = Math.round(
      Calculator.PV(kamat / 100 / 12, 1, 0, -maradvanyertekResult),
    );

    const finanszirozasHaviAlapdija = Math.round(
      Calculator.PMT(
        kamat / 100 / 12,
        futamidoHonap,
        -( berletiDijkepzesAlapjaNetto - induloNetto ),
        nettoMaradvanyPluszEgyHo,
        0,
      ),
    );

    const cascoResult = Math.round(( bruttoVetelar * ( casco / 100 ) ) / 12);

    const kotelezoBiztositas = this.mapValueToRange(
      motorTeljesitmenyKw,
      kotelezoBiztositasRanges,
    );

    const gepjarmuado = Math.round(( motorTeljesitmenyKw * 345 ) / 12);

    const cegautoado = Math.round(
      this.mapValueToRange(motorTeljesitmenyKw, cegautoAdoRanges) - gepjarmuado,
    );

    // calculate 'Price'
    const oneroPrice = bruttoVetelar * ( berletiDijSzazalek / 100 );

    const finalPrice =
      finanszirozasHaviAlapdija +
      cascoResult +
      gepjarmuado +
      cegautoado +
      kotelezoBiztositas +
      flottadij;

    // update HTML (Price)
    jQuery('#onero-price').text(Calculator.numberWithCommas(oneroPrice) + ' Ft');
    jQuery('#havidij-price').html(Calculator.numberWithCommas(finalPrice) + ' Ft <span>*</span>');
  }

  static numberWithCommas(n) {
    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }
}

export default Calculator;
