export default class MyOrder {
  constructor(item) {
    const {
      order_id,
      date_added,
      status,
      total,
      items,

    } = item

    try {
      this.id = order_id
      this.number = order_id
      this.date_created = date_added
      this.status = status
      this.payment_method_title = ""
      this.total = total
      this.sub_total = total
      this.shipping_total = 0

      // if (items && items.length > 0) {
      //   items.forEach((item) => {
      //     item.total = item.price
      //     item.quantity = item.qty_ordered
      //   })
      // }
      this.line_items = []
      this.currency = "$"

      const address = { street: [""] }
      this.shipping = {
        address_1: address.street[0],
        postcode: address.postcode,
        city: address.city,
        state: address.region,
        country: address.country_id,
      }
    } catch (e) {
      console.error(e.message)
    }
  }
}
