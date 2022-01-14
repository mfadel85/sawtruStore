/** @format */

export default class ShippingMethod {
  constructor(post) {
    const { title, quote } = post;

    try {
      var item = Object.values(quote).length > 0 ? Object.values(quote)[0] : {}
      this.id = item.code;
      this.title = title;
      this.enabled = true;
      this.order = 0;
      this.method_title = title;
      this.method_id = item.code;
      this.method_description = title;
      this.settings = {
        title: {
          value: title,
        },
        cost: {
          value: item.cost,
        },
      };
    } catch (e) {
      console.error(e.message);
    }
  }
}
