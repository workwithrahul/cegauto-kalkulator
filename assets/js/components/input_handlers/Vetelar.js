// External dependencies
import jQuery from 'jquery';

class Vetelar {
  constructor() {
    this.$negBtn = jQuery('#vetelar .inc-neg');
    this.$posBtn = jQuery('#vetelar .inc-pos');
    this.$currentValue = parseInt(
      jQuery('#vetelar .inc-val .inc-val-value').text()
    );

    this.$negBtn.click(() => {
      this.handleDecrement();
    });

    this.$posBtn.click(() => {
      this.handleIncrement();
    });
  }

  handleDecrement() {
    if (this.$currentValue >= 3.5) {
      this.$currentValue -= 0.5;
      this.handleDisabling();
      jQuery('#vetelar .inc-val .inc-val-value').text(this.$currentValue);
    }
  }

  handleIncrement() {
    if (this.$currentValue <= 79.5) {
      this.$currentValue += 0.5;
      this.handleDisabling();
      jQuery('#vetelar .inc-val .inc-val-value').text(this.$currentValue);
    }
  }

  handleDisabling() {
    switch (this.$currentValue) {
      case 3:
        this.$negBtn.prop('disabled', true);
        break;
      case 80:
        this.$posBtn.prop('disabled', true);
        break;
      default:
        this.$negBtn.prop('disabled', false);
        this.$posBtn.prop('disabled', false);
    }
  }
}

export default Vetelar;
