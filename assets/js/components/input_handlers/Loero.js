// External dependencies
import jQuery from 'jquery';

class Loero {
  constructor() {
    this.$negBtn = jQuery('#loero .inc-neg');
    this.$posBtn = jQuery('#loero .inc-pos');
    this.$currentValue = parseInt(
      jQuery('#loero .inc-val .inc-val-value').text()
    );

    this.$negBtn.click(() => {
      this.handleDecrement();
    });

    this.$posBtn.click(() => {
      this.handleIncrement();
    });
  }

  handleDecrement() {
    if (this.$currentValue >= 70) {
      this.$currentValue -= 10;
      this.handleDisabling();
      jQuery('#loero .inc-val .inc-val-value').text(this.$currentValue);
    }
  }

  handleIncrement() {
    if (this.$currentValue <= 590) {
      this.$currentValue += 10;
      this.handleDisabling();
      jQuery('#loero .inc-val .inc-val-value').text(this.$currentValue);
    }
  }

  handleDisabling() {
    switch (this.$currentValue) {
      case 60:
        this.$negBtn.prop('disabled', true);
        break;
      case 600:
        this.$posBtn.prop('disabled', true);
        break;
      default:
        this.$negBtn.prop('disabled', false);
        this.$posBtn.prop('disabled', false);
    }
  }
}

export default Loero;
