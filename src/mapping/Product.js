import _ from 'lodash'

export default class Product {
  constructor(post) {
    const {
      product_id,
      name,
      seo_url,
      date_added,
      date_modified,
      parent_id,
      description,
      tags,
      attribute_groups,
      status,
      sku,
      tax_class,
      related_product_ids,
      price,
      stock_status,
      dimensions,
      images,
      weight,
      quantity,
      stock_backorder,
      category,
      options,
      variants,
      url,
      reviews,
      rating
    } = post


    try {
      this.id = product_id
      this.name = name
      this.slug = seo_url
      this.permalink = ""
      this.date_created = date_added
      this.date_created_gmt = date_added
      this.date_modified = date_modified
      this.date_modified_gmt = date_modified
      this.type = ""
      this.status = status == "1" ? "publish" : ""
      this.featured = false
      this.catalog_visibility = ""
      this.description = description
      this.short_description = ""
      this.sku = sku
      this.price = price
      this.regular_price = price
      this.sale_price = price
      this.date_on_sale_from = ""
      this.date_on_sale_from_gmt = ""
      this.date_on_sale_to = ""
      this.date_on_sale_to_gmt = ""
      this.price_html = ""
      this.on_sale = false
      this.purchasable = true
      this.total_sales = 0
      this.virtual = false
      this.downloadable = false
      this.downloads = []
      this.download_limit = -1
      this.download_expiry = -1
      this.external_url = ""
      this.button_text = ""
      this.tax_status = ""
      this.tax_class = tax_class
      this.manage_stock = ""
      this.stock_quantity = quantity
      //this.in_stock = stock_status != "Out Of Stock"
      this.in_stock = quantity > 0;

      this.backorders = ""
      this.backorders_allowed = stock_backorder
      this.backordered = false
      this.sold_individually = false
      this.weight = weight
      this.dimensions = dimensions
      this.shipping_required = true
      this.shipping_taxable = true
      this.shipping_class = ""
      this.shipping_class_id = 0
      this.reviews_allowed = true
      this.average_rating = rating
      this.rating_count = reviews
      this.related_ids = related_product_ids
      this.upsell_ids = []
      this.cross_sell_ids = []
      this.parent_id = parent_id
      this.purchase_note = ""
      this.categories = category
      this.tags = tags

      this.images = _.map(images, (item) => ({ src: item }))
      if (!this.images || this.images.length == 0) {
        this.images = [{ src: "http://calgarypma.ca/wp-content/uploads/2018/01/default-thumbnail.jpg" }]
      }

      var attributes = []
      if (attribute_groups && attribute_groups.length > 0) {
        attribute_groups.forEach((item) => {
          item.attribute.forEach((item) => {
            attributes.push({
              position: 0,
              name: item.name,
              options: [item.text]
            })
          })
        })
      }
      this.attributes = attributes
      this.default_attributes = []

      if (variants && variants.length > 0) {
        variants.forEach((item) => {
          item.options.forEach((option) => {
            let { optionName, value } = this.getValueOption(options, option.option_id, option.value_id)
            option.value_name = value
            option.option_name = optionName
          })
        })
      }

      this.options = options

      // [
      //   {
      //     attributes:[
      //       {
      //         name,
      //         option,
      //       }
      //     ],
      //     image
      //   }
      // ]
      this.variations = variants

      this.grouped_products = []
      this.menu_order = 0
      this.meta_data = []
      this.permalink = url

    } catch (e) {
      console.error(e.message)
    }
  }

  getValueOption = (options, optionId, valueId) => {
    var option = null
    var value = null
    var optionName = null
    options.forEach((item) => {
      if (item.id == optionId) {
        option = item
        return
      }
    })
    if (option) {
      option.values.forEach((item) => {
        if (item.id == valueId) {
          value = item.name
          optionName = option.name
          return
        }
      })
    }

    return { optionName, value }
  }
}
