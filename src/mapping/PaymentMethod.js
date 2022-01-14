/** @format */

export default class PaymentMethod {
  constructor(post) {
    const { code, title } = post;

    try {
      this.id = code;

      if (code == 'paypal-checkout') {
        this.type = 'paypal';
      } else if (code == 'cod') {
        this.type = 'cod';
      } else if (code == 'stripe') {
        this.type = 'stripe';
      }

      this.gateway = code;
      this.title = title;
      this.enabled = true;
      this.order = 0;
      this.method_title = title;
      this.method_description = title;
      this.settings = {};
    } catch (e) {
      console.error(e.message);
    }
  }
}
